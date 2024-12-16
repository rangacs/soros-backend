<?php
/* @var $this RtdShiftTimesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rtd Shift Times',
);

$this->menu=array(
	array('label'=>'Create RtdShiftTimes', 'url'=>array('create')),
	array('label'=>'Manage RtdShiftTimes', 'url'=>array('admin')),
);
?>

<h1>Rtd Shift Times</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
