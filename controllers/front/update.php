<?php
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/connectionToCw.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/update/PsUpdatePriceAndStock.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/retrievers/PsSpreadRetriever.php';

use CodesWholesaleFramework\Postback\UpdatePriceAndStock\UpdatePriceAndStockAction;

class codeswholesaleappUpdateModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $connectToCw = new connectionToCw();
        $action = new UpdatePriceAndStockAction(
            new PsUpdatePriceAndStock(),
            new PsSpreadRetriever());

        $action->setProductId($cwProductId = null);
        $action->setConnection($connectToCw->cwConnection());
        $action->process();
    }
}