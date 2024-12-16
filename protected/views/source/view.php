<?php
/* @var $this SourceController */
/* @var $model Source */

$this->breadcrumbs=array(
	'Sources'=>array('index'),
	$model->src_id,
);

$this->menu=array(
	array('label'=>'List Source', 'url'=>array('index')),
	array('label'=>'Create Source', 'url'=>array('create')),
	array('label'=>'Update Source', 'url'=>array('update', 'id'=>$model->src_id)),
	array('label'=>'Delete Source', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->src_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Source', 'url'=>array('admin')),
);
?>

<h1>View Source #<?php echo $model->src_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'src_id',
		'src_name',
		'src_type',
		'product_id',
		'src_priority',
		'src_distance',
		'src_delay',
		'src_min_feedrate',
		'src_max_feedrate',
		'src_proposed_feedrate',
		'src_measured_feedrate',
		'src_cost',
		'src_status',
	),
)); ?>
