<?php

require_once _PS_CLASS_DIR_ . 'Mail.php';
require_once _PS_CLASS_DIR_ . 'Customer.php';
/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 31/08/15
 * Time: 10:15
 */
class PsNotifyLowBalance
{
    public function notifyLowBalance($order)
    {
        $client = new connectionToCw();
        $params['{current_balance}'] = '€' . number_format($client->cwConnection()->getAccount()->getCurrentBalance(), 2, '.', '');

        @Mail::Send((int)$order['order']['cookie']->id_lang, 'cw_info_admin_mail', 'Information about low balance', $params,
            ConfigurationCore::get('PS_SHOP_EMAIL'), ConfigurationCore::get('PS_SHOP_NAME'), null, null);
    }
}