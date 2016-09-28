/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/**
 *
 */
var IntegerNetVarnish = Class.create();

IntegerNetVarnish.prototype = {


    /**
     *
     */
    initialize: function () {

        this.isLoaded = false;
        this.script = null;
        this.blocks = null;

        this._fetchBlocks();

        Event.observe(window, 'dom:loaded', function () {
            this.isLoaded = true;
            this._updateBlocks();
        }.bind(this));
    },

    /**
     * @private
     */
    _fetchBlocks: function () {

        new Ajax.Request(window.location.href, {
            parameters: { dynamicblock: 1 },
            onSuccess: this._updateData.bind(this),
            onComplete: this._updateBlocks.bind(this)
        });
    },

    /**
     * @private
     */
    _updateData: function (respose) {

        if (respose.status == 200) {

            if (respose.responseJSON.script) {
                this.script = respose.responseJSON.script;
            }

            if (respose.responseJSON.blocks) {
                this.blocks = respose.responseJSON.blocks;
            }
        }
    },

    /**
     * @private
     */
    _updateBlocks: function () {

        if (this.isLoaded && this.blocks) {

            var element;

            for (var id in this.blocks) {
                element = $(id);
                if (element) {
                    element.replace(this.blocks[id]);
                }
            }

            this.blocks = null;
        }

        if (this.isLoaded && this.script) {
            eval(this.script);
            this.script = null;
        }
    }
};