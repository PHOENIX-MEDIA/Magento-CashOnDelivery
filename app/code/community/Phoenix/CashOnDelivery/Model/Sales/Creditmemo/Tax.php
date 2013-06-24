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

class Phoenix_CashOnDelivery_Model_Sales_Creditmemo_Tax extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'phoenix_cashondelivery') {
            return $this;
        }

        $creditmemoBaseGrandTotal = $creditmemo->getBaseGrandTotal();
        $creditmemoGrandTotal     = $creditmemo->getGrandTotal();
        $creditmemoBaseTaxAmount  = $creditmemo->getBaseTaxAmount();
        $creditmemoTaxAmount      = $creditmemo->getTaxAmount();

        $baseCodTaxAmountRefunded = $order->getBaseCodTaxAmountRefunded();
        $codTaxAmountRefunded     = $order->getCodTaxAmountRefunded();

        $baseCodTaxAmountInvoiced = $order->getBaseCodTaxAmountInvoiced();
        $codTaxAmountInvoiced     = $order->getCodTaxAmountInvoiced();

        $baseCodTaxAmountToRefund = abs($baseCodTaxAmountInvoiced - $baseCodTaxAmountRefunded);
        $codTaxAmountToRefund     = abs($codTaxAmountInvoiced     - $codTaxAmountRefunded);

        if ($baseCodTaxAmountToRefund <= 0) {
            return $this;
        }

        $creditmemo->setBaseGrandTotal($creditmemoBaseGrandTotal + $baseCodTaxAmountToRefund)
                   ->setGrandTotal($creditmemoGrandTotal         + $codTaxAmountToRefund)
                   ->setBaseTaxAmount($creditmemoBaseTaxAmount   + $baseCodTaxAmountToRefund)
                   ->setTaxAmount($creditmemoTaxAmount           + $codTaxAmountToRefund)
                   ->setBaseCodTaxAmount($codTaxAmountToRefund)
                   ->setCodTaxAmount($codTaxAmountToRefund);

        $order->setBaseCodTaxAmountRefunded($baseCodTaxAmountRefunded + $baseCodTaxAmountToRefund)
              ->setCodTaxAmountRefunded($codTaxAmountRefunded         + $codTaxAmountToRefund);


        return $this;
    }
}