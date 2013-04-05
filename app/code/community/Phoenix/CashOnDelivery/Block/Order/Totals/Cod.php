<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Phoenix
 * @package    Phoenix_CashOnDelivery
 * @copyright  Copyright (c) 2008-2009 Andrej Sinicyn, Mik3e
 * @copyright  Copyright (c) 2010 Phoenix Medien GmbH & Co. KG (http://www.phoenix-medien.de)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Phoenix_CashOnDelivery_Block_Order_Totals_Cod extends Mage_Core_Block_Abstract
{

    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order   = $parent->getOrder();
        if ($this->_order->getCodFee()) {
            $cod = new Varien_Object();
            $cod->setLabel($this->__('Cash on Delivery'));
            $cod->setValue($this->_order->getCodFee());
            $cod->setBaseValue($this->_order->getBaseCodFee());
            $cod->setCode('cod_fee');

            if (Mage::helper('cashondelivery')->displayCodBothPrices()) {
                $cod->setLabel($this->__('Cash on Delivery fee (Excl.Tax)'));

                $codIncl = new Varien_Object();
                $codIncl->setLabel($this->__('Cash on Delivery fee (Incl.Tax)'));
                $codIncl->setValue($this->_order->getCodFee()+$this->_order->getCodTaxAmount());
                $codIncl->setBaseValue($this->_order->getBaseCodFee()+$this->_order->getBaseCodTaxAmount());
                $codIncl->setCode('cod_fee_incl');
                
                $parent->addTotalBefore($cod, 'tax');
                $parent->addTotalBefore($codIncl, 'tax');
            } elseif (Mage::helper('cashondelivery')->displayCodFeeIncludingTax()) {
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