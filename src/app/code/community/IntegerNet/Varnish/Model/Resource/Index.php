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
     * @param string $url
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
     * @param int $priority Priority
     * @param boolean $count Conunt
     *
     * @return int The number of affected rows
     */
    public function indexUrl($url, $route, $lifetime, $priority = 0, $count = true)
    {
        try {

            $affected = $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array(
                    'updated_at' => date(self::DATE_FORMAT),
                    'expire_at' => date(self::DATE_FORMAT, time() + (int)$lifetime),
                    'purge_flag' => IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_NO,
                    'priority' => (int)$priority,
                    'count' => $count ? new Zend_Db_Expr('`count`') : new Zend_Db_Expr('`count` + 1'),
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
                        'priority' => (int)$priority,
                        'count' => 1,
                    )
                );
            }

            return $affected;

        } catch (Exception $e) {

            return 0;
        }
    }

    /**
     * @param int $priority
     * @return array
     */
    public function getUrls($priority)
    {
        $select = $this->_getReadAdapter()->select()->from($this->getMainTable(), array('entity_id', 'url'));
        $select->order(array('priority ASC', 'count DESC', 'expire_at ASC'));
        $select->where('(priority > 0 AND priority <= ?)', $priority);

        return $this->_getReadAdapter()->fetchPairs($select);
    }


    /**
     * @param null|int|array $id
     * @return int
     */
    public function setExpire($id = array())
    {
        $id = (array)$id;

        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('expire_at' => date(self::DATE_FORMAT, time() - 1)),
            $id ? array('entity_id IN (?)' => $id) : null
        );
    }


    /**
     * @param int $limit
     * @param int $priority
     * @return array
     */
    public function getExpiredUrls($limit, $priority)
    {
        $select = $this->_getReadAdapter()->select()->from($this->getMainTable(), array('entity_id', 'url'));
        $select->order(array('priority ASC', 'count DESC', 'expire_at ASC'));
        $select->where('expire_at <= ?', date(self::DATE_FORMAT));
        $select->where('(priority > 0 AND priority <= ?)', $priority);
        $select->limit($limit);

        return $this->_getReadAdapter()->fetchPairs($select);
    }


    /**
     * @param null|int|array $id
     * @return int
     */
    public function setPurge($id = array())
    {
        $id = (array)$id;

        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('purge_flag' => IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_YES),
            $id ? array('entity_id IN (?)' => $id) : null
        );
    }


    /**
     * @param null|int|array $id
     * @return int
     */
    public function unsetPurge($id = array())
    {
        $id = (array)$id;

        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('purge_flag' => IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_NO),
            $id ? array('entity_id IN (?)' => $id) : null
        );
    }


    /**
     * @param int $limit
     * @return array
     */
    public function getPurgeUrls($limit)
    {
        $select = $this->_getReadAdapter()->select()->from($this->getMainTable(), array('entity_id', 'url'));
        $select->order(array('count DESC'));
        $select->where('purge_flag = ?', IntegerNet_Varnish_Model_Index_Purge::PURGE_FLAG_YES);
        $select->limit($limit);

        return $this->_getReadAdapter()->fetchPairs($select);
    }


    /**
     * @param int|array $id
     * @param int $priority
     * @return int
     */
    public function setPriority($id, $priority)
    {
        $id = (array)$id;

        return $this->_getReadAdapter()->update(
            $this->getMainTable(),
            array('priority' => (int)$priority),
            array('entity_id IN (?)' => $id)
        );
    }


    /**
     * @param int|array $id
     * @return int
     */
    public function remove($id)
    {
        $id = (array)$id;

        return $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            array('entity_id IN (?)' => $id)
        );
    }


    /**
     * @param string $url
     * @return int
     */
    public function removeUrl($url)
    {
        return $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            array('url_key = ?' => $this->_getUrlKey($url))
        );
    }


    /**
     * @return array
     */
    public function getRouteOptions()
    {
        $select = $this->_getReadAdapter()->select()->from($this->getMainTable(), 'route');
        $select->order(array('route ASC'));
        $select->distinct();

        return $this->_getReadAdapter()->fetchCol($select);
    }
}
