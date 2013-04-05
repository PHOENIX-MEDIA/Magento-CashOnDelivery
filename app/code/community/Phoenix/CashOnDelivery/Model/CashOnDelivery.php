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

class Phoenix_CashOnDelivery_Model_CashOnDelivery extends Mage_Payment_Model_Method_Abstract
{
    /**
    * unique internal payment method identifier
    *
    * @var string [a-z0-9_]
    */
    protected $_code = 'cashondelivery';
    protected $_canUseForMultishipping  = false;

    protected $_formBlockType = 'cashondelivery/form';
    protected $_infoBlockType = 'cashondelivery/info';

    public function getCODTitle()
    {
        return $this->getConfigData('title');
    }

    public function getInlandCosts()
    {
        return floatval($this->getConfigData('inlandcosts'));
    }

    public function getForeignCountryCosts()
    {
        return floatval($this->getConfigData('foreigncountrycosts'));
    }

    public function getCustomText()
    {
        return $this->getConfigData('customtext');
    }

    /**
     * Returns COD fee for certain address
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return decimal
     *
     */
    public function getAddressCosts(Mage_Customer_Model_Address_Abstract $address)
    {
        if ($address->getCountry() == Mage::getStoreConfig('shipping/origin/country_id')) {
            return $this->getInlandCosts();
        } else {
            return $this->getForeignCountryCosts();
        }
    }

    public function getAddressCodFee(Mage_Customer_Model_Address_Abstract $address, $value = null,
        $alreadyExclTax = false)
    {
        if (is_null($value)) {
            $value = $this->getAddressCosts($address);
        }
        if (Mage::helper('cashondelivery')->codPriceIncludesTax()) {
            if (!$alreadyExclTax) {
                $value = Mage::helper('cashondelivery')->getCodPrice(
                    $value,
                    false,
                    $address,
                    $address->getQuote()->getCustomerTaxClassId()
                );
            }
        }
        return $value;
    }

    public function getAddressCodTaxAmount(Mage_Customer_Model_Address_Abstract $address, $value = null,
        $alreadyExclTax = false)
    {
        if (is_null($value)) {
            $value = $this->getAddressCosts($address);
        }
        if (Mage::helper('cashondelivery')->codPriceIncludesTax()) {
            $includingTax = Mage::helper('cashondelivery')->getCodPrice(
                $value,
                true,
                $address, $address->getQuote()->getCustomerTaxClassId()
            );
            if (!$alreadyExclTax) {
                $value = Mage::helper('cashondelivery')->getCodPrice(
                    $value,
                    false,
                    $address, $address->getQuote()->getCustomerTaxClassId()
                );
            }
            return $includingTax - $value;
        }
        return 0;
    }

    /**
     * Return true if the method can be used at this time
     *
     * @return bool
     */
    public function isAvailable($quote=null)
    {
        if (!parent::isAvailable($quote)) {
            return false;
        }
        if (!is_null($quote)) {
            if ($this->getConfigData('shippingallowspecific', $quote->getStoreId()) == 1) {
                $country = $quote->getShippingAddress()->getCountry();
                $availableCountries = $this->getConfigData('shippingspecificcountry', $quote->getStoreId());
                if (!in_array($country, explode(',', $availableCountries))) {
                    return false;
                }

            }
            if ($this->getConfigData('disallowspecificshippingmethods', $quote->getStoreId()) == 1) {
                $shippingMethodCode = explode('_', $quote->getShippingAddress()->getShippingMethod());
                $shippingMethodCode = $shippingMethodCode[0];
                $disallowedShippingMethods = $this->getConfigData('disallowedshippingmethods', $quote->getStoreId());
                if (in_array($shippingMethodCode, explode(',', $disallowedShippingMethods))) {
                    return false;
                }
            }
        }
        return true;
    }
}
