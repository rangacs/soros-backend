<?php
/* @var $this WclRfidLogMessagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Wcl Rfid Log Messages',
);

$this->menu=array(
	array('label'=>'Create WclRfidLogMessages', 'url'=>array('create')),
	array('label'=>'Manage WclRfidLogMessages', 'url'=>array('admin')),
);
?>

<h1>Wcl Rfid Log Messages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
