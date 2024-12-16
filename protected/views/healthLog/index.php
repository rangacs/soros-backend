<?php
/* @var $this HealthLogController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Health Logs',
);

$this->menu=array(
	array('label'=>'Create HealthLog', 'url'=>array('create')),
	array('label'=>'Manage HealthLog', 'url'=>array('admin')),
);
?>

<h1>Health Logs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
