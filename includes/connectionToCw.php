<?php

use CodesWholesaleFramework\Connection\Connection;

class connectionToCw extends Module {

    public function cwConnection()
    {
        if(Connection::hasConnection()) {
            return Connection::getConnection(array());
        }

        $pdo = new PDO('mysql:host=' . _DB_SERVER_ . ';dbname=' . _DB_NAME_, _DB_USER_, _DB_PASSWD_);

        $options = array(
            'environment'   => intval(Configuration::get('CODESWHOLESALE_ENV')),
            'client_id'     => Configuration::get('CODESWHOLESALE_CLIENT_ID'),
            'client_secret' => Configuration::get('CODESWHOLESALE_CLIENT_SECRET'),
            'client_headers' => 'CodesWholesale-PrestaShop/2.0',
            'db' => $pdo
        );

        return Connection::getConnection($options);
    }
}