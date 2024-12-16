<?php
/* @var $this RtaTagIndexQueuedController */
/* @var $model RtaTagIndexQueuedNew */

$this->breadcrumbs=array(
	'Rta Tag Index Queued News'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtaTagIndexQueuedNew', 'url'=>array('index')),
	array('label'=>'Manage RtaTagIndexQueuedNew', 'url'=>array('admin')),
);
?>

<h1>Create RtaTagIndexQueuedNew</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>