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
        this.blockStorageKeyPrefix = 'integernetvarnish_block_';

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
            onSuccess: this._updateData.bind(this),
            onComplete: this._updateBlocks.bind(this)
        });
    },

    /**
     *
     * @private
     */
    _updateData: function (respose) {
        if(respose.status == 200) {
            if(window.sessionStorage) {
                for(var key in respose.responseJSON.blocks) {
                    window.sessionStorage.setItem(this.blockStorageKeyPrefix + key, respose.responseJSON.blocks[key]);
                }
            } else {
                this.blocks = respose.responseJSON.blocks;
            }
        }
    },

    /**
     *
     * @private
     */
    _updateBlocks: function () {
        if(window.sessionStorage) {
            for(var key in window.sessionStorage) {
                if(key.match(this.blockStorageKeyPrefix)) {
                    var element = $(key.replace(this.blockStorageKeyPrefix, ''));
                    if(element) {
                        element.replace(window.sessionStorage.getItem(key));
                    }
                }
            }
        } else {
            for(var id in this.blocks) {
                var element = $(id);
                if(element) {
                    element.replace(this.blocks[id]);
                }
            }
        }
    }
};
