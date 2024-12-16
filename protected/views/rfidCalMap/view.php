<?php
/* @var $this RfidCalMapController */
/* @var $model RfidCalMap */

$this->breadcrumbs=array(
	'Rfid Cal Maps'=>array('index'),
	$model->wcl_acid,
);

$this->menu=array(
	array('label'=>'List RfidCalMap', 'url'=>array('index')),
	array('label'=>'Create RfidCalMap', 'url'=>array('create')),
	array('label'=>'Update RfidCalMap', 'url'=>array('update', 'id'=>$model->wcl_acid)),
	array('label'=>'Delete RfidCalMap', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->wcl_acid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RfidCalMap', 'url'=>array('admin')),
);
?>

<h1>View RfidCalMap #<?php echo $model->wcl_acid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'wcl_acid',
		'wcl_item_code',
		'wcl_item_namev',
		'wcl_sabia_cal_name',
		'wcl_updated',
	),
)); ?>
