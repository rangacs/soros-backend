<?php
/* @var $this RtaAverageGroupidsController */
/* @var $model RtaAverageGroupids */

$this->breadcrumbs=array(
	'Rta Average Groupids'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtaAverageGroupids', 'url'=>array('index')),
	array('label'=>'Manage RtaAverageGroupids', 'url'=>array('admin')),
);
?>

<h1>Create RtaAverageGroupids</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>