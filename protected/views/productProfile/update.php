<?php
/* @var $this ProductProfileController */
/* @var $model ProductProfile */

$this->breadcrumbs=array(
	'Product Profiles'=>array('index'),
	$model->product_id=>array('view','id'=>$model->product_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ProductProfile', 'url'=>array('index')),
	array('label'=>'Create ProductProfile', 'url'=>array('create')),
	array('label'=>'View ProductProfile', 'url'=>array('view', 'id'=>$model->product_id)),
	array('label'=>'Manage ProductProfile', 'url'=>array('admin')),
);
?>

<h1>Update ProductProfile <?php echo $model->product_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>