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

class Phoenix_CashOnDelivery_Block_Info extends Mage_Payment_Block_Info
{

    protected $_dataObject;
    protected $_priceModel;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('phoenix/cashondelivery/info.phtml');
    }

    public function toPdf()
    {
        $this->setTemplate('phoenix/cashondelivery/pdf/info.phtml');
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
            $extraFeeExcl = $_dataObject->getCodFee() ? $this->_getPriceModel()->formatPrice($_dataObject->getCodFee()) : null;
            return $extraFeeExcl;
        }
        return null;
    }

    public function getCodFeeInclTax()
    {
        if ($_dataObject = $this->_getDataObject()) {
            $extraFeeIncl = $_dataObject->getCodFee() ? $this->_getPriceModel()->formatPrice($_dataObject->getCodFee()+$_dataObject->getCodTaxAmount()) : null;
            return $extraFeeIncl;
        }
        return null;
    }

    protected function _getDataObject()
    {
        if (!isset($this->_dataObject)) {

            $dataObject = $this->getInfo()->getQuote();

            if (!is_object($dataObject)) {
                $dataObject = $this->getInfo()->getOrder();
            }

            $this->_dataObject = $dataObject;
        }
        return $this->_dataObject;
    }

    protected function _getPriceModel()
    {
        if (!isset($this->_priceModel)) {

            $quote      = $this->getInfo()->getQuote();
            $priceModel = null;

            if (is_object($quote)) {
                $priceModel = $quote->getStore();
            }

            if (!$priceModel) {
                $priceModel = $this->getInfo()->getOrder();
            }

            $this->_priceModel = $priceModel;
        }
        return $this->_priceModel;
    }
}