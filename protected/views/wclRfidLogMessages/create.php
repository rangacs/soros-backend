<?php
/* @var $this WclRfidLogMessagesController */
/* @var $model WclRfidLogMessages */

$this->breadcrumbs=array(
	'Wcl Rfid Log Messages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WclRfidLogMessages', 'url'=>array('index')),
	array('label'=>'Manage WclRfidLogMessages', 'url'=>array('admin')),
);
?>

<h1>Create WclRfidLogMessages</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>