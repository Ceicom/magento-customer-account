<?php

class Ceicom_CustomerAccount_Model_Account extends Mage_Core_Model_Abstract
{
	protected $_data;
	protected $_atrribute;
	protected $_defaultOptions = array(
        ''    => array('option' => '', 'is_required' => 0, 'is_visible' => 0),
        'opt' => array('option' => 'opt', 'is_required' => 0, 'is_visible' => 1),
        '1'   => array('option' => '1', 'is_required' => 0, 'is_visible' => 1),
        'req' => array('option' => 'req', 'is_required' => 1, 'is_visible' => 1),
    );

	public function setAttribute($attribute)
	{
		$this->_atrribute = $attribute;
		return $this;
	}

	public function saveValue()
	{
		Mage::getModel('core/config')->saveConfig("customer/address/{$this->_atrribute}_show", $this->_data['option']);
		$dobAtribute = Mage::getModel('eav/entity_attribute')->loadByCode('customer', $this->_atrribute);
		$dobAtribute->setData('is_required', $this->_data['is_required']);
		$dobAtribute->setData('is_visible', $this->_data['is_visible']);
		$dobAtribute->save();

		return $this;
	}

	public function setValue($data)
	{
		if (isset($data["{$this->_atrribute}_required"])) {
            $this->_data = $this->_defaultOptions[$data["{$this->_atrribute}_required"]];
        } else {
            $this->_data  = $this->_defaultOptions[''];
        }
        
        return $this;
	}
}