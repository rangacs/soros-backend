<?php
/* @var $this RmSettingsController */
/* @var $model RmSettings */

$this->breadcrumbs=array(
	'Rm Settings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RmSettings', 'url'=>array('index')),
	array('label'=>'Manage RmSettings', 'url'=>array('admin')),
);
?>

<h1>Create RmSettings</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>