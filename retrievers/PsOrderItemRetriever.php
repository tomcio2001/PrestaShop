<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 26/08/15
 * Time: 16:20
 */
class PsOrderItemRetriever
{

    public function retrieveItem($mergedValues){

        $qty = intval($mergedValues['item']['product_quantity']);
        $sql = 'SELECT codeswholesale_product FROM ' . _DB_PREFIX_ . 'product_shop WHERE id_product= "' . $mergedValues['item']['product_id'] . '" ';
        $result = Db::getInstance()->ExecuteS($sql);

        $cwProductId = $result[0]['codeswholesale_product'];

        $items = array(
            'cwProductId' => $cwProductId,
            'qty' => $qty
        );

        return $items;
    }


}