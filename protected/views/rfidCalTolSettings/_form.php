<?php
/* @var $this RfidCalTolSettingsController */
/* @var $model RfidCalTolSettings */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'rfid-cal-tol-settings-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>

    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo $form->labelEx($model, 'wcl_cal_tol_item_code'); ?></label>

        <div class="form-input">
            <?php echo $form->textField($model, 'wcl_cal_tol_item_code', array('size' => 60, 'maxlength' => 100)); ?>
        </div>
    </div>

    <div class="clearfix">

        <label class="form-label" for="form-name"></label>

        <div class="form-input">

        </div>
    </div>

    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo $form->labelEx($model, 'Moisture'); ?></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'Moisture'); ?>
        </div>
    </div>

    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo $form->labelEx($model, 'Moisture_Tol'); ?></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'Moisture_Tol'); ?>
        </div>
    </div>

    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo $form->labelEx($model, 'Ash'); ?></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'Ash'); ?>
        </div>
    </div>


    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo $form->labelEx($model, 'Ash_Tol'); ?></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'Ash_Tol'); ?>
        </div>
    </div>



    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo $form->labelEx($model, 'Sulfur'); ?></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'Sulfur'); ?>
        </div>
    </div>
    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo $form->labelEx($model, 'Sulfur_Tol'); ?></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'Sulfur_Tol'); ?>
        </div>
    </div>
    <div class="clearfix">

        <label class="form-label" for="form-name">GCV</label>

        <div class="form-input">

            <?php echo $form->textField($model, 'GCV'); ?>
        </div>
    </div>

    <div class="clearfix">

        <label class="form-label" for="form-name">GCV_Tol</label>

        <div class="form-input">

            <?php echo $form->textField($model, 'GCV_Tol'); ?>
        </div>
    </div>
    <div class="clearfix">

        <label class="form-label" for="form-name">BTU</label>

        <div class="form-input">

            <?php echo $form->textField($model, 'BTU'); ?>
        </div>
    </div>


    <div class="clearfix">

        <label class="form-label" for="form-name">BTU_Tol</label>

        <div class="form-input">

            <?php echo $form->textField($model, 'BTU_Tol'); ?>
        </div>
    </div>

    <div class="clearfix">

        <label class="form-label" for="form-name"><?php echo $form->labelEx($model, 'updated_on'); ?></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'updated_on'); ?>
        </div>
    </div>
    
    
    
    <div class="clearfix">

        <label class="form-label" for="form-name"></label>

        <div class="form-input">
            
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>
        </div>
    </div>



    <?php $this->endWidget(); ?>

</div><!-- form -->
