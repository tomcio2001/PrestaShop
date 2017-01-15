<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 31/08/15
 * Time: 10:21
 */
class PsSetPreOrderStatus extends OrderHistory
{
    public function setPreOrderedStatus($order){

        $statuses =  Db::getInstance()->executeS( "SELECT value FROM " . _DB_PREFIX_ . "configuration WHERE name= 'PS_OS_CWPREORDERED'" );

        $statusId = intval($statuses[0]['value']);
        $orderId  = intval($order['orderId']);
        $this->changeIdOrderState((int)$statusId, (int)$orderId);
        $this->id_order = $orderId;
        $this->add();
    }
}