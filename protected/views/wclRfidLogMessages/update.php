<?php
/* @var $this WclRfidLogMessagesController */
/* @var $model WclRfidLogMessages */

$this->breadcrumbs=array(
	'Wcl Rfid Log Messages'=>array('index'),
	$model->logid=>array('view','id'=>$model->logid),
	'Update',
);

$this->menu=array(
	array('label'=>'List WclRfidLogMessages', 'url'=>array('index')),
	array('label'=>'Create WclRfidLogMessages', 'url'=>array('create')),
	array('label'=>'View WclRfidLogMessages', 'url'=>array('view', 'id'=>$model->logid)),
	array('label'=>'Manage WclRfidLogMessages', 'url'=>array('admin')),
);
?>

<h1>Update WclRfidLogMessages <?php echo $model->logid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>