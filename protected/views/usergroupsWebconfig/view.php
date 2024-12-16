<?php
$this->breadcrumbs=array(
	'Usergroups Webconfigs'=>array('index'),
	$model->t_id,
);

$this->menu=array(
	array('label'=>'List UsergroupsWebconfig', 'url'=>array('index')),
	array('label'=>'Create UsergroupsWebconfig', 'url'=>array('create')),
	array('label'=>'Update UsergroupsWebconfig', 'url'=>array('update', 'id'=>$model->t_id)),
	array('label'=>'Delete UsergroupsWebconfig', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->t_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsergroupsWebconfig', 'url'=>array('admin')),
);
?>

<h1>View UsergroupsWebconfig #<?php echo $model->t_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		't_id',
		'u_id',
		'rule',
		'value',
		'options',
		'description',
	),
)); ?>
