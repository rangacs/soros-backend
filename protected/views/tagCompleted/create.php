<?php
/* @var $this TagCompletedController */
/* @var $model TagCompleted */

$this->breadcrumbs=array(
	'Tag Completeds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TagCompleted', 'url'=>array('index')),
	array('label'=>'Manage TagCompleted', 'url'=>array('admin')),
);
?>

<h1>Create TagCompleted</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>