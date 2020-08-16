<?php

namespace bvb\siteoption\common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_option".
 *
 * Due to the varying nature of options that a site may have, it allows for the
 * dynamic setting of a [[$label]], [[$hint]] and [[$rules]] through class properties
 *
 * A convenient way of being able to use the dynamic nature of this class is to create
 * static properties in helper classes that include the label, hint, and rules, and to
 * reference those static properties when instantiating this class, for example, in
 * \abc\xyz\SiteHelper:
 * ```
 *   const MYOPTION = 'myOptionKey';
 *   static $optionsConfig = [
 *       self::MYOPTION => [
 *           'label' => 'Label for My Option',
 *           'hint' => 'This is the hint for the option',
 *           'value' => 'defaultValue',
 *           'rules' => [
 *               ['string', 'max' => 1000]
 *           ]
 *       ]
 *   ]; 
 * ```
 * Then, when instantiating:
 * ```
 * $myOption = SiteOption::getModel(
 *      SiteHelper::MYOPTION,
 *      SiteHelper::$optionsConfig[SiteHelper::MYOPTION]
 * );
 * ```
 * That will set the label, hint, default value, and any validation rules
 *
 * @property string $key
 * @property string $value
 * @property string $create_time
 * @property string $update_time
 */
class SiteOption extends \yii\db\ActiveRecord
{
    /**
     * Label to be used for the 'value' attribute to allow for developers to set
     * custom hints for the values of options they create in their extensions
     * @see self::getAttributeLabel()
     * @var string
     */
    public $label = '';

    /**
     * Hint to be used for for the 'value' attribute to allow for developers to
     * set custom hints for the values of options they create in their extensions
     * @see self::getAttributeHint()
     * @var string
     */
    public $hint = '';

    /**
     * Array of rules that can be applied to the 'value' attribute to allow for
     * developers to set custom validation for the values of options they create
     * in their extensions
     * @see self::rules()
     * @var array
     */
    public $rules = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_option';
    }


    /**
     * Returns an instance of this class representing the records with the given key
     * If $returnNull is false it will return a new instance of this class initialized
     * with the given key, with the supplied configuration options. This is is useful
     * when using in the context of updating options that may not yet exist in the database.
     * A `behaviors` key may be added to $config to have and these will be attached after
     * the model is created using [[Yii::configure()]]
     * @param string $key
     * @param config $array
     * @param boolean $returnNull
     * @return $this|null
     */
    static function getModel($key, $config = [], $returnNull = false)
    {
        // --- Remove behaviors if set because they can't be set by instantiating
        // --- or configuring and must be attached on the fly
        $behaviors = ArrayHelper::remove($config, 'behaviors');

        $model = self::findOne($key);
        if($model){
            $value = ArrayHelper::remove($config, 'value');
            Yii::configure($model, $config);
        } else if(!$returnNull){
            $model = new static(ArrayHelper::merge(['key' => $key], $config));
        }

        
        if(!empty($behaviors)){
            foreach($behaviors as $behaviorName => $behaviorConfig){
                $model->attachBehavior($behaviorName, $behaviorConfig);
            }
        }
        return $model;
    }
}
