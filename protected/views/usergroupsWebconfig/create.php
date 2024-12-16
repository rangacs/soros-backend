<?php
$this->breadcrumbs=array(
	'Usergroups Webconfigs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UsergroupsWebconfig', 'url'=>array('index')),
	array('label'=>'Manage UsergroupsWebconfig', 'url'=>array('admin')),
);
?>

<h1>Create UsergroupsWebconfig</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>