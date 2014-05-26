<?php

class Ceicom_CustomerAccount_Model_Observer extends Mage_Core_Controller_Front_Action
{
	public function alterCustomerAccount($observer)
	{
		$params = Mage::app()->getRequest()->getParams();

		if (mage::helper('ceicom_customeraccount')->taxvatExist($params['taxvat'])) {
		    return;
		}

		Mage::getSingleton('core/session')->addError(mage::helper('ceicom_customeraccount')->getMessageTaxvatExist());
		Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/create'))->sendResponse();
		exit;
	}

	public function observersection($observer)
	{
		$params = Mage::app()->getRequest()->getParam('groups')['customeraccount_options']['fields'];
		
		foreach ($params as $key => $value) {
			
			$attributeCode = str_replace('_required', '', $key);
			
			if(!strstr($attributeCode, '_')){	
				
				Mage::getModel('ceicom_customeraccount/account')->setAttribute($attributeCode)
					->setValue(array($key => $value['value']))
					->saveValue();
			}
		}
	}
}