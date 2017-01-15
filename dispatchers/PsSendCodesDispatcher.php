<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 12:57
 */
class PsSendCodesDispatcher extends OrderDetail
{

    public function dispatchObserver($params)
    {
        $orderId = intval($params['order']['order']['id_order']);
        $orderedProducts = $this->getList($orderId);

        $retrievedParams = array(
            'orderId' => $orderId,
            'orderedItems' =>$orderedProducts,
            'order' => $params
        );

        return $retrievedParams;
    }


}