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
        this.script = '';
        this.blocks = {cached: {}, temp: {}};
        this.blocksStorageKey = 'integernetvarnish_blocks';
        this.scriptStorageKey = 'integernetvarnish_script';

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

            if(respose.responseJSON.script) {
                this.script = respose.responseJSON.script;

                if (window.sessionStorage) {
                    window.sessionStorage.setItem(this.scriptStorageKey, this.script);
                }
            }

            if (respose.responseJSON.blocks.a) {
                this.blocks.cached = respose.responseJSON.blocks.a;

                if (window.sessionStorage) {
                    window.sessionStorage.setItem(this.blocksStorageKey, Object.toJSON(this.blocks.cached));
                }
            }

            if (respose.responseJSON.blocks.b) {
                this.blocks.temp = respose.responseJSON.blocks.b;
            }
        }
    },

    /**
     *
     * @private
     */
    _updateBlocks: function () {
        if (window.sessionStorage && Object.keys(this.blocks.cached).length == 0 && window.sessionStorage.getItem(this.blocksStorageKey)) {
            this.blocks.cached = window.sessionStorage.getItem(this.blocksStorageKey).evalJSON();
        }

        var blocks = Object.extend(this.blocks.cached, this.blocks.temp);
        for (var id in blocks) {
            var element = $(id);
            if (element) {
                element.replace(blocks[id]);
            }
        }

        if (!this.script && window.sessionStorage && window.sessionStorage.getItem(this.scriptStorageKey)) {
            this.script = window.sessionStorage.getItem(this.scriptStorageKey);
        }

        eval(this.script);
    }
};