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

/**
 * @property SearchEngine $object
 */
class AdminSearchEnginesControllerCore extends AdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'search_engine';
        $this->className = 'SearchEngine';
        $this->lang = false;

        parent::__construct();

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        if (!Tools::getValue('realedit')) {
            $this->deleted = false;
        }

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Actions'),
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Warning'),
                'icon' => 'icon-trash'
            )
        );

        $this->fields_list = array(
            'id_search_engine' => array('title' => $this->trans('ID', array(), 'Admin.Global'), 'width' => 25),
            'server' => array('title' => $this->trans('Server', array(), 'Admin.ShopParameters.Feature')),
            'getvar' => array('title' => $this->trans('GET variable', array(), 'Admin.ShopParameters.Feature'), 'width' => 100)
        );

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Referrer', array(), 'Admin.ShopParameters.Feature')
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->trans('Server', array(), 'Admin.ShopParameters.Feature'),
                    'name' => 'server',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('$_GET variable', array(), 'Admin.ShopParameters.Feature'),
                    'name' => 'getvar',
                    'size' => 40,
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->trans('Save', array(), 'Admin.Actions'),
            )
        );
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_search_engine'] = array(
                'href' => self::$currentIndex.'&addsearch_engine&token='.$this->token,
                'desc' => $this->trans('Add new search engine', array(), 'Admin.ShopParameters.Feature'),
                'icon' => 'process-icon-new'
            );
        }

        $this->identifier_name = 'server';

        parent::initPageHeaderToolbar();
    }
}
