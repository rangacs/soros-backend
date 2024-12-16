<?php
/* @var $this RmSettingsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rm Settings',
);

$this->menu=array(
	array('label'=>'Create RmSettings', 'url'=>array('create')),
	array('label'=>'Manage RmSettings', 'url'=>array('admin')),
);
?>

<h1>Rm Settings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
