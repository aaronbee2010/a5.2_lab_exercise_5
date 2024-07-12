<?php declare(strict_types=1);

namespace App;

class Basket {
    /**
     * This function takes an items price excluding VAT, and a boolean stating whether or not VAT must be charged with the items
     * If the boolean is true, the function will calculate the price including VAT and return this as the sale price.
     * If the boolean is false, the function will not add VAT to the input price and will instead return it as is.
     */
    private static function returnSalePrice(float $priceExVat, bool $vat): float {
        $vatRate = 1.2;

        $priceIncVat = $priceExVat * $vatRate;

        return ($vat)
            ? $priceIncVat
            : $priceExVat;
    }

    /**
     * The size of the separator between the name and price within a printed basket row depends on the size of the name string
     * If the string is less than 16 characters in length, the function will return three tabs as the appropriate seperator,
     * otherwise it will return two tabs.
     */
    private static function returnCorrectSeparatorForNameSize(string $name): string {
        $lengthOfName = strlen($name);
        
        return ($lengthOfName < 16)
            ? "\t\t\t"
            : "\t\t";
    }

    /**
     * This function takes a name and price, fetches the appropriate separator to place between them, formats the price to
     * 2 decimal places, then returns a basket row containing the name, separator and formatted price.
     */
    private static function returnPrintedBasketRow(string $name, float $price): string {
        $separator = self::returnCorrectSeparatorForNameSize($name);     

        $formattedPrice = number_format($price, 2);

        return "{$name}{$separator}{$formattedPrice}\n";
    }

    /**
     * This function takes a basket array of items, and generates a printed basket string with one row in the string for each item
     * in the basket.
     */
    public static function printBasket(array $basket): string {
        // Header of printed basket string
        $basketOutput = "Item Name\t\t\tPrice\n";

        $totalPriceOfAllItemsInBasket = 0;

        foreach ($basket as $item) {
            // Save item values to variables with nice names
            $nameOfItem = $item["_name"];
            $priceOfItemWithoutVat = $item["_price"];
            $doesVatApplyToCurrentItem = $item["_vat"];

            // Calculate sale price of item then add it to the total basket cost
            $salePrice = self::returnSalePrice($priceOfItemWithoutVat, $doesVatApplyToCurrentItem);
            $totalPriceOfAllItemsInBasket += $salePrice;

            // Generate a basket row for the current item then append it to the printed basket string
            $basketOutput .= self::returnPrintedBasketRow($nameOfItem, $salePrice);
        }

        // Footer of printed basket string, with total basket cost
        $basketOutput .= "\n\t\t\tTotal\t£" . number_format($totalPriceOfAllItemsInBasket, 2);

        return $basketOutput;
    }
}
