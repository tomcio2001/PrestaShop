<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 31/08/15
 * Time: 10:19
 */
class PsSetFailedStatus extends OrderHistory
{
    public function setFailedStatus($order)
    {
        $statuses =  Db::getInstance()->executeS( "SELECT value FROM " . _DB_PREFIX_ . "configuration WHERE name= 'PS_OS_CWFAILED'" );

        $statusId = intval($statuses[0]['value']);
        $orderId  = intval($order['id_order']);
        $this->changeIdOrderState((int)$statusId, (int)$orderId);
        $this->id_order = $orderId;
        $this->add();
    }
}