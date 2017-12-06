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

/**
 * COD fee Total Row Renderer
 *
 */
class Phoenix_CashOnDelivery_Block_Checkout_Cod extends Mage_Checkout_Block_Total_Default
{
    protected $_template = 'phoenix/cashondelivery/checkout/cod.phtml';

    /**
     * Check if we need display COD fee include and exlude tax
     *
     * @return bool
     */
    public function displayBoth()
    {
        return Mage::helper('phoenix_cashondelivery')->displayCodBothPrices();
    }

    /**
     * Check if we need display COD fee include tax
     *
     * @return bool
     */
    public function displayIncludeTax()
    {
        return Mage::helper('phoenix_cashondelivery')->displayCodFeeIncludingTax();
    }

    /**
     * Get COD fee include tax
     *
     * @return float
     */
    public function getCodFeeIncludeTax()
    {
        $codFeeInclTax = 0;
        foreach ($this->getTotal()->getAddress()->getQuote()->getAllShippingAddresses() as $address) {
            $codFeeInclTax += $address->getCodFee() + $address->getCodTaxAmount();
        }
        return $codFeeInclTax;
    }

    /**
     * Get COD fee exclude tax
     *
     * @return float
     */
    public function getCodFeeExcludeTax()
    {
        $codFeeExclTax = 0;
        foreach ($this->getTotal()->getAddress()->getQuote()->getAllShippingAddresses() as $address) {
            $codFeeExclTax += $address->getCodFee();
        }
        return $codFeeExclTax;
    }

    /**
     * Get label for COD fee include tax
     *
     * @return float
     */
    public function getIncludeTaxLabel()
    {
        return $this->helper('phoenix_cashondelivery')->__('Cash on Delivery fee (Incl.Tax)');
    }

    /**
     * Get label for COD fee exclude tax
     *
     * @return float
     */
    public function getExcludeTaxLabel()
    {
        return $this->helper('phoenix_cashondelivery')->__('Cash on Delivery fee (Excl.Tax)');
    }
}
