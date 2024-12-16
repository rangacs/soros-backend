<?php
/* @var $this AcSettingsController */
/* @var $model AcSettings */

$this->breadcrumbs=array(
	'Ac Settings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AcSettings', 'url'=>array('index')),
	array('label'=>'Manage AcSettings', 'url'=>array('admin')),
);
?>

<h1>Create AcSettings</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>