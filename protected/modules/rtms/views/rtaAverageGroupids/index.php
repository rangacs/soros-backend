<?php
/* @var $this RtaAverageGroupidsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rta Average Groupids',
);

$this->menu=array(
	array('label'=>'Create RtaAverageGroupids', 'url'=>array('create')),
	array('label'=>'Manage RtaAverageGroupids', 'url'=>array('admin')),
);
?>

<h1>Rta Average Groupids</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
