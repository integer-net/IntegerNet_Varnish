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
        $this->_defaultSort = 'updated_at';
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
        $indexModel = Mage::getModel('integernet_varnish/index');

        $this->addColumn('updated_at', array(
            'header' => Mage::helper('integernet_varnish')->__('Updated At'),
            'index' => 'updated_at',
            'type' => 'datetime',
            'width' => '150px',
            'align' => 'center',
        ));

        $this->addColumn('expire', array(
            'header' => Mage::helper('integernet_varnish')->__('Expire'),
            'index' => 'expire',
            'renderer' => 'integernet_varnish/adminhtml_index_renderer_status',
            'filter' => 'adminhtml/widget_grid_column_filter_datetime',
            'width' => '150px',
        ));

        $this->addColumn('priority', array(
            'header' => Mage::helper('integernet_varnish')->__('priority'),
            'index' => 'priority',
            'type' => 'options',
            'options' => $indexModel->getPriorityOptions(),
            'width' => '100px',
            'align' => 'center',
        ));

        $this->addColumn('route', array(
            'header' => Mage::helper('integernet_varnish')->__('Route'),
            'index' => 'route',
            'type' => 'options',
            'options' => $indexModel->getRouteOptions(),
            'width' => '250px',
        ));

        $this->addColumn('url', array(
            'header' => Mage::helper('integernet_varnish')->__('Url'),
            'index' => 'url',
            'renderer' => 'integernet_varnish/adminhtml_index_renderer_url',
        ));

        return parent::_prepareColumns();
    }

    /**
     * Return row url for js event handlers
     *
     * @param Mage_Catalog_Model_Product|Varien_Object
     * @return string
     */
    public function getRowUrl($item)
    {
        return null;
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('index');
        $this->setNoFilterMassactionColumn(true);

        $this->getMassactionBlock()->addItem('expire', array(
            'label' => Mage::helper('integernet_varnish')->__('Expire'),
            'url' => $this->getUrl('*/*/massexpire'),
            'confirm'  => Mage::helper('integernet_varnish')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('integernet_varnish')->__('Delete'),
            'url' => $this->getUrl('*/*/massdelete'),
            'confirm'  => Mage::helper('integernet_varnish')->__('Are you sure?')
        ));

        return $this;
    }
}
