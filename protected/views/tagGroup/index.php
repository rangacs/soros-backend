<?php
$this->breadcrumbs=array(
	'Tag Groups',
);

$this->menu=array(
	array('label'=>'Create TagGroup', 'url'=>array('create')),
	array('label'=>'Manage TagGroup', 'url'=>array('admin')),
);
?>

<h1>Tag Groups</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
