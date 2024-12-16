<?php
/* @var $this CalibrationLogMessagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Calibration Log Messages',
);

$this->menu=array(
	array('label'=>'Create CalibrationLogMessages', 'url'=>array('create')),
	array('label'=>'Manage CalibrationLogMessages', 'url'=>array('admin')),
);
?>

<h1>Calibration Log Messages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
