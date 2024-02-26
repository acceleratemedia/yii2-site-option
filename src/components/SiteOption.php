<?php

namespace bvb\siteoption\components;

use Yii;

/**
 * SiteOption helps manage SiteOption records site-wide and implements caching
 * abilities to avoid loading values from the database more than once
 */
class SiteOption extends \yii\base\Component
{
    /**
     * SiteOption values can be provided here in the key/value format to override
     * database values or to permanently configure a value in application 
     * configuration without having to store it in the database
     * @var array
     */
    public $overrides = [];

    /**
     * Returns the value for the given key
     * @param string $key The key we want the option for
     * @param boolean $refresh If there is not an override set the first request
     * caches the option. Setting this to true will force it to query the database
     * again
     * @return string
     */
    public function get($key, $refresh = false)
    {
        if(isset($this->overrides[$key])){
            return $this->overrides[$key];
        }

        if($refresh){
            $this->getCache()->delete($key);
        }

        return $this->getCache()->getOrSet($key, function() use($key){
            // --- If the databse returns false that's because there is no
            // --- record or value found. Since a false value returned by a 
            // --- call to get a cache key indicates it isn't in the cache, 
            // --- we change this to null since if it's not in the database
            // --- we want to assume false or null without re-doing the
            // --- query every time
            $dbValue = $this->getDbValue($key);
            return $dbValue === false ? null : $dbValue;
        });
    }

    /**
     * Queries the database for the site option
     * @param $key
     * @return string
     */
    public function getDbValue($key)
    {
        return Yii::$app->db->createCommand('SELECT `value` FROM `site_option` WHERE`key`=:key')
                ->bindValue(':key', $key)
                ->queryScalar();
    }

    /**
     * @return \yii\cache\Cache
     */
    protected $_cache;
    protected function getCache()
    {
        if(empty($this->_cache)){
            $this->_cache = Yii::$app->get(\bvb\siteoption\helpers\SiteOption::CACHE_COMPONENT_NAME);
        }
        return $this->_cache;
    }
}