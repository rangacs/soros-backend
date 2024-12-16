<?php
/* @var $this HealthLogController */
/* @var $model HealthLog */

$this->breadcrumbs=array(
	'Health Logs'=>array('index'),
	$model->wcl_a_id=>array('view','id'=>$model->wcl_a_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List HealthLog', 'url'=>array('index')),
	array('label'=>'Create HealthLog', 'url'=>array('create')),
	array('label'=>'View HealthLog', 'url'=>array('view', 'id'=>$model->wcl_a_id)),
	array('label'=>'Manage HealthLog', 'url'=>array('admin')),
);
?>

<h1>Update HealthLog <?php echo $model->wcl_a_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>