<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;
use App\Basket;

#[CoversClass(Basket::class)]
class BasketTest extends TestCase {
    private const VAT_RATE = 1.2;
    private array $item1, $item2, $item3;
    private array $basket;
    private string $printedBasket;

    #[Before]
    public function setUp(): void {
        $this->item1 = [ "_id" => 1, "_name" => "Test", "_price" => 123.4, "_vat" => false ];
        $this->item2 = [ "_id" => 2, "_name" => "Test name longer than 15", "_price" => 0.75, "_vat" => false ];
        $this->item3 = [ "_id" => 3, "_name" => "Test", "_price" => 1.99, "_vat" => true ];
        $this->basket = [$this->item1, $this->item2, $this->item3];
        $this->printedBasket = Basket::printBasket($this->basket);
    }

    #[Test]
    public function shouldPrintAHeaderRowForTheBasket(): void {
        $this->assertStringContainsString("Item Name\t\t\tPrice\n", $this->printedBasket);
    }

    #[Test]
    public function shouldPrintTheItemName(): void {
        $this->assertStringContainsString($this->item1["_name"], $this->printedBasket);
    }

    #[Test]
    public function shouldPrint3TabsAfterTheItemNameIfItsLessThan16CharactersInLength(): void {
        $this->assertStringContainsString($this->item1["_name"] . "\t\t\t", $this->printedBasket);
    }

    #[Test]
    public function shouldPrint2TabsAfterTheItemNameIfIts16CharactersOrGreaterInLength(): void {
        $this->assertStringContainsString($this->item2["_name"] . "\t\t", $this->printedBasket);
        $this->assertStringNotContainsString($this->item2["_name"] . "\t\t\t", $this->printedBasket);
    }

    #[Test]
    public function shouldNotAddVatToItemIfVatIsFalse(): void {
        $this->assertStringContainsString((string) $this->item1["_price"], $this->printedBasket);
    }

    #[Test]
    public function shouldAddVatToItemIfVatIsTrue(): void {
        $this->assertStringContainsString(number_format($this->item3["_price"] * self::VAT_RATE), $this->printedBasket);
    }

    #[Test]
    public function shouldPrintTheItemPriceTo2DecimalPlaces(): void {
        $this->assertStringContainsString("123.40", $this->printedBasket);
    }

    #[Test]
    public function shouldPrintANewlineAfterTheItemPrice(): void {
        $this->assertStringContainsString("123.40\n", $this->printedBasket);
    }

    #[Test]
    public function shouldOnlyPrintTheNameAndPriceOnARow(): void {
        $this->assertStringContainsString("Test\t\t\t123.40\n", $this->printedBasket);
    }

    #[Test]
    public function shouldPrintANewlineAnd3TabsBeforeTheTextTotalAndATabAfterIt(): void {
        $this->assertStringContainsString("\n\t\t\tTotal\t", $this->printedBasket);
    }

    #[Test]
    public function shouldPrintATotalOfTheBasket(): void {
        $basketTotal = number_format($this->item1["_price"] + $this->item2["_price"] + $this->item3["_price"] * self::VAT_RATE, 2);
        $this->assertStringContainsString($basketTotal, $this->printedBasket);
    }
}