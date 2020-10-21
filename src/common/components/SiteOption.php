<?php

namespace bvb\siteoption\common\components;

use Yii;
use yii\base\Component;

/**
 * SiteOption helps manage SiteOption records site-wide and implements caching
 * abilities to avoid loading values from the database more than once
 */
class SiteOption extends Component
{
	/**
	 * SiteOption values can be provided here in the key/value format to override
	 * database values or to permanently configure a value in application 
	 * configuration without having to store it in the database
	 * @var array
	 */
	public $overrides = [];

	/**
	 * Holds values for site options with the key as the key and is used to 
	 * avoid multiple queries to the database
	 * @var array
	 */
	private $_cache = [];

	/**
	 * Returns the value for the given key
	 * @return string
	 */
	public function get($key, $refresh = false)
	{
		if(isset($this->overrides[$key])){
			return $this->overrides[$key];
		}
		if(!isset($this->_cache[$key]) || $refresh){
			$this->_cache[$key] = Yii::$app->db->createCommand('SELECT `value` FROM `site_option` WHERE`key`=:key')
				->bindValue(':key', $key)
				->queryScalar();
		}
		return $this->_cache[$key];
	}
}
