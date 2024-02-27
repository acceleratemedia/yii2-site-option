<?php
namespace siteoption\helpers;

use yii\helpers\Inflector;

/**
 * SiteOption helps manage SiteOption models and their related records
 */
class SiteOption
{
    /**
     * @var string Name to use for the site option component
     */
    const DEFAULT_COMPONENT_NAME = 'siteOption';

    /**
     * @var string Name to use for the cache component
     */
    const DEFAULT_CACHE_COMPONENT_NAME = 'siteOptionCache';

    /**
     * Returns the name attribute that should be used for meta models taking
     * into consideration many of these same models may be on a page at once
     * @param string $key
     * @return string
     */
    static function getActiveFormInputName($key)
    {
        return '['.Inflector::variablize($key).']value';
    }
}
