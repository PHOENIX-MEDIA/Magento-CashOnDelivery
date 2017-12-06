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

class Phoenix_CashOnDelivery_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Collects codFee from quote/addresses to quote
     *
     * @param Varien_Event_Observer $observer
     * @return Phoenix_CashOnDelivery_Model_Observer
     */
    public function salesQuoteCollectTotalsAfter(Varien_Event_Observer $observer) 
    {
        $quote = $observer->getEvent()->getQuote();
        $data  = $observer->getInput();

        $quote->setCodFee(0);
        $quote->setBaseCodFee(0);
        $quote->setCodTaxAmount(0);
        $quote->setBaseCodTaxAmount(0);

        foreach ($quote->getAllAddresses() as $address) {
            $quote->setCodFee((float)($quote->getCodFee() + $address->getCodFee()));
            $quote->setBaseCodFee((float)($quote->getBaseCodFee() + $address->getBaseCodFee()));
            $quote->setCodTaxAmount((float)($quote->getCodTaxAmount() + $address->getCodTaxAmount()));
            $quote->setBaseCodTaxAmount((float)($quote->getBaseCodTaxAmount() + $address->getBaseCodTaxAmount()));
        }
        return $this;
    }

    /**
     * Adds codFee to order
     * 
     * @param Varien_Event_Observer $observer
     * @return Phoenix_CashOnDelivery_Model_Observer
     */
    public function salesOrderPaymentPlaceEnd(Varien_Event_Observer $observer) 
    {
        $payment = $observer->getPayment();
        if ($payment->getMethodInstance()->getCode() != 'phoenix_cashondelivery') {
            return $this;
        }

        $order = $payment->getOrder();
        $quote = Mage::getSingleton('checkout/session')->getQuote();

        if (!$quote->getId()) {
            $quote = Mage::getSingleton('adminhtml/session_quote')->getQuote();
        }

        $order->setCodFee($quote->getCodFee());
        $order->setBaseCodFee($quote->getBaseCodFee());
        $order->setCodTaxAmount($quote->getCodTaxAmount());
        $order->setBaseCodTaxAmount($quote->getBaseCodTaxAmount());
        $order->save();

        return $this;
    }

    /**
     * When the order gets canceled we put the Cash on Delivery fee and tax also in the canceled columns.
     *
     * @param Varien_Event_Observer $observer
     * @return Phoenix_CashOnDelivery_Model_Observer
     */
    public function orderCancelAfter(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'phoenix_cashondelivery') {
            return $this;
        }

        $codFee     = $order->getCodFee();
        $baseCodFee = $order->getBaseCodFee();
        $codTax     = $order->getCodTaxAmount();
        $baseCodTax = $order->getBaseCodTaxAmount();

        $codFeeInvoiced = $order->getCodFeeInvoiced();

        if ($codFeeInvoiced) {
            $baseCodFeeInvoiced = $order->getBaseCodFeeInvoiced();
            $codTaxInvoiced     = $order->getCodTaxAmountInvoiced();
            $baseCodTaxInvoiced = $order->getBaseCodTaxAmountInvoiced();

            $codFee     = $codFee     - $codFeeInvoiced;
            $baseCodFee = $baseCodFee - $baseCodFeeInvoiced;
            $codTax     = $codTax     - $codTaxInvoiced;
            $baseCodTax = $baseCodTax - $baseCodTaxInvoiced;
        }

        if ($baseCodFee) {
            $order->setCodFeeCanceled($codFee)
                  ->setBaseCodFeeCanceled($baseCodFee)
                  ->setCodTaxAmountCanceled($codTax)
                  ->setBaseCodTaxAmountCanceled($baseCodTax)
                  ->save();
        }

        return $this;
    }


    /**
     * Invoice and set status to Cash On Delivery if order payment type is Cash on Delivery
     *
     * @author: https://github.com/Flipmediaco/Magento-CashOnDelivery/blob/8ae36cffd3bbab8e61852810c21a3c3a558378b4/app/code/community/Phoenix/CashOnDelivery/Model/Observer.php
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function invoiceCashOnDelivery($observer)
    {
		// Get order
		$order = $observer->getEvent()->getOrder();
    	
		// If order is instance of Phoenix_CashOnDelivery_Model_CashOnDelivery
		if ($order->getPayment()->getMethodInstance()->getCode() == 'phoenix_cashondelivery' && 
			// Can be invoiced
			$order->canvoice() &&
			// order_invoice is true
			Mage::getStoreConfig('payment/phoenix_cashondelivery/order_invoice')) {
			// Prepare invoice
			$invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
			// Set capture case - OFFLINE
			$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
			$invoice->register();
			//
			// Save transaction
			$transactionSave =	Mage::getModel('core/resource_transaction')
								->addObject($invoice)
								->addObject($invoice->getOrder());
			$transactionSave->save();
		}
    }
}