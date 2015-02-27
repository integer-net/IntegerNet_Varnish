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
 * Class IntegerNet_Varnish_Model_Resource_Index
 */
class IntegerNet_Varnish_Model_Resource_Index extends Mage_Core_Model_Resource_Db_Abstract
{


    /**
     *
     */
    const DATE_FORMAT = 'Y-m-d H:i:s';


    /**
     *
     */
    protected function _construct()
    {
        $this->_init('integernet_varnish/index', 'entity_id');
    }


    /**
     * @param $url URL
     *
     * @return string hashed URL
     */
    public function _getUrlKey($url)
    {
        return md5($url);
    }


    /**
     * @param string $url URL
     * @param string $route Route
     * @param int $lifetime Lifetime
     *
     * @return int The number of affected rows
     */
    public function indexUrl($url, $route, $lifetime)
    {
        $affected = $this->_getWriteAdapter()->update(
            $this->getMainTable(),
            array(
                'updated_at' => date(self::DATE_FORMAT),
                'expire_at' => date(self::DATE_FORMAT, time() + (int)$lifetime),
                'purge_flag' => IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_NO,
            ),
            array('url_key = ?' => $this->_getUrlKey($url))
        );

        if (!$affected) {

            $affected = $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'added_at' => now(),
                    'updated_at' => date(self::DATE_FORMAT),
                    'expire_at' => date(self::DATE_FORMAT, time() + (int)$lifetime),
                    'url_key' => $this->_getUrlKey($url),
                    'route' => $route,
                    'url' => $url,
                )
            );
        }

        return $affected;
    }


    /**
     * @param string $url URL
     *
     * @return int The number of affected rows
     */
    public function countUrl($url)
    {
        return $this->_getWriteAdapter()->update(
            $this->getMainTable(),
            array('count' => new Zend_Db_Expr('`count` + 1')),
            array('url_key = ?' => $this->_getUrlKey($url))
        );
    }


    /**
     * @return int The number of affected rows
     */
    public function setExpireAll()
    {
        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('expire_at' => date(self::DATE_FORMAT, time() - 1))
        );
    }


    /**
     * @param int|array $id Index entity ID
     *
     * @return int The number of affected rows
     */
    public function setExpireById($id)
    {
        $id = is_array($id) ? $id : array($id);

        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('expire_at' => date(self::DATE_FORMAT, time() - 1)),
            array('entity_id IN (?)' => $id)
        );
    }


    /**
     * @param int $limit
     *
     * @return array ID URL pare list
     */
    public function getExpiredUrls($limit = 100)
    {
        $select = $this->_getReadAdapter()->select()->from($this->getMainTable(), array('entity_id', 'url'));
        $select->order(array('priority ASC', 'count DESC', 'expire_at ASC'));
        $select->where('expire_at <= ?', date(self::DATE_FORMAT));
        $select->limit($limit);

        return $this->_getReadAdapter()->fetchPairs($select);
    }


    /**
     * @param int|array $id Index entity ID
     *
     * @return int The number of affected rows
     */
    public function setPurgeFlagById($id)
    {
        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('purge_flag' => IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_YES),
            array('entity_id IN (?)' => $id)
        );
    }


    /**
     * @return int The number of affected rows
     */
    public function unsetPurgeFlagAll()
    {
        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('purge_flag' => IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_NO)
        );
    }


    /**
     * @param int|array $id Index entity ID
     *
     * @return int The number of affected rows
     */
    public function unsetPurgeFlagById($id)
    {
        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('purge_flag' => IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_NO),
            array('entity_id IN (?)' => $id)
        );
    }


    /**
     * @param int $limit
     *
     * @return array ID URL pare list
     */
    public function getPurgeFlaggedUrls($limit = 100)
    {
        $select = $this->_getReadAdapter()->select()->from($this->getMainTable(), array('entity_id', 'url'));
        $select->order(array('count DESC'));
        $select->where('purge_flag = ?', IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_YES);
        $select->limit($limit);

        return $this->_getReadAdapter()->fetchPairs($select);
    }


    /**
     * @param int|array $id Index entity ID
     * @param int $priority
     *
     * @return int The number of affected rows
     */
    public function setPriority($id, $priority)
    {
        $id = is_array($id) ? $id : array($id);

        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('priority' => (int)$priority),
            array('entity_id IN (?)' => $id)
        );
    }


    /**
     * @param int|array $id Index entity ID
     *
     * @return int The number of affected rows
     */
    public function removeById($id)
    {
        $id = is_array($id) ? $id : array($id);

        return $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            array('entity_id IN (?)' => $id)
        );
    }


    /**
     * @param string $url URL
     *
     * @return int The number of affected rows
     */
    public function removeByUrl($url)
    {
        return $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            array('url_key = ?' => $this->_getUrlKey($url))
        );
    }


    /**
     * @return array Routes list
     */
    public function getRouteOptions()
    {
        $select = $this->_getReadAdapter()->select()->from($this->getMainTable(), 'route');
        $select->order(array('route ASC'));
        $select->distinct();

        return $this->_getReadAdapter()->fetchCol($select);
    }

}
