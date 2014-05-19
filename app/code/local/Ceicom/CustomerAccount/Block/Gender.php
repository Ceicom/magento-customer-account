<?php

class Ceicom_CustomerAccount_Block_Gender extends Mage_Customer_Block_Widget_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('ceicom/customeraccount/gender.phtml');
    }

    public function isEnabled()
    {
        return (bool)$this->_getAttribute('gender')->getIsVisible();
    }

    public function isRequired()
    {
        return (bool)$this->_getAttribute('gender')->getIsRequired();
    }

    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }
}
