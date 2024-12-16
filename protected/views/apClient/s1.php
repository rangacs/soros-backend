<section>
    <div class="container_8 clearfix">
        <!-- Main Section -->
        <div id="content">
            <!-- Abhinandan. Invoke the script responsible for rendering the Left Side Bar Menu upon page load.. -->


            <style type="text/css">a.longSize {width:810px !important; margin-left:5px;}
            </style>


            <section class="main-section grid_8">
                <nav class="">
                    <!-- Abhinandan. Invoke the script responsible for rendering the Left Side Bar Menu upon page load.. -->
                    <a class="chevron" href="#">»<span class="ui-icon ui-icon-circle-triangle-e"></span></a>


                    <!-- LR 03/10/2013. This is what renders the Left Side Bar Menu -->


                    <ul id="bodyLeftMenu"> 


                        <!-- 12/27.. left_menu_arr -->
                        <li id="leftMenuItemdash" title="" class="">
                            <a href="/HELIOS1/helios1-dev/rawmix/dash" class="navicon-house">
                                仪表盘                   </a>
                        </li>
                        <!-- 12/27.. left_menu_arr -->
                        <li id="leftMenuItemsettings" title="" class="">
                            <a href="/HELIOS1/helios1-dev/rawmix/admin" class="navicon-cabinet">
                                数据配置                   </a>
                        </li>
                        <!-- 12/27.. left_menu_arr -->
                        <li id="leftMenuItemtheme" title="" class="">
                            <a href="/HELIOS1/helios1-dev/rawmix/history" class="navicon-photos">
                                准值                   </a>
                        </li>
                        <!-- 12/27.. left_menu_arr -->
                        <li id="calibration" title="Calibration" class="">
                            <a href="/HELIOS1/helios1-dev/rawmix/lmessages" class="navicon-ekg">
                                日志                   </a>
                        </li>
                        <!-- 12/27.. left_menu_arr -->
                        <li id="rmSettings" title="rmSettings" class="">
                            <a href="/HELIOS1/helios1-dev/rawmix/rmsettings" class="navicon-gear-1">
                                运行设置                   </a>
                        </li>
                        <!-- 12/27.. left_menu_arr -->
                        <li id="simulateRun" title="Simulate RHEA" class="">
                            <a href="/HELIOS1/helios1-dev/rawmix/iohistory" class="navicon-gear-2">
                                RM-历史                   </a>
                        </li>
                        <!-- 12/27.. left_menu_arr -->
                        <li id="" title="" class="">
                            <a href="/HELIOS1/helios1-dev/site/logout" class="navicon-id-card">
                                退出                   </a>
                        </li>
                    </ul>                 
                </nav>
                <div class="main-content">

                    <section class="container_6 clearfix">
                        <!-- Tabs inside Portlet -->
                        <div class="grid_6 leading" style="min-height:250px !important;">

                            <section>
                                <div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
                                    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-top">
                                        <li class="ui-state-default ui-corner-top"><a href="#portlet-product_profile">混料简介 </a></li>
                                        <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a data-tab="#portlet-set-point" href="#portlet-set-point">设定值</a></li>
                                        <li class="ui-state-default ui-corner-top"><a data-tab="#portlet-source" href="#portlet-source">物料源</a></li>
                                        <li class="ui-state-default ui-corner-top"><a data-tab="#portlet-source-element" href="#portlet-source-element">物料源-元素</a></li>


                                    </ul>

                                    <div id="progress-bar" class="progress-bar" style="position:absolute;top:2px;right: 15px;width: 200px">

                                        <div data-show-value="true" data-value="10" class="progress ui-progressbar ui-widget ui-widget-content ui-corner-all" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="10">
                                            <div class="ui-progressbar-value ui-state-success ui-corner-left" style="width: 100%;"><b>100%</b></div>
                                        </div></div>
                                    <section id="portlet-product_profile" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">



                                        <section class="form">

                                            <form class="form has-validation" id="product-profile-form" action="/HELIOS1/helios1-dev/rawmix/settings/1" method="post" novalidate="novalidate">    <div class="grid_2">
                                                    <div class="clearfix">

                                                        <label class="form-label" for="form-name">产品简介  <em>*</em></label>

                                                        <div class="form-input">
                                                            <input size="60" maxlength="100" precision="2" class="" required="required" name="ProductProfile[product_name]" id="ProductProfile_product_name" type="text" value="RawMix#1">	    </div>

                                                    </div>

                                                </div>
                                                <div class="grid_2">


                                                    <div class="clearfix">

                                                        <label class="form-label" for="form-textarea"><small></small></label>

                                                        <div class="form-input">

                                                        </div>

                                                    </div>


                                                </div>

                                                <div class="clearfix"></div>

                                                <div class="full">

                                                    <input class="pull-right button ui-button ui-widget ui-state-default ui-corner-all" type="submit" name="yt0" value="Next" role="button" aria-disabled="false">       
                                                </div>
                                                <div class="clearfix"></div>

                                                <input name="ProductProfile[status]" id="ProductProfile_status" type="hidden" value="">    <input name="ProductProfile[product_id]" id="ProductProfile_product_id" type="hidden" value="1">
                                            </form>
                                        </section>			   


                                    </section>
                                    <section id="portlet-set-point" style="min-height:300px;" class="hide ui-tabs-panel ui-widget-content ui-corner-bottom">
                                        <a data-icon-primary="ui-icon-circle-plus" onclick="return addSetpoint()" href="javascript:void(0)" id="add-source" class="pull-right ui-button ui-widget ui-state-default ui-corner-all ui-state-focus ui-button-text-icon-primary" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text">

                                                增加其他设定值			    </span></a>





                                        <div class="clearfix">

                                        </div>
                                        <div id="set-point-list">


                                            <table class="full margin-top-20 list-table">

                                                <thead>
                                                    <tr>
                                                        <th class="ui-state-default ui-corner-top">
                                                            Set-Point Name
                                                        </th>
                                                        <th class="ui-state-default ui-corner-top">
                                                            Set-Point Active
                                                        </th>
                                                        <th class="ui-state-default ui-corner-top">
                                                            Set-Point Value
                                                        </th>
                                                        <th class="ui-state-default ui-corner-top">
                                                            Set-Point Tolerance (+-) 
                                                        </th>
                                                        <th class="ui-state-default ui-corner-top">
                                                            Priority
                                                        </th>
                                                        <th class="ui-state-default ui-corner-top">

                                                            <img width="30" src="/HELIOS1/helios1-dev/themes/tutorialzine1/images/navicons/20.png">

                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td>KH</td>
                                                        <td>1</td>
                                                        <td>0.96</td>
                                                        <td>0.02</td>
                                                        <td>1.00</td>
                                                        <th>
                                                            <a onclick="return updateSetPoint('1')" data-icon-only="true" data-icon-primary="ui-icon-pencil" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span><span class="ui-button-text">
                                                                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                                                                    <span class="ui-button-text">Edit Set Point&nbsp;</span></span></a>
                                                            <a onclick="return setpointDeleteConfirm('1', 'KH')" data-icon-only="true" data-icon-primary="ui-icon-trash" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete Set Point&nbsp;</span></span></a>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>IM</td>
                                                        <td>1</td>
                                                        <td>1.35</td>
                                                        <td>0.10</td>
                                                        <td>3.00</td>
                                                        <th>
                                                            <a onclick="return updateSetPoint('9')" data-icon-only="true" data-icon-primary="ui-icon-pencil" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span><span class="ui-button-text">
                                                                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                                                                    <span class="ui-button-text">Edit Set Point&nbsp;</span></span></a>
                                                            <a onclick="return setpointDeleteConfirm('9', 'IM')" data-icon-only="true" data-icon-primary="ui-icon-trash" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete Set Point&nbsp;</span></span></a>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>SM</td>
                                                        <td>1</td>
                                                        <td>2.50</td>
                                                        <td>0.10</td>
                                                        <td>2.00</td>
                                                        <th>
                                                            <a onclick="return updateSetPoint('14')" data-icon-only="true" data-icon-primary="ui-icon-pencil" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span><span class="ui-button-text">
                                                                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                                                                    <span class="ui-button-text">Edit Set Point&nbsp;</span></span></a>
                                                            <a onclick="return setpointDeleteConfirm('14', 'SM')" data-icon-only="true" data-icon-primary="ui-icon-trash" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete Set Point&nbsp;</span></span></a>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>Fe2O3</td>
                                                        <td>1</td>
                                                        <td>2.35</td>
                                                        <td>0.10</td>
                                                        <td>2.00</td>
                                                        <th>
                                                            <a onclick="return updateSetPoint('21')" data-icon-only="true" data-icon-primary="ui-icon-pencil" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span><span class="ui-button-text">
                                                                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                                                                    <span class="ui-button-text">Edit Set Point&nbsp;</span></span></a>
                                                            <a onclick="return setpointDeleteConfirm('21', 'Fe2O3')" data-icon-only="true" data-icon-primary="ui-icon-trash" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete Set Point&nbsp;</span></span></a>
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table> 
                                        </div>



                                        <div class="clearfix"><br></div>




                                        <div class="clearfix"></div>

                                        <div class="full">



                                            <a id="set-point-prev" class="pull-left button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button"><span class="ui-button-text">
                                                    前一项				       </span></a>

                                            <a id="set-point-next" class="pull-right ui-state-default ui-corner-top  ui-corner-all button ui-button ui-widget ui-button-text-only" role="button"><span class="ui-button-text">

                                                    后一项
                                                </span></a>

                                        </div>
                                        <div class="clearfix"></div>


                                    </section>
                                    <section id="portlet-source" style="position: relative;min-height: 300px;" class="hide ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">


                                        <a data-icon-primary="ui-icon-circle-plus" onclick="return addSource()" href="javascript:void(0)" id="add-source" class="pull-right ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-state-focus" role="button">
                                            <span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
                                            <span class="ui-button-text"> 添加物料源 </span>
                                        </a>

                                        <div class="clearfix">

                                        </div>

                                        <div id="source-list">

                                            <table class="full list-table margin-top-20">
                                                <thead>
                                                    <tr><th class="ui-state-default ui-corner-top">
                                                            Source Name
                                                        </th>
                                                        <th class="ui-state-default ui-corner-top">
                                                            Source Status
                                                        </th>
                                                        <th class="ui-state-default ui-corner-top">
                                                            Source Delay
                                                        </th>

                                                        <th class="ui-state-default ui-corner-top">
                                                            Source Min Feed Rate
                                                        </th>
                                                        <th class="ui-state-default ui-corner-top">
                                                            Source Max Feed Rate
                                                        </th>                
                                                        <th class="ui-state-default ui-corner-top">
                                                            <img width="30" src="/HELIOS1/helios1-dev/themes/tutorialzine1/images/navicons/20.png">
                                                        </th>
                                                    </tr></thead>
                                                <tbody>

                                                    <tr>
                                                        <td>LimeStone</td>
                                                        <td>1</td>
                                                        <td>0</td>
                                                        <td>74.00 %</td>
                                                        <td>82.00 %</td>
                                                        <th>
                                                            <a onclick="return updateSource('1')" data-icon-only="true" data-icon-primary="ui-icon-pencil" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span><span class="ui-button-text">
                                                                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                                                                    <span class="ui-button-text">Edit Source &nbsp;</span></span></a>
                                                            <a onclick="return deleteConfirm('source', '1', 'LimeStone')" data-icon-only="true" data-icon-primary="ui-icon-trash" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete Set Point&nbsp;</span></span></a>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>Iron</td>
                                                        <td>1</td>
                                                        <td>0</td>
                                                        <td>2.50 %</td>
                                                        <td>7.00 %</td>
                                                        <th>
                                                            <a onclick="return updateSource('2')" data-icon-only="true" data-icon-primary="ui-icon-pencil" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span><span class="ui-button-text">
                                                                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                                                                    <span class="ui-button-text">Edit Source &nbsp;</span></span></a>
                                                            <a onclick="return deleteConfirm('source', '2', 'Iron')" data-icon-only="true" data-icon-primary="ui-icon-trash" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete Set Point&nbsp;</span></span></a>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>Shale</td>
                                                        <td>1</td>
                                                        <td>0</td>
                                                        <td>6.00 %</td>
                                                        <td>14.00 %</td>
                                                        <th>
                                                            <a onclick="return updateSource('3')" data-icon-only="true" data-icon-primary="ui-icon-pencil" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span><span class="ui-button-text">
                                                                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                                                                    <span class="ui-button-text">Edit Source &nbsp;</span></span></a>
                                                            <a onclick="return deleteConfirm('source', '3', 'Shale')" data-icon-only="true" data-icon-primary="ui-icon-trash" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete Set Point&nbsp;</span></span></a>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td>SandStone</td>
                                                        <td>1</td>
                                                        <td>0</td>
                                                        <td>6.00 %</td>
                                                        <td>12.50 %</td>
                                                        <th>
                                                            <a onclick="return updateSource('4')" data-icon-only="true" data-icon-primary="ui-icon-pencil" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span><span class="ui-button-text">
                                                                    <span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span>
                                                                    <span class="ui-button-text">Edit Source &nbsp;</span></span></a>
                                                            <a onclick="return deleteConfirm('source', '4', 'SandStone')" data-icon-only="true" data-icon-primary="ui-icon-trash" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-button-icon-only" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span><span class="ui-button-text">Delete Set Point&nbsp;</span></span></a>
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>




                                        </div>

                                        <div class="clearfix"></div>

                                        <div class="full" style="margin-top: 20px;">



                                            <a id="source-prev" class="pull-left button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button"><span class="ui-button-text"> Previous</span></a>

                                            <a id="source-next" class="pull-right button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button"><span class="ui-button-text"> Next</span></a>

                                        </div>
                                        <div class="clearfix"></div>
                                    </section>
                                    <section id="portlet-source-element" class="hide ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">

                                        <a data-icon-primary="ui-icon-circle-plus" onclick="return addElementComposition()" href="javascript:void(0)" id="add-source" class="pull-right ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary ui-state-focus" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text"> Add element </span></a>



                                        <div class="pull-left" id="source-select">
                                            <span class="title-bold">物料源  </span>
                                            <div class="selector" id="uniform-selected-source-id"><span>Select sector</span><select class="pull" name="selected-source-id" id="selected-source-id" style="opacity: 0;">
                                                    <option value="">Select sector</option>
                                                    <option value="1">LimeStone</option>
                                                    <option value="2">Iron</option>
                                                    <option value="3">Shale</option>
                                                    <option value="4">SandStone</option>
                                                </select></div>				 </div>
                                        <div class="clearfix"></div>
                                        <div id="element-composition-list">


                                        </div>
                                        <div class="full margin-top-20">



                                            <a id="elelemt-prev" class="pull-left button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button"><span class="ui-button-text"> Previous</span></a>


                                        </div>
                                        <div class="clearfix"></div>

                                    </section>
                                </div>
                            </section>

                        </div>
                        <!-- End Tabs inside Portlet -->
                    </section>

                </div>
            </section>

        
        </div><!-- content -->
    </div>

    <footer>
        <div id="footer-inner" class="container_8 clearfix">
            <div class="grid_8">
                <span class="fr">© 2018 Sabia Inc. All rights reserved.</span>
            </div>
        </div>


</section>