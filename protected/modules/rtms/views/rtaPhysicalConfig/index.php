<?php
/* @var $this RtaPhysicalConfigController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rta Physical Configs',
);

$this->menu=array(
	array('label'=>'Create RtaPhysicalConfig', 'url'=>array('create')),
	array('label'=>'Manage RtaPhysicalConfig', 'url'=>array('admin')),
);
?>

<h1>Rta Physical Configs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
