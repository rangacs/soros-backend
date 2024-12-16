<?php
/* @var $this RmSettingsController */
/* @var $model RmSettings */

$this->breadcrumbs=array(
	'Rm Settings'=>array('index'),
	$model->varName,
);

$this->menu=array(
	array('label'=>'List RmSettings', 'url'=>array('index')),
	array('label'=>'Create RmSettings', 'url'=>array('create')),
	array('label'=>'Update RmSettings', 'url'=>array('update', 'id'=>$model->varName)),
	array('label'=>'Delete RmSettings', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->varName),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RmSettings', 'url'=>array('admin')),
);
?>

<h1>View RmSettings #<?php echo $model->varName; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'varName',
		'varKey',
		'varValue',
	),
)); ?>
