<?php
/* @var $this ChildTemplateiiiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Child Templateiiis',
);

$this->menu=array(
	array('label'=>'Create ChildTemplateiii', 'url'=>array('create')),
	array('label'=>'Manage ChildTemplateiii', 'url'=>array('admin')),
);
?>

<h1>Child Templateiiis</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
