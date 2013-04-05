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
 * @copyright  Copyright (c) 2010 Phoenix Medien GmbH & Co. KG (http://www.phoenix-medien.de)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Phoenix_CashOnDelivery_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Collects codFee from qoute/addresses to quote
     *
     * @param Varien_Event_Observer $observer
     *
     */
    public function sales_quote_collect_totals_after(Varien_Event_Observer $observer) 
    {        
        $quote = $observer->getEvent()->getQuote();
        $data = $observer->getInput();
        $quote->setCodFee(0);
        $quote->setBaseCodFee(0);
        $quote->setCodTaxAmount(0);
        $quote->setBaseCodTaxAmount(0);
        foreach ($quote->getAllAddresses() as $address) {
            $quote->setCodFee((float) $quote->getCodFee() + $address->getCodFee());
            $quote->setBaseCodFee((float) $quote->getBaseCodFee() + $address->getBaseCodFee());

            $quote->setCodTaxAmount((float) $quote->getCodTaxAmount() + $address->getCodTaxAmount());
            $quote->setBaseCodTaxAmount((float) $quote->getBaseCodTaxAmount() + $address->getBaseCodTaxAmount());
        }
    }

    /**
     * Adds codFee to order
     * 
     * @param Varien_Event_Observer $observer
     */
    public function sales_order_payment_place_end(Varien_Event_Observer $observer) 
    {        
        $payment = $observer->getPayment();
        if ($payment->getMethodInstance()->getCode() != 'cashondelivery') {
            return;
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
    }

    /**
     * Performs order_creage_loadBlock response update
     * adds totals block to each response
     * This function is depricated, the totals block update is implemented
     * in phoenix/cashondelivery/sales.js (SalesOrder class extension)
     * 
     * @param Varien_Event_Observer $observer
     */
    public function controller_action_layout_load_before(Varien_Event_Observer $observer) 
    {        
        $action = $observer->getAction();
        if ($action->getFullActionName() != 'adminhtml_sales_order_create_loadBlock' ||
            !$action->getRequest()->getParam('json')) {
            return;
        }
        $layout = $observer->getLayout();
        $layout->getUpdate()->addHandle('adminhtml_sales_order_create_load_block_totals');
    }

}