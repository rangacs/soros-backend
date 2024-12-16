<?php
/* @var $this RfidCalMapController */
/* @var $model RfidCalMap */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'rfid-cal-map-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>


    <p class="note">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

    <div class="clearfix">

        <label class="form-label" for="form-name">Name</label>

        <div class="form-input">

            <input size="30" maxlength="30" name="TagQueued[tagName]" id="TagQueued_tagName" type="text">      
        
        </div>
    </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($model, 'wcl_item_code'); ?>
<?php echo $form->textField($model, 'wcl_item_code', array('size' => 60, 'maxlength' => 100)); ?>
<?php echo $form->error($model, 'wcl_item_code'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'wcl_item_namev'); ?>
<?php echo $form->textField($model, 'wcl_item_namev', array('size' => 60, 'maxlength' => 100)); ?>
<?php echo $form->error($model, 'wcl_item_namev'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'wcl_sabia_cal_name'); ?>
<?php echo $form->textField($model, 'wcl_sabia_cal_name', array('size' => 60, 'maxlength' => 100)); ?>
<?php echo $form->error($model, 'wcl_sabia_cal_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'wcl_updated'); ?>
<?php echo $form->textField($model, 'wcl_updated', array('size' => 60, 'maxlength' => 200)); ?>
<?php echo $form->error($model, 'wcl_updated'); ?>
    </div>

    <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->