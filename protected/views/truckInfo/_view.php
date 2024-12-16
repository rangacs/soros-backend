<?php
/* @var $this TruckInfoController */
/* @var $data TruckInfo */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_tripID')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->w_tripID), array('view', 'id'=>$data->w_tripID)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_vehNo')); ?>:</b>
    <?php echo CHtml::encode($data->w_vehNo); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_plantCode')); ?>:</b>
    <?php echo CHtml::encode($data->w_plantCode); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_unloaderID')); ?>:</b>
    <?php echo CHtml::encode($data->w_unloaderID); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_matCode')); ?>:</b>
    <?php echo CHtml::encode($data->w_matCode); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_matName')); ?>:</b>
    <?php echo CHtml::encode($data->w_matName); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_suppCode')); ?>:</b>
    <?php echo CHtml::encode($data->w_suppCode); ?>
    <br />

    <?php /*
    <b><?php echo CHtml::encode($data->getAttributeLabel('w_suppName')); ?>:</b>
    <?php echo CHtml::encode($data->w_suppName); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_traCode')); ?>:</b>
    <?php echo CHtml::encode($data->w_traCode); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_traName')); ?>:</b>
    <?php echo CHtml::encode($data->w_traName); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_loadCity')); ?>:</b>
    <?php echo CHtml::encode($data->w_loadCity); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_chQty')); ?>:</b>
    <?php echo CHtml::encode($data->w_chQty); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('w_timestamp')); ?>:</b>
    <?php echo CHtml::encode($data->w_timestamp); ?>
    <br />

    */ ?>

</div>