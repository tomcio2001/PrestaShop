<?php
<<<<<<< HEAD
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
=======
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
>>>>>>> 81aa7fda2ffd8c747b99262ecae76fd22efddb3f

class AuthControllerCore extends FrontController
{
    public $ssl = true;
    public $php_self = 'authentication';
    public $auth = false;

    public function initContent()
    {
        parent::initContent();
<<<<<<< HEAD
        $should_redirect = false;

        if (Tools::isSubmit('submitCreate') || Tools::isSubmit('create_account')) {
            $register_form = $this
                ->makeCustomerForm()
                ->setGuestAllowed(false)
                ->fillWith(Tools::getAllValues())
            ;

            if (Tools::isSubmit('submitCreate')) {
                $hookResult = array_reduce(
                    Hook::exec('actionSubmitAccountBefore', array(), null, true),
                    function ($carry, $item) {
                        return $carry && $item;
                    },
                    true
                );
                if ($hookResult && $register_form->submit()) {
                    $should_redirect = true;
=======

        $this->context->smarty->assign('genders', Gender::getGenders());

        $this->assignDate();

        $this->assignCountries();

        $newsletter = Configuration::get('PS_CUSTOMER_NWSL') || (Module::isInstalled('blocknewsletter') && Module::getInstanceByName('blocknewsletter')->active);
        $this->context->smarty->assign('newsletter', $newsletter);
        $this->context->smarty->assign('optin', (bool)Configuration::get('PS_CUSTOMER_OPTIN'));

        $back = Tools::getValue('back');
        $key = Tools::safeOutput(Tools::getValue('key'));

        if (!empty($key)) {
            $back .= (strpos($back, '?') !== false ? '&' : '?').'key='.$key;
        }

        if ($back == Tools::secureReferrer(Tools::getValue('back'))) {
            $this->context->smarty->assign('back', html_entity_decode($back));
        } else {
            $this->context->smarty->assign('back', Tools::safeOutput($back));
        }

        if (Tools::getValue('display_guest_checkout')) {
            if (Configuration::get('PS_RESTRICT_DELIVERED_COUNTRIES')) {
                $countries = Carrier::getDeliveredCountries($this->context->language->id, true, true);
            } else {
                $countries = Country::getCountries($this->context->language->id, true);
            }

            $this->context->smarty->assign(array(
                    'inOrderProcess' => true,
                    'PS_GUEST_CHECKOUT_ENABLED' => Configuration::get('PS_GUEST_CHECKOUT_ENABLED'),
                    'PS_REGISTRATION_PROCESS_TYPE' => Configuration::get('PS_REGISTRATION_PROCESS_TYPE'),
                    'sl_country' => (int)$this->id_country,
                    'countries' => $countries
                ));
        }

        if (Tools::getValue('create_account')) {
            $this->context->smarty->assign('email_create', 1);
        }

        if (Tools::getValue('multi-shipping') == 1) {
            $this->context->smarty->assign('multi_shipping', true);
        } else {
            $this->context->smarty->assign('multi_shipping', false);
        }

        $this->context->smarty->assign('field_required', $this->context->customer->validateFieldsRequiredDatabase());

        $this->assignAddressFormat();

        // Call a hook to display more information on form
        $this->context->smarty->assign(array(
            'HOOK_CREATE_ACCOUNT_FORM' => Hook::exec('displayCustomerAccountForm'),
            'HOOK_CREATE_ACCOUNT_TOP' => Hook::exec('displayCustomerAccountFormTop')
        ));

        // Just set $this->template value here in case it's used by Ajax
        $this->setTemplate(_PS_THEME_DIR_.'authentication.tpl');

        if ($this->ajax) {
            // Call a hook to display more information on form
            $this->context->smarty->assign(array(
                    'PS_REGISTRATION_PROCESS_TYPE' => Configuration::get('PS_REGISTRATION_PROCESS_TYPE'),
                    'genders' => Gender::getGenders()
                ));

            $return = array(
                'hasError' => !empty($this->errors),
                'errors' => $this->errors,
                'page' => $this->context->smarty->fetch($this->template),
                'token' => Tools::getToken(false)
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
    }

    /**
     * Assign date var to smarty
     */
    protected function assignDate()
    {
        $selectedYears = (int)(Tools::getValue('years', 0));
        $years = Tools::dateYears();
        $selectedMonths = (int)(Tools::getValue('months', 0));
        $months = Tools::dateMonths();
        $selectedDays = (int)(Tools::getValue('days', 0));
        $days = Tools::dateDays();

        $this->context->smarty->assign(array(
                'one_phone_at_least' => (int)Configuration::get('PS_ONE_PHONE_AT_LEAST'),
                'onr_phone_at_least' => (int)Configuration::get('PS_ONE_PHONE_AT_LEAST'), //retro compat
                'years' => $years,
                'sl_year' => $selectedYears,
                'months' => $months,
                'sl_month' => $selectedMonths,
                'days' => $days,
                'sl_day' => $selectedDays
            ));
    }

    /**
     * Assign countries var to smarty
     */
    protected function assignCountries()
    {
        $this->id_country = (int)Tools::getCountry();
        if (Configuration::get('PS_RESTRICT_DELIVERED_COUNTRIES')) {
            $countries = Carrier::getDeliveredCountries($this->context->language->id, true, true);
        } else {
            $countries = Country::getCountries($this->context->language->id, true);
        }
        $this->context->smarty->assign(array(
                'countries' => $countries,
                'PS_REGISTRATION_PROCESS_TYPE' => Configuration::get('PS_REGISTRATION_PROCESS_TYPE'),
                'sl_country' => (int)$this->id_country,
                'vat_management' => Configuration::get('VATNUMBER_MANAGEMENT')
            ));
    }

    /**
     * Assign address var to smarty
     */
    protected function assignAddressFormat()
    {
        $addressItems = array();
        $addressFormat = AddressFormat::getOrderedAddressFields((int)$this->id_country, false, true);
        $requireFormFieldsList = AddressFormat::getFieldsRequired();

        foreach ($addressFormat as $addressline) {
            foreach (explode(' ', $addressline) as $addressItem) {
                $addressItems[] = trim($addressItem);
            }
        }

        // Add missing require fields for a new user susbscription form
        foreach ($requireFormFieldsList as $fieldName) {
            if (!in_array($fieldName, $addressItems)) {
                $addressItems[] = trim($fieldName);
            }
        }

        foreach (array('inv', 'dlv') as $addressType) {
            $this->context->smarty->assign(array(
                $addressType.'_adr_fields' => $addressFormat,
                $addressType.'_all_fields' => $addressItems,
                'required_fields' => $requireFormFieldsList
            ));
        }
    }

    /**
     * Start forms process
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        if (Tools::isSubmit('SubmitCreate')) {
            $this->processSubmitCreate();
        }

        if (Tools::isSubmit('submitAccount') || Tools::isSubmit('submitGuestAccount')) {
            $this->processSubmitAccount();
        }

        if (Tools::isSubmit('SubmitLogin')) {
            $this->processSubmitLogin();
        }
    }

    /**
     * Process login
     */
    protected function processSubmitLogin()
    {
        Hook::exec('actionBeforeAuthentication');
        $passwd = trim(Tools::getValue('passwd'));
        $_POST['passwd'] = null;
        $email = trim(Tools::getValue('email'));
        if (empty($email)) {
            $this->errors[] = Tools::displayError('An email address required.');
        } elseif (!Validate::isEmail($email)) {
            $this->errors[] = Tools::displayError('Invalid email address.');
        } elseif (empty($passwd)) {
            $this->errors[] = Tools::displayError('Password is required.');
        } elseif (!Validate::isPasswd($passwd)) {
            $this->errors[] = Tools::displayError('Invalid password.');
        } else {
            $customer = new Customer();
            $authentication = $customer->getByEmail(trim($email), trim($passwd));
            if (isset($authentication->active) && !$authentication->active) {
                $this->errors[] = Tools::displayError('Your account isn\'t available at this time, please contact us');
            } elseif (!$authentication || !$customer->id) {
                $this->errors[] = Tools::displayError('Authentication failed.');
            } else {
                $this->context->cookie->id_compare = isset($this->context->cookie->id_compare) ? $this->context->cookie->id_compare: CompareProduct::getIdCompareByIdCustomer($customer->id);
                $this->context->cookie->id_customer = (int)($customer->id);
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->logged = 1;
                $customer->logged = 1;
                $this->context->cookie->is_guest = $customer->isGuest();
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->email = $customer->email;

                // Add customer to the context
                $this->context->customer = $customer;

                if (Configuration::get('PS_CART_FOLLOWING') && (empty($this->context->cookie->id_cart) || Cart::getNbProducts($this->context->cookie->id_cart) == 0) && $id_cart = (int)Cart::lastNoneOrderedCart($this->context->customer->id)) {
                    $this->context->cart = new Cart($id_cart);
                } else {
                    $id_carrier = (int)$this->context->cart->id_carrier;
                    $this->context->cart->id_carrier = 0;
                    $this->context->cart->setDeliveryOption(null);
                    $this->context->cart->id_address_delivery = (int)Address::getFirstCustomerAddressId((int)($customer->id));
                    $this->context->cart->id_address_invoice = (int)Address::getFirstCustomerAddressId((int)($customer->id));
                }
                $this->context->cart->id_customer = (int)$customer->id;
                $this->context->cart->secure_key = $customer->secure_key;

                if ($this->ajax && isset($id_carrier) && $id_carrier && Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                    $delivery_option = array($this->context->cart->id_address_delivery => $id_carrier.',');
                    $this->context->cart->setDeliveryOption($delivery_option);
                }

                $this->context->cart->save();
                $this->context->cookie->id_cart = (int)$this->context->cart->id;
                $this->context->cookie->write();
                $this->context->cart->autosetProductAddress();

                Hook::exec('actionAuthentication', array('customer' => $this->context->customer));

                // Login information have changed, so we check if the cart rules still apply
                CartRule::autoRemoveFromCart($this->context);
                CartRule::autoAddToCart($this->context);

                if (!$this->ajax) {
                    $back = Tools::getValue('back','my-account');

                    if ($back == Tools::secureReferrer($back)) {
                        Tools::redirect(html_entity_decode($back));
                    }

                    Tools::redirect('index.php?controller='.(($this->authRedirection !== false) ? urlencode($this->authRedirection) : $back));
                }
            }
        }
        if ($this->ajax) {
            $return = array(
                'hasError' => !empty($this->errors),
                'errors' => $this->errors,
                'token' => Tools::getToken(false)
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        } else {
            $this->context->smarty->assign('authentification_error', $this->errors);
        }
    }

    /**
     * Process the newsletter settings and set the customer infos.
     *
     * @param Customer $customer Reference on the customer Object.
     *
     * @note At this point, the email has been validated.
     */
    protected function processCustomerNewsletter(&$customer)
    {
        $blocknewsletter = Module::isInstalled('blocknewsletter') && $module_newsletter = Module::getInstanceByName('blocknewsletter');
        if ($blocknewsletter && $module_newsletter->active && !Tools::getValue('newsletter')) {
            require_once _PS_MODULE_DIR_.'blocknewsletter/blocknewsletter.php';
            if (is_callable(array($module_newsletter, 'isNewsletterRegistered')) && $module_newsletter->isNewsletterRegistered(Tools::getValue('email')) == Blocknewsletter::GUEST_REGISTERED) {
                /* Force newsletter registration as customer as already registred as guest */
                $_POST['newsletter'] = true;
            }
        }

        if (Tools::getValue('newsletter')) {
            $customer->newsletter = true;
            $customer->ip_registration_newsletter = pSQL(Tools::getRemoteAddr());
            $customer->newsletter_date_add = pSQL(date('Y-m-d H:i:s'));
            /** @var Blocknewsletter $module_newsletter */
            if ($blocknewsletter && $module_newsletter->active) {
                $module_newsletter->confirmSubscription(Tools::getValue('email'));
            }
        }
    }

    /**
     * Process submit on an account
     */
    protected function processSubmitAccount()
    {
        Hook::exec('actionBeforeSubmitAccount');
        $this->create_account = true;
        if (Tools::isSubmit('submitAccount')) {
            $this->context->smarty->assign('email_create', 1);
        }
        // New Guest customer
        if (!Tools::getValue('is_new_customer', 1) && !Configuration::get('PS_GUEST_CHECKOUT_ENABLED')) {
            $this->errors[] = Tools::displayError('You cannot create a guest account.');
        }
        if (!Tools::getValue('is_new_customer', 1)) {
            $_POST['passwd'] = md5(time()._COOKIE_KEY_);
        }
        if ($guest_email = Tools::getValue('guest_email')) {
            $_POST['email'] = $guest_email;
        }
        // Checked the user address in case he changed his email address
        if (Validate::isEmail($email = Tools::getValue('email')) && !empty($email)) {
            if (Customer::customerExists($email)) {
                $this->errors[] = Tools::displayError('An account using this email address has already been registered.', false);
            }
        }
        // Preparing customer
        $customer = new Customer();
        $lastnameAddress = Tools::getValue('lastname');
        $firstnameAddress = Tools::getValue('firstname');
        $_POST['lastname'] = Tools::getValue('customer_lastname', $lastnameAddress);
        $_POST['firstname'] = Tools::getValue('customer_firstname', $firstnameAddress);
        $addresses_types = array('address');
        if (!Configuration::get('PS_ORDER_PROCESS_TYPE') && Configuration::get('PS_GUEST_CHECKOUT_ENABLED') && Tools::getValue('invoice_address')) {
            $addresses_types[] = 'address_invoice';
        }

        $error_phone = false;
        if (Configuration::get('PS_ONE_PHONE_AT_LEAST')) {
            if (Tools::isSubmit('submitGuestAccount') || !Tools::getValue('is_new_customer')) {
                if (!Tools::getValue('phone') && !Tools::getValue('phone_mobile')) {
                    $error_phone = true;
>>>>>>> 81aa7fda2ffd8c747b99262ecae76fd22efddb3f
                }
            }

            $this->context->smarty->assign([
                'register_form'  => $register_form->getProxy(),
                'hook_create_account_top' => Hook::exec('displayCustomerAccountFormTop')
            ]);
            $this->setTemplate('customer/registration');
        } else {
            $login_form = $this->makeLoginForm()->fillWith(
                Tools::getAllValues()
            );

            if (Tools::isSubmit('submitLogin')) {
                if ($login_form->submit()) {
                    $should_redirect = true;
                }
            }

            $this->context->smarty->assign([
                'login_form' => $login_form->getProxy()
            ]);
            $this->setTemplate('customer/authentication');
        }

        if ($should_redirect && !$this->ajax) {
            $back = urldecode(Tools::getValue('back'));

            if (Tools::secureReferrer($back)) {
                // Checks to see if "back" is a fully qualified
                // URL that is on OUR domain, with the right protocol
                $this->redirectWithNotifications($back);
            } else {
                // Well we're not redirecting to a URL,
                // so...
                if ($this->authRedirection) {
                    // We may need to go there if defined
                    $back = $this->authRedirection;
                } elseif (!preg_match('/^[\w\-]+$/', $back)) {
                    // Otherwise, check that "back" matches a controller name
                    // and set a default if not.
                    $back = 'my-account';
                }
                $this->redirectWithNotifications('index.php?controller='.urlencode($back));
            }
        }
    }
}
