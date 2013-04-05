<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Phoenix
 * @package    Phoenix_CashOnDelivery
 * @copyright  Copyright (c) 2010 Phoenix Medien GmbH & Co. KG (http://www.phoenix-medien.de)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$this->startSetup();

$this->_conn->addColumn($this->getTable('sales_flat_quote'), 'cod_tax_amount', 'decimal(12,4)');
$this->_conn->addColumn($this->getTable('sales_flat_quote'), 'base_cod_tax_amount', 'decimal(12,4)');
$this->_conn->addColumn($this->getTable('sales_flat_quote_address'), 'cod_tax_amount', 'decimal(12,4)');
$this->_conn->addColumn($this->getTable('sales_flat_quote_address'), 'base_cod_tax_amount', 'decimal(12,4)');

$eav = new Mage_Eav_Model_Entity_Setup('sales_setup');

$eav->addAttribute('order', 'cod_tax_amount', array('type' => 'decimal',));
$eav->addAttribute('order', 'base_cod_tax_amount', array('type' => 'decimal'));

$eav->addAttribute('order', 'cod_tax_amount_invoiced', array('type' => 'decimal',));
$eav->addAttribute('order', 'base_cod_tax_amount_invoiced', array('type' => 'decimal'));

//$eav->addAttribute('order', 'cod_tax_amount_refunded', array('type' => 'decimal',));
//$eav->addAttribute('order', 'base_cod_tax_amount_refunded', array('type' => 'decimal'));

//$eav->addAttribute('order', 'cod_tax_amount_canceled', array('type' => 'decimal',));
//$eav->addAttribute('order', 'base_cod_tax_amount_canceled', array('type' => 'decimal'));

$eav->addAttribute('invoice', 'cod_tax_amount', array('type' => 'decimal',));
$eav->addAttribute('invoice', 'base_cod_tax_amount', array('type' => 'decimal'));

//$eav->addAttribute('creditmemo', 'cod_tax_amount', array('type' => 'decimal',));
//$eav->addAttribute('creditmemo', 'base_cod_tax_amount', array('type' => 'decimal'));

$this->endSetup();

?>
