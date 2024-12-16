<?php
$this->breadcrumbs=array(
	'Dashboard Pages'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DashboardPage', 'url'=>array('index')),
	array('label'=>'Create DashboardPage', 'url'=>array('create')),
	array('label'=>'View DashboardPage', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DashboardPage', 'url'=>array('admin')),
);
?>

<h1>Update DashboardPage <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>