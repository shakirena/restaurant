<?php

namespace app\components;

use yii\db\Connection;

/**
 * Сборка снимка меню кассы для ИИ-официанта (shakirena/ai-waiter, ТЗ, приложение A.2).
 *
 * Соответствие полей кассы и снимка описано здесь один раз и используется
 * выгрузкой в файл импорта (`php yii ai-export-menu`) и, позже, коннектором
 * (`php yii ai-sync`, тело `PUT /menu`).
 *
 * - `type.id`, `type.name`, `type.vid` → `category_external_id`, `category_name`, `category_sort_key`;
 * - `menu.id`, `menu.name`, `menu.vid` → `external_id`, `name`, `sort_key`;
 * - `menu.price` → `price_default`, округление half-up до 2 знаков;
 * - `menu.price_vip` больше 0 → `price_vip`, иначе пусто (тарифа VIP у позиции нет);
 * - `currency` пусто (валюта заведения);
 * - `store.name` через `menu.id_store` → `station` (нет склада — пусто);
 * - позиции с `menu.disabled` не 0 и не NULL сняты с продажи и в снимок не попадают.
 *
 * Логика преобразования работает на массивах строк без БД; чтение из БД — {@see fetchRows()}.
 */
class MenuSnapshotBuilder
{
    /** Колонки файла импорта в порядке вывода (формат — ADR-8.2 ai-waiter). */
    const COLUMNS = [
        'category_external_id',
        'category_name',
        'category_sort_key',
        'external_id',
        'name',
        'price_default',
        'price_vip',
        'currency',
        'station',
        'sort_key',
    ];

    /** Максимальная цена снимка: NUMERIC(10,2) в ядре. */
    const MAX_PRICE_CENTS = 9999999999;

    /**
     * Читает позиции меню вместе с категорией и складом. Только SELECT.
     *
     * Цены приводятся к DECIMAL в запросе: так в PHP приходит десятичная строка,
     * а не float, отформатированный сервером до 6 значащих цифр.
     *
     * @param Connection $db
     * @return array[] строки с ключами menu_id, menu_name, menu_vid, price, price_vip,
     *                 disabled, type_id, type_name, type_vid, store_name
     */
    public static function fetchRows(Connection $db)
    {
        return $db->createCommand(
            'SELECT m.id AS menu_id, m.name AS menu_name, m.vid AS menu_vid,'
            . ' CAST(m.price AS DECIMAL(16,6)) AS price,'
            . ' CAST(m.price_vip AS DECIMAL(16,6)) AS price_vip,'
            . ' m.disabled AS disabled,'
            . ' t.id AS type_id, t.name AS type_name, t.vid AS type_vid,'
            . ' s.name AS store_name'
            . ' FROM menu m'
            . ' INNER JOIN type t ON t.id = m.id_type'
            . ' LEFT JOIN store s ON s.id = m.id_store'
            . ' ORDER BY t.id, m.id'
        )->queryAll();
    }

    /**
     * Строит строки файла импорта (без заголовка) из строк {@see fetchRows()}.
     *
     * @param array[] $rows
     * @return array[] списки значений в порядке {@see COLUMNS}
     * @throws \InvalidArgumentException если данные позиции нельзя выгрузить
     */
    public static function buildRows(array $rows)
    {
        $result = [];
        foreach ($rows as $row) {
            if (!self::isOnSale(self::value($row, 'disabled'))) {
                continue;
            }
            $menuId = self::text($row, 'menu_id', null);
            $result[] = [
                self::text($row, 'type_id', $menuId),
                self::text($row, 'type_name', $menuId),
                self::text($row, 'type_vid', $menuId),
                $menuId,
                self::text($row, 'menu_name', $menuId),
                self::formatPrice(self::value($row, 'price'), $menuId),
                self::formatVipPrice(self::value($row, 'price_vip'), $menuId),
                '',
                self::text($row, 'store_name', $menuId),
                self::text($row, 'menu_vid', $menuId),
            ];
        }
        return $result;
    }

    /**
     * Позиция в продаже: `disabled` равен 0 или NULL.
     *
     * @param mixed $disabled
     * @return bool
     */
    public static function isOnSale($disabled)
    {
        return $disabled === null || $disabled === '' || (string)$disabled === '0';
    }

