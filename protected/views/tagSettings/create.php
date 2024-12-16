

<section class="main-section grid_8">
    <nav>

        <?php
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);
        ?>
    </nav>
    <div class="main-content">


       <section class="container_6 clearfix">

            <!-- Tabs inside Portlet -->
            <div class="grid_6 leading">
                <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                    <h2> <?php echo Yii::t('app','Create Tag');?></h2>

                </header>
                <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:600px;">
                    <div class="grid_5 " style="width: 700px">
                        <?php
                        $this->renderPartial('_form', array('model' => $model));
                        ?>
</div>
                    </section>

                </div>
            </section>

        </div>

</section>
