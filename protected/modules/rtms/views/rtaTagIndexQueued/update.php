<?php
/* @var $this RtaTagIndexQueuedController */
/* @var $model RtaTagIndexQueuedNew */

$this->breadcrumbs=array(
	'Rta Tag Index Queued News'=>array('index'),
	$model->tagID=>array('view','id'=>$model->tagID),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtaTagIndexQueuedNew', 'url'=>array('index')),
	array('label'=>'Create RtaTagIndexQueuedNew', 'url'=>array('create')),
	array('label'=>'View RtaTagIndexQueuedNew', 'url'=>array('view', 'id'=>$model->tagID)),
	array('label'=>'Manage RtaTagIndexQueuedNew', 'url'=>array('admin')),
);
?>

<h1>Update RtaTagIndexQueuedNew <?php echo $model->tagID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>