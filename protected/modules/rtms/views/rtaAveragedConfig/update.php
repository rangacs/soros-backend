<?php
/* @var $this RtaAveragedConfigController */
/* @var $model RtaAveragedConfig */

$this->breadcrumbs=array(
	'Rta Averaged Configs'=>array('index'),
	$model->rta_ID_averaged=>array('view','id'=>$model->rta_ID_averaged),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtaAveragedConfig', 'url'=>array('index')),
	array('label'=>'Create RtaAveragedConfig', 'url'=>array('create')),
	array('label'=>'View RtaAveragedConfig', 'url'=>array('view', 'id'=>$model->rta_ID_averaged)),
	array('label'=>'Manage RtaAveragedConfig', 'url'=>array('admin')),
);
?>

<h1>Update RtaAveragedConfig <?php echo $model->rta_ID_averaged; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>