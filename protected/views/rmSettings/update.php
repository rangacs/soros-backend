<?php
/* @var $this RmSettingsController */
/* @var $model RmSettings */

$this->breadcrumbs=array(
	'Rm Settings'=>array('index'),
	$model->varName=>array('view','id'=>$model->varName),
	'Update',
);

$this->menu=array(
	array('label'=>'List RmSettings', 'url'=>array('index')),
	array('label'=>'Create RmSettings', 'url'=>array('create')),
	array('label'=>'View RmSettings', 'url'=>array('view', 'id'=>$model->varName)),
	array('label'=>'Manage RmSettings', 'url'=>array('admin')),
);
?>

<h1>Update RmSettings <?php echo $model->varName; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>