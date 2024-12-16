<?php
/* @var $this ElementCompositionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Element Compositions',
);

$this->menu=array(
	array('label'=>'Create ElementComposition', 'url'=>array('create')),
	array('label'=>'Manage ElementComposition', 'url'=>array('admin')),
);
?>

<h1>Element Compositions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
