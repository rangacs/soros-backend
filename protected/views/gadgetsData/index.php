<?php
/* @var $this GadgetsDataController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Gadgets Datas',
);

$this->menu=array(
	array('label'=>'Create GadgetsData', 'url'=>array('create')),
	array('label'=>'Manage GadgetsData', 'url'=>array('admin')),
);
?>

<h1>Gadgets Datas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
