<?php
$this->breadcrumbs=array(
	'Usergroups Webconfigs',
);

$this->menu=array(
	array('label'=>'Create UsergroupsWebconfig', 'url'=>array('create')),
	array('label'=>'Manage UsergroupsWebconfig', 'url'=>array('admin')),
);
?>

<h1>Usergroups Webconfigs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
