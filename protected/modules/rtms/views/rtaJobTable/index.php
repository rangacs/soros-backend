<?php
/* @var $this RtaJobTableController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rta Job Tables',
);

$this->menu=array(
	array('label'=>'Create RtaJobTable', 'url'=>array('create')),
	array('label'=>'Manage RtaJobTable', 'url'=>array('admin')),
);
?>

<h1>Rta Job Tables</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
