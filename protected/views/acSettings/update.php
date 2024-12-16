<?php
/* @var $this AcSettingsController */
/* @var $model AcSettings */

$this->breadcrumbs=array(
	'Ac Settings'=>array('index'),
	$model->ac_id=>array('view','id'=>$model->ac_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AcSettings', 'url'=>array('index')),
	array('label'=>'Create AcSettings', 'url'=>array('create')),
	array('label'=>'View AcSettings', 'url'=>array('view', 'id'=>$model->ac_id)),
	array('label'=>'Manage AcSettings', 'url'=>array('admin')),
);
?>

<h1>Update AcSettings <?php echo $model->ac_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>