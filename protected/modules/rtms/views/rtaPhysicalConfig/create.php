<?php
/* @var $this RtaPhysicalConfigController */
/* @var $model RtaPhysicalConfig */

$this->breadcrumbs=array(
	'Rta Physical Configs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtaPhysicalConfig', 'url'=>array('index')),
	array('label'=>'Manage RtaPhysicalConfig', 'url'=>array('admin')),
);
?>

<h1>Create RtaPhysicalConfig</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>