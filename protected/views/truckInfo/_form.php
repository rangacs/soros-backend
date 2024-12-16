<?php
/* @var $this TruckInfoController */
/* @var $model TruckInfo */
/* @var $form CActiveForm */
?>


<div class="form" style="margin-top:20px;">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'truck-info-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="clearfix" style="padding:20px;">


        <div style="background:lightyellow;color:red;">
            
            <p class="note">Fields with <span class="required" style="color:red;">*</span> are required.</p>
            <p id="esummary" ></p>
            <?php echo $form->errorSummary($model); ?>
        </div>
    </div>

        <div class="clearfix">

        <label class="form-label" for="form-name">Trip ID<em>*</em></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'w_tripID', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_tripID'); ?>   
            
            
        </div>
    </div>
    <div class="clearfix">

        <label class="form-label" for="form-name"></label>

        <div class="form-input">

            <input type="text" name="w_trigType" value="PB-2" style="float:left;width:50px !important;font-weight:bold;color:darkblue;background:lightyellow;"/>  
            <br/>
            
        </div>
        
    </div>

    <div class="clearfix">

        <label class="form-label" for="form-name">Vehicle No.<em>*</em></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'w_vehNo', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_vehNo'); ?>   
        </div>
    </div>



    <div class="clearfix">

        <label class="form-label" for="form-name">Plant Code<em>*</em></label>

        <div class="form-input">
            <?php echo $form->textField($model, 'w_plantCode', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_plantCode'); ?>

        </div>
    </div>



    <div class="clearfix">

        <label class="form-label" for="form-name">Unloader Id<em>*</em></label>

        <div class="form-input">
            <?php echo $form->textField($model, 'w_unloaderID', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_unloaderID'); ?>

        </div>
    </div>




    <div class="clearfix">

        <label class="form-label" for="form-name">Material Code<em>*</em></label>

        <div class="form-input">
            <?php echo $form->textField($model, 'w_matCode', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_matCode'); ?>

        </div>
    </div>


    <div class="clearfix">

        <label class="form-label" for="form-name">Material Name<em>*</em></label>

        <div class="form-input">

            <?php echo $form->textField($model, 'w_matName', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
             <?php echo $form->error($model, 'w_matName'); ?>

        </div>
    </div>


    <div class="clearfix">

        <label class="form-label" for="form-name">Supplier Code<em>*</em></label>

        <div class="form-input">
            <?php echo $form->textField($model, 'w_suppCode', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_suppCode'); ?>

        </div>
    </div>


    <div class="clearfix">

        <label class="form-label" for="form-name">Supplier Name<em>*</em></label>

        <div class="form-input">
            <?php echo $form->textField($model, 'w_suppName', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_suppName'); ?>

        </div>
    </div>


    <div class="clearfix">

        <label class="form-label" for="form-name">Transporter code<em>*</em></label>

        <div class="form-input">
            <?php echo $form->textField($model, 'w_traCode', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_traCode'); ?>

        </div>
    </div>


    <div class="clearfix">

        <label class="form-label" for="form-name">Transporter Name<em>*</em></label>

        <div class="form-input">
            <?php echo $form->textField($model, 'w_traName', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_traName'); ?>

        </div>
    </div>



    <div class="clearfix">


        <label class="form-label" for="form-name">Loading City<em>*</em></label>


        <div class="form-input"><?php echo $form->textField($model, 'w_loadCity', array('size' => 60, 'maxlength' => 100, 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_loadCity'); ?>
        </div>
    </div>

    <div class="clearfix">
        <label class="form-label" for="form-name">Challan Quantity<em>*</em></label>

        <div class="form-input">
            <?php echo $form->textField($model, 'w_chQty', array('required'=>true)); ?>
            <?php echo $form->error($model, 'w_chQty'); ?>
        </div>
    </div>

    <div class="clearfix">
        <label class="form-label" for="form-name">Timestamp <em>*</em></label>
        <div class="form-input">
            <?php $model->w_timestamp = date("H:i") ?>
            <input type="text" id="datepicker" name='w_dateVal' required value="<?php echo date("Y-m-d"); ?>"/>
            <?php echo $form->textField($model, 'w_timestamp', array('id'=>'w_timestamp','required'=>true, 'style'=>'max-width:60px;', 'required'=>true)); ?>
            <?php echo $form->error($model, 'w_timestamp'); ?>
        </div></div>

    <div class="clearfix buttons">
        <div class="form-input"><?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?></div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

