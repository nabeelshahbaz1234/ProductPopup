define([
    'jquery'
], function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.catalogAddToCart', widget, {
            /**
             * Enhance the ajaxSubmit method.
             * @param {Object} form
             */
            ajaxSubmit: function (form) {
                this._super(form);
                let prodDetails = [];
                $.ajax({
                    url: window.BASE_URL + "/customcatalog/index/productdetails",
                    type: "POST",
                    data: {'sku': form.data().productSku},
                    success: function (response) {
                        let prodDescription = "<h1 style='text-align:center'>Related Products Details</h1><hr>";
                        var popup = $('<div class="add-to-cart-modal-popup"/>').html('<span>' + prodDescription + '</span><br>' + response.details + '<br></span>').modal({
                            modalClass: 'add-to-cart-popup',
                            // Added Inner Slider in a Popup
                            innerScroll: true,
                            responsive: true,
                            clickableOverlay: false,

                            title: $.mage.__($('.page-title span').text()),
                            buttons: [
                                {
                                    text: 'Continue Shopping',
                                    click: function () {
                                        this.closeModal();
                                    }
                                },
                                {
                                    text: 'Proceed to Checkout',
                                    click: function () {
                                        window.location = window.checkout.checkoutUrl
                                    }
                                }
                            ]
                        });
                        popup.modal('openModal');
                    }
                });
            }
        });

        return $.mage.catalogAddToCart;
    };
});
