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

class Phoenix_CashOnDelivery_Model_Sales_Creditmemo_Total extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    /**
     * @var Phoenix_CashOnDelivery_Helper_Data $_helper
     */
    private $_helper;

    public function __construct()
    {
        parent::__construct();
        $this->_helper = Mage::helper('phoenix_cashondelivery');
    }

    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'phoenix_cashondelivery') {
            return $this;
        }

        $baseCodFeeToRefund = $this->_getCodAmount($creditmemo);

        $baseCodFeeRefunded = $order->getBaseCodFeeRefunded();
        $codFeeRefunded = $order->getCodFeeRefunded();

        $creditmemoBaseGrandTotal = $creditmemo->getBaseGrandTotal();
        $creditmemoGrandTotal = $creditmemo->getGrandTotal();

        $ratio = $this->_helper->getBaseRatio($order);
        $codFeeToRefund = Mage::app()->getStore()->roundPrice($baseCodFeeToRefund * $ratio);

        if ($baseCodFeeToRefund <= 0) {
            return $this;
        }

        $creditmemo->setBaseGrandTotal($creditmemoBaseGrandTotal + $baseCodFeeToRefund)
            ->setGrandTotal($creditmemoGrandTotal + $codFeeToRefund)
            ->setBaseCodFee($baseCodFeeToRefund)
            ->setCodFee($codFeeToRefund);

        $order->setBaseCodFeeRefunded($baseCodFeeRefunded + $baseCodFeeToRefund)
            ->setCodFeeRefunded($codFeeRefunded + $codFeeToRefund);

        return $this;
    }

    private function _getCodAmount(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $controller = Mage::app()->getFrontController();
        $requestData = $controller->getRequest()->getParam('creditmemo');
        $codAmount = $requestData['cashondelivery_amount'];

        $order = $creditmemo->getOrder();

        /**
         * @var $helper Phoenix_CashOnDelivery_Helper_Data
         */
        $this->_helper = Mage::helper('phoenix_cashondelivery');

        if ($codAmount === null) {
            $codAmount = $order->getBaseCodFee() - $order->getBaseCodFeeRefunded();
            if ($this->_helper->codPriceIncludesTax()) {
                $codAmount += $order->getBaseCodTaxAmount() - $order->getBaseCodTaxAmountRefunded();
            }
        }

        /**
         * @var $model Phoenix_CashOnDelivery_Model_CashOnDelivery
         */
        $model = Mage::getModel('phoenix_cashondelivery/cashOnDelivery');

        $address = $creditmemo->getShippingAddress();

        $codFee = $model->getAddressCodFee($address, $order, $codAmount);

        if ($this->_helper->codPriceIncludesTax()) {
            $this->_addTaxToMemo($creditmemo, $codAmount);
        }

        $allowedRefund = $this->_getAllowedRefund($order);
        if ($allowedRefund + 0.0001 < $codAmount) {
            Mage::throwException(
                $this->_helper->__('Maximum Cash on Delivery amount allowed to refund is: %s', $allowedRefund)
            );
        }

        return $codFee;
    }

    private function _getAllowedRefund(Mage_Sales_Model_Order $order)
    {
        $baseCodFeeRefunded = $order->getBaseCodFeeRefunded();
        $baseCodFeeInvoiced = $order->getBaseCodFeeInvoiced();
        if ($this->_helper->codPriceIncludesTax()) {
            $baseCodFeeRefunded += $order->getBaseCodTaxAmountRefunded();
            $baseCodFeeInvoiced += $order->getBaseCodTaxAmountInvoiced();
        }
        $allowedBaseCodFee = $baseCodFeeInvoiced - $baseCodFeeRefunded;

        return $allowedBaseCodFee;
    }

    private function _addTaxToMemo(Mage_Sales_Model_Order_Creditmemo $creditmemo, $codFee)
    {
        $ratio = $this->_helper->getBaseRatio($creditmemo->getOrder());
        $codTax = $this->_helper->getCodTaxAmount($creditmemo->getOrder(), $codFee);
        $creditmemo->setBaseCodTaxAmount($codTax);
        $creditmemo->setCodTaxAmount($codTax * $ratio);
    }
}
