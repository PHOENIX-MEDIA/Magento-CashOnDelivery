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
 * @copyright  Copyright (c) 2008-2009 Andrej Sinicyn, Mik3e
 * @copyright  Copyright (c) 2010 - 2013 PHOENIX MEDIA GmbH (http://www.phoenix-media.eu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Phoenix_CashOnDelivery_Block_Order_Totals_Cod extends Mage_Core_Block_Abstract
{
    /**
     * Holds the actual order object.
     * @var Mage_Sales_Model_Order
     */
    protected $_order;

    public function initTotals()
    {
        $parent       = $this->getParentBlock();
        $this->_order = $parent->getOrder();

        if ($this->_order->getCodFee()) {

            $cod = new Varien_Object();
            $cod->setLabel($this->__('Cash on Delivery'));
            $cod->setValue($this->_order->getCodFee());
            $cod->setBaseValue($this->_order->getBaseCodFee());
            $cod->setCode('phoenix_cashondelivery_fee');

            if (Mage::helper('phoenix_cashondelivery')->displayCodBothPrices()) {

                $cod->setLabel($this->__('Cash on Delivery fee (Excl.Tax)'));

                $codIncl = new Varien_Object();
                $codIncl->setLabel($this->__('Cash on Delivery fee (Incl.Tax)'));
                $codIncl->setValue($this->_order->getCodFee()+$this->_order->getCodTaxAmount());
                $codIncl->setBaseValue($this->_order->getBaseCodFee()+$this->_order->getBaseCodTaxAmount());
                $codIncl->setCode('phoenix_cashondelivery_fee_incl');
                
                $parent->addTotalBefore($cod, 'tax');
                $parent->addTotalBefore($codIncl, 'tax');

            } elseif (Mage::helper('phoenix_cashondelivery')->displayCodFeeIncludingTax()) {

                $cod->setValue($this->_order->getCodFee()+$this->_order->getCodTaxAmount());
                $cod->setBaseValue($this->_order->getBaseCodFee()+$this->_order->getBaseCodTaxAmount());

                $parent->addTotalBefore($cod, 'tax');

            } else {
                $parent->addTotalBefore($cod, 'tax');
            }
        }
        return $this;
    }
}