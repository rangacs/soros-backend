<?php
/* @var $this RfidCalTolSettingsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rfid Cal Tol Settings',
);

$this->menu=array(
	array('label'=>'Create RfidCalTolSettings', 'url'=>array('create')),
	array('label'=>'Manage RfidCalTolSettings', 'url'=>array('admin')),
);
?>

<h1>Rfid Cal Tol Settings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
