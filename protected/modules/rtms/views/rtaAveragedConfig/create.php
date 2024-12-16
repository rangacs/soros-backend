<?php
/* @var $this RtaAveragedConfigController */
/* @var $model RtaAveragedConfig */

$this->breadcrumbs=array(
	'Rta Averaged Configs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtaAveragedConfig', 'url'=>array('index')),
	array('label'=>'Manage RtaAveragedConfig', 'url'=>array('admin')),
);
?>

<h1>Create RtaAveragedConfig</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>