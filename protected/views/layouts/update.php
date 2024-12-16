<?php
/* @var $this LayoutsController */
/* @var $model Layouts */

$this->breadcrumbs=array(
	'Layouts'=>array('index'),
	$model->name=>array('view','id'=>$model->layout_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Layouts', 'url'=>array('index')),
	array('label'=>'Create Layouts', 'url'=>array('create')),
	array('label'=>'View Layouts', 'url'=>array('view', 'id'=>$model->layout_id)),
	array('label'=>'Manage Layouts', 'url'=>array('admin')),
);
?>

<h1>Update Layouts <?php echo $model->layout_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>