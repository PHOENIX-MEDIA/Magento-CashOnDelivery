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

class Phoenix_CashOnDelivery_Model_Sales_Pdf_Cod extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    /**
     * Get array of arrays with totals information for display in PDF
     * array(
     *  $index => array(
     *      'amount'   => $amount,
     *      'label'    => $label,
     *      'font_size'=> $font_size
     *  )
     * )
     * @return array
     */
    public function getTotalsForDisplay()
    {
        $amount        = $this->getOrder()->formatPriceTxt($this->getAmount());
        $amountInclTax = $this->getAmount() + $this->getSource()->getCodTaxAmount();
        $amountInclTax = $this->getOrder()->formatPriceTxt($amountInclTax);
        $fontSize      = $this->getFontSize() ? $this->getFontSize() : 7;
        $helper        = Mage::helper('phoenix_cashondelivery');

        if ($helper->displayCodBothPrices()){
            $totals = array(
                array(
                    'amount'    => $this->getAmountPrefix().$amount,
                    'label'     => $helper->__('Cash on Delivery fee (Excl.Tax)') . ':',
                    'font_size' => $fontSize
                ),
                array(
                    'amount'    => $this->getAmountPrefix().$amountInclTax,
                    'label'     => $helper->__('Cash on Delivery fee (Incl.Tax)') . ':',
                    'font_size' => $fontSize
                ),
            );
        } elseif ($helper->displayCodFeeIncludingTax()) {
            $totals = array(
                array(
                    'amount'    => $this->getAmountPrefix().$amountInclTax,
                    'label'     => $helper->__($this->getTitle()) . ':',
                    'font_size' => $fontSize
                )
            );
        } else {
            $totals = array(
                array(
                    'amount'    => $this->getAmountPrefix().$amount,
                    'label'     => $helper->__($this->getTitle()) . ':',
                    'font_size' => $fontSize
                )
            );
        }
        return $totals;
    }
}
