jQuery( document).ready(function() {

    jQuery('[name*="telephone"]').attr('data-type', "phone");
    jQuery('[name*="postcode"]').attr('data-type', "postcode");
    jQuery('[name*="taxvat"]').attr('data-type', "taxvat");

    CustomerAccount.create();
});