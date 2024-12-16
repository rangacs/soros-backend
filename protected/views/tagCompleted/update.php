<?php
/* @var $this TagCompletedController */
/* @var $model TagCompleted */

$this->breadcrumbs=array(
	'Tag Completeds'=>array('index'),
	$model->tagID=>array('view','id'=>$model->tagID),
	'Update',
);

$this->menu=array(
	array('label'=>'List TagCompleted', 'url'=>array('index')),
	array('label'=>'Create TagCompleted', 'url'=>array('create')),
	array('label'=>'View TagCompleted', 'url'=>array('view', 'id'=>$model->tagID)),
	array('label'=>'Manage TagCompleted', 'url'=>array('admin')),
);
?>

<h1>Update TagCompleted <?php echo $model->tagID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>