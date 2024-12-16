<?php
/* @var $this WclRfidLogMessagesController */
/* @var $model WclRfidLogMessages */

$this->breadcrumbs=array(
	'Wcl Rfid Log Messages'=>array('index'),
	$model->logid,
);

$this->menu=array(
	array('label'=>'List WclRfidLogMessages', 'url'=>array('index')),
	array('label'=>'Create WclRfidLogMessages', 'url'=>array('create')),
	array('label'=>'Update WclRfidLogMessages', 'url'=>array('update', 'id'=>$model->logid)),
	array('label'=>'Delete WclRfidLogMessages', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->logid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WclRfidLogMessages', 'url'=>array('admin')),
);
?>

<h1>View WclRfidLogMessages #<?php echo $model->logid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'logid',
		'message_type',
		'short_descrip',
		'long_descrip',
		'trip_id',
		'unloaderID',
		'vehNo',
		'flag',
		'timestamp',
	),
)); ?>
