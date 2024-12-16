<?php
$this->breadcrumbs=array(
	'Rta Tag Index Queueds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtaTagIndexQueued', 'url'=>array('index')),
	array('label'=>'Manage RtaTagIndexQueued', 'url'=>array('admin')),
);
?>

<h1>Create RtaTagIndexQueued</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>