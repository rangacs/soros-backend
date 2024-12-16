<?php
/* @var $this ProductProfileController */
/* @var $model ProductProfile */

$this->breadcrumbs=array(
	'Product Profiles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ProductProfile', 'url'=>array('index')),
	array('label'=>'Manage ProductProfile', 'url'=>array('admin')),
);
?>

<h1>Create ProductProfile</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>