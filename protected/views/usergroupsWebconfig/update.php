<?php
$this->breadcrumbs=array(
	'Usergroups Webconfigs'=>array('index'),
	$model->t_id=>array('view','id'=>$model->t_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UsergroupsWebconfig', 'url'=>array('index')),
	array('label'=>'Create UsergroupsWebconfig', 'url'=>array('create')),
	array('label'=>'View UsergroupsWebconfig', 'url'=>array('view', 'id'=>$model->t_id)),
	array('label'=>'Manage UsergroupsWebconfig', 'url'=>array('admin')),
);
?>

<h1>Update UsergroupsWebconfig <?php echo $model->t_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>