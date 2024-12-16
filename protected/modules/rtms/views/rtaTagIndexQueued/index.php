<?php
/* @var $this RtaTagIndexQueuedController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rta Tag Index Queued News',
);

$this->menu=array(
	array('label'=>'Create RtaTagIndexQueuedNew', 'url'=>array('create')),
	array('label'=>'Manage RtaTagIndexQueuedNew', 'url'=>array('admin')),
);
?>

<h1>Rta Tag Index Queued News</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
