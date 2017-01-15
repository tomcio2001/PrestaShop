<?php
/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 12:57
 */
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/connectionToCw.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/vendor/autoload.php';
require_once _PS_CLASS_DIR_ . 'Mail.php';
require_once _PS_CLASS_DIR_ . 'Customer.php';

class PsSendCodeMail{

    public function sendCodeMail($order, $attachments, $keys, $totalPreOrders)
    {
        $customer = new Customer((int)$order['order']['order']['cart']->id_customer);

        if (count($keys)) {

            $links = '<ul>';

            foreach ($keys as $item) {

                $links .= $item['item']['product_name'] . '<br/>';
                foreach ($item['codes'] as $code) {

                    $links .= '<li>';

                    if ($code->isPreOrder()) {

                        $links .= 'Code is Pre-Order' . '<br/>';
                    }
                    if ($code->isImage()) {

                        $links .= 'Check in attachment file: ' . $code->getFileName() . '<br />';
                    }
                    if ($code->isText()) {

                        $links .= $code->getCode() . '<br/>';
                    }

                    $links .= '</li>';
                }
            }

            $links .= '</ul>';
        }

        $params['{lastname}'] = $customer->lastname;
        $params['{firstname}'] = $customer->firstname;
        $params['{totalPreOrders}'] = $totalPreOrders;
        $params['{keys}'] = $links;
        $params['{orderId}'] = $order['order']['orderId'];

        $filesAttached = array();
        $i = 0;

        if (count($attachments) > 0) {

            foreach ($attachments as $attachment) {

                $filesAttached[$i] = array();
                $filesAttached[$i]['content'] = file_get_contents($attachment);
                $filesAttached[$i]['name'] = basename($attachment);
                $filesAttached[$i]['mime'] = 'image/jpeg';
                $i++;
            }
        }

        @MailCore::Send((int)$order['order']['order']['cookie']->id_lang, 'cw_send_code_mail', 'Completed Order ' . $order['order']['orderId'], $params,
            $customer->email, $customer->firstname . ' ' . $customer->lastname, null, null, $filesAttached);
    }

}