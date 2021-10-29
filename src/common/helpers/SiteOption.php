<?php

namespace bvb\siteoption\common\helpers;

use yii\helpers\Inflector;

/**
 * SiteOption helps manage SiteOption models and their related records
 */
class SiteOption
{
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
