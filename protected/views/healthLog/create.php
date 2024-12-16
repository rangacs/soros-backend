<?php
/* @var $this HealthLogController */
/* @var $model HealthLog */

$this->breadcrumbs=array(
	'Health Logs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List HealthLog', 'url'=>array('index')),
	array('label'=>'Manage HealthLog', 'url'=>array('admin')),
);
?>

<h1>Create HealthLog</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>