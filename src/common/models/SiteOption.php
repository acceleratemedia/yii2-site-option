<?php

namespace bvb\siteoption\common\models;

/**
 * This is the model class for table "site_config".
 *
 * @property string $key
 * @property string $value
 * @property string $create_time
 * @property string $update_time
 */
class SiteOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_option';
    }
}
