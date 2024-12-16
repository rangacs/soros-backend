<?php
/* @var $this RfidCalTolSettingsController */
/* @var $model RfidCalTolSettings */

$this->breadcrumbs=array(
	'Rfid Cal Tol Settings'=>array('index'),
	$model->wcl_cal_tol_id,
);

$this->menu=array(
	array('label'=>'List RfidCalTolSettings', 'url'=>array('index')),
	array('label'=>'Create RfidCalTolSettings', 'url'=>array('create')),
	array('label'=>'Update RfidCalTolSettings', 'url'=>array('update', 'id'=>$model->wcl_cal_tol_id)),
	array('label'=>'Delete RfidCalTolSettings', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->wcl_cal_tol_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RfidCalTolSettings', 'url'=>array('admin')),
);
?>

<h1>View RfidCalTolSettings #<?php echo $model->wcl_cal_tol_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'wcl_cal_tol_id',
		'wcl_cal_tol_item_code',
		'Moisture',
		'Moisture_Tol',
		'Ash',
		'Ash_Tol',
		'Sulfur',
		'Sulfur_Tol',
		'GCV',
		'GCV_Tol',
		'BTU',
		'BTU_Tol',
		'updated_on',
	),
)); ?>
