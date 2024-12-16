        <section class="main-section grid_8">
            <nav class="">
				<?php include_once(LogicalHelper::osWrapper( dirname(__FILE__) . "\..\pageDefaults\authLeftMenu.php")); ?>
            </nav>
            <div class="main-content">
                <header>
                    <ul class="action-buttons clearfix">
                        <li><a href="#" class="button" data-icon-primary="ui-icon-flag" >5M</a></li>
                        <li><a href="#" class="button" rel="#overlay" data-icon-primary="ui-icon-flag" >30M</a></li>
                        <li><a href="#" class="button" rel="#overlay" data-icon-primary="ui-icon-flag" >60M</a></li>
                    </ul>
                    <h2>
                        <?php echo Yii::app()->name; ?>
                    </h2>
                </header>
                <section class="container_6 clearfix">
                
                    <div class="grid_6">
                        <div class="ui-widget message info closeable">
                            <div class="ui-state-highlight ui-corner-all"> 
                                <p>
                                    <span class="ui-icon ui-icon-info"></span>
                                    <strong>Status Alerts</strong>The blocks you see below can be customized using Settings.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="grid_1 leading">
                        <fieldset class="fieldset-buttons ui-corner-all">
	                        <header>
                                 <span class="button" id="alertVal">2</span>
                            </header>
                            - 10
                            <div class="vertical-sliders clearfix">
                            	<input id="vSlider" type="range" value="2" min="1" max="10" data-range="max" data-orientation="vertical" />
                            </div>
                            - 1
                        </fieldset>
                    </div>
                        	                    
                    <div class="grid_5 leading">
                        <fieldset class="fieldset-buttons ui-corner-all">
                            <legend class="buttonset-legend">
                                <span id="dashboardview-filter" class="buttonset">
                                    <input type="radio" name="dashboardview" id="dashboardview-orders" value=".dash-order" checked /><label for="dashboardview-orders">Critical</label>
                                    <input type="radio" name="dashboardview" id="dashboardview-statistics" value=".dash-stat" /><label for="dashboardview-statistics">Real-Time</label>
                                </span>
                            </legend>
                            <ul class="isotope-widgets isotope-container">
                                <li class="dash-order">
                                    <a class="button-gray ui-corner-all" href="#">
                                        <strong>1.3</strong>
                                        <span>Aliminium Percentage</span>
                                    </a>
                                </li>
                                <li class="dash-order">
                                    <a class="button-orange ui-corner-all" href="#">
                                        <strong>0.8</strong>
                                        <span>BTU Percentage</span>
                                    </a>
                                </li>
                                <li class="dash-order">
                                    <a class="button-blue ui-corner-all" href="#">
                                        <strong>1000</strong>
                                        <span>TPH Average</span>
                                    </a>
                                </li>                                
                                <li class="dash-order">
                                    <a class="button-red ui-corner-all" href="#">
                                        <strong>5.3</strong>
                                        <span>Moisture Content</span>
                                    </a>
                                </li>
                            <!--</ul>
                            <ul class="display-inline dash-stats">-->
                                <li class="dash-stat">
                                    <a class="button-green ui-corner-all" href="#">
                                        <strong>5</strong>
                                        <span>Active Alerts</span>
                                    </a>
                                </li>
                                <li class="dash-stat">
                                    <a class="button-red ui-corner-all" href="#">
                                        <strong>3</strong>
                                        <span>Notifications</span>
                                    </a>
                                </li>
                                <li class="dash-stat">
                                    <a class="button-orange ui-corner-all" href="#">
                                        <strong>0</strong>
                                        <span>Pending Tags</span>
                                    </a>
                                </li>
                                <li class="dash-stat">
                                    <a class="button-blue ui-corner-all" href="#">
                                        <strong>0</strong>
                                        <span>Tags in Queue</span>
                                    </a>
                                </li>
                                <li class="dash-stat">
                                    <a class="button-green ui-corner-all" href="#">
                                        <strong>0</strong>
                                        <span>Completed Tags</span>
                                    </a>
                                </li>
                            </ul>
                        </fieldset>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="grid_6 leading">
                        <div class="ui-widget message info closeable">
                            <div class="ui-state-highlight ui-corner-all"> 
                                <p>
                                    <span class="ui-icon ui-icon-info"></span>
                                    <strong>Dynamic Charts</strong> Data Ranges can be selected based on the top left Markers.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="grid_6 leading">
                        <div class="ui-widget portlet-content">
			        		<?php include_once( LogicalHelper::osWrapper(dirname(__FILE__) . "\..\helperStubs\highChartsDyWidget.php")); ?>
                        </div>
                    </div>
                    
                    <div class="clear"></div>                    
                    
                    <div class="sortable leading clearfix">
                        <div class="grid_2 portlet collapsible" id="widget-orders" draggable="true">
                            <header>
                                <h2>Percentages</h2>
                            </header>
                            <section class="no-padding">
                                <table class="full">
                                    <tbody>
                                        <tr>
                                            <td>Active Tags</td>
                                            <td class="ar">5</td>
                                        </tr>
                                        <tr>
                                            <td>Deleted Tags</td>
                                            <td class="ar">3</td>
                                        </tr>
                                        <tr>
                                            <td>Overdue Tags</td>
                                            <td class="ar">3</td>
                                        </tr>
                                        <tr>
                                            <td>Pending Tags</td>
                                            <td class="ar">0</td>
                                        </tr>
                                        <tr>
                                            <td>Suspended Tags</td>
                                            <td class="ar">1</td>
                                        </tr>
                                        <tr>
                                            <td>Flagged Tags</td>
                                            <td class="ar">0</td>
                                        </tr>
                                        <tr>
                                            <td>Notified Tags</td>
                                            <td class="ar">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </div>
                        <div class="grid_2 portlet collapsible" id="widget-statistics" draggable="true">
                            <header>
                                <h2>Statistics</h2>
                            </header>
                            <section class="no-padding">
                                <table class="full">
                                    <tbody>
                                        <tr>
                                            <td>Active Tags</td>
                                            <td class="ar">5</td>
                                        </tr>
                                        <tr>
                                            <td>Deleted Tags</td>
                                            <td class="ar">3</td>
                                        </tr>
                                        <tr>
                                            <td>Overdue Tags</td>
                                            <td class="ar">3</td>
                                        </tr>
                                        <tr>
                                            <td>Pending Tags</td>
                                            <td class="ar">0</td>
                                        </tr>
                                        <tr>
                                            <td>Suspended Tags</td>
                                            <td class="ar">1</td>
                                        </tr>
                                        <tr>
                                            <td>Flagged Tags</td>
                                            <td class="ar">0</td>
                                        </tr>
                                        <tr>
                                            <td>Notified Tags</td>
                                            <td class="ar">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </div>
                        <div class="grid_2 portlet collapsible" id="widget-tickets" draggable="true">
                            <header>
                                <h2>Flags</h2>
                            </header>
                            <section class="no-padding">
                                <table class="full">
                                    <tbody>
                                        <tr>
                                            <td>Active Tags</td>
                                            <td class="ar">5</td>
                                        </tr>
                                        <tr>
                                            <td>Deleted Tags</td>
                                            <td class="ar">3</td>
                                        </tr>
                                        <tr>
                                            <td>Overdue Tags</td>
                                            <td class="ar">3</td>
                                        </tr>
                                        <tr>
                                            <td>Pending Tags</td>
                                            <td class="ar">0</td>
                                        </tr>
                                        <tr>
                                            <td>Suspended Tags</td>
                                            <td class="ar">1</td>
                                        </tr>
                                        <tr>
                                            <td>Flagged Tags</td>
                                            <td class="ar">0</td>
                                        </tr>
                                        <tr>
                                            <td>Notified Tags</td>
                                            <td class="ar">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </div>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="grid_6 leading">
                        <div class="ui-widget message info closeable">
                            <div class="ui-state-highlight ui-corner-all"> 
                                <p>
                                    <span class="ui-icon ui-icon-info"></span>
                                    <strong>Static Widgets</strong> This is a static widgets dashboard sample. They can also be collapsed/expanded and they can remember their state.
                                </p>
                            </div>
                        </div>
                    </div>                    
                    
                    <div class="clear"></div>
                    
                    <div class="grid_6 leading">
                        <div class="portlet collapsible" id="widget-todolist">
                            <header>
                                <h2>System Messages</h2>
                            </header>
                            <section class="no-padding clearfix">
								<table class="display" id="example"> 
                                    <thead> 
                                        <tr> 
                                            <th>Rendering engine</th> 
                                            <th>Browser</th> 
                                            <th>Platform(s)</th> 
                                            <th>Engine version</th> 
                                            <th>CSS grade</th> 
                                        </tr> 
                                    </thead> 
                                    <tbody> 
                                        <tr class="gradeX"> 
                                            <td>Al2O3</td> 
                                            <td>Internet
                                                 Explorer 4.0</td> 
                                            <td>San Diego</td> 
                                            <td class="center">4</td> 
                                            <td class="center">X</td> 
                                        </tr> 
                                        <tr class="gradeC"> 
                                            <td>Al2O3</td> 
                                            <td>Internet
                                                 Explorer 5.0</td> 
                                            <td>San Diego</td> 
                                            <td class="center">5</td> 
                                            <td class="center">C</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Al2O3</td> 
                                            <td>Internet
                                                 Explorer 5.5</td> 
                                            <td>San Diego</td> 
                                            <td class="center">5.5</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Al2O3</td> 
                                            <td>Internet
                                                 Explorer 6</td> 
                                            <td>Win 98+</td> 
                                            <td class="center">6</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Al2O3</td> 
                                            <td>Analyzer 7</td> 
                                            <td>San Diego SP2+</td> 
                                            <td class="center">7</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Al2O3</td> 
                                            <td>Analyzer-1</td> 
                                            <td>San Diego</td> 
                                            <td class="center">6</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Analyzer-3</td> 
                                            <td>India</td> 
                                            <td class="center">1.7</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Firefox 1.5</td> 
                                            <td>India</td> 
                                            <td class="center">1.8</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Firefox 2.0</td> 
                                            <td>India</td> 
                                            <td class="center">1.8</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Firefox 3.0</td> 
                                            <td>Win 2k+ / Arizona+</td> 
                                            <td class="center">1.9</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Camino 1.0</td> 
                                            <td>OSX.2+</td> 
                                            <td class="center">1.8</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Camino 1.5</td> 
                                            <td>Arizona+</td> 
                                            <td class="center">1.8</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Netscape 7.2</td> 
                                            <td>San Diego / Mac OS 8.6-9.2</td> 
                                            <td class="center">1.7</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Netscape Browser 8</td> 
                                            <td>Win 98SE+</td> 
                                            <td class="center">1.7</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Netscape Navigator 9</td> 
                                            <td>India</td> 
                                            <td class="center">1.8</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Mozilla 1.0</td> 
                                            <td>San Diego / OSX.1+</td> 
                                            <td class="center">1</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Mozilla 1.1</td> 
                                            <td>San Diego / OSX.1+</td> 
                                            <td class="center">1.1</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Mozilla 1.2</td> 
                                            <td>San Diego / OSX.1+</td> 
                                            <td class="center">1.2</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Mozilla 1.3</td> 
                                            <td>San Diego / OSX.1+</td> 
                                            <td class="center">1.3</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Mozilla 1.4</td> 
                                            <td>San Diego / OSX.1+</td> 
                                            <td class="center">1.4</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Mozilla 1.5</td> 
                                            <td>San Diego / OSX.1+</td> 
                                            <td class="center">1.5</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Mozilla 1.6</td> 
                                            <td>San Diego / OSX.1+</td> 
                                            <td class="center">1.6</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Mozilla 1.7</td> 
                                            <td>Win 98+ / OSX.1+</td> 
                                            <td class="center">1.7</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Mozilla 1.8</td> 
                                            <td>Win 98+ / OSX.1+</td> 
                                            <td class="center">1.8</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Seamonkey 1.1</td> 
                                            <td>India</td> 
                                            <td class="center">1.8</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>Fe2O3</td> 
                                            <td>Analyzer-2</td> 
                                            <td>Missippi</td> 
                                            <td class="center">1.8</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>CaO</td> 
                                            <td>Analyzer-2</td> 
                                            <td></td> 
                                            <td class="center">125.5</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>CaO</td> 
                                            <td>Safari 1.3</td> 
                                            <td>Arizona</td> 
                                            <td class="center">312.8</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>CaO</td> 
                                            <td>Safari 2.0</td> 
                                            <td>OSX.4+</td> 
                                            <td class="center">419.3</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>CaO</td> 
                                            <td>Safari 3.0</td> 
                                            <td>OSX.4+</td> 
                                            <td class="center">522.1</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>CaO</td> 
                                            <td>OmniWeb 5.5</td> 
                                            <td>OSX.4+</td> 
                                            <td class="center">420</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>CaO</td> 
                                            <td>iPod Touch / iPhone</td> 
                                            <td>iPod</td> 
                                            <td class="center">420.1</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>CaO</td> 
                                            <td>S60</td> 
                                            <td>S60</td> 
                                            <td class="center">413</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Opera 7.0</td> 
                                            <td>San Diego / OSX.1+</td> 
                                            <td class="center">-</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Opera 7.5</td> 
                                            <td>San Diego / OSX.2+</td> 
                                            <td class="center">-</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Opera 8.0</td> 
                                            <td>San Diego / OSX.2+</td> 
                                            <td class="center">-</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Opera 8.5</td> 
                                            <td>San Diego / OSX.2+</td> 
                                            <td class="center">-</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Opera 9.0</td> 
                                            <td>Arizona</td> 
                                            <td class="center">-</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Opera 9.2</td> 
                                            <td>Arizona</td> 
                                            <td class="center">-</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Opera 9.5</td> 
                                            <td>Arizona</td> 
                                            <td class="center">-</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Analyzer-1</td> 
                                            <td>Seattle</td> 
                                            <td class="center">-</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Analyzer-2</td> 
                                            <td>Nevada</td> 
                                            <td class="center">-</td> 
                                            <td class="center">A</td> 
                                        </tr> 
                                        <tr class="gradeA"> 
                                            <td>SiO2</td> 
                                            <td>Analyzer-1</td> 
                                            <td>San Diego</td> 
                                            <td class="center">8.5</td> 
                                            <td class="center">C/A<sup>1</sup></td> 
                                        </tr> 
                                    </tbody> 
                                </table>

                            </section>
                        </div>
                    </div>
                                        
                </section>
            </div>
        </section>
        <!-- Main Section End -->
    </div>
</section>