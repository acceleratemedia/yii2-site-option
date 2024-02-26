<?php
namespace siteoption;

use yiiutils\Helper;

/**
 * Bootstrap registers a site option caching component for the application
 */
class Bootstrap implements \yii\base\BootstrapInterface
{
    /**
     * @var array Configuration for a widget cache component. Set to false or null to
     * not register a cache component.
     */
    public $cacheConfig = ['class' => \yii\caching\FileCache::class];

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        // --- Register an application cache component
        Helper::applyDefaultComponentConfig(
            $app,
            \siteoption\helpers\SiteOption::CACHE_COMPONENT_NAME,
            '',
            $this->cacheConfig
        );
    }
}
