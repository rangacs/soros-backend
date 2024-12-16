<?php
$this->breadcrumbs=array(
	'Rta Tag Index Queueds'=>array('index'),
	$model->tagID,
);

$this->menu=array(
	array('label'=>'List RtaTagIndexQueued', 'url'=>array('index')),
	array('label'=>'Create RtaTagIndexQueued', 'url'=>array('create')),
	array('label'=>'Update RtaTagIndexQueued', 'url'=>array('update', 'id'=>$model->tagID)),
	array('label'=>'Delete RtaTagIndexQueued', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->tagID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RtaTagIndexQueued', 'url'=>array('admin')),
);
?>

<h1>View RtaTagIndexQueued #<?php echo $model->tagID; ?></h1>

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
