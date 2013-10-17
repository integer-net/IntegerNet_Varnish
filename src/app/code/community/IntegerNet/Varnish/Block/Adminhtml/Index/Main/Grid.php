<?php

class IntegerNet_Varnish_Block_Adminhtml_Index_Main_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('integernet_varnish_index');
    }

    /**
     * @return this
     */
    protected function _prepareCollection()
    {
        /** @var $collection IntegerNet_Varnish_Model_Resource_Index_Collection */
        $collection = Mage::getResourceModel('integernet_varnish/index_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('integernet_varnish')->__('Entity'),
            'index' => 'entity_id',
            'width' => '100px',
        ));

        $this->addColumn('url_key', array(
            'header' => Mage::helper('integernet_varnish')->__('Key'),
            'index' => 'url_key',
        ));

        $this->addColumn('route', array(
            'header' => Mage::helper('integernet_varnish')->__('Route'),
            'index' => 'route',
        ));

        $this->addColumn('url', array(
            'header' => Mage::helper('integernet_varnish')->__('Url'),
            'index' => 'url',
        ));

        $this->addColumn('expire', array(
            'header' => Mage::helper('integernet_varnish')->__('Expire'),
            'index' => 'expire',
        ));

        $this->addColumn('priority', array(
            'header' => Mage::helper('integernet_varnish')->__('priority'),
            'index' => 'priority',
        ));

        return parent::_prepareColumns();
    }
}
