<?php
/* @var $this RtaJobTableController */
/* @var $model RtaJobTable */

$this->breadcrumbs=array(
	'Rta Job Tables'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtaJobTable', 'url'=>array('index')),
	array('label'=>'Manage RtaJobTable', 'url'=>array('admin')),
);
?>

<h1>Create RtaJobTable</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>