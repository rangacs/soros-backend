<?php
/* @var $this RtaJobTableController */
/* @var $model RtaJobTable */

$this->breadcrumbs=array(
	'Rta Job Tables'=>array('index'),
	$model->jobID,
);

$this->menu=array(
	array('label'=>'List RtaJobTable', 'url'=>array('index')),
	array('label'=>'Create RtaJobTable', 'url'=>array('create')),
	array('label'=>'Update RtaJobTable', 'url'=>array('update', 'id'=>$model->jobID)),
	array('label'=>'Delete RtaJobTable', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->jobID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RtaJobTable', 'url'=>array('admin')),
);
?>

<h1>View RtaJobTable #<?php echo $model->jobID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'jobID',
		'jobStatus',
		'linuxPID',
		'start_time',
		'end_time',
		'backupTable',
		'tempTable',
		'regenTable',
		'originalTable',
		'originalTableID',
		'loopsFinished',
		'recordsRemaining',
		'recordsTotal',
		'maxID',
		'dateAdded',
		'dateModified',
		'dateCompleted',
		'userAdded',
		'userModified',
	),
)); ?>
