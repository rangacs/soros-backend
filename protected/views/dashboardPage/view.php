<?php
$this->breadcrumbs=array(
	'Dashboard Pages'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List DashboardPage', 'url'=>array('index')),
	array('label'=>'Create DashboardPage', 'url'=>array('create')),
	array('label'=>'Update DashboardPage', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DashboardPage', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DashboardPage', 'url'=>array('admin')),
);
?>

<h1>View DashboardPage #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'title',
	),
)); ?>
