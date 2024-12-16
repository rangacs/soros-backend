<?php
$this->breadcrumbs=array(
	'Rta Tag Index Queueds'=>array('index'),
	$model->tagID=>array('view','id'=>$model->tagID),
	'Update',
);

$this->menu=array(
	array('label'=>'List RtaTagIndexQueued', 'url'=>array('index')),
	array('label'=>'Create RtaTagIndexQueued', 'url'=>array('create')),
	array('label'=>'View RtaTagIndexQueued', 'url'=>array('view', 'id'=>$model->tagID)),
	array('label'=>'Manage RtaTagIndexQueued', 'url'=>array('admin')),
);
?>

<h1>Update RtaTagIndexQueued <?php echo $model->tagID; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>