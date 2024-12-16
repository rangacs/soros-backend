<?php
/* @var $this RtaTagIndexQueuedController */
/* @var $model RtaTagIndexQueuedNew */

$this->breadcrumbs=array(
	'Rta Tag Index Queued News'=>array('index'),
	$model->tagID,
);

$this->menu=array(
	array('label'=>'List RtaTagIndexQueuedNew', 'url'=>array('index')),
	array('label'=>'Create RtaTagIndexQueuedNew', 'url'=>array('create')),
	array('label'=>'Update RtaTagIndexQueuedNew', 'url'=>array('update', 'id'=>$model->tagID)),
	array('label'=>'Delete RtaTagIndexQueuedNew', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->tagID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RtaTagIndexQueuedNew', 'url'=>array('admin')),
);
?>

<h1>View RtaTagIndexQueuedNew #<?php echo $model->tagID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'tagID',
		'rtaMasterID',
		'status',
		'tagName',
		'tagGroupID',
		'LocalstartTime',
		'LocalendTime',
	),
)); ?>
