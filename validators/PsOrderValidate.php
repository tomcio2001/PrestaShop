<?php

require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/mails/PsNotifyLowBalance.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/mails/PsNotifyAboutPreOrder.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/statuses/PsSetPreOrderStatus.php';

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 26/08/15
 * Time: 16:22
 */
class PsOrderValidate
{
    public function validatePurchase($orderedCodes, $item, $orderDetails, $connection, $error)
    {

        if ($orderedCodes['preOrders'] > 0) {

            $gameNames[] = $item['product_name'];
            $notifyAboutPreOrder = new PsNotifyAboutPreOrder();
            $notifyAboutPreOrder->notifyAboutPreOrder($orderDetails, $gameNames);
            $orderedCodes['preOrders'] = 0;
            $cwStatuses = new PsSetPreOrderStatus();
            $cwStatuses->setPreOrderedStatus($orderDetails);
        }

        if (doubleval(ConfigurationCore::get('CODESWHOLESALE_BALANCE')) >= doubleval($connection->getAccount()->getCurrentBalance())) {

            $send = new PsNotifyLowBalance();
            $send->notifyLowBalance($orderDetails);
        }

        if(!$error){

            $eventDataArray = array('order' => $orderDetails);

            return $eventDataArray;
        }
    }
}