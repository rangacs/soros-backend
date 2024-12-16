<?php
/* @var $this TruckInfoController */
/* @var $model TruckInfo */

$this->breadcrumbs=array(
    'Truck Infos'=>array('index'),
    $model->w_tripID,
);

$this->menu=array(
    array('label'=>'List TruckInfo', 'url'=>array('index')),
    array('label'=>'Create TruckInfo', 'url'=>array('create')),
    array('label'=>'Update TruckInfo', 'url'=>array('update', 'id'=>$model->w_tripID)),
    array('label'=>'Delete TruckInfo', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->w_tripID),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage TruckInfo', 'url'=>array('admin')),
);
?>

<h1>View TruckInfo #<?php echo $model->w_tripID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'w_tripID',
        'w_vehNo',
        'w_plantCode',
        'w_unloaderID',
        'w_matCode',
        'w_matName',
        'w_suppCode',
        'w_suppName',
        'w_traCode',
        'w_traName',
        'w_loadCity',
        'w_chQty',
        'w_timestamp',
    ),
)); ?>
