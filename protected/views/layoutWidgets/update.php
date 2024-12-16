<?php
/* @var $this LayoutWidgetsController */
/* @var $model LayoutWidgets */

$this->breadcrumbs=array(
	'Layout Widgets'=>array('index'),
	$model->title=>array('view','id'=>$model->widget_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LayoutWidgets', 'url'=>array('index')),
	array('label'=>'Create LayoutWidgets', 'url'=>array('create')),
	array('label'=>'View LayoutWidgets', 'url'=>array('view', 'id'=>$model->widget_id)),
	array('label'=>'Manage LayoutWidgets', 'url'=>array('admin')),
);
?>

<h1>Update LayoutWidgets <?php echo $model->widget_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>