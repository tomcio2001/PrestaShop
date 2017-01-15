<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 14:18
 */
class PsItemRetriever
{

    public function retrieveItem($orderId)
    {

        $sql = ('SELECT * FROM ' . _DB_PREFIX_ . 'order_detail WHERE codeswholesale_product_id = "' . $orderId . '"');

        $result = Db::getInstance()->ExecuteS($sql);

        if (count($result) > 0) {

            $sql = ('SELECT * FROM ' . _DB_PREFIX_ . 'order_detail WHERE codeswholesale_product_id = "' . $orderId . '"');

            $item = Db::getInstance()->ExecuteS($sql);

            return $item[0];

        }

    }
}