//Test plugin
(function() {

    function subscribeToEvents(placeholder, eventName, updateEventName, contentCallback, getPriceFromParamFunc, getPriceFromUpdatedItemFunc) {
        var contentUpdateFunc = null;
        mozPlugins.subscribeEvent(eventName, function(event, callbackInit, param) {
            var callbackData = callbackInit();
            if (callbackData && callbackData.setContent) {
                console.log(eventName, param);
                contentUpdateFunc = callbackData.setContent;
                contentUpdateFunc(contentCallback(placeholder, param.page.language, getPriceFromParamFunc(param)));
            }
        });

        if (updateEventName) {
            mozPlugins.subscribeEvent(updateEventName, function(event, callbackInit, param, updatedItem) {
                if (contentUpdateFunc) {
                    console.log(updateEventName, param, updatedItem);
                    contentUpdateFunc(contentCallback(placeholder, param.page.language, getPriceFromUpdatedItemFunc(param, updatedItem)));
                }
            });
        }
    }

    function getContent(placeholder, language, price) {
        window.plugin_hello_merchant_id
        var ret = 'Hello from plugin in ' + placeholder + '! Price: ' + price + '; Language: ' + language;
        if (window.plugin_hello_merchant_id) {
            ret = ret + "; Merchant: " + window.plugin_hello_merchant_id;
        }
        return ret;
    }

    // -- View item ---------------------
    subscribeToEvents(
        'Product View After Price',
        'moz-content-after-price',
        'moz-content-after-price-updated',
        getContent,
        function(param) {
            return param.item.price;
        },
        function(param, updatedItem) {
            var price = updatedItem.item.price;
            if (updatedItem.selectedVariant && updatedItem.selectedVariant.price) {
                price = updatedItem.selectedVariant.price;
            }
            return price;
        }
    );


    // -- Cart ---------------------
    subscribeToEvents(
        'Cart',
        'moz-content-after-cart',
        false,
        getContent,
        function(param) {
            return param.cartData.total_no_discount;
        },
        null
    );


    // -- Checkout form ---------------------
    subscribeToEvents(
        'Checkout Form',
        'moz-content-after-checkout',
        'moz-content-after-checkout-updated',
        getContent,
        function(param) {
            return param.cartData.total;
        },
        function(param, updatedCartData) {
            return updatedCartData.total;
        }
    );

})();