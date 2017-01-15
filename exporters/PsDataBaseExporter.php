<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 26/08/15
 * Time: 16:18
 */
class PsDataBaseExporter
{
    public function export($item, $orderDataArray)
    {
        Db::getInstance()->update('order_detail', array('links' => json_encode($orderDataArray['links'])), '`id_order` = ' . (int)$item['id_order'] . ' AND `product_id` = ' . (int)$item['product_id'] . '');
        Db::getInstance()->update('order_detail', array('number_of_preorders' => $orderDataArray['preOrders']), '`id_order` = ' . (int)$item['id_order'] . '');
        Db::getInstance()->update('order_detail', array('codeswholesale_product_id' => $orderDataArray['cwOrderId']), '`id_order` = ' . (int)$item['id_order'] . ' AND `product_id` = ' . (int)$item['product_id'] . '');
    }
}