<?php
namespace siteoption;

use Yii;
use yii\base\Event;
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
            \siteoption\helpers\SiteOption::DEFAULT_COMPONENT_NAME,
            '',
            ['class' => \siteoption\components\SiteOption::class]
        );

        // --- If a cache config is supplied, create the component
        // --- and handle clearing the cache when records are saved
        if(!empty($this->cacheConfig)){
            Helper::applyDefaultComponentConfig(
                $app,
                \siteoption\helpers\SiteOption::DEFAULT_CACHE_COMPONENT_NAME,
                '',
                $this->cacheConfig
            );

            $events = [
                \yii\db\ActiveRecord::EVENT_AFTER_INSERT,
                \yii\db\ActiveRecord::EVENT_AFTER_UPDATE,
                \yii\db\ActiveRecord::EVENT_AFTER_DELETE,
            ];
            foreach($events as $event){            
                Event::on(\siteoption\models\SiteOption::class, $event, [self::class, 'deleteCachedValue']);
            }
        }
    }

    /**
     * @param \yii\base\Event $event
     * @return void
     */
    static function deleteCachedValue($event)
    {
        if(!Yii::$app->has(\siteoption\helpers\SiteOption::DEFAULT_CACHE_COMPONENT_NAME)){
            return;
        }
        Yii::$app->get(\siteoption\helpers\SiteOption::DEFAULT_CACHE_COMPONENT_NAME)->delete($event->sender->key);
    }
}
