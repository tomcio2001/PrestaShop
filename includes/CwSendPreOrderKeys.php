<?php


require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/dispatchers/PsUpdateOrderWithPreOrders.php';
use CodesWholesaleFramework\Postback\ReceivePreOrders\UpdateOrderWithPreOrdersAction;

class CwSendPreOrderKeys
{
    public static function sendPreOrder($eventDataArray)
    {
        $action = new UpdateOrderWithPreOrdersAction(new PsUpdateOrderWithPreOrders());
        $action->setKeys($eventDataArray);
        $action->process();
    }
}







