<?php

class FormulaBuilderController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$result = $this->loadModel($id);
		$this->sendSuccessResponse(array('data' => $result));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$columnData = $this->request;

		$model=new FormulaBuilder;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->name = $columnData['name'];
		$model->formula = $columnData['formula'];
		$model->history = $columnData['history'];
		
		
		if($model->save()){
			$this->sendSuccessResponse(array('message'=>'Created Succesfully'));
		}else{
			$this->sendFailedResponse(array('message'=>'Not Created'));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$columnData = $this->request;

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->name = $columnData['name'];
		$model->formula = $columnData['formula'];
		$model->history = $columnData['history'];
		
		
		if($model->save()){
			$this->sendSuccessResponse(array('message'=>'Updated Succesfully'));
		}else{
			$this->sendFailedResponse(array('message'=>'Not Updated'));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		$this->sendSuccessResponse(array('message'=>'Deleted successfully'));

	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$result = Yii::app()->db->createCommand()
                ->select()
                ->from('custom_formulas')
                ->queryAll();
        $this->sendSuccessResponse(array('data' => $result));
	}

public function actionFormula()
	{
		
	$SiO2 = 12.48;
	$Al2O3 = 1.64;
	$Fe2O3 = 0.69;
	$CaO = 45.05;

	$sm = ($SiO2/($Al2O3+$Fe2O3));


	$im = ($Al2O3/$Fe2O3);


	$sm = ($SiO2/($Al2O3+$Fe2O3));
	$lsf = ($CaO/(2.80*$SiO2+1.18*$Al2O3+0.65*$Fe2O3)*100);
	
	var_dump(array('LSF' => $lsf, 'sm' => $sm, "IM" => $im));

	
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FormulaBuilder('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FormulaBuilder']))
			$model->attributes=$_GET['FormulaBuilder'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FormulaBuilder the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FormulaBuilder::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FormulaBuilder $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='formula-builder-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
