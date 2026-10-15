<?php

namespace app\commands;

use app\components\MenuSnapshotBuilder;
use Yii;
use yii\console\Controller;

/**
 * Выгрузка меню кассы в файл импорта ИИ-официанта (shakirena/ai-waiter#67, ТЗ A.2).
 *
 * Пример: `php yii ai-export-menu C:\ai-waiter\local-data\menu.csv`.
 * Команда только читает базу кассы. Файл — CSV UTF-8 без BOM, разделитель `,`,
 * первая строка — заголовок. Проверка файла на стороне ИИ-официанта:
 * `python -m app.menu_sync check <файл>`.
 */
class AiExportMenuController extends Controller
{
    /** Файл записан. */
    const EXIT_OK = 0;
    /** Ошибка данных или базы кассы. */
    const EXIT_DATA_ERROR = 1;
    /** Неверный аргумент: путь к файлу. */
    const EXIT_USAGE = 2;
    /** Не удалось записать файл. */
    const EXIT_IO_ERROR = 3;

    /**
     * Выгружает меню (позиции в продаже) в CSV-файл импорта.
     *
     * @param string $file путь к файлу .csv; каталог должен существовать, файл перезаписывается
     * @return int код выхода
     */
    public function actionIndex($file = '')
    {
        $file = trim((string)$file);
        if ($file === '') {
            return $this->fail('Укажите путь к файлу: php yii ai-export-menu <файл.csv>', self::EXIT_USAGE);
        }
        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'csv') {
            return $this->fail("Файл должен иметь расширение .csv: {$file}", self::EXIT_USAGE);
        }
        $dir = dirname($file);
        if (!is_dir($dir)) {
            return $this->fail("Каталог не существует: {$dir}", self::EXIT_USAGE);
        }
        if (is_dir($file)) {
            return $this->fail("Указан каталог, а не файл: {$file}", self::EXIT_USAGE);
        }

        try {
            $rows = MenuSnapshotBuilder::fetchRows(Yii::$app->db);
            $lines = MenuSnapshotBuilder::buildRows($rows);
        } catch (\yii\db\Exception $e) {
            return $this->fail('Ошибка базы кассы: ' . $e->getMessage(), self::EXIT_DATA_ERROR);
        } catch (\InvalidArgumentException $e) {
            return $this->fail('Меню нельзя выгрузить: ' . $e->getMessage(), self::EXIT_DATA_ERROR);
        }
        if (!$lines) {
            return $this->fail('В меню кассы нет позиций в продаже, файл не записан.', self::EXIT_DATA_ERROR);
        }

        $csv = MenuSnapshotBuilder::csvLine(MenuSnapshotBuilder::COLUMNS);
        $categories = [];
        foreach ($lines as $line) {
            $csv .= MenuSnapshotBuilder::csvLine($line);
            $categories[$line[0]] = true;
        }

        // Пишем во временный файл рядом и переименовываем: недописанный файл не попадёт в импорт.
        $tmp = $file . '.tmp';
        if (@file_put_contents($tmp, $csv, LOCK_EX) !== strlen($csv) || !@rename($tmp, $file)) {
            @unlink($tmp);
            return $this->fail("Не удалось записать файл: {$file}", self::EXIT_IO_ERROR);
        }

        $skipped = count($rows) - count($lines);
        $this->stdout(sprintf(
            "Файл записан: %s\nПозиций: %d, категорий: %d, снято с продажи (не выгружено): %d\n",
            $file,
            count($lines),
            count($categories),
            $skipped
        ));
        return self::EXIT_OK;
    }

    /**
     * @param string $message
     * @param int $code
     * @return int
     */
    private function fail($message, $code)
    {
        $this->stderr($message . "\n");
        return $code;
    }
}
