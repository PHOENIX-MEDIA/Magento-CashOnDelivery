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

class Phoenix_CashOnDelivery_Block_Invoice_Totals_Cod extends Mage_Core_Block_Abstract
{
    protected $_invoice;
    
    public function initTotals()
    {
        $parent         = $this->getParentBlock();
        $this->_invoice = $parent->getInvoice();

        if ($this->_invoice->getCodFee()) {

            $cod = new Varien_Object();
            $cod->setLabel($this->__('Cash on Delivery fee'));
            $cod->setValue($this->_invoice->getCodFee());
            $cod->setBaseValue($this->_invoice->getBaseCodFee());
            $cod->setCode('phoenix_cashondelivery_fee');

            if (Mage::helper('phoenix_cashondelivery')->displayCodBothPrices()) {

                $cod->setLabel($this->__('Cash on Delivery fee (Excl.Tax)'));

                $codIncl = new Varien_Object();
                $codIncl->setLabel($this->__('Cash on Delivery fee (Incl.Tax)'));
                $codIncl->setValue($this->_invoice->getCodFee() + $this->_invoice->getCodTaxAmount());
                $codIncl->setBaseValue($this->_invoice->getBaseCodFee() + $this->_invoice->getBaseCodTaxAmount());
                $codIncl->setCode('phoenix_cashondelivery_fee_incl');

                $parent->addTotalBefore($cod, 'tax');
                $parent->addTotalBefore($codIncl, 'tax');

            } elseif (Mage::helper('phoenix_cashondelivery')->displayCodFeeIncludingTax()) {

                $cod->setValue($this->_invoice->getCodFee() + $this->_invoice->getCodTaxAmount());
                $cod->setBaseValue($this->_invoice->getBaseCodFee() + $this->_invoice->getBaseCodTaxAmount());

                $parent->addTotalBefore($cod, 'tax');

            } else {
                $parent->addTotalBefore($cod, 'tax');
            }
        }
        return $this;
    }
}