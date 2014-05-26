<?php

class Ceicom_CustomerAccount_Block_Taxvat extends Mage_Customer_Block_Widget_Abstract
{
	public function _construct()
    {
        parent::_construct();
        $this->setTemplate('ceicom/customeraccount/taxvat.phtml');
    }

    public function isEnabled()
    {
        return (bool)$this->_getAttribute('taxvat')->getIsVisible();
    }

    public function isRequired()
    {
        return (bool)$this->_getAttribute('taxvat')->getIsRequired();
    }

    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function isEditable()
    {
        $AtionName = Mage::app()->getRequest()->getActionName();
        if($AtionName == 'edit'){
            return (bool)Mage::Helper('ceicom_customeraccount')->isTaxvatEditable();
        }else{
            return true;
        }
    }
}