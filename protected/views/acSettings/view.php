<?php
/* @var $this AcSettingsController */
/* @var $model AcSettings */

$this->breadcrumbs=array(
	'Ac Settings'=>array('index'),
	$model->ac_id,
);

$this->menu=array(
	array('label'=>'List AcSettings', 'url'=>array('index')),
	array('label'=>'Create AcSettings', 'url'=>array('create')),
	array('label'=>'Update AcSettings', 'url'=>array('update', 'id'=>$model->ac_id)),
	array('label'=>'Delete AcSettings', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ac_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AcSettings', 'url'=>array('admin')),
);
?>

<h1>View AcSettings #<?php echo $model->ac_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ac_id',
		'element_name',
		'min',
		'max',
		'diff',
		'max_offset_change',
		'correction_pct',
		'last_updated',
	),
)); ?>
