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
    initialize: function () {
        this.blocks = {cached: {}, temp: {}};
        this.blockStorageKey = 'integernetvarnish_blocks';

        this._fetchBlocks();

        Event.observe(window, 'load', function () {
            this._updateBlocks();
        }.bind(this));
    },

    /**
     *
     * @private
     */
    _fetchBlocks: function () {
        new Ajax.Request(window.location.href, {
            parameters: { dyn_block: 1 },
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

            if (respose.responseJSON._ba) {
                this.blocks.cached = respose.responseJSON._ba

                if (window.sessionStorage) {
                    window.sessionStorage.setItem(this.blockStorageKey, Object.toJSON(this.blocks.cached));
                }
            }

            if (respose.responseJSON._bb) {
                this.blocks.temp = respose.responseJSON._bb
            }
        }
    },

    /**
     *
     * @private
     */
    _updateBlocks: function () {
        if (window.sessionStorage && Object.keys(this.blocks.cached).length == 0 && window.sessionStorage.getItem(this.blockStorageKey)) {
            this.blocks.cached = window.sessionStorage.getItem(this.blockStorageKey).evalJSON();
        }

        var blocks = Object.extend(this.blocks.cached, this.blocks.temp);
        for (var id in blocks) {
            var element = $(id);
            if (element) {
                element.replace(blocks[id]);
            }
        }
    }
};