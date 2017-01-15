<?php

require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/CwSendKeys.php';

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 26/08/15
 * Time: 16:19
 */
class PsOrderEventDispatcher
{
    public function dispatchEvent($eventDataArray){

        $cwSendKeys = new CwSendKeys();
        $cwSendKeys->sendKeys($eventDataArray);
    }
}