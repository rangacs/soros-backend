<?php
/* @var $this LayoutWidgetsController */
/* @var $model LayoutWidgets */

$this->breadcrumbs=array(
	'Layout Widgets'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List LayoutWidgets', 'url'=>array('index')),
	array('label'=>'Create LayoutWidgets', 'url'=>array('create')),
	array('label'=>'Update LayoutWidgets', 'url'=>array('update', 'id'=>$model->widget_id)),
	array('label'=>'Delete LayoutWidgets', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->widget_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LayoutWidgets', 'url'=>array('admin')),
);
?>

<h1>View LayoutWidgets #<?php echo $model->widget_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'widget_id',
		'title',
		'type',
		'layout_id',
		'position',
		'settings',
		'created_on',
		'created_by',
		'updated_on',
		'updated_by',
	),
)); ?>
