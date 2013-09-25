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
            if(respose.responseJSON.blocks) {
                this.blocks = respose.responseJSON.blocks;
            }
        }
    },

    /**
     *
     * @private
     */
    _updateBlocks: function () {
        for(var id in this.blocks) {
            $(id).replace(this.blocks[id]);
        }
    }
}
