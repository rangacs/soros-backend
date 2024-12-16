<?php
/* @var $this ChildTemplateiiiController */
/* @var $model ChildTemplateiii */

$this->breadcrumbs=array(
	'Child Templateiiis'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ChildTemplateiii', 'url'=>array('index')),
	array('label'=>'Create ChildTemplateiii', 'url'=>array('create')),
	array('label'=>'View ChildTemplateiii', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ChildTemplateiii', 'url'=>array('admin')),
);
?>

<h1>Update ChildTemplateiii <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>