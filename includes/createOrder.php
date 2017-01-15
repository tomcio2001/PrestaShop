<?php

require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/connectionToCw.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/CwSendKeys.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/creators/PsStatusChecker.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/exporters/PsDataBaseExporter.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/dispatchers/PsOrderEventDispatcher.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/retrievers/PsOrderItemRetriever.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/mails/PsSendAdminErrorMail.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/mails/PsSendAdminGeneralError.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/validators/PsOrderValidate.php';

use CodesWholesaleFramework\Orders\Codes\OrderCreatorAction;

class CreateOrder extends OrderDetail
{
    /**
     * This method checks for complete transaction. If Transaction is completed plugin sends
     * CodesWholesale Game Key's to customer. Also included another statuses like PreOrdered by CodesWholesale or
     * Failed by CodesWholesale.
     */
    public function buyKeys($params)
    {
        $connection = new connectionToCw();
            $action = new OrderCreatorAction(
                new PsStatusChecker(),
                new PsDataBaseExporter(),
                new PsOrderEventDispatcher(),
                new PsOrderItemRetriever(),
                new PsSendAdminErrorMail(),
                new PsSendAdminGeneralError(),
                new PsOrderValidate());

            $action->setConnection($connection->cwConnection());
            $action->setCurrentStatus($params);
            $action->process();
    }
}