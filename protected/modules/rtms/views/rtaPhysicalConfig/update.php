<?php
/* @var $this RtaPhysicalConfigController */
/* @var $model RtaPhysicalConfig */

$this->breadcrumbs=array(
	'Rta Physical Configs'=>array('index'),
	$model->rta_ID_physical=>array('view','id'=>$model->rta_ID_physical),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtaPhysicalConfig', 'url'=>array('index')),
	array('label'=>'Create RtaPhysicalConfig', 'url'=>array('create')),
	array('label'=>'View RtaPhysicalConfig', 'url'=>array('view', 'id'=>$model->rta_ID_physical)),
	array('label'=>'Manage RtaPhysicalConfig', 'url'=>array('admin')),
);
?>

<h1>Update RtaPhysicalConfig <?php echo $model->rta_ID_physical; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>