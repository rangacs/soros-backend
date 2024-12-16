<?php
/* @var $this RtaAverageGroupidsController */
/* @var $model RtaAverageGroupids */

$this->breadcrumbs=array(
	'Rta Average Groupids'=>array('index'),
	$model->rtaMasterID=>array('view','id'=>$model->rtaMasterID),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtaAverageGroupids', 'url'=>array('index')),
	array('label'=>'Create RtaAverageGroupids', 'url'=>array('create')),
	array('label'=>'View RtaAverageGroupids', 'url'=>array('view', 'id'=>$model->rtaMasterID)),
	array('label'=>'Manage RtaAverageGroupids', 'url'=>array('admin')),
);
?>

<h1>Update RtaAverageGroupids <?php echo $model->rtaMasterID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>