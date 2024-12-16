<?php
/* @var $this RtaAveragedConfigController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rta Averaged Configs',
);

$this->menu=array(
	array('label'=>'Create RtaAveragedConfig', 'url'=>array('create')),
	array('label'=>'Manage RtaAveragedConfig', 'url'=>array('admin')),
);
?>

<h1>Rta Averaged Configs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