    /**
     * Цена тарифа `default`: десятичная строка с двумя знаками, half-up.
     *
     * @param mixed $value int, float или десятичная строка
     * @param string|null $menuId для сообщения об ошибке
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function formatPrice($value, $menuId = null)
    {
        if ($value === null || $value === '') {
            throw new \InvalidArgumentException(self::where($menuId) . 'нет цены');
        }
        $cents = self::toCents($value, $menuId);
        if ($cents < 0) {
            throw new \InvalidArgumentException(self::where($menuId) . 'отрицательная цена');
        }
        return self::centsToString($cents);
    }

    /**
     * Цена тарифа `vip`: больше 0 — строка с двумя знаками, NULL/0/меньше 0 — пусто.
     *
     * @param mixed $value
     * @param string|null $menuId
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function formatVipPrice($value, $menuId = null)
    {
        if ($value === null || $value === '') {
            return '';
        }
        $cents = self::toCents($value, $menuId);
        return $cents > 0 ? self::centsToString($cents) : '';
    }

    /**
     * Одна строка CSV по RFC 4180 (разделитель `,`, кавычки удваиваются, конец строки `\n`).
     *
     * Собственная запись вместо fputcsv: в PHP 7.1 у fputcsv нельзя отключить
     * escape-символ `\`, и значение с `\"` записалось бы не по RFC 4180.
     *
     * @param array $fields
     * @return string
     */
    public static function csvLine(array $fields)
    {
        $out = [];
        foreach ($fields as $field) {
            $field = (string)$field;
            if (preg_match('/[",\r\n]|^\s|\s$/', $field)) {
                $field = '"' . str_replace('"', '""', $field) . '"';
            }
            $out[] = $field;
        }
        return implode(',', $out) . "\n";
    }

    /**
     * @param mixed $value
     * @param string|null $menuId
     * @return int цена в копейках, half-up
     */
    private static function toCents($value, $menuId)
    {
        if (is_int($value)) {
            $value = (string)$value;
        } elseif (is_float($value)) {
            if (!is_finite($value)) {
                throw new \InvalidArgumentException(self::where($menuId) . 'цена не число');
            }
            // 9 знаков после точки: шум двоичной дроби (7.999999 → 7.99999899…) не доходит до третьего знака.
            $value = sprintf('%.9F', $value);
        }
        $value = trim((string)$value);
        if (!preg_match('/^(-?)(\d+)(?:\.(\d*))?$/', $value, $m)) {
            throw new \InvalidArgumentException(self::where($menuId) . 'цена не число: ' . mb_substr($value, 0, 30));
        }
        $integer = ltrim($m[2], '0');
        if (strlen($integer) > 8) {
            throw new \InvalidArgumentException(self::where($menuId) . 'цена больше 99999999.99');
        }
        $fraction = str_pad(isset($m[3]) ? $m[3] : '', 3, '0');
        $cents = (int)$integer * 100 + (int)substr($fraction, 0, 2);
        if ((int)$fraction[2] >= 5) {
            $cents++;
        }
        if ($cents > self::MAX_PRICE_CENTS) {
            throw new \InvalidArgumentException(self::where($menuId) . 'цена больше 99999999.99');
        }
        return $m[1] === '-' ? -$cents : $cents;
    }

    /**
     * @param int $cents неотрицательное
     * @return string
     */
    private static function centsToString($cents)
    {
        return intdiv($cents, 100) . '.' . str_pad((string)($cents % 100), 2, '0', STR_PAD_LEFT);
    }

    /**
     * @param array $row
     * @param string $key
     * @return mixed
     */
    private static function value(array $row, $key)
    {
        return array_key_exists($key, $row) ? $row[$key] : null;
    }

    /**
     * Текстовое поле: NULL → пустая строка; строка должна быть в UTF-8; управляющие символы → пробел, края обрезаются.
     *
     * @param array $row
     * @param string $key
     * @param string|null $menuId
     * @return string
     */
    private static function text(array $row, $key, $menuId)
    {
        $value = self::value($row, $key);
        $value = $value === null ? '' : (string)$value;
        if (!preg_match('//u', $value)) {
            throw new \InvalidArgumentException(self::where($menuId) . "поле {$key} не в UTF-8");
        }
        // Ядро отвергает управляющие и невидимые символы форматирования (ADR-8.2):
        // заменяем их пробелом, пробелы по краям обрезаем, как это делает ядро.
        return trim(preg_replace('/[\p{Cc}\p{Cf}\p{Zl}\p{Zp}]+/u', ' ', $value), " \t\n\r\0\x0B");
    }

    /**
     * @param string|null $menuId
     * @return string
     */
    private static function where($menuId)
    {
        return $menuId === null || $menuId === '' ? '' : "menu.id={$menuId}: ";
    }
}
