<?php
/* @var $this MasterTemplateiiiController */
/* @var $model MasterTemplateiii */

$this->breadcrumbs=array(
	'Master Templateiiis'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List MasterTemplateiii', 'url'=>array('index')),
	array('label'=>'Create MasterTemplateiii', 'url'=>array('create')),
	array('label'=>'View MasterTemplateiii', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage MasterTemplateiii', 'url'=>array('admin')),
);
?>

<h1>Update MasterTemplateiii <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>