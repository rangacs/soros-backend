
<div class="main-content">

    <section class="container_6 clearfix">

        <!-- Tabs inside Portlet -->
        <div class="grid_6 leading">
            <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                <h2> <?php echo Yii::t('app', 'Truck Loading'); ?></h2>

            </header>
            <section id="section_rawMix" class="ui-widget-content ui-corner-bottom">

                <form id="truck_info_form" action="" method="post" class="form grid_4">

                    <div class="clearfix">

                        <label class="form-label" for="form-name"></label>

                        <div class="form-input">

                            <p class="note">Fields with <span class="required">*</span> are required.</p> 

                        </div>
                    </div>


                    <div class="clearfix">

                        <label class="form-label" for="form-name">Truck Id<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_trip_id" id="wcl_trip_id" type="text">      
                        </div>
                    </div>


                    <div class="clearfix">

                        <label class="form-label" for="form-name">Vehicle No<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_veh_no" id="wcl_veh_no" type="text">      
                        </div>
                    </div>


                    <div class="clearfix">

                        <label class="form-label" for="form-name">Plant Code<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_pl_code" id="wcl_pl_code" type="text">      
                        </div>
                    </div>


                    <div class="clearfix">

                        <label class="form-label" for="form-name">Unloader Id<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_unl_id" id="wcl_unl_id" type="text">      
                        </div>
                    </div>


                    <div class="clearfix">

                        <label class="form-label" for="form-name">Material Code<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_mat_code" id="wcl_mat_code" type="text">      
                        </div>
                    </div>



                    <div class="clearfix">

                        <label class="form-label" for="form-name">Material Name<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_mat_name" id="wcl_mat_name" type="text">      
                        </div>
                    </div>


                    <div class="clearfix">

                        <label class="form-label" for="form-name">Supplier Code<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_supp_code" id="wcl_supp_code" type="text">      
                        </div>
                    </div>


                    <div class="clearfix">

                        <label class="form-label" for="form-name">Supplier Name<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_supp_name" id="wcl_supp_name" type="text">      
                        </div>
                    </div>

                    <div class="clearfix">

                        <label class="form-label" for="form-name">Transporter Code<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_trans_code" id="wcl_trans_code" type="text">      
                        </div>
                    </div>


                    <div class="clearfix">

                        <label class="form-label" for="form-name">Transporter Name<em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_trans_name" id="wcl_trans_name" type="text">      
                        </div>
                    </div>


                    <div class="clearfix">

                        <label class="form-label" for="form-name">Loading <em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_loading_city" id="wcl_loading_city" type="text">      
                        </div>
                    </div>

                    <div class="clearfix">

                        <label class="form-label" for="form-name">Challan Quantity <em>*</em></label>

                        <div class="form-input">

                            <input size="30" maxlength="30" name="wcl_chl_qty" id="wcl_chl_qty" type="text">      
                        </div>
                    </div>


                    <div class="clearfix ">
                        <label class="form-label" for="form-name">Timestamp  <em>*</em></label>
                        <div class="form-input">
                            <input type="text" required="required" name="wcl_tr_updated" id="wcl_tr_updated_date" autocomplete="off" value="<?= date("Y-m-d H:i:s")?>" class="hasDatepicker">

                        </div>
                    </div> 

                    <div class="clearfix">
                        <div class="form-input">

                            <input id="subMB" type="submit" name="yt0" value="Create">        </div>
                    </div>

                </form>

            </section>
        </div><!--  grid_6 -->
    </section>

</div>
<div class="clearfix"></div>
<script src="<?php echo Yii::app()->baseUrl ?>/js/axios.min.js"></script>
<script type="text/javascript">

    window.onload = function () {

        $("#truck_info_form").live("submit", function (e) {
            e.preventDefault();
            var serialize_data = $("#truck_info_form").serialize();
            var url = "http://localhost/wonder_rest/server/sabRemRFServ.php"; 
            axios.post(url, {
                data: serialize_data,
                
            }).then(function (response) {
                console.log(response);
            })
                    .catch(function (error) {
                        console.log(error);
                    }); //END :: Axios 
        });


    }


</script>