<?php

namespace tests\codeception\unit\components;

use app\components\MenuSnapshotBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Unit-тесты соответствия A.2 (shakirena/ai-waiter#67). БД не нужна.
 */
class MenuSnapshotBuilderTest extends TestCase
{
    private function row(array $override = [])
    {
        return array_merge([
            'menu_id' => '7',
            'menu_name' => 'Чай',
            'menu_vid' => '01',
            'price' => '2.500000',
            'price_vip' => '3.000000',
            'disabled' => '0',
            'type_id' => '3',
            'type_name' => 'Напитки',
            'type_vid' => '1',
            'store_name' => 'Бар',
        ], $override);
    }

    public function testColumnsInOrder()
    {
        $this->assertSame([
            'category_external_id', 'category_name', 'category_sort_key',
            'external_id', 'name', 'price_default', 'price_vip', 'currency', 'station', 'sort_key',
        ], MenuSnapshotBuilder::COLUMNS);
    }

    public function testMapsRowToColumns()
    {
        $rows = MenuSnapshotBuilder::buildRows([$this->row()]);
        $this->assertSame([['3', 'Напитки', '1', '7', 'Чай', '2.50', '3.00', '', 'Бар', '01']], $rows);
    }

    public function testRoundsFloatNoiseHalfUp()
    {
        $this->assertSame('8.00', MenuSnapshotBuilder::formatPrice(7.999999));
        $this->assertSame('8.00', MenuSnapshotBuilder::formatPrice('7.999999'));
        $this->assertSame('8.00', MenuSnapshotBuilder::formatPrice((float)'7.999999046325684'));
        $this->assertSame('2.68', MenuSnapshotBuilder::formatPrice(2.675));
        $this->assertSame('2.68', MenuSnapshotBuilder::formatPrice('2.675'));
        $this->assertSame('2.67', MenuSnapshotBuilder::formatPrice('2.674999'));
        $this->assertSame('0.10', MenuSnapshotBuilder::formatPrice(0.1));
        $this->assertSame('10.00', MenuSnapshotBuilder::formatPrice(10));
        $this->assertSame('0.00', MenuSnapshotBuilder::formatPrice('0'));
        $this->assertSame('100.00', MenuSnapshotBuilder::formatPrice('99.995'));
        $this->assertSame('99999999.99', MenuSnapshotBuilder::formatPrice('99999999.99'));
    }

    public function testRejectsInvalidPrice()
    {
        foreach ([null, '', '-1', 'abc', '1e5', '100000000.00', '99999999.995', INF] as $bad) {
            try {
                MenuSnapshotBuilder::formatPrice($bad, '5');
                $this->fail('ожидалось исключение для ' . var_export($bad, true));
            } catch (\InvalidArgumentException $e) {
                $this->assertContains('menu.id=5', $e->getMessage());
            }
        }
    }

    public function testVipPriceEmptyWhenZeroOrNull()
    {
        $this->assertSame('', MenuSnapshotBuilder::formatVipPrice(null));
        $this->assertSame('', MenuSnapshotBuilder::formatVipPrice(''));
        $this->assertSame('', MenuSnapshotBuilder::formatVipPrice('0'));
        $this->assertSame('', MenuSnapshotBuilder::formatVipPrice('0.000000'));
        $this->assertSame('', MenuSnapshotBuilder::formatVipPrice(0.0));
        $this->assertSame('', MenuSnapshotBuilder::formatVipPrice('-2.5'));
        $this->assertSame('', MenuSnapshotBuilder::formatVipPrice('0.004'));
        $this->assertSame('12.50', MenuSnapshotBuilder::formatVipPrice('12.499999'));

        $rows = MenuSnapshotBuilder::buildRows([
            $this->row(['price_vip' => '0']),
            $this->row(['menu_id' => '8', 'price_vip' => null]),
        ]);
        $this->assertSame('', $rows[0][6]);
        $this->assertSame('', $rows[1][6]);
    }

    public function testDisabledItemsExcluded()
    {
        $rows = MenuSnapshotBuilder::buildRows([
            $this->row(['menu_id' => '1', 'disabled' => '1']),
            $this->row(['menu_id' => '2', 'disabled' => null]),
            $this->row(['menu_id' => '3', 'disabled' => 0]),
            $this->row(['menu_id' => '4', 'disabled' => 1]),
            $this->row(['menu_id' => '5', 'disabled' => '0']),
        ]);
        $this->assertSame(['2', '3', '5'], array_column($rows, 3));
    }

    public function testNullStoreGivesEmptyStation()
    {
        $rows = MenuSnapshotBuilder::buildRows([$this->row(['store_name' => null])]);
        $this->assertSame('', $rows[0][8]);
        $rows = MenuSnapshotBuilder::buildRows([array_diff_key($this->row(), ['store_name' => 1])]);
        $this->assertSame('', $rows[0][8]);
    }

    public function testNoAvailabilityColumn()
    {
        $this->assertNotContains('is_available', MenuSnapshotBuilder::COLUMNS);
        $rows = MenuSnapshotBuilder::buildRows([$this->row()]);
        $this->assertCount(count(MenuSnapshotBuilder::COLUMNS), $rows[0]);
    }

    public function testCsvEscaping()
    {
        $this->assertSame("a,b,,1.00\n", MenuSnapshotBuilder::csvLine(['a', 'b', '', '1.00']));
        $this->assertSame("\"Кофе, латте\",x\n", MenuSnapshotBuilder::csvLine(['Кофе, латте', 'x']));
        $this->assertSame("\"Суп \"\"Дня\"\"\"\n", MenuSnapshotBuilder::csvLine(['Суп "Дня"']));
        $this->assertSame("\"a\\\"\"b\"\n", MenuSnapshotBuilder::csvLine(['a\\"b']));
        $this->assertSame("\"две\nстроки\"\n", MenuSnapshotBuilder::csvLine(["две\nстроки"]));
        $this->assertSame("\" пробел\"\n", MenuSnapshotBuilder::csvLine([' пробел']));
    }

    public function testCsvRoundTripsThroughStrGetCsv()
    {
        $name = 'Плов "Шах", 1/2';
        $line = MenuSnapshotBuilder::csvLine(MenuSnapshotBuilder::buildRows([$this->row(['menu_name' => $name])])[0]);
        $this->assertSame($name, str_getcsv(rtrim($line, "\n"))[4]);
    }

    public function testControlCharsReplacedAndTrimmed()
    {
        $rows = MenuSnapshotBuilder::buildRows([$this->row([
            'menu_name' => "Чай черный\t",
            'type_name' => " Напитки\r\n",
            'store_name' => "Бар\nкухня",
            'menu_vid' => "0\x001",
        ])]);
        $this->assertSame('Чай черный', $rows[0][4]);
        $this->assertSame('Напитки', $rows[0][1]);
        $this->assertSame('Бар кухня', $rows[0][8]);
        $this->assertSame('0 1', $rows[0][9]);
    }

    public function testRejectsInvalidUtf8()
    {
        $this->expectException(\InvalidArgumentException::class);
        MenuSnapshotBuilder::buildRows([$this->row(['menu_name' => "\xC0\xAF"])]);
    }
}
