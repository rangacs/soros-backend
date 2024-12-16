<?php
/* @var $this GadgetsDataController */
/* @var $model GadgetsData */

$this->breadcrumbs=array(
	'Gadgets Datas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GadgetsData', 'url'=>array('index')),
	array('label'=>'Manage GadgetsData', 'url'=>array('admin')),
);
?>

<h1>Create GadgetsData</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>