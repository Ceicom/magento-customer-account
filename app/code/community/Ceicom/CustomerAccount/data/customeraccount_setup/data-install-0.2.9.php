<?php

Mage::getModel('core/config')->saveConfig('customer/address/dob_show', 'req');
Mage::getModel('core/config')->saveConfig('customer/address/taxvat_show', 'req');
Mage::getModel('core/config')->saveConfig('customer/address/gender_show', 'req');

Mage::getModel('core/config')->saveConfig('customeraccount/customeraccount_options/taxvat_edit', '0');
Mage::getModel('core/config')->saveConfig('customeraccount/customeraccount_options/taxvat_required', 'opt');
Mage::getModel('core/config')->saveConfig('customeraccount/customeraccount_options/dob_required', 'opt');
Mage::getModel('core/config')->saveConfig('customeraccount/customeraccount_options/gender_required', 'opt');
Mage::getModel('core/config')->saveConfig('customeraccount/customeraccount_options/adreress_complemento_required', 'opt');


$dobAtribute = Mage::getModel('eav/entity_attribute')->loadByCode('customer', 'dob');
$dobAtribute->setData('is_required', 0);
$dobAtribute->setData('is_visible', 1);
$dobAtribute->save();

$taxvatAtribute = Mage::getModel('eav/entity_attribute')->loadByCode('customer', 'taxvat');
$taxvatAtribute->setData('is_required', 0);
$taxvatAtribute->setData('is_visible', 1);
$taxvatAtribute->save();

$genderAtribute = Mage::getModel('eav/entity_attribute')->loadByCode('customer', 'gender');
$genderAtribute->setData('is_required', 0);
$genderAtribute->setData('is_visible', 1);
$genderAtribute->save();