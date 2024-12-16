<?php
/* @var $this RtaAverageGroupidsController */
/* @var $model RtaAverageGroupids */

$this->breadcrumbs=array(
	'Rta Average Groupids'=>array('index'),
	$model->rtaMasterID,
);

$this->menu=array(
	array('label'=>'List RtaAverageGroupids', 'url'=>array('index')),
	array('label'=>'Create RtaAverageGroupids', 'url'=>array('create')),
	array('label'=>'Update RtaAverageGroupids', 'url'=>array('update', 'id'=>$model->rtaMasterID)),
	array('label'=>'Delete RtaAverageGroupids', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->rtaMasterID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RtaAverageGroupids', 'url'=>array('admin')),
);
?>

<h1>View RtaAverageGroupids #<?php echo $model->rtaMasterID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'recordID',
		'rtaMasterID',
		'groupAveragingID',
	),
)); ?>
