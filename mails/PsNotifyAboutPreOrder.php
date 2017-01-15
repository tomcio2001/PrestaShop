<?php

require_once _PS_CLASS_DIR_ . 'Mail.php';
require_once _PS_CLASS_DIR_ . 'Customer.php';
/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 31/08/15
 * Time: 10:13
 */
class PsNotifyAboutPreOrder
{
    public function notifyAboutPreOrder($order, $gameNames)
    {
        $customer = new Customer((int)$order['cart']->id_customer);

        $links = '<ul>';

        if (count($gameNames) > 0) {

            foreach ($gameNames as $gameName) {

                $links .= '<li>';

                $links .= $gameName . '.' . '<br/>';

                $links .= '</li>';
            }
        }

        $links .= '</ul>';

        $params['{order_id}'] = $order['orderId'];
        $params['{username}'] = $customer->firstname . ' ' . $customer->lastname;
        $params['{pre_orders}'] = $links;

        @Mail::Send((int)$order['order']['cookie']->id_lang, 'cw_preorder_mail', 'Information about PreOrder- Order ID: ' . $order['orderId'], $params,
            ConfigurationCore::get('PS_SHOP_EMAIL'), ConfigurationCore::get('PS_SHOP_NAME'), null, null);
    }
}