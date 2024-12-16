<?php
/* @var $this LayoutsController */
/* @var $model Layouts */

$this->breadcrumbs=array(
	'Layouts'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Layouts', 'url'=>array('index')),
	array('label'=>'Create Layouts', 'url'=>array('create')),
	array('label'=>'Update Layouts', 'url'=>array('update', 'id'=>$model->layout_id)),
	array('label'=>'Delete Layouts', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->layout_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Layouts', 'url'=>array('admin')),
);
?>

<h1>View Layouts #<?php echo $model->layout_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'layout_id',
		'name',
		'type',
		'category',
		'is_default',
		'user_id',
		'created_on',
		'updated_on',
		'created_by',
	),
)); ?>
