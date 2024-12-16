<?php
/* @var $this SourceController */
/* @var $model Source */

$this->breadcrumbs=array(
	'Sources'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Source', 'url'=>array('index')),
	array('label'=>'Manage Source', 'url'=>array('admin')),
);
?>

<h1>Create Source</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>