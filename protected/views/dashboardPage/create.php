<?php
$this->breadcrumbs=array(
	'Dashboard Pages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DashboardPage', 'url'=>array('index')),
	array('label'=>'Manage DashboardPage', 'url'=>array('admin')),
);
?>

<h1>Create DashboardPage</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>