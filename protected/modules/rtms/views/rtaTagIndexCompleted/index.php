<?php
/* @var $this RtaTagIndexCompletedController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rta Tag Index Completed News',
);

$this->menu=array(
	array('label'=>'Create RtaTagIndexCompletedNew', 'url'=>array('create')),
	array('label'=>'Manage RtaTagIndexCompletedNew', 'url'=>array('admin')),
);
?>

<h1>Rta Tag Index Completed News</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
