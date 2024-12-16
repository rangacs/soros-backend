<?php
/* @var $this MasterTemplateiiiController */
/* @var $model MasterTemplateiii */

$this->breadcrumbs=array(
	'Master Templateiiis'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MasterTemplateiii', 'url'=>array('index')),
	array('label'=>'Create MasterTemplateiii', 'url'=>array('create')),
	array('label'=>'Update MasterTemplateiii', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MasterTemplateiii', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MasterTemplateiii', 'url'=>array('admin')),
);
?>

<h1>View MasterTemplateiii #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'_key_',
	),
)); ?>
