<?php

namespace bvb\siteoption\backend\actions;

use bvb\siteoption\backend\models\SiteOption;
use kartik\form\ActiveForm;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\UserException;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * SaveOptions is best used on pages where one intends to display multiple options
 * related to a module they have developed that require saving.
 */
class SaveOptions extends Action
{
	/**
	 * Configuration for options that should be saved by this action.
	 * An example format is:
	 * ```
	 * $optionsConfig = [
 	 *       self::MYOPTION => [
 	 *           'label' => 'Label for My Option',
 	 *           'hint' => 'This is the hint for the option',
 	 *           'value' => 'defaultValue',
 	 *           'rules' => [
 	 *               ['string', 'max' => 1000]
 	 *           ]
 	 *       ]
 	 * ]
 	 * ```
 	 * The key to each options configuration array will be modified using
 	 * [[yii\helpers\Inflector::variablize()]] and a SiteOption model will
 	 * be passed into the view using that name
	 * @var array
	 */
	public $optionsConfig = [];

	/**
	 * The flash message that will be displayed if all options saved successfully
	 * on submission. Can be set to false or null to have no flash message
	 * @var string
	 */
	public $savedFlashMessage = 'All options saved';

	/**
	 * URL to be redirected to after a successful save. Defaults to self so the
	 * page will not try to repeat a submit if the page is refreshed. Setting this
	 * as false will not redirect after a successful save
	 * @var mixed
	 */
	public $redirectUrl;

	/**
	 * Name of the view file to be rendered that displays the option inputs.
	 * @var string
	 */
	public $view = 'index';

    /**
     * Initialize the form used to create the model
     * Set the a submit button as a toolbar widget
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if(empty($this->optionsConfig)){
        	throw new InvalidConfigException('Options must be provided for this action to save in the $optionsConfig property.');
        }
        Yii::$app->view->form = Yii::createObject(['class' => ActiveForm::class]);
        Yii::$app->view->toolbar['widgets'] = [
			Html::submitButton('Save', ['class' => 'btn btn-success'])
		];
    }

    /**
     * Loads the options from the database or creates new ones where necessary; passes
     * the models through to the view; saves any data posted in forms while 
     * @return string
     */
    public function run()
    {
        $viewParams = [];
        foreach($this->optionsConfig as $optionKey => $optionConfig){
            $optionModel = SiteOption::getModel($optionKey, $optionConfig);
            $viewParams[Inflector::variablize($optionKey)] = $optionModel;
        }

        $postParams = Yii::$app->request->post('SiteOption');
        if(!empty($postParams)){
        	$allSaved = true;
            foreach($postParams as $optionKey => $keyValueArray){
                $viewParams[$optionKey]->value = $keyValueArray['value'];
                if(!$viewParams[$optionKey]->save()){
                	$allSaved = false;
                    throw new UserException('Unknown error when trying to save option '.$optionKey.'. Please troubleshoot.');
                }
            }
            if($allSaved){
            	if(!empty($this->savedFlashMessage)){
            		Yii::$app->session->addFlash('success', $this->savedFlashMessage);
            	}
            	if($this->redirectUrl !== false){
            		if($this->redirectUrl === null){
            			return $this->controller->refresh();
            		} else {
            			return $this->controller->redirect($this->redirectUrl);
            		}
            	}
            }
        }
    	return $this->controller->render($this->view, $viewParams);
    }
}