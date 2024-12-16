<?php
$this->breadcrumbs=array(
	'Dashboard Pages',
);

$this->menu=array(
	array('label'=>'Create DashboardPage', 'url'=>array('create')),
	array('label'=>'Manage DashboardPage', 'url'=>array('admin')),
);
?>

<h1>Dashboard Pages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
