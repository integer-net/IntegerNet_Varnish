<?php
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
class IntegerNet_Varnish_Model_Resource_Index extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('integernet_varnish/index', 'entity_id');
    }

    /**
     * @param array $urls
     * @return $this
     */
    public function updateUrls(array $urls)
    {
        foreach ($urls as $key => $url) {
            $affected = $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                $url,
                array('url_key = ?' => $key)
            );

            if($affected) {
                unset($urls[$key]);
            }
        }

        return $this;
    }

    /**
     * @param array $urls
     * @return array
     */
    public function addUrls(array $urls)
    {
        foreach ($urls as $url) {
            $this->_getWriteAdapter()->insertOnDuplicate(
                $this->getMainTable(),
                $url
            );
        }

        return $this;
    }

    /**
     * @param $limit
     * @return array
     */
    public function getExpiredUrls($limit)
    {
        $select = $this->_getReadAdapter()->select()->from($this->getMainTable(), array('entity_id', 'url'));
        $select->order(array('priority ASC', 'expire ASC'));
        $select->where('expire <= ?', date('Y-m-d H:i:s'));
        $select->limit($limit);

        return $this->_getReadAdapter()->fetchPairs($select);
    }

    /**
     * @param array $ids
     * @return $this
     */
    public function removeByIds(array $ids)
    {
        $this->_getWriteAdapter()->delete($this->getMainTable(), array('entity_id IN (?)' => $ids));

        return $this;
    }
}
