define(
    [
        'jquery',
        'Magento_Ui/js/modal/modal',
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/model/customer',
        'mage/url'
    ],
    function ($,
        modal,
        ko,
        Component,
        _,
        stepNavigator,
        customer,
        url
    ) {
        'use strict';
        /**
         * mystep - is the name of the component's .html template
         */
        return Component.extend({
            defaults: {
                template: 'Harriswebworks_AdobeSignCheckout/mystep'
            },

            //add here your logic to display step,
            isVisible: ko.observable(false),
            isLogedIn: customer.isLoggedIn(),
            //step code will be used as step content id in the component template
            stepCode: 'adobeSignint',
            //step title value
            stepTitle: 'Sign Agreement',

            /**
             *
             * @returns {*}
             */
            initialize: function () {
                this._super();
                // register your step
                stepNavigator.registerStep(
                    this.stepCode,
                    //step alias
                    null,
                    this.stepTitle,
                    //observable property with logic when display step or hide step
                    this.isVisible,

                    _.bind(this.navigate, this),

                    /**
                     * sort order value
                     * 'sort order value' < 10: step displays before shipping step;
                     * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                     * 'sort order value' > 20 : step displays after payment step
                     */
                    15
                );
                var tid;
                $(document).on('click', '#click-me', function () {
                    jQuery('body').trigger('processStart');
                    $(".modal-inner-wrap").delay(1000).fadeIn();   
                    $.ajax({
                        url: url.build('adobesigncheckout/index/index'),
                        method: "POST",
                        dataType: "json",
                        success: function (data) {
                            if (data != '') {
//                                console.log(data.output);
                                tid=data.lastSentAgreementID;
//                                console.log(data.lastSentAgreementID);
                                $('.sign-iframe').attr('src', data.output);
                            } else {
                                console.log("No Data found");
                            }

                        },
                        complete: function (data) {
                            jQuery('body').trigger('processStop');
                           }
                    });
                });

                $(document).on('click', '.action-close', function () {
                   
                    // Do some action when modal closed
                    var settings = {
                        "url": "https://api.na3.adobesign.com/api/rest/v6/agreements/"+tid,
                        "method": "GET",
                        "timeout": 0,
                        "headers": {
                            "x-api-user": "email:adobesign1999@gmail.com",
                            "Authorization": "Bearer 3AAABLblqZhByeyKN73VAQYcQjrZoZAp-wPZoZq7h2F-ItpOKQ4KYi-qTP_nHTKhf-Q7hSHd3fX6Nkag3N0uW0IdGzcdonTq-"
                        },
                    };
                    $.ajax(settings).done(function (response) {
                        console.log((response.status));
                         if(response.status=="SIGNED"){
                            $('.signnext').css('display','block');
                            $('.thankutext').css('display','block');
                            $('#click-me').css('display','none');
                            $('.view-sign-text').css('display','none');
                            
                         }
                    });
                });
                
                // var options = {
                //     type: 'popup',
                //     responsive: true,
                //     innerScroll: true,
                //     buttons: [{
                //         text: $.mage.__('Continue'),
                //         class: '',
                //         click: function () {
                //             this.closeModal();
                //         }
                //     }]
                // };

                // var popup = modal(options, $('#popup-modal'));
                // $("#click-me").on('click',function(){ 
                //     $("#popup-modal").modal("openModal");
                // });

                return this;
            },

            /**
             * The navigate() method is responsible for navigation between checkout step
             * during checkout. You can add custom logic, for example some conditions
             * for switching to your custom step
             */
            navigate: function () {

            },

            /**
             * @returns void
             */
            navigateToNextStep: function () {
                stepNavigator.next();
            }
        });
    }
);
