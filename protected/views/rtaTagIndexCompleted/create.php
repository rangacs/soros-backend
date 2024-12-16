<?php
$this->breadcrumbs=array(
	'Rta Tag Index Completeds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RtaTagIndexCompleted', 'url'=>array('index')),
	array('label'=>'Manage RtaTagIndexCompleted', 'url'=>array('admin')),
);
?>

<h1>Create RtaTagIndexCompleted</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>