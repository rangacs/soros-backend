<?php
/* @var $this RtaJobTableController */
/* @var $model RtaJobTable */

$this->breadcrumbs=array(
	'Rta Job Tables'=>array('index'),
	$model->jobID=>array('view','id'=>$model->jobID),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtaJobTable', 'url'=>array('index')),
	array('label'=>'Create RtaJobTable', 'url'=>array('create')),
	array('label'=>'View RtaJobTable', 'url'=>array('view', 'id'=>$model->jobID)),
	array('label'=>'Manage RtaJobTable', 'url'=>array('admin')),
);
?>

<h1>Update RtaJobTable <?php echo $model->jobID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>