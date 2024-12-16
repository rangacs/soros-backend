<?php
/* @var $this ElementCompositionController */
/* @var $model ElementComposition */

$this->breadcrumbs=array(
	'Element Compositions'=>array('index'),
	$model->element_id,
);

$this->menu=array(
	array('label'=>'List ElementComposition', 'url'=>array('index')),
	array('label'=>'Create ElementComposition', 'url'=>array('create')),
	array('label'=>'Update ElementComposition', 'url'=>array('update', 'id'=>$model->element_id)),
	array('label'=>'Delete ElementComposition', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->element_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ElementComposition', 'url'=>array('admin')),
);
?>

<h1>View ElementComposition #<?php echo $model->element_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'element_id',
		'source_id',
		'element_name',
		'element_value',
		'element_type',
		'estimated_prob_error',
		'estimated_max',
		'estimated_min',
		'update_timestamp',
	),
)); ?>
