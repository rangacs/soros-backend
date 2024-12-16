<?php
/* @var $this LayoutWidgetsController */
/* @var $model LayoutWidgets */

$this->breadcrumbs=array(
	'Layout Widgets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LayoutWidgets', 'url'=>array('index')),
	array('label'=>'Manage LayoutWidgets', 'url'=>array('admin')),
);
?>

<h1>Create LayoutWidgets</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>