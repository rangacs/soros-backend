

<div class="row hide">
    <div class="col-md-12">
        <div class="portlet light">


            <table class="table table-black table-bordered">

                <tr><th ><?php echo Yii::t('Library','app');?></th><th > <?php echo Yii::t('Configuration','app');?> </th></tr>
                <tbody>
                    <tr><td><select class="form-control" onchange="scaleDirSelect(this.value)">
                                <option value="default" selected="default">
								
								
								<?php echo Yii::t('default','app');?>
								
								</option>
								</select></td><td id="scaleParamFileList"><select class="form-control" onchange="scaleFileSelect(this.value)"><option value="/usr/local/sabia-ck/cal_adjust/usrCalLib/default/default.cfg" selected="/usr/local/sabia-ck/cal_adjust/usrCalLib/default/default.cfg">default/default</option></select>
                        </td>
                    </tr>

                </tbody>
            </table>


        </div>
    </div>
</div>
<div class="row"> 
    <div class="col-md-12"> 
        <!--Portlet Start-->
        <div class="portlet light" >

            <div  class="portlet-title">
                <div class="caption font-black ">
                   
					
					
				<?php echo Yii::t('app',' User Applied Output Parameter Offset');?>
                </div>

                <div  class="action pull-right hide">
                    <button type="button" class="btn blue "  onclick="addNew()">
                      
				<?php echo Yii::t('app' ,' Add New');?>  <i class="fa fa-plus-circle"></i>
                    </button>
                </div>
            </div>
            <div class="portlet-body">

                <div class="clearfix"></div>

                <table class="table table-blue table-bordered">
                    <thead>
                        <tr><th><?php echo Yii::t('app' ,'Output Parameter');?> </th><th><?php echo Yii::t('app' ,'Offset');?></th>
						<th style="width: 200px"><?php echo Yii::t('app' ,'Action');?></th></tr>
                    </thead>
                    <tbody  id="scaleList">

                    </tbody>
                </table>



            </div>
        </div>
        <!--Portlet end-->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="pop-form" tabindex="-1" role="dialog" aria-labelledby="pop-formLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="pop-formLabel"><?php echo Yii::t('app' ,'Update Parameter');?> </h4>
                </div>

                <form   id="update-calib-form" method="post"  class="form-horizontal"> 

                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo Yii::t('app' ,'Output Parameter');?></label>
                            <div class="col-md-9">

                                <input class="form-control "  readonly="readonly" type="text"  required="required" name="updateScaleParamSelection" value="">
                            </div>
                        </div>

                        <div class="form-group hide">
                            <label class="col-md-3 control-label"><?php echo Yii::t('app' ,'Gain');?></label>
                            <div class="col-md-9">
                                <input class="form-control " placeholder="Gain" type="text"    name="updateScaleParamSelectionGain"> </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo Yii::t('app' ,'Offset');?></label>
                            <div class="col-md-9">
                                <input class="form-control " placeholder="Offset" type="text" required   name="updateScaleParamSelectionOffset"> </div>
                        </div>






                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><?php echo Yii::t('app' ,'Save');?> </button>

                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('app' ,'Close');?></button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="new-form" tabindex="-1" role="dialog" aria-labelledby="pop-formLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
			
			
                
				
				<?php 
				
				    $scaleOptionAr = explode(',','Al2O3,SiO2,Fe2O3,CaO');//$labTempColumns;
 	
				  if(count($existingScaleList) != count($scaleOptionAr) ){
					  
					  
				?>

				<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="pop-formLabel"> <?php echo Yii::t('app' ,'Add Parameter');?></h4>
                </div>
				
                <form id="new-calib-form" method="post"  class="form-horizontal"> 
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo Yii::t('app' ,'Output Parameter');?></label>
                            <div class="col-md-9">

                                <!--<input class="form-control" type="text" name="updateScaleParamSelection" value="">-->
                                <select class="form-control" name="newScaleParamSelection" required="required">
                                    
                                    <?php 
                                    

                                        foreach ($scaleOptionAr as $sOption){
                                            
                                            if(!in_array( $sOption , $existingScaleList))
                                            echo  '<option value="'.$sOption.'">'.$sOption.'</option>';
                                        }
                                    
                                    ?>
                                </select>


                            </div>
                        </div>

                        <div class="form-group hide">
                            <label class="col-md-3 control-label"><?php echo Yii::t('app' ,'Gain');?></label>
                            <div class="col-md-9">
                                <input class="form-control"   placeholder="Gain" type="text" name="newScaleParamSelectionGain" value=""> </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" ><?php echo Yii::t('app' ,'Offset');?></label>
                            <div class="col-md-9">
                                <input class="form-control " placeholder="Offset" type="text" name="newScaleParamSelectionOffset" value=""> </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><?php echo Yii::t('app' ,'Save');?> </button>

                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('app' ,'Close');?></button>
                    </div>
                </form>
				  <?php }else{
					  ?>
					  
					  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   
                </div>
				
					  <h3 class="alert alert-text text-center  font-red "> No more elements to add</h3>
					  <?php
					     
				  }?>

            </div>
        </div>
    </div>

