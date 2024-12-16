<?php
/* @var $this RtaDerivedConfigController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rta Derived Configs',
);

$this->menu=array(
	array('label'=>'Create RtaDerivedConfig', 'url'=>array('create')),
	array('label'=>'Manage RtaDerivedConfig', 'url'=>array('admin')),
);
?>

<h1>Rta Derived Configs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
