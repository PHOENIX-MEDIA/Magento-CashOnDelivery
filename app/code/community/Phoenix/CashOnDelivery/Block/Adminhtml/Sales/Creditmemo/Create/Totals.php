<?php
class Phoenix_CashOnDelivery_Block_Adminhtml_Sales_Creditmemo_Create_Totals extends Mage_Adminhtml_Block_Template
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'phoenix/cashondelivery/sales/creditmemo/create/totals.phtml';

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
            'block_name'=> $this->getNameInLayout(),
        ));

        $parent->addTotal($total, 'subtotal');
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
        $codFee = $this->getSource()->getCodFee();

        if ($this->helper('phoenix_cashondelivery')->codPriceIncludesTax()) {
            $codFee += $this->getSource()->getCodTaxAmount();
        }

        return Mage::app()->getStore()->roundPrice($codFee);
    }

    /**
     * Get label for refund subtotal
     * @return string Refund label
     */
    public function getRefundLabel()
    {
        $base = $this->helper('phoenix_cashondelivery')->__('Refund Cash on Delivery fee');

        $taxInclusionLabel = $this->helper('phoenix_cashondelivery')->codPriceIncludesTax() ? 'Incl.' : 'Excl.';
        $taxLabel = sprintf('(%s Tax)', $taxInclusionLabel);
        $tax = $this->helper('phoenix_cashondelivery')->__($taxLabel);

        return sprintf('%s %s', $base, $tax);
    }
}
