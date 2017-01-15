<?php

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 14:18
 */
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/CwSendPreOrderKeys.php';
use CodesWholesaleFramework\Postback\ReceivePreOrders\EventDispatcher;
class PsEventDispatcher implements EventDispatcher
{
    function dispatchEvent(array $newKeys){

        CwSendPreOrderKeys::sendPreOrder($newKeys);
    }

}