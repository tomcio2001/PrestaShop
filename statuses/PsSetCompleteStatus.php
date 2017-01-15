<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 12:58
 */
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/createOrder.php';

class PsSetCompleteStatus extends OrderHistory
{
    public function setStatus($order)
    {
        $statuses = Db::getInstance()->executeS("SELECT value FROM " . _DB_PREFIX_ . "configuration WHERE name= 'PS_OS_CWCOMPLETED'");

        $statusId = intval($statuses[0]['value']);
        $orderId = intval($order['order']['orderId']);

        $this->changeIdOrderState((int)$statusId, (int)$orderId);
        $this->id_order = $orderId;
        $this->add();
    }


}