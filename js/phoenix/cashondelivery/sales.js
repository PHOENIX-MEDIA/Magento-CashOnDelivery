/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Phoenix
 * @package    Phoenix_CashOnDelivery
 * @copyright  Copyright (c) 2010 - 2013 PHOENIX MEDIA GmbH (http://www.phoenix-media.eu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var AdminOrder = Class.create(AdminOrder, {
    initialize : function($super, data){
        $super(data);
        Event.observe(window, 'load', this.updatePaymentMethod.bind(this));
    },
    updatePaymentMethod : function(){        
        if($('edit_form')){
            var radio;
            if (radio = $('edit_form').getInputs('radio','payment[method]').find(function(radio){ return radio.checked; })){
                if (radio.value == 'cashondelivery'){
                    this.switchPaymentMethod('cashondelivery');
                }
            }
        }
    },
    switchPaymentMethod : function(method){        
        this.setPaymentMethod(method);
        var data = {};
        data['order[payment_method]'] = method;
        //this.saveData(data);
        this.loadArea(['totals'], true, data);
    },
    selectAddress : function(el, container){
        //console.log('selectAddress');
        id = el.value;
        if(this.addresses[id]){
            this.fillAddressFields(container, this.addresses[id]);
        }
        else{
            this.fillAddressFields(container, {});
        }

        var data = this.serializeData(container);
        data[el.name] = id;
        var loadAreas = [];
        if(this.isShippingField(container) && !this.isShippingMethodReseted){
            this.resetShippingMethod(data);
        }
        else{
            loadAreas.push('billing_method');
            this.saveData(data);            
            if (this.paymentMethod == 'cashondelivery'){                
                loadAreas.push('totals');
            }
            if (loadAreas.length){
                data = {
                    json: true,
                    "payment[method]" : this.paymentMethod
                };
                this.loadArea(loadAreas, true, data);
            }
        }
    }
});

