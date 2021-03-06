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

class AdminShippingControllerCore extends AdminController
{
    protected $_fieldsHandling;

    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
        $this->table = 'delivery';

        $carriers = Carrier::getCarriers($this->context->language->id, true, false, false, null, Carrier::PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);
        foreach ($carriers as $key => $carrier) {
            if ($carrier['is_free']) {
                unset($carriers[$key]);
            }
        }

        $carrier_default_sort = array(
            array('value' => Carrier::SORT_BY_PRICE, 'name' => $this->trans('Price', array(), 'Admin.Global')),
            array('value' => Carrier::SORT_BY_POSITION, 'name' => $this->l('Position'))
        );

        $carrier_default_order = array(
            array('value' => Carrier::SORT_BY_ASC, 'name' => $this->trans('Ascending', array(), 'Admin.Global')),
            array('value' => Carrier::SORT_BY_DESC, 'name' => $this->trans('Descending', array(), 'Admin.Global'))
        );

        $this->fields_options = array(
            'handling' => array(
                'title' =>    $this->l('Handling'),
                'icon' => 'delivery',
                'fields' =>    array(
                    'PS_SHIPPING_HANDLING' => array(
                        'title' => $this->l('Handling charges'),
                        'suffix' => $this->context->currency->getSign().' '.$this->l('(tax excl.)'),
                        'cast' => 'floatval',
                        'type' => 'text',
                        'validation' => 'isPrice'),
                    'PS_SHIPPING_FREE_PRICE' => array(
                        'title' => $this->l('Free shipping starts at'),
                        'suffix' => $this->context->currency->getSign(),
                        'cast' => 'floatval',
                        'type' => 'text',
                        'validation' => 'isPrice'),
                    'PS_SHIPPING_FREE_WEIGHT' => array(
                        'title' => $this->l('Free shipping starts at'),
                        'suffix' => Configuration::get('PS_WEIGHT_UNIT'),
                        'cast' => 'floatval',
                        'type' => 'text',
                        'validation' => 'isUnsignedFloat'),
                ),
                'description' =>
                    '<ul>
						<li>'.$this->l('If you set these parameters to 0, they will be disabled.').'</li>
						<li>'.$this->l('Coupons are not taken into account when calculating free shipping.').'</li>
					</ul>',
                'submit' => array('title' => $this->trans('Save', array(), 'Admin.Actions'))
            ),
            'general' => array(
                'title' => $this->l('Carrier options'),
                'fields' => array(
                    'PS_CARRIER_DEFAULT' => array(
                        'title' => $this->l('Default carrier'),
                        'desc' => $this->l('Your shop\'s default carrier'),
                        'cast' => 'intval',
                        'type' => 'select',
                        'identifier' => 'id_carrier',
                        'list' => array_merge(
                            array(
                                -1 => array('id_carrier' => -1, 'name' => $this->l('Best price')),
                                -2 => array('id_carrier' => -2, 'name' => $this->l('Best grade'))
                            ),
                            Carrier::getCarriers((int)Configuration::get('PS_LANG_DEFAULT'), true, false, false, null, Carrier::ALL_CARRIERS))
                    ),
                    'PS_CARRIER_DEFAULT_SORT' => array(
                        'title' => $this->l('Sort by'),
                        'desc' => $this->l('This will only be visible in the front office.'),
                        'cast' => 'intval',
                        'type' => 'select',
                        'identifier' => 'value',
                        'list' => $carrier_default_sort
                    ),
                    'PS_CARRIER_DEFAULT_ORDER' => array(
                        'title' => $this->l('Order by'),
                        'desc' => $this->l('This will only be visible in the front office.'),
                        'cast' => 'intval',
                        'type' => 'select',
                        'identifier' => 'value',
                        'list' => $carrier_default_order
                    ),
                ),
                'submit' => array('title' => $this->trans('Save', array(), 'Admin.Actions'))
            )
        );
    }

    public function postProcess()
    {
        /* Shipping fees */
        if (Tools::isSubmit('submitFees'.$this->table)) {
            if ($this->access('edit')) {
                if (($id_carrier = (int)(Tools::getValue('id_carrier'))) && $id_carrier == ($id_carrier2 = (int)(Tools::getValue('id_carrier2')))) {
                    $carrier = new Carrier($id_carrier);
                    if (Validate::isLoadedObject($carrier)) {
                        /* Get configuration values */
                        $shipping_method = $carrier->getShippingMethod();
                        $rangeTable = $carrier->getRangeTable();

                        $carrier->deleteDeliveryPrice($rangeTable);
                        $currentList = Carrier::getDeliveryPriceByRanges($rangeTable, $id_carrier);

                        /* Build prices list */
                        $priceList = array();
                        foreach ($_POST as $key => $value) {
                            if (strstr($key, 'fees_')) {
                                $tmpArray = explode('_', $key);

                                $price = number_format(abs(str_replace(',', '.', $value)), 6, '.', '');
                                $current = 0;
                                foreach ($currentList as $item) {
                                    if ($item['id_zone'] == $tmpArray[1] && $item['id_'.$rangeTable] == $tmpArray[2]) {
                                        $current = $item;
                                    }
                                }
                                if ($current && $price == $current['price']) {
                                    continue;
                                }

                                $priceList[] = array(
                                    'id_range_price' => ($shipping_method == Carrier::SHIPPING_METHOD_PRICE) ? (int)$tmpArray[2] : null,
                                    'id_range_weight' => ($shipping_method == Carrier::SHIPPING_METHOD_WEIGHT) ? (int)$tmpArray[2] : null,
                                    'id_carrier' => (int)$carrier->id,
                                    'id_zone' => (int)$tmpArray[1],
                                    'price' => $price,
                                );
                            }
                        }
                        /* Update delivery prices */
                        $carrier->addDeliveryPrice($priceList);
                        Tools::redirectAdmin(self::$currentIndex.'&conf=6&id_carrier='.$carrier->id.'&token='.$this->token);
                    } else {
                        $this->errors[] = $this->trans('An error occurred while attempting to update fees (cannot load carrier object).', array(), 'Admin.Shipping.Notification');
                    }
                } elseif (isset($id_carrier2)) {
                    $_POST['id_carrier'] = $id_carrier2;
                } else {
                    $this->errors[] = $this->trans('An error occurred while attempting to update fees (cannot load carrier object).', array(), 'Admin.Shipping.Notification');
                }
            } else {
                $this->errors[] = $this->trans('You do not have permission to edit this.', array(), 'Admin.Notifications.Error');
            }
        } else {
            return parent::postProcess();
        }
    }
}
