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
    public function sales_quote_collect_totals_after(Varien_Event_Observer $observer) 
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
    public function sales_order_payment_place_end(Varien_Event_Observer $observer) 
    {        
        $payment = $observer->getPayment();
        if ($payment->getMethodInstance()->getCode() != 'phoenix_cashondelivery') {
            return $this;;
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
     * Performs order_create_loadBlock response update
     * adds totals block to each response
     * This function is deprecated, the totals block update is implemented
     * in phoenix/cashondelivery/sales.js (SalesOrder class extension)
     * 
     * @param Varien_Event_Observer $observer
     * @return Phoenix_CashOnDelivery_Model_Observer
     */
    public function controller_action_layout_load_before(Varien_Event_Observer $observer) 
    {        
        $action = $observer->getAction();

        if ($action->getFullActionName() != 'adminhtml_sales_order_create_loadBlock' || !$action->getRequest()->getParam('json')) {
            return $this;
        }

        $layout = $observer->getLayout();
        $layout->getUpdate()->addHandle('adminhtml_sales_order_create_load_block_totals');

        return $this;
    }

    /**
     * When the order gets canceled we put the Cash on Delivery fee and tax also in the canceled columns.
     *
     * @param Varien_Event_Observer $observer
     * @return Phoenix_CashOnDelivery_Model_Observer
     */
    public function order_cancel_after(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'phoenix_cashondelivery') {
            return $this;
        }

        $codFee     = $order->getCodFee();
        $baseCodFee = $order->getBaseCodFee();
        $codTax     = $order->getCodTaxAmount();
        $baseCodTax = $order->getBaseCodTaxAmount();

        $codFeeInvoiced     = $order->getCodFeeInvoiced();

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
}