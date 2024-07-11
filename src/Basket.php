<?php declare(strict_types=1);

namespace App;

class Basket {
    public static function printBasket(array $basket): string {
        $basketOutput = "Item Name\t\t\tPrice\n";
        $total = 0;

        foreach ($basket as $item) {
            foreach ($item as $key => $value) {
                switch ($key) {
                    case "_name":
                        $basketOutput .= $value;
                        $basketOutput .= (strlen($value) < 16) ? "\t\t\t" : "\t\t";
                        break;
                    case "_price":
                        $vat = $item["_vat"];
                        $priceToAdd = ($vat) ? $value * 1.2 : $value;
                        $basketOutput .= number_format($priceToAdd, 2) . "\n";
                        $total += $priceToAdd;
                        break;
                    case "_id":
                    case "_vat":
                    default:
                        break;
                }
            }
        }

        $basketOutput .= "\n\t\t\tTotal\t£" . number_format($total, 2);
        return $basketOutput;
    }
}
