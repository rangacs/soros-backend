<?php
/* @var $this TagCompletedController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tag Completeds',
);

$this->menu=array(
	array('label'=>'Create TagCompleted', 'url'=>array('create')),
	array('label'=>'Manage TagCompleted', 'url'=>array('admin')),
);
?>

<h1>Tag Completeds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
