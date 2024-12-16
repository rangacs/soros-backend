<?php
/* @var $this LayoutsController */
/* @var $model Layouts */

$this->breadcrumbs=array(
	'Layouts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Layouts', 'url'=>array('index')),
	array('label'=>'Manage Layouts', 'url'=>array('admin')),
);
?>

<h1>Create Layouts</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>