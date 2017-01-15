<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 14:01
 */

const STOCK_STATUS = 2;

class PsUpdatePriceAndStock
{

    public function updateProduct($cwProductId, $quantity, $priceSpread)
    {
        $sql = ('SELECT * FROM ' . _DB_PREFIX_ . 'product_shop WHERE codeswholesale_product= "' . $cwProductId . '"');
        $products = Db::getInstance()->ExecuteS($sql);

        $sql = ('SELECT codeswholesale_calculate FROM ' . _DB_PREFIX_ . 'product_shop WHERE codeswholesale_product= "' . $cwProductId . '"');
        $calculation = Db::getInstance()->ExecuteS($sql);

        if ($products && $calculation[0]['codeswholesale_calculate'] == 0) {

            foreach ($products as $product) {

                $productId = $product['id_product'];

                $price = 'UPDATE ' . _DB_PREFIX_ . 'product_shop SET `price` = ' . (int)$priceSpread . ' WHERE `codeswholesale_product` = "' . $cwProductId . '"';
                Db::getInstance()->execute($price);

                $stock = 'UPDATE ' . _DB_PREFIX_ . 'stock_available SET `quantity` = ' . (int)$quantity . ' WHERE `id_product` = "' . $productId . '"';
                Db::getInstance()->execute($stock);

                $stockUpdate = 'UPDATE ' . _DB_PREFIX_ . 'stock_available SET `out_of_stock` = ' . STOCK_STATUS . ' WHERE `id_product` = "' . $productId . '"';
                Db::getInstance()->execute($stockUpdate);
            }
        }
    }
}