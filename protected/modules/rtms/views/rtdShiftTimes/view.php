<?php
/* @var $this RtdShiftTimesController */
/* @var $model RtdShiftTimes */

$this->breadcrumbs=array(
	'Rtd Shift Times'=>array('index'),
	$model->shiftTimeID,
);

$this->menu=array(
	array('label'=>'List RtdShiftTimes', 'url'=>array('index')),
	array('label'=>'Create RtdShiftTimes', 'url'=>array('create')),
	array('label'=>'Update RtdShiftTimes', 'url'=>array('update', 'id'=>$model->shiftTimeID)),
	array('label'=>'Delete RtdShiftTimes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->shiftTimeID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RtdShiftTimes', 'url'=>array('admin')),
);
?>

<h1>View RtdShiftTimes #<?php echo $model->shiftTimeID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'shiftTimeID',
		'shiftStart1',
		'shiftDuration1',
		'shiftStart2',
		'shiftDuration2',
		'shiftStart3',
		'shiftDuration3',
	),
)); ?>
