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
 * @copyright  Copyright (c) 2010 - 2013 PHOENIX MEDIA GmbH (http://www.phoenix-media.eu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Phoenix_CashOnDelivery_Block_Adminhtml_Sales_Order_Creditmemo_Totals_Cod extends Mage_Core_Block_Abstract
{
    public function initTotals()
    {
        $parent = $this->getParentBlock();

        if ($this->_invoice->getCodFee()) {

            $cod = new Varien_Object();
            $cod->setLabel($this->__('Refund Cash on Delivery fee'));
            $cod->setValue($parent->getSource()->getCodFee());
            $cod->setBaseValue($parent->getSource()->getBaseCodFee());
            $cod->setCode('phoenix_cashondelivery_fee');

            $parent->addTotalBefore($cod, 'adjustment_positive');
        }
        return $this;
    }
}