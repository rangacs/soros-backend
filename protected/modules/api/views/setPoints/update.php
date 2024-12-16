<?php
/* @var $this SetPointsController */
/* @var $model SetPoints */

$this->breadcrumbs=array(
	'Set Points'=>array('index'),
	$model->sp_id=>array('view','id'=>$model->sp_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SetPoints', 'url'=>array('index')),
	array('label'=>'Create SetPoints', 'url'=>array('create')),
	array('label'=>'View SetPoints', 'url'=>array('view', 'id'=>$model->sp_id)),
	array('label'=>'Manage SetPoints', 'url'=>array('admin')),
);
?>

<h1>Update SetPoints <?php echo $model->sp_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>