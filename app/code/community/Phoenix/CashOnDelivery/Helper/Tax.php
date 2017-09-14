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
 * @copyright  Copyright (c) 2010 - 2017 PHOENIX MEDIA GmbH (http://www.phoenix-media.eu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Phoenix_CashOnDelivery_Helper_Tax extends Mage_Tax_Helper_Data
{
    public function getCalculatedTaxes($source)
    {
        $taxes = parent::getCalculatedTaxes($source);

        if (Mage::registry('current_invoice')) {
            $current = Mage::registry('current_invoice');
        } elseif (Mage::registry('current_creditmemo')) {
            $current = Mage::registry('current_creditmemo');
        } else {
            $current = $source;
        }

        if ($current !== $source) {
            $codTaxInfo = $this->_getCashOnDeliveryTax($current);
            array_unshift($taxes, $codTaxInfo);
        }

        return $taxes;
    }

    private function _getCashOnDeliveryTax($current)
    {
        $codTaxInfo = array(
            'tax_amount' => $current->getCodTaxAmount(),
            'base_tax_amount' => $current->getBaseCodTaxAmount(),
            'title' => $this->__('Cash on Delivery Tax'),
            'percent' => null
        );

        return $codTaxInfo;
    }
}
