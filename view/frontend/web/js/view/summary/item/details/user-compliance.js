/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define(['uiComponent'], function (Component) {
    'use strict';

    var quoteMessages = window.checkoutConfig.quoteMessages;

    return Component.extend({
        defaults: {
            template: 'Trellis_Compliance/summary/item/details/message'
        },
        displayArea: 'item_message',
        quoteMessages: quoteMessages,

        /**
         * @param {Object} item
         * @return {null}
         */
        getMessage: function (item) {

            let message = null;
            window.checkoutConfig.quoteItemData.forEach(function(el) {
                if (el.item_id == item.item_id) {
                    message = el.user_compliance;
                }
            });
            return message;
        }
    });
});
