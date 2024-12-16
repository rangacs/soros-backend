<?php
/* @var $this TagQueuedController */
/* @var $model TagQueued */

$this->breadcrumbs=array(
	'Tag Queueds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TagQueued', 'url'=>array('index')),
	array('label'=>'Manage TagQueued', 'url'=>array('admin')),
);
?>

<h1>Create TagQueued</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>