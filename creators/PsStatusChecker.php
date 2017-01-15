<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 26/08/15
 * Time: 16:16
 */

class PsStatusChecker extends OrderDetail
{
    public function checkStatus($params)
    {
        $orderId = intval($params['id_order']);
        $orderedProducts = $this->getList($orderId);

        return $orderedItems = array(
            'orderId' => $orderId,
            'orderedItems' => $orderedProducts,
            'order' => $params
        );
    }
}