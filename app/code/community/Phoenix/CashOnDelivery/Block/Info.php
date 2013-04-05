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

class Phoenix_CashOnDelivery_Block_Info extends Mage_Payment_Block_Info
{

    protected $_dataObject;
    protected $_priceModel;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('cashondelivery/info.phtml');
    }

    public function toPdf()
    {
        $this->setTemplate('cashondelivery/pdf/info.phtml');
        return $this->toHtml();
    }

    public function getRawCodFee()
    {
        if ($_dataObject = $this->_getDataObject()) {
            return $_dataObject->getCodFee();
        }
        return null;
    }

    public function getCodFeeExclTax()
    {
        if ($_dataObject = $this->_getDataObject()) {
            $extraFeeExcl = $_dataObject->getCodFee() ?
               $this->_getPriceModel()->formatPrice($_dataObject->getCodFee()) : null;
            return $extraFeeExcl;
        }
        return null;
    }

    public function getCodFeeInclTax()
    {
        if ($_dataObject = $this->_getDataObject()) {
            $extraFeeIncl = $_dataObject->getCodFee() ?
               $this->_getPriceModel()->formatPrice($_dataObject->getCodFee()+$_dataObject->getCodTaxAmount()) : null;
            return $extraFeeIncl;
        }
        return null;
    }

    protected function _getDataObject()
    {
        if (!isset($this->_dataObject)) {
            if ($this->_dataObject = $this->getInfo()->getQuote()) {
            } elseif ($this->_dataObject = $this->getInfo()->getOrder()) {
            }
        }
        return $this->_dataObject;
    }

    protected function _getPriceModel()
    {
        if (!isset($this->_priceModel)) {
            if ($this->getInfo()->getQuote()) {
                $this->_priceModel = $this->getInfo()->getQuote()->getStore();
            } elseif ($this->getInfo()->getOrder()) {
                $this->_priceModel = $this->getInfo()->getOrder();
            }
        }
        return $this->_priceModel;
    }
}