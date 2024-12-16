<?php

class MoniterController extends BaseController
{
	public function actionIndex()
	{
		$moniterTags = array();
		$queuedTag = Yii::app()->db->createCommand()
		->select()
		->from('rta_tag_index_queued')
		->queryAll();
		$queuedStartTime = $queuedTag[0]['LocalstartTime'];
		$queuedEndTime = $queuedTag[0]['LocalendTime'];
		$selectSql = array();
		$moniter = array();
		$moniteravg = array();
		$moniterTag = array();
		$sql = "select varValue from rm_settings where varKey = 'SOROS_ELEMENTS'";
		$res = Yii::app()->db->createCommand($sql)->queryScalar();
		$query = "select varValue from rm_settings where varKey = 'MINIMUM_TPH'";
		$minTPH = (int)Yii::app()->db->createCommand($query)->queryScalar();
		$element = explode(',',$res);
		foreach($element as $key => $value){
			$selectSql[] = 'Avg('.$value.') as '.$value;
		}
		$subSql = implode(',',$selectSql);
		$sql = 'select '.$subSql.'
						from analysis_A1_A2_Blend
						where LocalendTime >= \''.$queuedStartTime.'\' and LocalendTime <= \''.$queuedEndTime.'\' and TPH > \''.$minTPH.'\'';
				
						$result = Yii::app()->db->createCommand($sql)->queryAll();
						// var_dump($result);
						// die();
						$result[0]['LocalstartTime'] = $queuedStartTime;
						$result[0]['LocalendTime'] = $queuedEndTime;
		$completedTag = Yii::app()->db->createCommand()
		->select()
		->from('rta_tag_index_completed')
		->order('LocalendTime','DESC')
		->limit(4)
		->queryAll();
		foreach($completedTag as $key =>$rows){
			$moniterTags[]=$rows;
		}
		foreach($queuedTag as $key => $row){
			$moniter[]=$row;
		}
		foreach ($result as $key => $value) {
			$moniteravg[]=$value;
		}
		
		$moniterTag[4] = array_merge($moniteravg[0],$moniter[0]);
		
		$res = array_merge($moniterTags,$moniterTag);
		$this->sendSuccessResponse(array($res));
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
