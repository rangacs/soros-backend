<?php
//$this->title = "Lab Data"; 
$baseUrl = Yii::app()->baseUrl;

$labDataHistoryDeleteUrl = Yii::app()->createAbsoluteUrl('LabDataHistory/delete');
//$accessToken = Yii::$app->params['X-Access-Token'];
$viewTemplate = Yii::app()->createAbsoluteUrl('labdata/viewTemplate');



$cs = Yii::app()->clientScript;


$cs->registerScript('appConfig', "var baseUrl = " . json_encode($baseUrl) . ";"
        . "var lab_data_hitory_delete = '{$labDataHistoryDeleteUrl}';"
        . "var view_template = '{$viewTemplate}';", CClientScript::POS_BEGIN);
$currentDate = date('m/d/Y', time());

/*
  $this->registerJs( "var baseUrl = '{$baseUrl}';"
  . "var lab_data_hitory_delete = '{$labDataHistoryDeleteUrl}';"
  . "var view_template = '{$viewTemplate}';"
  . "var access_token = '{$accessToken}';", View::POS_BEGIN); */
  
  $currentHour =  date('H:00:00');

?>



<div class="portlet light waiting alert-info"  >
    <div class="portlet-body ">
        <div class="row">
            <div class="col-md-6">
                <form id='data-entry-form' class="form-horizontal has-validation" method="POST" action="<?php echo Yii::app()->createAbsoluteUrl('/labdata/SaveLabValues') ?>">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"><?php Yii::t('dash', 'End Time')?></label>
                        <div class="col-sm-10">

                            <div class="input-group date" id="EndTime">
							
							 <input type="hidden" id="timestamp" name="LabValues[EndTime]" /> 
                                <input id="dateOnlyField" type="text" class="form-control"  name="LabValues[DateOnly]" placeholder="EndTime"  value="<?php echo $currentDate ?>" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
								<select  id="timeOnlyField" class="form-control"  name="LabValues[TimeOnly]"  placeholder="TimeOnly"   style="width:80px;">
									<option value="00:00:00">00:00</option>
									<option value="01:00:00">01:00</option>
									<option value="02:00:00">02:00</option>
									<option value="03:00:00">03:00</option>
									<option value="04:00:00">04:00</option>
									<option value="05:00:00">05:00</option>
									<option value="06:00:00">06:00</option>
									<option value="07:00:00">07:00</option>
									<option value="08:00:00">08:00</option>
									<option value="09:00:00">09:00</option>
									<option value="10:00:00">10:00</option>
									<option value="11:00:00">11:00</option>
									<option value="12:00:00">12:00</option>
									<option value="13:00:00">13:00</option>
									<option value="14:00:00">14:00</option>
									<option value="15:00:00">15:00</option>
									<option value="16:00:00">16:00</option>
									<option value="17:00:00">17:00</option>
									<option value="18:00:00">18:00</option>
									<option value="19:00:00">19:00</option>
									<option value="20:00:00">20:00</option>
									<option value="22:00:00">22:00</option>
									<option value="23:00:00">23:00</option>
								</select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">SiO2</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" data-min="12.95" data-max="14" id="SiO2" placeholder="SiO2" name="LabValues[SiO2]" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Al2O3</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" data-min="3" data-max="3.7" id="Al2O3" placeholder="Al2O3" name="LabValues[Al2O3]" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Fe2O3</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" data-min="2" data-max="2.7" id="Fe2O3" placeholder="Fe2O3" name="LabValues[Fe2O3]" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">CaO</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" data-min="41.5" data-max="44.5"  id="CaO" placeholder="CaO" name="LabValues[CaO]" required>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">KH</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" data-min="0" data-max="1"  id="KH" placeholder="KH" name="LabValues[KH]" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">SM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" data-min="0" data-max="1"  id="N" placeholder="SM" name="LabValues[N]" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">IM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" data-min="0" data-max="1"  id="P" placeholder="IM" name="LabValues[P]" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="button"  onclick="entryConfim()" class="btn btn-primary">
                            
                                <?php echo Yii::t('app','Save Lab Values');?>
                                
                            
                            </button>
							
							  <button type="button"  onclick="populateRandom()" class="btn btn-primary">
                            
                                <?php echo Yii::t('app','Fill Default	 Values');?>
                                
                            
                            </button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>


    <!-- Modal -->
    <div class="modal fade" id="validation-table-modal" tabindex="-1" role="dialog" aria-labelledby="pop-formLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="pop-formLabel"><?php echo Yii::t('app','Do you wish to save these records?'); ?></h4>
                </div>
                <div class="modal-body">
				   
				   <table  style="" class="table" id='validation-table'>
				   
				   </table>
		       </form>

				</div>
                <div class="modal-footer">
				
					<button class="btn btn-blue" onclick="saveValues()"> Save  </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('app','Cancel'); ?></button>

                </div>
            </div>
        </div>
    </div>





