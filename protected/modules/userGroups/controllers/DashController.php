<?php
/**
 * @author Nicola Puddu
 * @package userGroups
 * admin controller
 */
class DashController extends Controller
{

	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$dataProvider=new CActiveDataProvider('ChartData',
			array(
					'criteria'=>$criteria,
			)
		);

		$this->render('index' ,array(
			'dataProvider'=>$dataProvider,
		));

	}

}