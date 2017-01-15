<?php

if (!defined('_PS_VERSION_'))
    exit;

require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/vendor/autoload.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/connectionToCw.php';
require_once _PS_MODULE_DIR_ . 'codeswholesaleapp/includes/createOrder.php';
require_once _PS_CLASS_DIR_ . 'order/OrderHistory.php';
require_once _PS_CLASS_DIR_ . 'Product.php';


class CodesWholesaleApp extends Module
{

    public function __construct()
    {
        $this->name = 'codeswholesaleapp';
        $this->tab = 'smart_shopping';
        $this->version = '2.0.0';
        $this->author = 'DevTeam CodesWholesale';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('CodesWholesale');
        $this->description = $this->l('Integration with CodesWholesale.com');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }


    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        if (!parent::install() ||
            !$this->addCwStatuses() ||
            !$this->alterTable() ||
            !$this->registerHook('actionAdminControllerSetMedia') ||
            !$this->registerHook('actionProductUpdate') ||
            !$this->registerHook('displayAdminProductsExtra') ||
            !$this->registerHook('actionSendPreOrder') ||
            !$this->registerHook('actionOrderStatusPostUpdate') ||

            !Configuration::updateValue('CODESWHOLESALE_ENV', 0) ||
            !Configuration::updateValue('CODESWHOLESALE_BALANCE', 100) ||
            !Configuration::updateValue('CODESWHOLESALE_SPREAD_TYPE', 0) ||
            !Configuration::updateValue('CODESWHOLESALE_SPREAD_VALUE', 5) ||
            !Configuration::updateValue('CODESWHOLESALE_CLIENT_ID', 'Client ID') ||
            !Configuration::updateValue('CODESWHOLESALE_CLIENT_SECRET', 'Client Secret') ||
            !Configuration::updateValue('CODESWHOLESALE_CALCULATE', 0)
        )
            return false;
        return true;

    }

    public function addCwStatuses()
    {

        $valuesForCompleted = array(
            'invoice' => 1,
            'send_email' => 0,
            'module_name' => $this->name,
            'color' => '#44e099',
            'unremovable' => 1,
            'hidden' => 0,
            'logable' => 1,
            'delivery' => 0,
            'shipped' => 1,
            'paid' => 1,
            'deleted' => 0);

        $valuesForPreOrdered = array(
            'invoice' => 1,
            'send_email' => 0,
            'module_name' => $this->name,
            'color' => '#4dbbce',
            'unremovable' => 1,
            'hidden' => 0,
            'logable' => 1,
            'delivery' => 0,
            'shipped' => 0,
            'paid' => 1,
            'deleted' => 0);

        $valuesForFailed = array(
            'invoice' => 1,
            'send_email' => 0,
            'module_name' => $this->name,
            'color' => '#333333',
            'unremovable' => 1,
            'hidden' => 0,
            'logable' => 1,
            'delivery' => 0,
            'shipped' => 0,
            'paid' => 1,
            'deleted' => 0);

        Db::getInstance()->insert('order_state', $valuesForCompleted);
        Db::getInstance()->insert('order_state', $valuesForPreOrdered);
        Db::getInstance()->insert('order_state', $valuesForFailed);

        $languages = Language::getLanguages(false);
        $statuses = array(
            array('name' => 'Completed by CodesWholesale', 'icon' => 'Completed.png', 'config_name' => 'PS_OS_CWCOMPLETED', 'id_order_state' => -1),
            array('name' => 'Failed by CodesWholesale', 'icon' => 'Failed.png', 'config_name' => 'PS_OS_CWFAILED', 'id_order_state' => -1),
            array('name' => 'PreOrdered by CodesWholesale', 'icon' => 'PreOrdered.png', 'config_name' => 'PS_OS_CWPREORDERED', 'id_order_state' => -1)
        );

        foreach ($languages as $lang) {


            for ($i = 0; $i < 3; $i++) {

                $statuses[$i]['id_order_state'];

                if ($statuses[$i]['id_order_state'] == -1) {

                    $idOrderState = Db::getInstance()->executeS('SELECT MAX(id_order_state) as IdOrderState FROM ' . _DB_PREFIX_ . 'order_state_lang');
                    $statuses[$i]['id_order_state'] = ++$idOrderState[0]['IdOrderState'];
                }

                Db::getInstance()->insert('order_state_lang', array(
                    'id_order_state' => $statuses[$i]['id_order_state'],
                    'id_lang' => $lang['id_lang'],
                    'name' => $statuses[$i]['name'],
                    'template' => null));

                copy(_PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $statuses[$i]['icon'],
                    _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'os' . DIRECTORY_SEPARATOR . $idOrderState[0]['IdOrderState'] . '.png');
            }
        }

        foreach ($statuses as $status) {

            Configuration::updateValue($status['config_name'], $status['id_order_state']);
        }

        return true;
    }


    public function alterTable()
    {
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'product_shop ADD `codeswholesale_product` LONGTEXT NULL';
        Db::getInstance()->Execute($sql);
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'product_shop ADD `codeswholesale_calculate` INTEGER NULL';
        Db::getInstance()->Execute($sql);
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'order_detail ADD `links` LONGTEXT NULL';
        Db::getInstance()->Execute($sql);
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'order_detail ADD `number_of_preorders` INTEGER NULL';
        Db::getInstance()->Execute($sql);
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'order_detail ADD `codeswholesale_product_id` LONGTEXT NULL';
        Db::getInstance()->Execute($sql);
        $sql = 'CREATE TABLE access_tokens (
         client_config_id VARCHAR(50),
         user_id VARCHAR(255),
         scope VARCHAR(20),
         token_type VARCHAR(50),
         expires_in varchar(55),
         access_token VARCHAR(255),
         issue_time varchar(55))';
        Db::getInstance()->Execute($sql);
        $sql = 'CREATE TABLE refresh_tokens (
         client_config_id VARCHAR(50),
         user_id VARCHAR(255),
         scope VARCHAR(20),
         refresh_token VARCHAR(50),
         issue_time varchar(55))';
        Db::getInstance()->Execute($sql);

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !$this->dropTable() ||
            !$this->deleteStatuses() ||
            !Configuration::deleteByName('CODESWHOLESALE_ENV') ||
            !Configuration::deleteByName('CODESWHOLESALE_BALANCE') ||
            !Configuration::deleteByName('CODESWHOLESALE_SPREAD_TYPE') ||
            !Configuration::deleteByName('CODESWHOLESALE_SPREAD_VALUE') ||
            !Configuration::deleteByName('CODESWHOLESALE_CLIENT_ID') ||
            !Configuration::deleteByName('CODESWHOLESALE_CLIENT_SECRET') ||
            !Configuration::deleteByName('PS_OS_CWCOMPLETED') ||
            !Configuration::deleteByName('PS_OS_CWPREORDERED') ||
            !Configuration::deleteByName('PS_OS_CWFAILED')
        )
            return false;
        return true;
    }

    public function dropTable()
    {
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'product_shop DROP COLUMN `codeswholesale_product`';
        Db::getInstance()->Execute($sql);
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'product_shop DROP COLUMN `codeswholesale_calculate`';
        Db::getInstance()->Execute($sql);
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'order_detail DROP COLUMN `links`';
        Db::getInstance()->Execute($sql);
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'order_detail DROP COLUMN `number_of_preorders`';
        Db::getInstance()->Execute($sql);
        $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'order_detail DROP COLUMN `codeswholesale_product_id`';
        Db::getInstance()->Execute($sql);
        $sql = 'DROP TABLE access_tokens';
        Db::getInstance()->Execute($sql);
        $sql = 'DROP TABLE refresh_tokens';
        Db::getInstance()->Execute($sql);

        return true;
    }

    public function deleteStatuses()
    {

        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'order_state WHERE id_order_state= ' . (int)Configuration::get('PS_OS_CWCOMPLETED') . '';
        Db::getInstance()->Execute($sql);

        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'order_state WHERE id_order_state= ' . (int)Configuration::get('PS_OS_CWFAILED') . '';
        Db::getInstance()->Execute($sql);

        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'order_state WHERE id_order_state= ' . (int)Configuration::get('PS_OS_CWPREORDERED') . '';
        Db::getInstance()->Execute($sql);

        return true;
    }

    public function getContent()
    {
        $html = '';

        if (Tools::isSubmit('submit' . $this->name)) {
            $cwEnv = Tools::getValue('CODESWHOLESALE_ENV');
            $cwBalance = Tools::getValue('CODESWHOLESALE_BALANCE');
            $cwSpreadType = Tools::getValue('CODESWHOLESALE_SPREAD_TYPE');
            $cwSpreadValue = Tools::getValue('CODESWHOLESALE_SPREAD_VALUE');

            $cwClientId = Tools::getValue('CODESWHOLESALE_CLIENT_ID');
            $cwClientSecret = Tools::getValue('CODESWHOLESALE_CLIENT_SECRET');

            Configuration::updateValue('CODESWHOLESALE_ENV', $cwEnv);
            Configuration::updateValue('CODESWHOLESALE_BALANCE', $cwBalance);
            Configuration::updateValue('CODESWHOLESALE_SPREAD_TYPE', $cwSpreadType);
            Configuration::updateValue('CODESWHOLESALE_SPREAD_VALUE', $cwSpreadValue);

            if (!$cwClientId || !$cwClientSecret) {

                Configuration::updateValue('CODESWHOLESALE_CLIENT_ID', 'I don\'t see your Client ID !');
                Configuration::updateValue('CODESWHOLESALE_CLIENT_SECRET', 'and Client Secret');

            } else {

                Configuration::updateValue('CODESWHOLESALE_CLIENT_ID', $cwClientId);
                Configuration::updateValue('CODESWHOLESALE_CLIENT_SECRET', $cwClientSecret);
            }

            $html .= $this->displayConfirmation($this->l('Configuration updated'));
        }

        $html .= $this->displayForm();
        return $html;

    }

    public function displayForm()
    {
        $connectionToCw = new connectionToCw();
        $error = null;
        try {
            //get params for connection status
            $cwAccount = $connectionToCw->CwConnection()->getAccount();

        } catch (Exception $e) {

            $error = $e;

            $accName = '' .
                $eMail = '' .
                    $currentBalance = '' .
                        $connectionStatus = 'Status: Disconnected, Reason: ' . $e->getMessage();
        }

        if (!$error) {

            $accName = 'Account Name: ' . $cwAccount->getFullName() . '<br/>' .
                $eMail = 'E-mail : ' . $cwAccount->getEmail() . '<br/>' .
                    $currentBalance = 'Current Balance: €' . $cwAccount->getCurrentBalance() . '<br/>' .
                        $connectionStatus = 'Status: Connected';
        }

        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('CodesWholesale Settings'),
            ),

            'description' => $accName,

            $envSelect = array(
                array('value' => 0, 'name' => 'SandBox'),
                array('value' => 1, 'name' => 'Live')
            ),

            $spreadTypeSelect = array(
                array('value' => 0, 'name' => 'Flat'),
                array('value' => 1, 'name' => 'Percent')
            ),

            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Environment:'),
                    'name' => 'CODESWHOLESALE_ENV',
                    'options' => array(
                        'query' => $envSelect,
                        'id' => 'value',
                        'name' => 'name'
                    ),
                    'required' => true
                ),
                $clientID = array(
                    'type' => 'text',
                    'label' => $this->l('Client ID:'),
                    'name' => 'CODESWHOLESALE_CLIENT_ID',
                    'required' => true
                ),
                $clientSecret = array(
                    'type' => 'text',
                    'label' => $this->l('Client Secret:'),
                    'name' => 'CODESWHOLESALE_CLIENT_SECRET',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Balance Value:'),
                    'name' => 'CODESWHOLESALE_BALANCE',
                    'size' => 20,
                    'required' => false
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Spread Type:'),
                    'name' => 'CODESWHOLESALE_SPREAD_TYPE',
                    'options' => array(
                        'query' => $spreadTypeSelect,
                        'id' => 'value',
                        'name' => 'name'
                    ),
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Spread Value:'),
                    'name' => 'CODESWHOLESALE_SPREAD_VALUE',
                    'size' => 20,
                    'required' => false
                ),

            ),

            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                        '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['CODESWHOLESALE_ENV'] = Configuration::get('CODESWHOLESALE_ENV');
        $helper->fields_value['CODESWHOLESALE_BALANCE'] = Configuration::get('CODESWHOLESALE_BALANCE');
        $helper->fields_value['CODESWHOLESALE_SPREAD_TYPE'] = Configuration::get('CODESWHOLESALE_SPREAD_TYPE');
        $helper->fields_value['CODESWHOLESALE_SPREAD_VALUE'] = Configuration::get('CODESWHOLESALE_SPREAD_VALUE');
        $helper->fields_value['CODESWHOLESALE_CLIENT_ID'] = Configuration::get('CODESWHOLESALE_CLIENT_ID');
        $helper->fields_value['CODESWHOLESALE_CLIENT_SECRET'] = Configuration::get('CODESWHOLESALE_CLIENT_SECRET');

        $this->context->controller->addJS(($this->_path) . 'js/codeswholesaleapp.js');


        return $helper->generateForm($fields_form);
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
            $this->context->controller->addJS(_PS_JS_DIR_ . 'tab.js');
            $this->prepareNewTab();
            return $this->display(__FILE__, 'codeswholesale_product.tpl');
        } else {
            $this->adminDisplayWarning('You must save the product in this shop before adding customization.');
        }
    }

    public function prepareNewTab()
    {
        $connectionToCw = new connectionToCw();

        $products = $connectionToCw->cwConnection()->getProducts();
        $calculate = $this->getCwCalculate((int)Tools::getValue('id_product'));

        $this->context->smarty->assign(array(
            'codeswholesale_products' => $products,
            'codeswholesale_product' => $this->getCustomField((int)Tools::getValue('id_product')),
            'codeswholesale_calculate' => $calculate[0]['codeswholesale_calculate'],
            'codeswholesale_spread' => (int)Configuration::get('CODESWHOLESALE_SPREAD_VALUE'),
            'codeswholesale_spread_type' => (int)Configuration::get('CODESWHOLESALE_SPREAD_TYPE'),
            'languages' => $this->context->controller->_languages,
            'default_language' => (int)Configuration::get('PS_LANG_DEFAULT')
        ));
    }

    public function hookActionProductUpdate($params)
    {
        $id_product = (int)Tools::getValue('id_product');
        $codeswholesale_product = Tools::getValue('codeswholesale_product');

        $cw_calculate = Tools::getValue('cw_calculate');

        Db::getInstance()->update('product_shop', array('codeswholesale_product' => $codeswholesale_product),
            '`id_product` = ' . (int)$id_product . '');

        Db::getInstance()->update('product_shop', array('codeswholesale_calculate' => $cw_calculate),
            '`id_product` = ' . (int)$id_product . '');
    }

    public function getCustomField($id_product)
    {
        $result = Db::getInstance()->ExecuteS('SELECT codeswholesale_product FROM '
            . _DB_PREFIX_ . 'product_shop WHERE id_product = ' . (int)$id_product);
        if (!$result)
            return array();

        foreach ($result as $field)
            $cw_product = $field['codeswholesale_product'];

        return $cw_product;
    }

    public function getCwCalculate($id_product)
    {
        return Db::getInstance()->ExecuteS('SELECT codeswholesale_calculate FROM '
            . _DB_PREFIX_ . 'product_shop WHERE id_product = ' . (int)Tools::getValue('id_product'));
    }


    public function hookActionOrderStatusPostUpdate($params)
    {
        $order = new OrderCore($params['id_order']);

        if ($order->hasBeenPaid()) {

            return;
        }

        if (in_array($params['newOrderStatus']->id, array(Configuration::get('PS_OS_PAYMENT'), Configuration::get('PS_OS_WS_PAYMENT')))) {

            $createOrder = new CreateOrder();
            $createOrder->buyKeys($params);
        }
    }

    public function setMedia()
    {
        $this->addJqueryUI('cw.settings');
    }


}





