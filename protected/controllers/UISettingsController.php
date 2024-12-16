<?php

class UISettingsController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		if(isset($_REQUEST['ajax']) && isset($_REQUEST['uid']))
		{
			$u_id 		= $_REQUEST['uid'];	
			$rule 		= $_REQUEST['rule'];	
			$options 	= $_REQUEST['options'];	
			$value      = TRUE;
			
			$model		= new UISettings;
			$ruleArray  = $model->getRules($u_id);
			//print_r($ruleArray);
			
			if(in_array($rule,$ruleArray))
			{			
				$return= $model->setConfig($u_id, $rule, $value, $options);
				echo $return;
			}
			else {
				echo "false";
			}	
		}
		echo "false";
	}
	// Uncomment the following methods and override them if needed
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(

		);
	}
}