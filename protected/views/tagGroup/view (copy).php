
<section class="main-section grid_8">
    <nav class="">

        <?php
     
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);
        ?>
    </nav>
    <div class="main-content">


        <div class="grid_6 leading" style="min-height:250px !important;">

                    <section class="container_6 clearfix">

            <!-- Tabs inside Portlet -->
            <div class="grid_6 leading">
                <header class="ui-widget-header ui-corner-top">
                    <h2><?php echo $tagGroupObject->tagGroupName?></h2>

                </header>
                
                <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:600px;">

                    <section id="portlet-set-point"  class=" ui-tabs-panel ui-widget-content ui-corner-bottom">


            <?php
//$elements = array('Al2O3', 'Fe2O3', 'TPH');
//TagHelper::renderTagtable($tagObject, $elements);
            $tags = $tagGroupObject->getAllTags();

            $elements =LabUtility::spElements();

            TagGroupHelper::renderTagtable($tagGroupObject, $elements);
            ?>

                    </section>

                </div>
            </section>

        </div>

        <div class="clear"><br/></div>



    </div>
</section>

