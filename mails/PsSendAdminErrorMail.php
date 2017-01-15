<?php
/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 26/08/15
 * Time: 16:20
 */

require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/connectionToCw.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/vendor/autoload.php';
require_once _PS_CLASS_DIR_ . 'Mail.php';
require_once _PS_CLASS_DIR_ . 'Customer.php';

class PsSendAdminErrorMail{

    public function sendAdminErrorMail($order, $title, $e)
    {
        $connection = new connectionToCw();
        $connection->cwConnection();

        $params['{stack_trace}'] = $e->getTraceAsString();
        $params['{message}'] = $e->getMessage();
        $params['{error_class}'] = get_class($e);

        @Mail::Send((int)$order['cookie']->id_lang, 'cw_admin_error_mail', $title, $params,
            ConfigurationCore::get('PS_SHOP_EMAIL'), ConfigurationCore::get('PS_SHOP_NAME'), null, null);
    }
}