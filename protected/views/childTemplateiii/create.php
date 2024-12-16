<?php
/* @var $this ChildTemplateiiiController */
/* @var $model ChildTemplateiii */

$this->breadcrumbs=array(
	'Child Templateiiis'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ChildTemplateiii', 'url'=>array('index')),
	array('label'=>'Manage ChildTemplateiii', 'url'=>array('admin')),
);
?>

<h1>Create ChildTemplateiii</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>