</div>

<script type="text/javascript">

    var scaleList

    window.onload = function () {

        jQuery.validator.addMethod("non_zero", function (value, element) {
            // allow any non-whitespace characters as the host part
            return this.optional(element) || value != 0;
        }, 'Please enter non-zero values');




        loadScaleTable();
        var nForm = $('#new-calib-form');
        var nError = $('.alert-danger', nForm);

        var nSuccess = $('.alert-success', nForm);

        nForm.validate(
                {
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "", // validate all fields including form hidden input
                    rules: {
                        
                
                        newScaleParamSelectionOffset: {
                            required: true,
                            non_zero: true,
                            number: true,

//                         decimal: true
                        },
                    },

                    errorPlacement: function (error, element) { // render error placement for each input typeW
                        if (element.parent(".input-group").size() > 0) {
                            error.insertAfter(element.parent(".input-group"));
                        } else if (element.attr("data-error-container")) {
                            error.appendTo(element.attr("data-error-container"));
                        } else {
                            error.insertAfter(element); // for other inputs, just perform default behavior
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit   
                        nSuccess.hide();
                        nError.show();
                        App.scrollTo(nError, -200);
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                                .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                                .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function (form) {
                        nSuccess.show();
                        nError.hide();
                        saveNew();

                    }

                });



        var uForm = $('#update-calib-form');
        var uError = $('.alert-danger', uForm);

        var uSuccess = $('.alert-success', uForm);

        uForm.validate(
                {
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "", // validate all fields including form hidden input
                    rules: {
                        updateScaleParamSelection: {
                            required: true,
                        }
                        ,
                        updateScaleParamSelectionOffset: {
                          required: true,
                            non_zero: true,
                            number: true,
                        },
                                     },

                    errorPlacement: function (error, element) { // render error placement for each input typeW
                        if (element.parent(".input-group").size() > 0) {
                            error.insertAfter(element.parent(".input-group"));
                        } else if (element.attr("data-error-container")) {
                            error.appendTo(element.attr("data-error-container"));
                        } else {
                            error.insertAfter(element); // for other inputs, just perform default behavior
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit   
                        uSuccess.hide();
                        uError.show();
                        App.scrollTo(uError, -200);
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                                .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                                .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },

                    submitHandler: function (form) {
                        uSuccess.show();
                        uError.hide();
                        saveEntry();

                    }

                });

    }

    function init() {

        $(".edit").on('click', function () {

            edit(this);
        })

        $(".delete").on('click', function () {

            deleteScale(this)
        })


    }

    function edit(ele) {

        var scaleEle = $(ele).attr('data-name');

        var temp = scaleList[scaleEle];


        $("input[name='updateScaleParamSelection']").val(scaleEle);
        $("input[name='updateScaleParamSelectionGain']").val(temp.gain);
        $("input[name='updateScaleParamSelectionOffset']").val(temp.offset);


        $("#pop-form").modal('show');
    }


    function deleteScale(ele) {


        var scaleName = $(ele).attr('data-name')
        swal({
            title: "Are you sure?",
            text: "<?php echo Yii::t('app' ,'Do you want to delete scale element?');?>",
            icon: "warning",
            buttons: {

                confirm: {
                    text: "Delete",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                },
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: true,
                },

            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {

//                deleteLabHistory(id)

//                console.log("Deleting =>", scaleName);
                delete scaleList[scaleName];
                saveAllEntries('delete')

            } else {
                //swal("Your imaginary file is safe!");
            }
        }); // EOF swal

    }
    function loadScaleTable() {
        $("#scaleList").html('');
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl('/calib/getScale') ?>',
            success: function (response) {

                var list = response;
                scaleList = list;
                for (x in list) {

//                scaleList.push()
                    var delBtn = "<button class='btn red delete' data-name='" + x + "' >Delete</button>";
                    
                    var editText = ' <?php echo Yii::t('plot', 'Edit'); ?>';
                    var editBrn = "<button class='btn blue edit'  data-name='" + x + "' >"+editText+"</button>";
                    var trB = '<tr>'
                    var trE = '</tr>'

                    var nameTd = "<td>" + x + "</td>"
                    var offTd = "<td>" + list[x].offset + "</td>"
                    var gainTd = "<td>" + list[x].gain + "</td>"

                    var actionTd = "<td>" + editBrn + "</td>";
//                    console.log(list[x]);


                    var row = trB + nameTd +  offTd + actionTd;

                    $("#scaleList").append(row);

                }
                init();
            }// End of function


        });
    }


    function addNew() {

        $("input[name='newScaleParamSelection']").val('');
        $("input[name='newScaleParamSelectionGain']").val('')
        $("input[name='newScaleParamSelectionOffset']").val('')
        $("#new-form").modal('show');

    }



    function saveNew() {


        var scale = $("select[name='newScaleParamSelection']").val();

        $.ajax({
            type: 'GET',
            url: '<?php echo Yii::app()->createAbsoluteUrl('/calib/create') ?>',
            data: { element: scale, scaleList: scaleList, offset: $("input[name='newScaleParamSelectionOffset']").val(), gain: $("input[name='newScaleParamSelectionGain']").val()},
            success: function (response) {

                swal('Success', scale + ' scale parameter added', 'success');
				 
				//window.location = '';
                $("#new-form").modal('hide');
                loadScaleTable();
            },
            error: function () {

                swal('Error', "Unable to add" + scale + '  parameter', 'error')
            }
        })
    }
    function saveEntry() {



//    var csrfToken = $('meta[name="csrf-token"]').attr("content");
//        $("#pop-form").modal('close');
        var scale = $("input[name='updateScaleParamSelection']").val();
        var gain = $("input[name='updateScaleParamSelectionGain']").val();
        var offset = $("input[name='updateScaleParamSelectionOffset']").val();

        if (!scaleList.hasOwnProperty(scale)) {

            let key = $("input[name='updateScaleParamSelection']").val()
            var tmpObj = {key: {gain: gain, offset: offset}}

            scaleList.key = {gain: gain, offset: offset};
//            scaleList.push(key);  
            scaleList[key].gain = gain;
            scaleList[scale].offset = offset;

        } else {
            scaleList[scale].gain = gain;
            scaleList[scale].offset = offset;


        }

        saveAllEntries('save');
    }

    function handleSave(e) {

    }

    function saveAllEntries(operation = '') {

	
	  var eleValue =  $("input[name='updateScaleParamSelection']").val(); 
        $.ajax({
            type: 'GET',
            url: '<?php echo Yii::app()->createAbsoluteUrl('/calib/saveEntry') ?>',
            data: { scaleList: scaleList, offset: $("input[name='updateScaleParamSelectionOffset']").val(),eleValue : eleValue, gain: $("input[name='updateScaleParamSelectionGain']").val()},
            success: function (response) {

                $("#pop-form").modal('hide');
                
                if(operation === 'delete' ){
                    
                    
                swal('Success', ' Operation success', 'success');
                }else{
                    
                swal('Success', ' Operation success', 'success');
                }
                
                window.location = '';
                loadScaleTable();
            }
        })
    }


    function hancleError(formSelector) {

        var validationOption = {
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: !1,
            ignore: "",

            errorPlacement: function (e, r) {
                var i = $(r).parent(".input-group");
                i.size() > 0 ? i.after(e) : r.after(e)
            },
            highlight: function (e) {
                $(e).closest(".form-group").addClass("has-error")
            },
            unhighlight: function (e) {
                $(e).closest(".form-group").removeClass("has-error")
            },
            success: function (e) {
                e.closest(".form-group").removeClass("has-error")
            },
            submitHandler: function (e) {
                i.show(), r.hide()
            }
        }

  
        var validator = $(formSelector).validate();

        return validator.valid();

    }

</script>

<?php
//$this->registerJsFile('@web/js/site/form/calib-validation.js'); ?>