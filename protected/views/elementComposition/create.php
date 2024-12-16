<?php
/* @var $this ElementCompositionController */
/* @var $model ElementComposition */

$this->breadcrumbs=array(
	'Element Compositions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ElementComposition', 'url'=>array('index')),
	array('label'=>'Manage ElementComposition', 'url'=>array('admin')),
);
?>

<h1>Create ElementComposition</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>