<script type="text/javascript">

            $(function () {
            $('#EndTime').datetimepicker({
               format: 'L'
            });
			
			
		
			$("#timeOnlyField").val('<?php echo $currentHour?>');
			
				//alert('<?php echo $currentHour?>');
            });
			
            function entryConfim(id) {

			var entries = $("#data-entry-form input:text");
			
			$('#validation-table').html('');
			
			entries.each(function(){
				
				var ele = $(this).attr('placeholder');
				var min = parseFloat($(this).attr('data-min'));
				var max = parseFloat($(this).attr('data-max'));
				
				var textValue = $(this).val();
				
			
				var value = textValue.length == 0 ? 0 : parseFloat(textValue);
				var cellStyle = '';
				var data = '-';
				
				if(ele == "EndTime")
					data = $(this).val() + " " + $("#timeOnlyField").val();
				else if(ele == "TimeOnly")
					data = "";
				else if(value > min && value < max){
					cellStyle = '';
					data = value;
				}else{
					data = value + ' ?';
					cellStyle = 'font-red';
				}
				
				if(ele !=  "TimeOnly") {
					var row = '<tr><td>'+ele+'</td><td class="'+cellStyle+'"> '+data+'</td></tr>';
					console.log(row);
					$('#validation-table').append(row);
				}
			});
			
			
			
			
			var timeString = $("#timeOnlyField").val();
			var dateString = $('#dateOnlyField').val();
            var endTime =  dateString + ' '+  timeString;
			
			$('#timestamp').val(endTime);
			$('#validation-table-modal').modal('show');
		//	alert($('#timestamp').val());
                
			}
			
			function saveValues(){
				
				var formData = $("#data-entry-form").serialize();
		
				  $.ajax({
						 'url' : '<?php echo Yii::app()->createAbsoluteUrl('/labdata/SaveLabValues') ?>',
						 type : 'post',
						 data : {'formData' : formData },
						 success: function(){
							 swal('Success','Lab records saved','success');
							 window.location = '<?php echo Yii::app()->createAbsoluteUrl('/labdata') ?>'
						 },
						 error : function(){
							 
							 
							 swal('Success','Lab records saved','success');
							 window.location = '<?php echo Yii::app()->createAbsoluteUrl('/labdata') ?>'
							 
							 
						 }
			
				  });
			}
			
			
			function populateRandom(){
				
				var entries = $("#data-entry-form input:text");
			
				entries.each(function(){
				
				var ele = $(this).attr('placeholder');
				if(ele === 'EndTime'){
					return;
				}
				var min = parseFloat($(this).attr('data-min'));
				var max = parseFloat($(this).attr('data-max'));
				
				var diff =  max - min ;
				 
				 
				var rValue =getRandom(0,diff);
				var nValue = rValue + min;
			
				$(this).val(nValue.toPrecision(4) );
		
			});	}
			

			function getRandom(min, max) {
				
				min = Math.ceil(min);
				max = Math.floor(max);
				var decimal = Math.floor(Math.random() * (max - min + 1)) + min
				return decimal / 100;
			}
			
			

</script>