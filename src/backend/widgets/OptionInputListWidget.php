<?php

namespace bvb\siteoption\backend\widgets;

use bvb\siteoption\common\helpers\SiteOption;
use Yii;
use yii\helpers\ArrayHelper;
use yiiutils\Helper;

/**
 * Option renders inputs for SiteOption in a list
 */
class OptionInputListWidget extends \yii\base\Widget
{
    /**
     * An ActiveForm widget which will be used to render the inputs
     * @var \yii\widget\ActiveForm
     */
    public $form;

    /**
     * Array of configuration for the SiteOption models
     * @see \bvb\siteoption\common\models\SiteOption
     * @var []
     */
    public $optionsConfig;

    /**
     * The models that will have inputs rendered
     * @var \yii\base\Model[]
     */
    public $siteOptions = [];

    /**
     * Render a list of inputs for $siteOptions
     * {@inheritdoc}
     */
    public function run()
    {
    	$inputWidgetsHtml = [];
		foreach($this->siteOptions as $optionKey => $optionModel){
            $inputConfig = $this->optionsConfig[$optionKey]['input'];
            $widgetClass = ArrayHelper::remove($inputConfig, 'class');

            if(Helper::IsExtendsOrImplements(\yii\widgets\InputWidget::class, $widgetClass)){
                // --- Handle the InputWidgets in a unique fashion since they are passed into an ActiveField::widget() call
                $inputWidgetsHtml[] = '<div class="option-input-widget">'.$this->field($optionModel, SiteOption::getActiveFormInputName($optionKey))->widget($widgetClass, $inputConfig).'</div>';
            } else {
                if(property_exists($widgetClass, 'model')){
                    $inputConfig['model'] = $optionModel;
                    $inputConfig['attribute'] = SiteOption::getActiveFormInputName($optionKey);
                }
                if(property_exists($widgetClass, 'form')){
                    $inputConfig['form'] = $this->form;
                }
                $widget = Yii::createObject(array_merge(['class' => $widgetClass], $inputConfig));
                // --- Just in case the widget echoes, output buffer it
                ob_start();
                $widgetContent = $widget->run();
                if(empty($widgetContent)){
                    $widgetContent = ob_get_contents();
                }
                ob_end_clean();
                echo '<div class="option-input-widget">'.$widgetContent.'</div>';
            }
		}
		return implode("\n", $inputWidgetsHtml);
    }
}