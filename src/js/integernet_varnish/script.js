/**
 * integer_net Magento Module
 *
 * @category IntegerNet
 * @package IntegerNet_<Module>
 * @copyright  Copyright (c) 2012-2013 integer_net GmbH (http://www.integer-net.de/)
 * @author Viktor Franz <vf@integer-net.de>
 */

/**
 * Enter description here ...
 */

var IntegerNetVarnish = Class.create();

IntegerNetVarnish.prototype = {

    /**
     *
     * @param config
     */
    initialize: function (fetchUrl) {
        this.fetchUrl = fetchUrl;
        this.blocks = {};
        this.blockStorageKey = 'integernetvarnish_blocks';

        Event.observe(window, 'load', function () {
            this._updateBlocks();
            this._fetchBlocks();
        }.bind(this));
    },

    /**
     *
     * @private
     */
    _fetchBlocks: function () {
        new Ajax.Request(this.fetchUrl, {
            parameters: {pathname : window.location.pathname},
            onSuccess: this._updateData.bind(this),
            onComplete: this._updateBlocks.bind(this)
        });
    },

    /**
     *
     * @private
     */
    _updateData: function (respose) {
        if (respose.status == 200) {

            if (respose.responseJSON.blocks) {
                this.blocks = respose.responseJSON.blocks
            }

            if (window.sessionStorage && respose.responseJSON.storage) {
                window.sessionStorage.setItem(this.blockStorageKey, respose.responseJSON.storage);
            }
        }
    },

    /**
     *
     * @private
     */
    _updateBlocks: function () {
        if (window.sessionStorage && Object.keys(this.blocks).length == 0 && window.sessionStorage.getItem(this.blockStorageKey)) {
            this.blocks = window.sessionStorage.getItem(this.blockStorageKey).evalJSON();
        }

        for (var id in this.blocks) {
            var element = $(id);
            if (element) {
                element.replace(this.blocks[id]);
            }
        }
    }
};
