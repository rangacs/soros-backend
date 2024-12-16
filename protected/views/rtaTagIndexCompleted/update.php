<?php
$this->breadcrumbs=array(
	'Rta Tag Index Completeds'=>array('index'),
	$model->tagID=>array('view','id'=>$model->tagID),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtaTagIndexCompleted', 'url'=>array('index')),
	array('label'=>'Create RtaTagIndexCompleted', 'url'=>array('create')),
	array('label'=>'View RtaTagIndexCompleted', 'url'=>array('view', 'id'=>$model->tagID)),
	array('label'=>'Manage RtaTagIndexCompleted', 'url'=>array('admin')),
);
?>

<h1>Update RtaTagIndexCompleted <?php echo $model->tagID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>