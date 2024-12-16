<?php
/* @var $this ProductProfileController */
/* @var $model ProductProfile */

$this->breadcrumbs=array(
	'Product Profiles'=>array('index'),
	$model->product_id,
);

$this->menu=array(
	array('label'=>'List ProductProfile', 'url'=>array('index')),
	array('label'=>'Create ProductProfile', 'url'=>array('create')),
	array('label'=>'Update ProductProfile', 'url'=>array('update', 'id'=>$model->product_id)),
	array('label'=>'Delete ProductProfile', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->product_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ProductProfile', 'url'=>array('admin')),
);
?>

<h1>View ProductProfile #<?php echo $model->product_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'product_id',
		'user_id',
		'product_name',
		'created_on',
		'updated_on',
		'target_flow',
		'max_flow_deviation',
		'estimate_lsq_mins',
		'sensitivity',
		'control_period_mins',
		'actual_fpm',
		'actual_tph',
		'default_profile',
		'status',
		'comment',
	),
)); ?>
