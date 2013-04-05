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
            $cod->setCode('cod_fee');            
            $parent->addTotalBefore($cod, 'adjustment_positive');
        }

        return $this;
    }

}