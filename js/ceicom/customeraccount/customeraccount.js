var CustomerAccount = function () {};
CustomerAccount.create = function (  ) {
  var instance = new this();
  instance.init();
  return instance;
};
CustomerAccount.prototype.init = function () {
    this.getElements();
    this.mask();
    this.event();
    this.validation();
};
CustomerAccount.prototype.getElements = function() {
    this.elements = {
        birthDay: jQuery('[data-type="day"]'),
        birthMonth: jQuery('[data-type="month"]'),
        birthYear: jQuery('[data-type="year"]'),
        phone: jQuery('[data-type="phone"]'), 
        postcode: jQuery('[data-type="postcode"]'), 
        taxvat: jQuery('[data-type="taxvat"]')
    };
};
CustomerAccount.prototype.mask = function() {
    var customeraccount = this;
    this.elements.birthDay.mask('99');
    this.elements.birthMonth.mask('99');
    this.elements.birthYear.mask('9999');
    this.elements.postcode.mask('99999-999');
    this.customMasks.phone(this.elements.phone);
    this.elements.taxvat.each(function (i,a) {
        if( this.type != 'hidden' ) {
            jQuery(this).mask(customeraccount.customMasks.taxvat(this));
        }
    });
};
CustomerAccount.prototype.event = function() {
    var customeraccount = this;
    this.elements.taxvat.on('focus', function() {
        jQuery(this).data('mask').remove();
        jQuery(this).keyup(function() {
            jQuery(this)
                .val(jQuery(this).val().replace(/[^0-9]/g, ''))
                .attr('maxlength', '14');
        });
    }).on('blur', function() {
        var _this = jQuery(this);
        console.log(customeraccount.customMasks.taxvat(this));
        _this.mask(customeraccount.customMasks.taxvat(this));
        jQuery('[name*="taxvat"][type="hidden"]').val(_this.val().replace(/[^0-9]/g, ''));
    });
    this.elements.postcode.not('[name="estimate_postcode"]').on('keyup blur', function () {
        if(jQuery(this).val().length >= 9){
            customeraccount.autoComplete();
        }
    });
};
CustomerAccount.prototype.customMasks = {
    phone: function (phone) {
        var masks = ['(00) 00000-0000', '(00) 0000-00000'];
        phone.mask(masks[1], {
            onKeyPress: function(val, e, field, options) {
                var mask = val.length > 14 ? masks[0] : masks[1];
                field.mask(mask, options);
           }
        });
    }, 
    taxvat: function (taxvat) {
        return jQuery.trim(taxvat.value.replace(/[^0-9]/g, '')).length < 12 ? '999.999.999-99' : '99.999.999/9999-99';
    }, 
};
CustomerAccount.prototype.autoComplete = function() {
    var customeraccount = this;
    customeraccount.elements.postcode.each(function () {
        var postcode = jQuery(this),
        form = postcode.closest('.form-list'),
        street1 = form.find('[name*="street"]').eq(0),
        street2 = form.find('[name*="street"]').eq(3),
        city = form.find('[name*="city"]'),
        region = form.find('[name*="region_id"]');

        if ((jQuery.trim(postcode.val()).length == 9) && (jQuery.trim(postcode.val()) != postcode.data('data-postcode'))) {
            postcode.data('data-postcode', jQuery.trim(postcode.val()));
            jQuery.ajax({
                url: "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20html%20where%20url%3D%22http%3A%2F%2Fm.correios.com.br%2Fmovel%2FbuscaCepConfirma.do%3FcepEntrada%3D" + jQuery.trim(postcode.val()).replace('-', '') + "%26metodo%3DbuscarCep%22%20and%20xpath%3D'%2F%2Fdiv%5Bcontains(%40class%2C%22caixacampobranco%22)%5D'&format=json&callback=",
                beforeSend: function() {
                    customeraccount.elements.postcode
                        .parent()
                        .find('.please-wait')
                        .show();
                    customeraccount.removeValidationAdvice(customeraccount.elements.postcode);
                    street1
                        .attr('disabled', 'disabled')
                        .val('');
                    street2
                        .attr('disabled', 'disabled')
                        .val('');
                    city
                        .attr('disabled', 'disabled')
                        .val('');
                    region
                        .attr('disabled', 'disabled')
                        .children()
                        .first()
                        .prop('selected', 'selected');
                },
                success: function(data) {
                    var results = data.query.results;
                    var address = [];
                    if (results) {
                        for (var i = 0; i < results.div.span.length; i++) {
                            address[results.div.span[i].content] = results.div.span[i + 1].content;
                            i++;
                        }
                        var addressStreet1 = address['Logradouro:'];
                        var addressStreet2 = address['Bairro:'];
                        // Set street
                        if (addressStreet1) {
                            street1.val(addressStreet1);
                            customeraccount.removeValidationAdvice(street1);
                        }
                        if (addressStreet2) {
                            street2.val(addressStreet2);
                            customeraccount.removeValidationAdvice(street2);
                        }
                        // Set city and region
                        var cityRegion = address['Localidade / UF:'].split(' /');
                        if (jQuery.trim(cityRegion[0])) {
                            city.val(jQuery.trim(cityRegion[0]));
                            customeraccount.removeValidationAdvice(city);
                        }
                    } else {
                        console.log('not found');
                        return false;
                    }
                },
                complete: function() {
                    customeraccount.elements.postcode.parent().find('.please-wait').hide();
                    street1.removeAttr('disabled');
                    street2.removeAttr('disabled');
                    city.removeAttr('disabled');
                    region.removeAttr('disabled');
                }
            });
        } 
    });
};
CustomerAccount.prototype.removeValidationAdvice = function(el) {
    el.removeClass('validation-failed');
    el.parent().find('.validation-advice').fadeOut('fast');
};
CustomerAccount.prototype.validation = function() {
    var customeraccountform = this;
    Validation.add('validate-taxvat', 'Informe o CPF/CNPJ corretamente.', function(v) {
        return customeraccountform.isTaxvat(customeraccountform.elements.taxvat.val());
    });
    Validation.add('validate-phone', 'Informe um nÃºmero vÃ¡lido.', function(v, el) {
        if (jQuery(el).is('[name*="telephone"]')) {
            return customeraccountform.isPhone(v);
        } else {
            if (v.length > 0)
                return customeraccountform.isPhone(v);
            else
                return true;
        }
    });
    Validation.add('validate-zip-international', 'Informe um CEP vÃ¡lido.', function(v) {
        return customeraccountform.isPostcode(v);
    });
};
CustomerAccount.prototype.isTaxvat = function(val) {
    
    if (val.length == 14) {

        val = val.replace(/[^\d]+/g, '');
        if (val.length != 11)
            return false;

        for (var i = 0; i < 10; i++) {
            var valT = '';
            for (var j = 0; j < 11; j++)
                valT = valT + i;
            if (val == valT)
                return false;
        }

        add = 0;
        for (i = 0; i < 9; i++)
            add += parseInt(val.charAt(i)) * (10 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(val.charAt(9)))
            return false;

        add = 0;
        for (i = 0; i < 10; i++)
            add += parseInt(val.charAt(i)) * (11 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(val.charAt(10)))
            return false;

        return true;

    } else if (val.length == 18) {

        val = val.replace(/[^\d]+/g, '');

        for (var i = 0; i < 10; i++) {
            var valT = '';
            for (var j = 0; j < 11; j++)
                valT = valT + i;
            if (val == valT)
                return false;
        }

        tamanho = val.length - 2
        numeros = val.substring(0, tamanho);
        digitos = val.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;

        tamanho = tamanho + 1;
        numeros = val.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;

        return true;

    }

    else
        return false;

};
CustomerAccount.prototype.isPhone = function(val) {
    var _match = val.match(/\(\d{2}\) \d{4,5}-\d{4}/);
    if (_match) {
        if (_match.length > 0)
            return true;
    }
    return false;
};
CustomerAccount.prototype.isPostcode = function(val) {
    var _match = val.match(/\d{5}-\d{3}/);
    if (_match) {
        if (_match.length > 0)
            return true;
    }
    return false;
};