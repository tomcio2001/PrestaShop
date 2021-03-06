<?php
<<<<<<< HEAD
/**
=======
/*
>>>>>>> 81aa7fda2ffd8c747b99262ecae76fd22efddb3f
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
<<<<<<< HEAD
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
=======
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
>>>>>>> 81aa7fda2ffd8c747b99262ecae76fd22efddb3f
 * International Registered Trademark & Property of PrestaShop SA
 */

function alter_ignore_drop_key($table, $key)
{
    $indexes = Db::getInstance()->executeS('
        SHOW INDEX FROM `'._DB_PREFIX_.pSQL($table).'` WHERE Key_name = \''.pSQL($key).'\'
    ');

    if (count($indexes) > 0) {
        Db::getInstance()->execute('
            ALTER TABLE `'._DB_PREFIX_.pSQL($table).'` DROP KEY `'.pSQL($key).'`
        ');
    }
}
