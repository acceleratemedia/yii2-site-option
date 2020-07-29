<?php

namespace bvb\siteoption;

/**
 * SiteOptionModule is used to manage site-wide options for other pieces of
 * functionality. Ideal applications are when a module needs to store a few
 * pieces of data in a database but creating an entire table for that would
 * be in excess. For example, a Terms of Service text field for a User module
 * or selecting a home page for a Content module.
 */
class SiteOptionModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // --- Set the bvb alias to the root of this
        Yii::setAlias("@bvb-site-option", __DIR__);
    }
}