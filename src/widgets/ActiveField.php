<?php
namespace siteoption\widgets;

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;

/**
 * This extension of Yii's ActiveField class modifies the output so that the key
 * of the record is used in places where the ActiveRecord's attribute would normally
 * be. 
 * 
 * For example, in an Person model with a `name` attribute, the name attribute
 * of a text input would be 'Person[name]'. Well, with all SiteOption records that
 * would be SiteOption[value]. This adjusts it to be SiteOption[debugMode] where
 * 'debugMode' would be the `key` of the record.
 * 
 * Using this requires unique handling when loading these values onto the models
 * in controllers or after data is POSTed.
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * Make sure name attribute, id and label for are all correct
     * {@inheritdoc}
     */
    public function textInput($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustForSiteOption($options); // --- Only new line

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $options);

        return $this;
    }

    /**
     * Make sure name attribute, id and label for are all correct
     * {@inheritdoc}
     */
    public function input($type, $options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustForSiteOption($options); // --- Only new line

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeInput($type, $this->model, $this->attribute, $options);

        return $this;
    }

    /**
     * Make sure name attribute, id and label for are all correct
     * {@inheritdoc}
     */
    public function checkbox($options = [], $enclosedByLabel = true)
    {
        $this->adjustForSiteOption($options); // --- Only new line

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        if ($enclosedByLabel) {
            $this->parts['{input}'] = Html::activeCheckbox($this->model, $this->attribute, $options);
            $this->parts['{label}'] = '';
        } else {
            if (isset($options['label']) && !isset($this->parts['{label}'])) {
                $this->parts['{label}'] = $options['label'];
                if (!empty($options['labelOptions'])) {
                    $this->labelOptions = $options['labelOptions'];
                }
            }
            unset($options['labelOptions']);
            $options['label'] = null;
            $this->parts['{input}'] = Html::activeCheckbox($this->model, $this->attribute, $options);
        }

        return $this;
    }

    /**
     * Make sure name attribute, id and label for are all correct
     * {@inheritdoc}
     */
    public function dropDownList($items, $options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustForSiteOption($options); // --- Only new line

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeDropDownList($this->model, $this->attribute, $items, $options);

        return $this;
    }

    /**
     * Make sure the name, ID and everything use the model key instead of just
     * 'value' (which is the attributes's name)
     * @return void
     */
    protected function adjustForSiteOption(&$options)
    {
        if(!isset($options['label'])){
            $options['label'] = Inflector::titleize($this->model->key);
        }
        if(!isset($options['name'])){
            $options['name'] = 'SiteOption['.$this->model->key.']';
        }
        if(!isset($options['id'])){
            $options['id'] = 'siteoption-'.$this->model->key;
        }
    }
}
