<?php
/* @var $this MasterTemplateiiiController */
/* @var $model MasterTemplateiii */

$this->breadcrumbs=array(
	'Master Templateiiis'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List MasterTemplateiii', 'url'=>array('index')),
	array('label'=>'Manage MasterTemplateiii', 'url'=>array('admin')),
);
?>

<h1>Create MasterTemplateiii</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>