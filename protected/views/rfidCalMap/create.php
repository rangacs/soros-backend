<?php
/* @var $this RfidCalMapController */
/* @var $model RfidCalMap */

$this->breadcrumbs=array(
	'Rfid Cal Maps'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RfidCalMap', 'url'=>array('index')),
	array('label'=>'Manage RfidCalMap', 'url'=>array('admin')),
);
?>

<h1>Create RfidCalMap</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>