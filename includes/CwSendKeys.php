<?php

require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/dispatchers/PsSendCodesDispatcher.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/mails/PsSendCodeMail.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/statuses/PsSetCompleteStatus.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/retrievers/PsLinksRetriever.php';

use CodesWholesaleFramework\Orders\Codes\SendCodesAction;

class CwSendKeys extends OrderDetail {

    public function sendKeys($params)
    {
        $action = new SendCodesAction(
            new PsSendCodesDispatcher(),
            new PsSendCodeMail(),
            new PsSetCompleteStatus(),
            new PsLinksRetriever);
        $action->setOrderDetails($params);
        $action->process();
    }
}
