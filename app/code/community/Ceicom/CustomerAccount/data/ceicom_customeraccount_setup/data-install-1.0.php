<?php

Mage::getModel('core/config')->saveConfig('customer/address/dob_show', 'req');
Mage::getModel('core/config')->saveConfig('customer/address/taxvat_show', 'req');
Mage::getModel('core/config')->saveConfig('customer/address/gender_show', 'req');

$dobAtribute = Mage::getModel('eav/entity_attribute')->loadByCode('customer', 'dob');
$dobAtribute->setData('is_required', 1);
$dobAtribute->setData('is_visible', 1);
$dobAtribute->save();

$taxvatAtribute = Mage::getModel('eav/entity_attribute')->loadByCode('customer', 'taxvat');
$taxvatAtribute->setData('is_required', 1);
$taxvatAtribute->setData('is_visible', 1);
$taxvatAtribute->save();

$genderAtribute = Mage::getModel('eav/entity_attribute')->loadByCode('customer', 'gender');
$genderAtribute->setData('is_required', 1);
$genderAtribute->setData('is_visible', 1);
$genderAtribute->save();