<?php

class IsValidExpressionController extends BaseController
{
	public function actionIndex()
	{
		$expression = $this->request;
		try{
		$result = Yii::app()->db->createCommand()
                        ->select($expression['expression'])
                        ->from('analysis_A1_A2_Blend')
                        ->execute();
						if(isset($result)){
							$this->sendSuccessResponse(array('status' => 1));						
						}
	}catch (Exception $e) {
		throw $this->sendFailedResponse	(array('status' => 0));					
	}
	}
	

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}