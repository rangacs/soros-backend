<?php
/* @var $this LayoutWidgetsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Layout Widgets',
);

$this->menu=array(
	array('label'=>'Create LayoutWidgets', 'url'=>array('create')),
	array('label'=>'Manage LayoutWidgets', 'url'=>array('admin')),
);
?>

<h1>Layout Widgets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
