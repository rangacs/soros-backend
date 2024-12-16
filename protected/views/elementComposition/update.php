<?php
/* @var $this ElementCompositionController */
/* @var $model ElementComposition */

$this->breadcrumbs=array(
	'Element Compositions'=>array('index'),
	$model->element_id=>array('view','id'=>$model->element_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ElementComposition', 'url'=>array('index')),
	array('label'=>'Create ElementComposition', 'url'=>array('create')),
	array('label'=>'View ElementComposition', 'url'=>array('view', 'id'=>$model->element_id)),
	array('label'=>'Manage ElementComposition', 'url'=>array('admin')),
);
?>

<h1>Update ElementComposition <?php echo $model->element_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>