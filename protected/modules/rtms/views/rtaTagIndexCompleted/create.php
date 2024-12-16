<?php
/* @var $this RtaTagIndexCompletedController */
/* @var $model RtaTagIndexCompletedNew */

$this->breadcrumbs=array(
	'Rta Tag Index Completed News'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtaTagIndexCompletedNew', 'url'=>array('index')),
	array('label'=>'Manage RtaTagIndexCompletedNew', 'url'=>array('admin')),
);
?>

<h1>Create RtaTagIndexCompletedNew</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>