<?php
/* @var $this ProductProfileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Product Profiles',
);

$this->menu=array(
	array('label'=>'Create ProductProfile', 'url'=>array('create')),
	array('label'=>'Manage ProductProfile', 'url'=>array('admin')),
);
?>

<h1>Product Profiles</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
