<?php
/* @var $this RtaDerivedConfigController */
/* @var $model RtaDerivedConfig */

$this->breadcrumbs=array(
	'Rta Derived Configs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtaDerivedConfig', 'url'=>array('index')),
	array('label'=>'Manage RtaDerivedConfig', 'url'=>array('admin')),
);
?>

<h1>Create RtaDerivedConfig</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>