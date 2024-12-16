

<section class="main-section grid_8">
    <nav class="">
        <!-- Abhinandan. Invoke the script responsible for rendering the Left Side Bar Menu upon page load.. -->

        <?php
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);
        ?>

    </nav>
    <div class="main-content">

        <div class="clear"><br/></div>

        <section id="portlet-set-point" style="min-height:300px;" class="hide ui-tabs-panel ui-widget-content ui-corner-bottom">
            <a data-icon-primary="ui-icon-circle-plus" onclick="return addSetpoint()" href="javascript:void(0)" id="add-source" class="pull-right ui-button ui-widget ui-state-default ui-corner-all ui-state-focus ui-button-text-icon-primary" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text">

                    增加其他设定值			    </span></a>



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
         
        </section>

    </div>
</section>
