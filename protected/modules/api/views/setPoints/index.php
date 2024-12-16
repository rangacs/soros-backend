<?php
/* @var $this SetPointsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Set Points',
);

$this->menu=array(
	array('label'=>'Create SetPoints', 'url'=>array('create')),
	array('label'=>'Manage SetPoints', 'url'=>array('admin')),
);
?>

<h1>Set Points</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
