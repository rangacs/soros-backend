<?php
/* @var $this TagQueuedController */
/* @var $model TagQueued */

$this->breadcrumbs=array(
	'Tag Queueds'=>array('index'),
	$model->tagID,
);

$this->menu=array(
	array('label'=>'List TagQueued', 'url'=>array('index')),
	array('label'=>'Create TagQueued', 'url'=>array('create')),
	array('label'=>'Update TagQueued', 'url'=>array('update', 'id'=>$model->tagID)),
	array('label'=>'Delete TagQueued', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->tagID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TagQueued', 'url'=>array('admin')),
);
?>

<h1>View TagQueued #<?php echo $model->tagID; ?></h1>

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
