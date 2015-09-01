/**
 * Created by thainv on 8/20/2015.
 */
var calculatePrice = Class.create({
    initialize: function (id) {
        this.url = $(id).readAttribute('data_url');
        this.addMeasureOdd(0);
    },

    /**
     * Add odd number for drop and width
     *
     * @param unitType
     */
    addMeasureOdd: function (unitType) {
        var targetOpt = '';
        switch (unitType) {
            case 0:/*cm*/
                for (var i = 0; i <= 9; i++) {
                    if (i == 0) {
                        targetOpt += "<option selected='selected' value='0'>0</option>";

                    } else {
                        var cm = "0." + i;
                        targetOpt += '<option value="' + cm + '">' + cm + '</option>';
                    }
                }
                break;
            case 1:/*inch*/
                for (var j = 0; j <= 7; j++) {
                    if (j == 0) {
                        targetOpt += '<option selected="selected" value="0">0</option>';
                    } else {
                        var inch = j / 8 * j;
                        targetOpt += '<option value="' + inch + '">' + inch + '</option>';
                    }
                }
                break;
        }
        $('measure-unit').value = unitType;
        $('drop_odd').update(targetOpt);
        $('width_odd').update(targetOpt);
    },

    validateMeasure: function () {
        if ($('measure_drop').value === '') {
            var mess = 'Drop cannot empty!';
            alert(mess);
            return false;
        }
        if ($('measure_width').value === '') {
            var mess = 'Width cannot empty!';
            alert(mess);
            return false;
        }
        return true;
    },

    calculatePrice: function () {
        this.collectData();
        //if (this.validateMeasure()) {
        new Ajax.Request(this.url, {
            method: 'get',
            parameters: this.collectData(),
            onLoading: function () {
                $('loading').show();
            },
            onSuccess: function (transport) {
                $('loading').hide();
                var obj = transport.responseText.evalJSON();
                var currency = $('currency_symbol').value;
                var matrixPrice = currency + obj.price;
                $('matrix_price_result').update(matrixPrice);
                $$('.product-shop .price-box .price').each(function (i) {
                    i.update(matrixPrice);
                })
                $$('.price').update(matrixPrice);
                $('price_matrix').setAttribute('value', obj.price);
            }
        });
        //}
    },

    collectData: function () {
        return $('product_addtocart_form').serialize();
    },
    checkInteger: function (obj) {
        var number = obj.value;
        number = number.replace(/\./g, '');
        number = number.replace(/-/g, '');
        number = number.replace(/\+/g, '');
        var additionExp = '101';
        if (parseInt(number + additionExp) != number + additionExp) {
            obj.value = number.substr(0, number.length - 1);
            if (number.length <= 1) {
                obj.value = "";
            }
            return false;
        }
        obj.value = number;
        return true;
    },

    indirectChooseOption: function (evt) {
        var pin = '<div class="sprite pin"></div>';
        var option_type = evt.target.readAttribute('option_type');
        var data_id = evt.target.readAttribute('data_id');
        if (option_type == 'checkbox') {/*Checkbox*/
            if ($(data_id).checked) {
                $(data_id).checked = false;
                evt.target.up('div.colorSwatchWrap').removeClassName('selected');
                evt.target.up('div.colorSwatchWrap').select('.pin').invoke('remove');
            } else {
                evt.target.up('div.colorSwatchWrap').insert(pin);
                evt.target.up('div.colorSwatchWrap').addClassName('selected');
                $(data_id).checked = true;
            }
        } else {
            evt.target.up('.content').select('.colorSwatchWrap').each(function (i) {
                i.removeClassName('selected');
                if (i.select('.pin').length > 0) {
                    i.select('.pin').invoke('remove');
                }
            });
            evt.target.up('div.colorSwatchWrap').insert(pin);
            evt.target.up('div.colorSwatchWrap').addClassName('selected');

            if (option_type == 'radio') {/*Radio*/
                $(data_id).checked = true;
            } else if (option_type == 'drop_down') {/*Drop down*/
                var optId = evt.target.readAttribute('option_id');
                var optTypeId = evt.target.readAttribute('option_type_id');
                for (var i = 0; i < $('select_' + optId).length; i++) {
                    if ($('select_' + optId)[i].value == optTypeId) {
                        $('select_' + optId)[i].selected = true;
                    }
                }
            }
        }
        this.calculatePrice();
    },

    calculateMeasure: function (obj) {
        var value = obj.value;
        var targetEl = obj.readAttribute('targetElm');
        $(targetEl).value = value;
        this.calculatePrice();
    },

    validateOptions: function (elm) {
        /*Validate measure*/
        var measureError = 0;
        if ($('measure_drop').value === '') {
            $('measure_drop').addClassName('validation-failed');
            measureError = 1;
        } else {
            $('measure_drop').removeClassName('validation-failed');
        }
        if ($('measure_width').value === '') {
            measureError = 1;
            $('measure_width').addClassName('validation-failed');
        } else {
            $('measure_width').removeClassName('validation-failed');
        }
        if (measureError === 1) {
            if ($('section0').hasClassName('accordion-close')) {
                $('section0').click();
            }
            $('measure_form').scrollTo();
            return false;
        }
        /*End Validate measure*/

        /*Validate custom option*/
        var optionsError = 0;
        var option = '';
        $$('.available_options').each(function (i) {
            var options = i.select('.colorSwatchWrap');
            var count = 0;
            options.each(function (j) {
                if (j.hasClassName('selected')) {
                    count += 1;
                }
            });
            if (count == 0) {
                optionsError += 1;
                option = i;
            } else {
                i.removeClassName('validation-failed');
            }
        });
        if (optionsError > 0) {
            if (option.previous('.accordion').hasClassName('accordion-close')) {
                option.previous('.accordion').click();
            }
            option.previous('.accordion').scrollTo();
            option.addClassName('validation-failed');
            return false;
        }
        /*End Validate custom option*/
        productAddToCartForm.submit(elm)
    }
});