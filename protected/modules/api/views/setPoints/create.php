<?php
/* @var $this SetPointsController */
/* @var $model SetPoints */

$this->breadcrumbs=array(
	'Set Points'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SetPoints', 'url'=>array('index')),
	array('label'=>'Manage SetPoints', 'url'=>array('admin')),
);
?>

<h1>Create SetPoints</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>