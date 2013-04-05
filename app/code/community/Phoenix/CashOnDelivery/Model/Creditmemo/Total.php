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

class Phoenix_CashOnDelivery_Model_Creditmemo_Total extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $cm)
    {

        $order = $cm->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'cashondelivery') {
            return $this;
        }

        $baseCmTotal = $cm->getBaseGrandTotal();
        $cmTotal = $cm->getGrandTotal();

        $baseCodFeeCredited = $order->getBaseCodFeeCredited();
        $codFeeCredited = $order->getCodFeeCredited();

        $baseCodFeeInvoiced = $order->getBaseCodFeeInvoiced();
        $codFeeInvoiced = $order->getCodFeeInvoiced();

        if ($cm->getInvoice()) {
            $invoice = $cm->getInvoice();
            $baseCodFeeToCredit = $invoice->getBaseCodFee();
            $codFeeToCredit = $invoice->getCodFee();
        } else {
            $baseCodFeeToCredit = $baseCodFeeInvoiced;
            $codFeeToCredit = $codFeeInvoiced;
        }

        if (!$baseCodFeeToCredit > 0) {
            return $this;
        }


        // Subtracting invoiced COD fee from Credit memo total
        //$cm->setBaseGrandTotal($baseCmTotal-$baseCodFeeToCredit);
        //$cm->setGrandTotal($cmTotal-$codFeeToCredit);

        //$cm->setBaseCodFee($baseCodFeeToCredit);
        //$cm->setCodFee($codFeeToCredit);

        //$order->setBaseCodFeeCredited($baseCodFeeCredited+$baseCodFeeToCredit);
        //$order->setCodFeeCredited($codFeeCredited+$baseCodFeeToCredit);


        return $this;
    }
}