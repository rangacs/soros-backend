<?php
/* @var $this RfidCalTolSettingsController */
/* @var $model RfidCalTolSettings */

$this->breadcrumbs=array(
	'Rfid Cal Tol Settings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RfidCalTolSettings', 'url'=>array('index')),
	array('label'=>'Manage RfidCalTolSettings', 'url'=>array('admin')),
);
?>

<h1>Create RfidCalTolSettings</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>