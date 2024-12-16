<?php
/* @var $this RtdShiftTimesController */
/* @var $model RtdShiftTimes */

$this->breadcrumbs=array(
	'Rtd Shift Times'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtdShiftTimes', 'url'=>array('index')),
	array('label'=>'Manage RtdShiftTimes', 'url'=>array('admin')),
);
?>

<h1>Create RtdShiftTimes</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>