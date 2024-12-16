<?php
/* @var $this SetPointsController */
/* @var $model SetPoints */

$this->breadcrumbs=array(
	'Set Points'=>array('index'),
	$model->sp_id,
);

$this->menu=array(
	array('label'=>'List SetPoints', 'url'=>array('index')),
	array('label'=>'Create SetPoints', 'url'=>array('create')),
	array('label'=>'Update SetPoints', 'url'=>array('update', 'id'=>$model->sp_id)),
	array('label'=>'Delete SetPoints', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->sp_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SetPoints', 'url'=>array('admin')),
);
?>

<h1>View SetPoints #<?php echo $model->sp_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'sp_id',
		'product_id',
		'sp_name',
		'sp_value_num',
		'sp_measured',
		'sp_value_den',
		'sp_const_value_num',
		'sp_const_value_den',
		'sp_tolerance_ulevel',
		'sp_tolerance_llevel',
		'sp_weight',
		'sp_status',
		'sp_priority',
	),
)); ?>
