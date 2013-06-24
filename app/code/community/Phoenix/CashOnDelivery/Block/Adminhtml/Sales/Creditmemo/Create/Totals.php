<?php
class Phoenix_CashOnDelivery_Block_Adminhtml_Sales_Creditmemo_Create_Totals extends Mage_Adminhtml_Block_Template
{
    /**
     * Holds the creditmemo object.
     * @var Mage_Sales_Model_Order_Creditmemo
     */
    protected $_source;

    /**
     * Initialize creditmemo CoD totals
     *
     * @return Phoenix_CashOnDelivery_Block_Adminhtml_Sales_Creditmemo_Create_Totals
     */
    public function initTotals()
    {
        $parent         = $this->getParentBlock();
        $this->_source  = $parent->getSource();
        $total          = new Varien_Object(array(
            'code'      => 'phoenix_cashondelivery_fee',
            'value'     => $this->getCodAmount(),
            'base_value'=> $this->getCodAmount(),
            'label'     => $this->helper('phoenix_cashondelivery')->__('Refund Cash on Delivery fee')
        ));

        $parent->addTotalBefore($total, 'shipping');
        return $this;
    }

    /**
     * Getter for the creditmemo object.
     *
     * @return Mage_Sales_Model_Order_Creditmemo
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * Get CoD fee amount for actual invoice.
     * @return float
     */
    public function getCodAmount()
    {
        $codFee = $this->_source->getCodFee() + $this->_source->getCodTaxAmount();

        return Mage::app()->getStore()->roundPrice($codFee);
    }
}