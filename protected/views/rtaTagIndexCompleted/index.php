<?php
$this->breadcrumbs=array(
	'Rta Tag Index Completeds',
);

$this->menu=array(
	array('label'=>'Create RtaTagIndexCompleted', 'url'=>array('create')),
	array('label'=>'Manage RtaTagIndexCompleted', 'url'=>array('admin')),
);
?>

<h1>Rta Tag Index Completeds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
