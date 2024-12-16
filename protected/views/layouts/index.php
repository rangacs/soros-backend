<?php
/* @var $this LayoutsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Layouts',
);

$this->menu=array(
	array('label'=>'Create Layouts', 'url'=>array('create')),
	array('label'=>'Manage Layouts', 'url'=>array('admin')),
);
?>

<h1>Layouts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
