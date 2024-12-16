<?php
/* @var $this TruckInfoController */
/* @var $model TruckInfo */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model,'w_tripID'); ?>
        <?php echo $form->textField($model,'w_tripID'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_vehNo'); ?>
        <?php echo $form->textField($model,'w_vehNo',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_plantCode'); ?>
        <?php echo $form->textField($model,'w_plantCode',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_unloaderID'); ?>
        <?php echo $form->textField($model,'w_unloaderID',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_matCode'); ?>
        <?php echo $form->textField($model,'w_matCode',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_matName'); ?>
        <?php echo $form->textField($model,'w_matName',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_suppCode'); ?>
        <?php echo $form->textField($model,'w_suppCode',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_suppName'); ?>
        <?php echo $form->textField($model,'w_suppName',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_traCode'); ?>
        <?php echo $form->textField($model,'w_traCode',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_traName'); ?>
        <?php echo $form->textField($model,'w_traName',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_loadCity'); ?>
        <?php echo $form->textField($model,'w_loadCity',array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_chQty'); ?>
        <?php echo $form->textField($model,'w_chQty'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'w_timestamp'); ?>
        <?php echo $form->textField($model,'w_timestamp'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
