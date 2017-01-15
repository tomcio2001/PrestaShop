<?php

require_once _PS_CLASS_DIR_ . 'Mail.php';
require_once _PS_CLASS_DIR_ . 'Customer.php';
/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 14:44
 */
class PsSendPreOrderMail
{
    public static function sendPreOrderMail($order, $attachments, $keys, $preOrdersLeft)
    {

        $customer = new Customer((int)$order[0]['id_customer']);

        $filesAttached = array();
        $i = 0;

        if (count($attachments) > 0) {

            foreach ($attachments as $attachment) {

                $filesAttached[$i] = array();
                $filesAttached[$i] = file_get_contents($attachment);
                $filesAttached[$i] = basename($attachment);
                $filesAttached[$i] = 'image/jpeg';
                $i++;
            }
        }

        $links = '<ul>';

        if (count($keys[0]) > 0) {

            if ($preOrdersLeft == 0) {

                $links .= "Hi there." .'<br/><br/>'. " Your recent order has been completed. Your keys are shown below for your reference:" . '<br/></br>';

            } else {

                $links .= "Hi there." .'<br/><br/>'. " Your recent order has been partially completed. Your keys are shown below for your reference next keys: " . $preOrdersLeft . " are going to be sent ASAP." . '<br/></br></br>';
            }

            $links .= '<br/><b>PreOrdered Games</b></b><br/>';


            foreach ($keys as $item) {

                $links .= $gameName = $item['item']['name'];

                foreach ($item['codes'] as $code) {

                    $textCode = $code->isImage() ? $code->getFileName() : $code->getCode();
                    $links .= $textCode . '<br/>';
                }
            }
        }
        $links .= '</ul>';

        $params['{lastname}'] = $customer->lastname;
        $params['{firstname}'] = $customer->firstname;

        $params['{keys}'] = $links;

        @MailCore::Send((int)$order[0]['id_lang'], 'cw_send_preorder_mail', 'PreOrder ID: ' . $order[0]['id_order'], $params,
            $customer->email, $customer->firstname . ' ' . $customer->lastname, null, null, $filesAttached);
    }
}