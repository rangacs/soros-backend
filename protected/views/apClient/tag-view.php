
<section class="main-section grid_8">
    <nav class="">

        <?php
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);
		$tripInfo = TagQueuedHelper::getTripInfo($tagObject->tagID);  

        $activeCalibrationFile = WclRfidCalMap::findActiveCalibFile($tripInfo["w_matCode"]) ;
        ?>
    </nav>
    <div class="main-content">
        <section class="container_6 clearfix">
			    <div class="grid_6 leading" style="min-height:250px !important;">
		
		                <header class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-widget-header-blue ui-corner-top">
                                    <h2 ><?php echo $tagObject->tagName?>        <a href="#create" class="font-red underline">[ <?php echo Yii::t('app',"Calibration : ").$activeCalibrationFile?> ]</a></h2>
		                </header>
		                <section id="create" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
		
		                    <?php
					
                                        $elements =  HeliosUtility::getDisplayElements();; 

								
		                        //$elements = array('LocalendTime','SiO2','Al2O3', 'Fe2O3','CaO', 'TPH');
		                        TagHelper::renderTagtable($tagObject, $elements);
		                    ?>
		                </section>
		        </div>
		        <div class="clear"><br/></div>
		</section>
    </div>
</section>


<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl . '/js/jquery.min_new.js' ?>" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl . '/js/jquery.dataTables.min.js' ?>" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl . '/js/dataTables.tableTools.min.js' ?>" ></script>

<script type="text/javascript">
    
    
     $("#tagview").dataTable({
        "sPaginationType": "full_numbers",
        "bLengthChange": true,
        "bFilter": false,
        "bSort": false,
        "bInfo": true,
        "bAutoWidth": true,
        "bDestroy": true,
        "iDisplayLength": 25,
        dom: "T<'clear'>lfrtip", "tableTools": {
            "sSwfPath": "<?php echo Yii::app()->theme->baseUrl; ?>/js/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                "copy",
                "xls",
                "print",
                {
                    "sExtends": "collection",
                    "sButtonText": "Save",
                    "aButtons": ["csv", "xls"]
                }
            ]
        }
    });
window.onload = function(){
    
    
}

</script>