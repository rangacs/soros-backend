<?php
/* @var $this TagQueuedController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tag Queueds',
);

$this->menu=array(
	array('label'=>'Create TagQueued', 'url'=>array('create')),
	array('label'=>'Manage TagQueued', 'url'=>array('admin')),
);
?>

<h1>Tag Queueds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
