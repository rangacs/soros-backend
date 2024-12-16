<?php
/* @var $this AcSettingsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ac Settings',
);

$this->menu=array(
	array('label'=>'Create AcSettings', 'url'=>array('create')),
	array('label'=>'Manage AcSettings', 'url'=>array('admin')),
);
?>

<h1>Ac Settings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
