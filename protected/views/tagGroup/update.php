





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

            <section>
                <div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
                    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-top">
                        <li class="ui-state-default ui-corner-top"><a href="#create">
<?php echo Yii::t('app','Update TagGroup')?> :: <?php echo $model->tagGroupName; ?></a></li>


                    </ul>
                    <section id="create" class="ui-tabs-panel ui-widget-content ui-corner-bottom">


                        <?php
                        $this->renderPartial('_form', array('model' => $model));
                        ?>

                    </section>

                </div>
            </section>

        </div>

        <div class="clear"><br/></div>



    </div>
</section>
