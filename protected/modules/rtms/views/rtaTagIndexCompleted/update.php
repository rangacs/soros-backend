<?php
/* @var $this RtaTagIndexCompletedController */
/* @var $model RtaTagIndexCompletedNew */

$this->breadcrumbs=array(
	'Rta Tag Index Completed News'=>array('index'),
	$model->tagID=>array('view','id'=>$model->tagID),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtaTagIndexCompletedNew', 'url'=>array('index')),
	array('label'=>'Create RtaTagIndexCompletedNew', 'url'=>array('create')),
	array('label'=>'View RtaTagIndexCompletedNew', 'url'=>array('view', 'id'=>$model->tagID)),
	array('label'=>'Manage RtaTagIndexCompletedNew', 'url'=>array('admin')),
);
?>

<h1>Update RtaTagIndexCompletedNew <?php echo $model->tagID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>