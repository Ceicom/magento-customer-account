<?php 

class Ceicom_CustomerAccount_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_TAXVAT_EDITABLE          = 'customeraccount/customeraccount_options/taxvat_edit';

	const XML_PATH_TAXVAT_REQUIRED          = 'customeraccount/customeraccount_options/taxvat_required';
	const XML_PATH_DOB_REQUIRED             = 'customeraccount/customeraccount_options/dob_required';
	const XML_PATH_GENDER_REQUIRED          = 'customeraccount/customeraccount_options/gender_required';
    const XML_PATH_COMPLEMENT_REQUIRED      = 'customeraccount/customeraccount_options/adreress_complemento_required';

    const MESSAGE_TAXVAT_EXIST              = 'O CPF ou CNPJ informado já é cadastrado.';

	public function getConfig($path, $store = null)
	{
		return (bool)Mage::getStoreConfig($path, $store);
	}
	
    public function isTaxvatEditable($store = null)
    {
        return $this->getConfig(self::XML_PATH_TAXVAT_EDITABLE, $store);
    }

    public function isTaxvatRequired($store = null)
    {
        return $this->getConfig(self::XML_PATH_TAXVAT_REQUIRED, $store);
    }

    public function isDobRequired($store = null)
    {
        return $this->getConfig(self::XML_PATH_DOB_REQUIRED, $store);
    }

    public function isGenderRequired($store = null)
    {
        return $this->getConfig(self::XML_PATH_GENDER_REQUIRED, $store);
    }

    public function isComplementRequired($store = null)
    {
        return $this->getConfig(self::XML_PATH_COMPLEMENT_REQUIRED, $store);
    }

    public function taxvatExist($taxvat = '', $entityId = null)
    {
        $collection = Mage::getResourceModel('customer/customer_collection');
        $collection->addAttributeToFilter('taxvat', $taxvat);
        
        if($entityId != null){
            echo("Entity id: $entityId ");
            $collection->addFieldToFilter('entity_id',array('neq'=>$entityId));
        }
        $data = $collection->getData();
        return empty($data);
    }

    public function getMessageTaxvatExist()
    {
        return self::MESSAGE_TAXVAT_EXIST;
    }
}