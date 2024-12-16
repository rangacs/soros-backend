<?php
/* @var $this ChildTemplateiiiController */
/* @var $model ChildTemplateiii */

$this->breadcrumbs=array(
	'Child Templateiiis'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ChildTemplateiii', 'url'=>array('index')),
	array('label'=>'Create ChildTemplateiii', 'url'=>array('create')),
	array('label'=>'Update ChildTemplateiii', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ChildTemplateiii', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ChildTemplateiii', 'url'=>array('admin')),
);
?>

<h1>View ChildTemplateiii #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'child_key_',
		'action',
		'controller',
		'value',
	),
)); ?>
