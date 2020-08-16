<?php

namespace bvb\siteoption\backend\models;

use yii\helpers\Inflector;

/**
 * SiteOption implements backend functionality for saving models like validation
 */
class SiteOption extends \bvb\siteoption\common\models\SiteOption
{
    /**
     * Uses [[$rules]] to append additional custom rules per instance for specific
     * needs in regards to options that a developer may include in their application
     * {@inheritdoc}
     */
    public function rules()
    {
        // --- Default rules related to key should not receive user input so these
        // --- are here to make sure developers follow these rules 
        $rules = [
            [['key'], 'string', 'max' => 50],
            [['key'], 'unique']
        ];

        if(!empty($this->rules)){
            foreach($this->rules as $rule){
                array_unshift($rule, ['value']);
                $rules[] = $rule;
            }
        }
        return $rules;
    }

    /**
     * Uses [[$label]] to return the label
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'value' => $this->label,
            // --- Sets the label properly for the suggested use of SiteOption
            // --- models when submitting in forms since frequently more than one
            // --- model is updated on a single page so we set the attribute based
            // --- on the key
            '['.Inflector::variablize($this->key).']value' => $this->label,
        ];
    }

    /**
     * Uses [[$hint]] to return the label
     * {@inheritdoc}
     */
    public function attributeHints()
    {
        return [
            'value' => $this->hint,
            '['.Inflector::variablize($this->key).']value' => $this->hint,
        ];
    }
}
