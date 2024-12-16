<?php
/* @var $this TagQueuedController */
/* @var $model TagQueued */

$this->breadcrumbs=array(
	'Tag Queueds'=>array('index'),
	$model->tagID=>array('view','id'=>$model->tagID),
	'Update',
);

$this->menu=array(
	array('label'=>'List TagQueued', 'url'=>array('index')),
	array('label'=>'Create TagQueued', 'url'=>array('create')),
	array('label'=>'View TagQueued', 'url'=>array('view', 'id'=>$model->tagID)),
	array('label'=>'Manage TagQueued', 'url'=>array('admin')),
);
?>

<h1>Update TagQueued <?php echo $model->tagID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>