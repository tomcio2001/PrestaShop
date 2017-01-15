<?php
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/connectionToCw.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/retrievers/PsItemRetriever.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/dispatchers/PsEventDispatcher.php';

use CodesWholesaleFramework\Postback\ReceivePreOrders\ReceivePreOrdersAction;

class CodesWholesaleAppPreOrderModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $connectToCw = new connectionToCw();
        $action = new ReceivePreOrdersAction(
            new PsItemRetriever(),
            new PsEventDispatcher());
        $action->setConnection($connectToCw->cwConnection());
        $action->process();
    }
}