<?php
/**
 * Created by PhpStorm.
 * User: jonatan
 * Date: 05/05/14
 * Time: 09:51
 */

class Ceicom_CustomerAccount_Block_Street extends Mage_Directory_Block_Data
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->_address = Mage::getModel('customer/address');

        // Init address object
        if ($id = $this->getRequest()->getParam('id')) {
            $this->_address->load($id);
            if ($this->_address->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId()) {
                $this->_address->setData(array());
            }
        }

        if (!$this->_address->getId()) {
            $this->_address->setPrefix($this->getCustomer()->getPrefix())
                ->setFirstname($this->getCustomer()->getFirstname())
                ->setMiddlename($this->getCustomer()->getMiddlename())
                ->setLastname($this->getCustomer()->getLastname())
                ->setSuffix($this->getCustomer()->getSuffix());
        }

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->getTitle());
        }

        if ($postedData = Mage::getSingleton('customer/session')->getAddressFormData(true)) {
            $this->_address->addData($postedData);
        }
    }

    function getStreetLabel($id)
    {
        $Street = array(
            2 => 'Número',
            3 => 'Complemento',
            4 => 'Bairro'
        );

        return $Street[$id];
    }

    /**
     * Retrieve form data
     *
     * @return Varien_Object
     */
    public function getFormData()
    {
        $data = $this->getData('form_data');
        if (is_null($data)) {
            $formData = Mage::getSingleton('customer/session')->getCustomerFormData(true);
            $data = new Varien_Object();
            if ($formData) {
                $data->addData($formData);
                $data->setCustomerData(1);
            }
            if (isset($data['region_id'])) {
                $data['region_id'] = (int)$data['region_id'];
            }
            $this->setData('form_data', $data);
        }
        return $data;
    }

    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }
}