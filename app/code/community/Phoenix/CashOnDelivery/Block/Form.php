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
 * @copyright  Copyright (c) 2008-2009 Andrej Sinicyn, Mik3e
 * @copyright  Copyright (c) 2010 - 2013 PHOENIX MEDIA GmbH (http://www.phoenix-media.eu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Phoenix_CashOnDelivery_Block_Form extends Mage_Payment_Block_Form
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'phoenix/cashondelivery/form.phtml';

    public function getQuote()
    {
        return $this->getMethod()->getInfoInstance()->getQuote();
    }

    public function getShippingAddress()
    {
        return $this->getQuote()->getShippingAddress();
    }

    public function convertPrice($price, $format=false, $includeContainer = true)
    {
        return $this->getQuote()->getStore()->convertPrice($price, $format, $includeContainer);
    }

}
