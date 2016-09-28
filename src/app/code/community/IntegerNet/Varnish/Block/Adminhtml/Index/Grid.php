<?php
/**
 * integer_net GmbH Magento Module
 *
 * @package    IntegerNet_Varnish
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     integer_net GmbH <info@integer-net.de>
 * @author     Viktor Franz <vf@integer-net.de>
 */


/**
 * Class IntegerNet_Varnish_Block_Adminhtml_Index_Grid
 */
class IntegerNet_Varnish_Block_Adminhtml_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
     * @return $this
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
        $this->addColumn('added_at', array(
            'header' => Mage::helper('integernet_varnish')->__('Added At'),
            'index' => 'added_at',
            'type' => 'datetime',
            'filter_time' => true,
            'width' => '150px',
            'align' => 'center',
        ));

        $this->addColumn('updated_at', array(
            'header' => Mage::helper('integernet_varnish')->__('Updated At'),
            'index' => 'updated_at',
            'type' => 'datetime',
            'filter_time' => true,
            'width' => '150px',
            'align' => 'center',
        ));

        $this->addColumn('expire_at', array(
            'header' => Mage::helper('integernet_varnish')->__('Expire At'),
            'index' => 'expire_at',
            'renderer' => 'integernet_varnish/adminhtml_index_renderer_expireAt',
            'filter' => 'adminhtml/widget_grid_column_filter_datetime',
            'filter_time' => true,
            'width' => '150px',
            'align' => 'center',
        ));

        $this->addColumn('purge_flag', array(
            'header' => Mage::helper('integernet_varnish')->__('Purge Flag'),
            'renderer' => 'integernet_varnish/adminhtml_index_renderer_purgeFlag',
            'index' => 'purge_flag',
            'type' => 'options',
            'options' => Mage::getModel('integernet_varnish/index_purge')->getFlagOptions(),
            'width' => '80px',
            'align' => 'center',
        ));

        $this->addColumn('route', array(
            'header' => Mage::helper('integernet_varnish')->__('Route'),
            'index' => 'route',
            'type' => 'options',
            'options' => Mage::getModel('integernet_varnish/index')->getRouteOptions(),
            'width' => '200px',
            'align' => 'center',
        ));

        $this->addColumn('url', array(
            'header' => Mage::helper('integernet_varnish')->__('Url'),
            'index' => 'url',
            'renderer' => 'integernet_varnish/adminhtml_index_renderer_url',
        ));


        $this->addColumn('priority', array(
            'header' => Mage::helper('integernet_varnish')->__('Priority'),
            'index' => 'priority',
            'type' => 'number',
            'renderer' => 'integernet_varnish/adminhtml_index_renderer_priority',
            'width' => '80px',
            'align' => 'center',
        ));


        $this->addColumn('count', array(
            'header' => Mage::helper('integernet_varnish')->__('Count'),
            'index' => 'count',
            'width' => '80px',
            'type' => 'number',
            'align' => 'center',
        ));

        return parent::_prepareColumns();
    }


    /**
     * Return row url for js event handlers
     *
     * @param Mage_Catalog_Model_Product|Varien_Object
     *
     * @return string
     */
    public function getRowUrl($item)
    {
        return null;
    }


    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setData('form_field_name', 'index');
        $this->setData('no_filter_massaction_column', true);

        $this->getMassactionBlock()->addItem('purge_flag', array(
            'label' => Mage::helper('integernet_varnish')->__('Purge'),
            'url' => $this->getUrl('*/*/masspurgeflag'),
            'confirm' => Mage::helper('integernet_varnish')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('expire', array(
            'label' => Mage::helper('integernet_varnish')->__('Expire'),
            'url' => $this->getUrl('*/*/massexpire'),
            'confirm' => Mage::helper('integernet_varnish')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('priority', array(
            'label' => Mage::helper('integernet_varnish')->__('Priority'),
            'url' => $this->getUrl('*/*/masspriority'),
            'additional' => array(
                'visibility' => array(
                    'name' => 'priority',
                    'type' => 'text',
                    'class' => 'required-entry',
                    'style' => 'width: 120px;',
                    'label' => Mage::helper('catalog')->__('Priority'),

                )
            )
        ));

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('integernet_varnish')->__('Delete'),
            'url' => $this->getUrl('*/*/massdelete'),
            'confirm' => Mage::helper('integernet_varnish')->__('Are you sure?')
        ));

        return $this;
    }
}
