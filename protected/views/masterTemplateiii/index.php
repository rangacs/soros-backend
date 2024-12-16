<?php
/* @var $this MasterTemplateiiiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Master Templateiiis',
);

$this->menu=array(
	array('label'=>'Create MasterTemplateiii', 'url'=>array('create')),
	array('label'=>'Manage MasterTemplateiii', 'url'=>array('admin')),
);
?>

<h1>Master Templateiiis</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
