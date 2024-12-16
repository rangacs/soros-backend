<?php

class ProfileController extends Controller
{
	#static $_permissionControl = array('label' => 'Better Label');

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'userGroupsAccessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // just guest can perform 'activate' and 'login' actions
				#'ajax'=>false,
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionLoad()
	{
		$model= Profile::model()->findByAttributes(array('ug_id' => Yii::app()->user->id));
        if(isset($_POST['Profile']))
        {
			$this->performAjaxValidation($model);

            $model->attributes=$_POST['Profile'];
            $model->avatar=CUploadedFile::getInstance($model,'avatar');
            if($model->save())
            {
               $model->avatar->saveAs('avatars/'.Yii::app()->user->name.'.jpg');


            } else
				Yii::app()->user->setFlash('user', 'you can just upload images.');

			$this->redirect(array('/userGroups'));
        }
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}