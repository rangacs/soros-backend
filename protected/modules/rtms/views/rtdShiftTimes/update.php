<?php
/* @var $this RtdShiftTimesController */
/* @var $model RtdShiftTimes */

$this->breadcrumbs=array(
	'Rtd Shift Times'=>array('index'),
	$model->shiftTimeID=>array('view','id'=>$model->shiftTimeID),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtdShiftTimes', 'url'=>array('index')),
	array('label'=>'Create RtdShiftTimes', 'url'=>array('create')),
	array('label'=>'View RtdShiftTimes', 'url'=>array('view', 'id'=>$model->shiftTimeID)),
	array('label'=>'Manage RtdShiftTimes', 'url'=>array('admin')),
);
?>

<h1>Update RtdShiftTimes <?php echo $model->shiftTimeID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>