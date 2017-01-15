<?php

require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/mails/PsSendPreOrderMail.php';

/**
 * Created by PhpStorm.
 * User: maciejklowan
 * Date: 27/08/15
 * Time: 14:57
 */
class PsUpdateOrderWithPreOrders
{
    public function update($newKeys, $textComment)
    {
        $links = json_decode($newKeys[0]['item']['links']);

        Db::getInstance()->update('order_detail', array('links' => json_encode(array_merge($newKeys[0]['linksToAdd'], array_values($links)))), '`id_order` = ' . (int)$newKeys[0]['item']['id_order'] . '');
        Db::getInstance()->update('order_detail', array('number_of_preorders' => $newKeys[0]['preOrdersLeft']), '`id_order` = ' . (int)$newKeys[0]['item']['id_order'] . '');

        $sql = ('SELECT * FROM ' . _DB_PREFIX_ . 'orders WHERE id_order = "' . $newKeys[0]['item']['id_order'] . '"');

        $order = Db::getInstance()->ExecuteS($sql);

        $keys[] = array(
            'item' => $newKeys[0]['item'],
            'codes' => $newKeys[0]['codes']
        );

        $send = new PsSendPreOrderMail();
        $send->sendPreOrderMail($order, $newKeys[0]['attachments'], $keys, $newKeys[0]['preOrdersLeft']);
    }
}