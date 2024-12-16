<?php
/**
 * LogicalHelper is a logical helper class.
 * It helps deciding View based logical handling of data/screen.
 */

 /*
 *  Error Notes: gridIds undefinex index : You need to reload the 'gadlay_layouts.gadPlacement'
 *   To do this: look inside MYCODE/parse_serialized_array_March1st.php, and echo out the serialized array,
 *                then copy & paste..
 *
 */
 class DashHelper extends StraightShotHelper
 {

	/*
		Static function to determine the default page layout.
	 *  1) Login Page is userScreen
	 *  2) Admin/Management Screen are authScreen
	 *  3) All static pages are otherwise
	 *
	 *  Abhinandan: 12/27:
	 *   @param  columns   int    The number of columns.. ie 2
	 *   @param  portlets  array  Represents an array returned by $this->applyUserPref()
	 *                            $portlets was formed using arrays $pref AND $this->_portlets..
	 *
	 *   REF_A: See bottom of file for sample output of $portlets..
	 *
	 *   @param  widString string  Is used in order to create $gridIds array..
	 *   REF_B (here): ' button-toggle:1; Live_Status:6; Charts:6; Alerts:2; Meter_Gauge:2; System_Messages:4; AlarmBar:6; IdiotLights:6; '
	 *
	 *  @created $gridIds  array  Represents the passed back $arr array from 'array_push_associative()'
	 *                            This array is aggregated. Sample structure BEFORE ' $gridIds["Blank1"] = 0; '
	 *
	 *         REF_C: $gridIds.. $gridIds becomes the finalized yet aggregated array  .... $gridIds aka &$arr from the 'array_push_associative(&$arr)' method
	 *
	 *           $gridIds = array(
     *                        'button-toggle' => '1'
     *                        'Live_Status' => '6'
     *                        'Charts' => '6'
     *                        'Alerts' => '2'
     *                        'Meter_Gauge' => '2'
     *                        'System_Messages' => '4'
     *                        'AlarmBar' => '6'
     *                        'IdiotLights' => '6'
     *           );
	 *
	*/
	public static function createDisplay($columns,$portlets,$widString)
	{
       //Controller::fb('inside createDisplay');
       //Controller::fb($portlets);                    //May28th bug: portlets is automatically being filled with Live_Status, even tho it was not previously set..
    //Feb29th.. Abhinandan new constuction..  Part 1/2..
   $temp_arr            = array();        //Start here..
   $widString_arr       = array();
   $gridIds = array();

   $temp_arr      = explode( ';', $widString );
    //Controller::fb($temp_arr);

   foreach($temp_arr as $k1 => $v1){
    $no_space_v1           = preg_replace('/\s+/', '', $v1);          //Abhinandan. Reference json_decode_test_A.php
    $widString_val         = substr( $no_space_v1, strpos($no_space_v1, ':') + 1 );    // 6
    $wSv_offset            = strlen($widString_val) + 1;
    $gadgetType            = substr($no_space_v1, 0, -($wSv_offset) );                // Charts

            $widString_arr[$gadgetType][$k1] = $widString_val;                         //Both 'widgetsPos' and 'widString' MUST be in identical order, so this trick can work..
        }
        //Controller::fb($widString_arr);
        //Last step eliminates numeric indexes from being appended..
        foreach ($widString_arr as $key1 => $val1) {
            if (($n = is_int($key1)) === FALSE) {
                $gridIds[$key1] = $val1;
            }
        }
        //TODO get back here Abhi
        //echo "$portlets, $gridIds, $columns";
        DashHelper::createDisplay_renderLogic($portlets, $gridIds, $columns);
    }

  }//end createDisplay()..


	 /*
		Display Rendor Logic
	 */
  public function createDisplay_renderLogic($portlets, $gridIds, $columns){
     $jsIncluded = 0;
     $jsTIncluded = 0;

   for($i=0; $i < $columns; $i++){
	  if( !empty ($portlets[$i]) )   //Abhinandan. If our container is not empty..
	  {

	    //Feb29th: Abhinandan. Begin construction here (part 2/2):
      for($j = 0; $j< count($portlets[$i]); ++$j){


       //$this->test_A = $j;               // @ DashController.php ////////////
       //Abhinandan...(NEW)
       $row       = $portlets[$i][$j];    //Subarray

       $divIdVal1 = $row['id'];           // 'Live_Status'

       foreach($gridIds as $k1 => $v1){
	if($k1 == $divIdVal1)
	{
	 if( isset($v1[$j]) )
	 {
	  $gridColVal1 = $v1[$j];           // ie 6..
	  $StraightShotHelper_A = new StraightShotHelper($j);
	  $bullseye = $StraightShotHelper_A->grabBullseye();
	    Yii::app()->params['bullseye'] = $bullseye;
	    //Controller::fb( Yii::app()->params['bullseye'] );
	 }
	}
       }
       $gridColVal2  = 0;


       //  Mar11th: Live_Status. works!. jdr. Commenting out for work on other gadgets now..
       if( ($divIdVal1 == "Live_Status") )
       {

	      echo
		   // '<div class="grid_'. $gridColVal1.' portlet ui-sortable clearfix padMargin collapsible" draggable="true" id="'.$row["id"].'">
		   '<div class="grid_'. $gridColVal1.' portlet ui-sortable clearfix padMargin collapsible" draggable="true" id="'.$row["id"].'_'.$j.'">
			<header>
				<h2>
							'.Yii::t('dash',$row["title"]).'
				</h2>
			</header>
			<section id = "section_'.$j.'"> ';


	echo '<fieldset id="fieldset_main_'.$j.'" class="fieldset-buttons ui-corner-all" style="height: 168px;">';        //Begin live_status fieldset..
				 //echo $row['content'];       //Abhinandan. March11th: Was originally invoking the '_d_liveStats.php' ...

			  //echo 'j is ' . $j;


	echo '<ul id="top_ul_' . $j . '" class="isotope-widgets isotope-container iStatus">';
			  echo '</ul>';


			   echo '<ul id="top_ul" class="isotope-widgets isotope-container iStatus">';
	 echo '</ul>';


	echo '</fieldset>';




	      echo '</section></div>';    //End live_status fieldset..

	      //echo '<script type="text/javascript" src="'. Yii::app()->theme->baseUrl.'/js/json2.js" ></script>';
		  //echo '<script type="text/javascript" src="'. Yii::app()->theme->baseUrl . '/js/jquery.jplayer/jquery.jplayer.js" ></script>';

	      echo ' <script type="text/javascript">

		       $("header").addClass( function(index, currentClass){
			  var addedClass;
			  if( currentClass === "" )
			  {
			   addedClass = "ui-widget-header ui-corner-top";
			  }
			  return addedClass;
			 });

		 var fetchDashboardElements = "fetchDashboardElements";
		 var gadgetType             = "Live_Status";

	     ' ;

	       //Ajax goes here..
	  echo '
	       var LS_callback = {
				       LS_execute : function(){
							 var fetchDashboardElements = "fetchDashboardElements";
							 var gadgetType             = "Live_Status";
							 var widgetsPos             = '.$j.';


						       // var a = setInterval(function(){  //Feb14th.. Placed outside of object.

							$.ajax({
								type: "POST",
								url: "'. Yii::app()->baseUrl .'/dash/Dash",
								data: {"ajaxForwardRequest" : fetchDashboardElements, "gadgetType" : gadgetType, "widgetsPos" : widgetsPos},
								success: function(stringified)
								{
								 //alert(stringified);
								 var data_obj     = $.parseJSON(stringified);

								 console.log(data_obj);

								 var legacy_record_count = 0;
								 var b =0;
								 //var b = setInterval(function()
								 {

								  if(legacy_record_count == data_obj[0].data_value.length) //Abhinandan. Asserting that ALL the elements contain the same exact number within its own legacy records array..
								  {
								   clearInterval(b);
								  }
								  else
								  {
								   //Abhinandan.  Refresh timestamps in db, if not appearing..

								   //data_count = number of elements present in the gadget
								   var data_count   = data_obj.length;   //Abhinandan. Must clear 1x outside for loop..

								   //var ul = document.getElementById("top_ul");
								   var ul = document.getElementById("top_ul_'.$j.'");
								       ul.innerHTML = "";


								   for(var hb_i=0; hb_i < data_count; hb_i++)
								   {
								    var element_type   = data_obj[hb_i].element_type;
								    var currentColor   = data_obj[hb_i].data_value[legacy_record_count][element_type][1];
								    var data_value     = data_obj[hb_i].data_value[legacy_record_count][element_type][0];

																		if(data_value == null)
																		{
																	 data_value = "Absent";
																	}

								    var a_id           = data_obj[hb_i].dom_unique_attribute;

								    //Dynamically create @ .js
								    currentElement = document.createElement("li");
								    currentElement.className = "dash-order";

								    currentElement_a = document.createElement("a");
								    currentElement_a.id = a_id;
								    currentElement_a.className = "button-" + currentColor + " ui-corner-all";
								    currentElement_a.href = "#";

								     currentElement_strong = document.createElement("strong");
								     currentElement_strong.id = "a_strong_" + a_id;
								     currentElement_strong.className = "animJq";
								     currentElement_strong.innerHTML = data_value;

								     currentElement_span = document.createElement("span");
								     currentElement_span.innerHTML = element_type;

								     currentElement_a.appendChild(currentElement_strong);
								     currentElement_a.appendChild(currentElement_span);

								     currentElement.appendChild(currentElement_a);


								    ul.appendChild(currentElement);
								   }//end for loop..



								    //Attach animate events to colors:
								    //  Find a tags and attach events if color is of our interest..


								   var ul = document.getElementById("top_ul_'.$j.'");


								   var a_items = ul.getElementsByTagName("a");
								   for(var k = 0; k < a_items.length; ++k){
								    if(a_items[k].className == "button-red ui-corner-all")
								    {

								     //niftyplayer("niftyPlayer1").playToggle();  //Abhinandan. Feb20th..
										//var greete = function(){
									$("#jquery_jplayer_1").jPlayer("play", 0);
								     //};

								     var shake_id = a_items[k].id;

								     //alert(shake_id);

								     $(".animJq").animate({opacity:1},2000,"linear",function(){       //March10th.. Only shaking the first gadget @ red, since both gadgets are sharing the SAME <a id="..." > ..
								      $("#" + shake_id).effect("shake", { times:1 }, 100);    //Abhinandan. Better appearance is when used with the <a> element..
								     });
									    }//if button red
									   }// for loop

								   // ++legacy_record_count;
								  }//else Legacy-count
								  //},3000); // @b interval
								  } //@nointerval

								}//success for ajax
							  }); //end ajax FIRST RECORD..

							  /*
							  //BEGIN 2nd record and beyond..
							  $.ajax({
								type: "POST",
								url: "'. Yii::app()->baseUrl .'/dash/Dash",
								data: {"ajaxForwardRequest" : fetchDashboardElements, "gadgetType" : gadgetType, "widgetsPos" : widgetsPos},
								success: function(stringified){

								//alert(stringified);

								var data_obj     = $.parseJSON(stringified);

								console.log(data_obj);

								//alert(data_obj[0].data_value.length);


								var legacy_record_count = 1;
								 var b =0;
								 //var b = setInterval(function()
								 {
								  if(legacy_record_count == data_obj[0].data_value.length) //Abhinandan. Asserting that ALL the elements contain the same exact number within its own legacy records array..
								  {
								   clearInterval(b);
								  }
								  else{
								   //Abhinandan.  Refresh timestamps in db, if not appearing..

								   var data_count   = data_obj.length;   //Abhinandan. Must clear 1x outside for loop..


								   //var ul = document.getElementById("top_ul");
								   var ul = document.getElementById("top_ul_'.$j.'");
								       ul.innerHTML = "";


								   for(var hb_i=0; hb_i < data_count; ++hb_i){
								    var element_type   = data_obj[hb_i].element_type;
								    var currentColor   = data_obj[hb_i].data_value[legacy_record_count][element_type][1];
								    var data_value     = data_obj[hb_i].data_value[legacy_record_count][element_type][0];

																	if(data_value == null){
																	 data_value = "Absent";
																	}

								    var a_id           = data_obj[hb_i].dom_unique_attribute;

								    //Dynamically create @ .js
								    currentElement = document.createElement("li");
								    currentElement.className = "dash-order";

								    currentElement_a = document.createElement("a");
								    currentElement_a.id = a_id;
								    currentElement_a.className = "button-" + currentColor + " ui-corner-all";
								    currentElement_a.href = "#";

								     currentElement_strong = document.createElement("strong");
								     currentElement_strong.id = "a_strong_" + a_id;
								     currentElement_strong.className = "animJq";
								     currentElement_strong.innerHTML = data_value;

								     currentElement_span = document.createElement("span");
								     currentElement_span.innerHTML = element_type;

								     currentElement_a.appendChild(currentElement_strong);
								     currentElement_a.appendChild(currentElement_span);

								     currentElement.appendChild(currentElement_a);


								    ul.appendChild(currentElement);
								   }//end for loop..



								    //Attach animate events to colors:
								    //  Find a tags and attach events if color is of our interest..


								   //var ul = document.getElementById("top_ul");
								   var ul = document.getElementById("top_ul_'.$j.'");


								   var a_items = ul.getElementsByTagName("a");
								   for(var k = 0; k < a_items.length; ++k){
								    if(a_items[k].className == "button-red ui-corner-all")
								    {

								     //niftyplayer("niftyPlayer1").playToggle();  //Abhinandan. Feb20th..
										 //var greetre = function(){
									    $("#jquery_jplayer_1").jPlayer("play", 0);
								     //};
								     var shake_id = a_items[k].id;

								     //alert(shake_id);

								     $(".animJq").animate({opacity:1},2000,"linear",function(){       //March10th.. Only shaking the first gadget @ red, since both gadgets are sharing the SAME <a id="..." > ..
								      $("#" + shake_id).effect("shake", { times:1 }, 100);    //Abhinandan. Better appearance is when used with the <a> element..
								     });
								    }//if
								   } //for
								   ++legacy_record_count;
								  }//else

								 //}, 3000);  //end setInterval @b..
								 } //end setinterval
								} //end success..
							      }); //end ajax..
							      */
							       //March8th commenting out for debugging..

							   //  }, 20000);  //end setInterval @a ..   //Feb14th..
				       }  //LS_execute
			};  //LS_callback




			LS_callback.LS_execute();

		   ' ;


	    echo ' </script> ' ;


	     }

       //end Live_Status..




       //March11th: IdiotLights works!. jdr.. Commenting out now to work on 'Alerts' gadget..
       if( ($divIdVal1 == "IdiotLights") )
       {
	//Controller::fb('IdiotLights');
	//Controller::fb($j);

			  echo
		   // '<div class="grid_'. $gridColVal1.' portlet ui-sortable clearfix padMargin collapsible" draggable="true" id="'.$row["id"].'">
		   '<div class="grid_'. $gridColVal1.' portlet ui-sortable clearfix padMargin collapsible" draggable="true" id="'.$row["id"].'_'.$j.'">
			<header>
				<h2>
							'.Yii::t('dash',$row["title"]).'
				</h2>
			</header>
			<section id = "section_'.$j.'"> ';


				 //echo $row['content'];       //Abhinandan. March11th: Was originally invoking the '_d_ilights.php' ...

			  //echo 'j is ' . $j;


	echo '<ul id="top_ul_' . $j . '" class="isotope-widgets isotope-container iLights isotope" style="overflow: hidden; position: relative;">';
			  echo '</ul>';


	echo '</fieldset>';




	      echo '</section></div>';    //End live_status fieldset..

		echo'
		<!-- Div for Jplayer -->
		<script type="text/javascript" src="'. Yii::app()->theme->baseUrl . '/js/jquery.min.js" ></script>
		<script type="text/javascript" src="'. Yii::app()->theme->baseUrl . '/js/jquery.jplayer/jquery.jplayer.js" ></script>

		<script type="text/javascript">
		jQuery.noConflict();
		jQuery(document).ready(function($){
			$("#jquery_jplayer_1").jPlayer({
				ready: function () {
					$(this).jPlayer("setMedia", {
					mp3: "'. Yii::app()->theme->baseUrl .'/files/audio/honk.mp3",
					});
				},
				swfPath: "/js",
				supplied: "mp3",
			});
		});
		$("#jquery_jplayer_2").click( function() {
			$("#jquery_jplayer_1").jPlayer("play", 0); // Begins playing 42 seconds into the media.
		});
		</script>';



			  echo '<script type="text/javascript">

	       $("header").addClass( function(index, currentClass){
			  var addedClass;
			  if( currentClass === "" )
			  {
			   addedClass = "ui-widget-header ui-corner-top";
			  }
			  return addedClass;
	       });

	    ' ;


	 //Ajax goes here I think..
	  echo '
	      var IL_callback = {

		     IL_execute : function(){
					 var timeOut				= 0;
					 var fetchDashboardElements = "fetchDashboardElements";
					 var gadgetType             = "IdiotLights";
					 var widgetsPos             = '.$j.';

					 //var alert_a = setInterval(function(){     //Feb14th.. Placed outside of object.

					 //var alert_a = setInterval(function(){

					  //timeOut				= 30000;
					 //SHOW FIRST RECORD to our audience upon page load..
					  $.ajax({
						type: "POST",
						//url: "/Helios_Jan_4/dash/Dash",     //UIDashboardController::Dash()..
						url: "'. Yii::app()->baseUrl .'/dash/Dash",
						data: {"ajaxForwardRequest" : fetchDashboardElements, "gadgetType" : gadgetType, "widgetsPos" : widgetsPos},
						success: function(stringified)
						{
						 //alert(stringified  + "idiotlights");
						 //alert(434);
						 var data_obj     = $.parseJSON(stringified);
						 console.log(data_obj);

						 var legacy_record_count = 0;

						 var b =0;
						 //var b = setInterval(function()
						 {
						 if(legacy_record_count == data_obj[0].data_value.length) //Abhinandan. Asserting that ALL the elements contain the same exact number within its own legacy records array..
						  {
						   clearInterval(b);
						  }
						  else
						  {
						   //Abhinandan.  Refresh timestamps in db, if not appearing..

						   var data_count   = data_obj.length;
						   //var ul = document.getElementById("ul_iLights_parent");    //Abhinandan. Must clear 1x outside for loop..
						   var ul = document.getElementById("top_ul_'. $j .'");
						       ul.innerHTML = "";

						   for(var hb_i=0; hb_i < data_count; ++hb_i)
						   {

						    var element_type   = data_obj[hb_i].element_type;
						    var currentColor   = data_obj[hb_i].data_value[legacy_record_count][element_type][1];
						    var data_value     = data_obj[hb_i].data_value[legacy_record_count][element_type][0];

													if(element_type == "GoodDataSecs")
														element_type="GDS";

													if(element_type == "TotalTons")
														element_type="Tons";

						    var a_id           = data_obj[hb_i].dom_unique_attribute;

						    //Dynamically create @ .js
						    currentElement = document.createElement("li");
						    currentElement.className = "dash-order";

						    currentElement_a = document.createElement("a");
						    currentElement_a.id = a_id;
						    currentElement_a.className = "button-" + currentColor + " ui-corner-all";
						    currentElement_a.href = "#";


							currentElement_strong = document.createElement("strong");
							currentElement_strong.id = "a_strong_" + a_id;
							currentElement_strong.className = "animJq";
							currentElement_strong.innerHTML = "";

						     currentElement_span = document.createElement("span");
						     currentElement_span.style.position = "relative";
							currentElement_span.innerHTML = data_value;

													 if(data_value == null)
													 {
													  currentElement_strong.innerHTML = element_type + " absent";
													 }
													 else
													 {
													  currentElement_strong.innerHTML = element_type;
													 }

						     currentElement_a.appendChild(currentElement_strong);
						     currentElement_a.appendChild(currentElement_span);

						     currentElement.appendChild(currentElement_a);

						    ul.appendChild(currentElement);

						   }//end for loop..


						    //Attach animate events to colors:
						    //  Find a tags and attach events if color is of our interest..

						   var ul = document.getElementById("top_ul_'.$j.'");

						   var a_items = ul.getElementsByTagName("a");
						   for(var k = 0; k < a_items.length; ++k)
						   {
						    if(a_items[k].className == "button-red ui-corner-all")
						    {
						     //Add animshaker class
						     a_items[k].className = "button-red ui-corner-all animshaker";

						     //var shake_id = a_items[k].id;

						     //alert(shake_id);

						     //March10th.. Only shaking the first gadget @ red, since both gadgets are sharing the SAME <a id="..." > ..

						     var shakerSet = 1;
						    }//if
						   } //for loop a.items.length

						   if(shakerSet)
						   {
						     //niftyplayer("niftyPlayer1").playToggle();  //Abhinandan. Feb20th..
						     //var greet = function(){
						      $("#jquery_jplayer_1").jPlayer("play", 0);
													 //};
						      $(".animshaker").effect("shake", { times:1 }, 2000);    //Abhinandan. Better appearance is when used with the <a> element..
						   }

						   //Remove animShaker class
						   for(var k = 0; k < a_items.length; ++k){
						     if(a_items[k].className == "button-red ui-corner-all animshaker")
						     {
						     a_items[k].className = "button-red ui-corner-all";
													 }//if
													} //for
						   // ++legacy_record_count;

						  } //end else loop
						  //},3000); //@b interval timer
						  } //set interval

						}//success for ajax
					  }); //end ajax..

					  /*
					 //END SHOW FIRST RECORD..

					 //BEGIN SHOW 2 or more records..
					 $.ajax({
						type: "POST",
						//url: "/Helios_Jan_4/dash/Dash",     //UIDashboardController::Dash()..
						url: "'. Yii::app()->baseUrl .'/dash/Dash",
						data: {"ajaxForwardRequest" : fetchDashboardElements, "gadgetType" : gadgetType, "widgetsPos" : widgetsPos},
						success: function(stringified){

						  var data_obj     = $.parseJSON(stringified);
						 console.log(data_obj);

						 var legacy_record_count = 1;

						 var b =0;
						 //var b = setInterval(function()
						 {

						  if(legacy_record_count == data_obj[0].data_value.length)  //If we are out of range.. go ahead and cancel the setInterval..
						  {
						   clearInterval(b);
						  }
						  else{
						   //Abhinandan.  Refresh timestamps in db, if not appearing..

						   var data_count   = data_obj.length;
						   //var ul = document.getElementById("ul_iLights_parent");    //Abhinandan. Must clear 1x outside for loop..
						   var ul = document.getElementById("top_ul_'. $j .'");
						       ul.innerHTML = "";

						   for(var hb_i=0; hb_i < data_count; ++hb_i){  //Abhinandan. Start looping at data_obj[0].data_value[1]...our 2nd record representing the 2nd pass..

						    var element_type   = data_obj[hb_i].element_type;
						    var currentColor   = data_obj[hb_i].data_value[legacy_record_count][element_type][1];
						    var data_value     = data_obj[hb_i].data_value[legacy_record_count][element_type][0];
						    var a_id           = data_obj[hb_i].dom_unique_attribute;

						    //Dynamically create @ .js
						    currentElement = document.createElement("li");
						    currentElement.className = "dash-order";

						    currentElement_a = document.createElement("a");
						    currentElement_a.id = a_id;
						    currentElement_a.className = "button-" + currentColor + " ui-corner-all";
						    currentElement_a.href = "#";

						     currentElement_span = document.createElement("span");
						     currentElement_span.style.position = "relative";


													 if(data_value == null){
													  currentElement_span.innerHTML = element_type + " absent";
													 }
													 else{
													  currentElement_span.innerHTML = element_type;
													 }

						     currentElement_a.appendChild(currentElement_span);

						     currentElement.appendChild(currentElement_a);

						    ul.appendChild(currentElement);

						   }//end for loop..

						   ++legacy_record_count;

						  } //end else loop

						 //}, 300);  //end setInterval @b..
						 } //b set interval


						} //end success..

					      });  //end ajax..
					      */


					  //  }, timeOut);  //end setInterval @alert_a ..      //July15th 2013: Probably wont need this..every 30 seconds retrieve entire new flush of records from table..

		     }  //IL_execute
      };

       //IL_callback.IL_execute();

       IL_callback.IL_execute();      //Start the countdown ie 30 seconds for next data fetch from server..

      </script> ';


	     }
	     //end IdiotLights..



	     //Alerts, works. jdr..
	   if( ($divIdVal1 == "Alerts") )
       {
			  //Controller::fb('Alerts');
	//Controller::fb($j);

	    echo '<div class="grid_'. $gridColVal1.' portlet ui-sortable clearfix padMargin collapsible" draggable="true" id="'.$row["id"].'_'.$j.'">
			<header>
				<h2>'.Yii::t('dash',$row["title"]).'
				</h2>
			</header>
			<section id = "section_'.$j.'"> ';




	echo '<div id="alerts_'.$j.'" style="padding-left:50px;height:200px;"></div>' ;

	    echo '</section></div>';

	echo '
	    <script type="text/javascript">
	      $("header").addClass( function(index, currentClass){
			   var addedClass;
			   if( currentClass === "" )
			   {
			    addedClass = "ui-widget-header ui-corner-top";
			   }
			   return addedClass;
			  });
	    </script>
	     ' ;


	echo '<script type="text/javascript">

	       var A_callback = {

		    A_execute : function(){
				    var fetchDashboardElements = "fetchDashboardElements";
				    var gadgetType             = "Alerts";
				    var widgetsPos             = '.$j.';

				    //var alert_a = setInterval(function(){     //Feb14th.. Placed outside of object.


				     $.ajax({
				      type: "POST",
				      //url: "/Helios_Jan_4/dash/Dash",
				      url: "'. Yii::app()->baseUrl .'/dash/Dash",
				      data: {"ajaxForwardRequest" : fetchDashboardElements, "gadgetType" : gadgetType, "widgetsPos" : widgetsPos},
				      success: function(stringified){

				       //alert(stringified);

				       var data_obj = $.parseJSON(stringified);
				       console.log(data_obj);

				       var legacy_record_count_alerts = 0;

				       // SHOW FIRST RECORD only. BEGIN HERE
					//alert(legacy_record_count_alerts);
					if(legacy_record_count_alerts != data_obj[0].data_value.length) //Abhinandan. Asserting that ALL the elements contain the same exact number within its own legacy records array..
					{
					 var data_count   = data_obj.length;

					 var parent_div = document.getElementById("alerts_'.$j.'");
					     parent_div.innerHTML = "";

					 for(var hb_i=0; hb_i < data_count; ++hb_i){
					  var elementType  = data_obj[hb_i].element_type;
					  var currentColor = data_obj[hb_i].data_value[legacy_record_count_alerts][elementType][1];      //Abhinandan. Asserting that there is only 1x record fetched from server..
					  var dataValue    = data_obj[hb_i].data_value[legacy_record_count_alerts][elementType][0];

					  var upperLimit   = data_obj[hb_i].element_setpoint[0][0];
					  var pbar_height  = 0;

					  var division_output = (dataValue / upperLimit);
					  var totalPercentage = ( division_output * 100).toFixed(0);

					  if( totalPercentage >= 100 ){
					   pbar_height = 100;
					  }else{
					   pbar_height = totalPercentage;
					  }                                                                                                                                                                    //bravo                                        //charlie                                                    //delta                                       //echo                                         //foxtrot                                     //golf                                        //hulu                                        //india                                       //jackalope                                    //kitten                                                     //lima                                         //mike                                          //november                                     //oscar                                           //parrot                                      //quebec                                        //end alpha     //alphabet                                                                                   //letter_a                                             //letter_b                                              //letter_c                                              //letter_d                                                      //letter_e                                                                                             //letter_f                               //letter_g

					   var span_alpha = document.createElement("span");
					       span_alpha.className = "vertical large";
					       span_alpha.style.height = "200px";
					       span_alpha.style.marginRight = "15px";
					       span_alpha.style.width = "15px";
					       span_alpha.style.cssFloat = "left";

					   var span_bravo = document.createElement("span");
					       span_bravo.className = "left-mark";
					       span_bravo.style.bottom = "1px";

					   var span_charlie = document.createElement("span");
					       span_charlie.className = "mark-label align-bottom";
					       span_charlie.innerHTML = "0%";

					   span_bravo.appendChild(span_charlie);           ///

					   var span_delta = document.createElement("span");
					       span_delta.className = "left-mark";
					       span_delta.style.bottom = "20%";

					   var span_echo = document.createElement("span");
					       span_echo.className = "mark-label";
					       span_echo.innerHTML = "25%";

					   span_delta.appendChild(span_echo);            //////

					   var span_foxtrot = document.createElement("span");
					       span_foxtrot.className = "left-mark";
					       span_foxtrot.style.bottom = "40%";

					   var span_golf = document.createElement("span");
					       span_golf.className = "mark-label";
					       span_golf.innerHTML = "50%";

					   span_foxtrot.appendChild(span_golf);        ////

					   var span_hulu = document.createElement("span");
					       span_hulu.className = "left-mark";
					       span_hulu.style.bottom = "60%";

					   var span_india = document.createElement("span");
					       span_india.className = "mark-label";
					       span_india.innerHTML = "75%";

					   span_hulu.appendChild(span_india);             /////

					   var span_rhea = document.createElement("span");
					       span_rhea.className = "left-mark";
					       span_rhea.style.bottom = "80%";

					   var span_indial = document.createElement("span");
					       span_indial.className = "mark-label";
					       span_indial.innerHTML = "100%";

					   span_rhea.appendChild(span_indial);             /////

					   var span_jackalope = document.createElement("span");
					       span_jackalope.className = "right-mark";
					       span_jackalope.style.bottom = "60%";

					   var span_kitten = document.createElement("span");
					       span_kitten.style.color = "red";
					       span_kitten.className = "mark-label";
					       span_kitten.innerHTML = "Red: Critical";

					   span_jackalope.appendChild(span_kitten);        ///

					   var span_lima = document.createElement("span");
					       span_lima.className = "right-mark";
					       span_lima.style.bottom = "50%";

					   var span_mike = document.createElement("span");
					       span_mike.style.color = "orange";
					       span_mike.className = "mark-label";
					       span_mike.innerHTML = "Orange: High";

					   span_lima.appendChild(span_mike);          ///

					   var span_november = document.createElement("span");
					       span_november.className = "right-mark";
					       span_november.style.bottom = "40%";

					   var span_oscar = document.createElement("span");
					       span_oscar.style.color = "green";
					       span_oscar.className = "mark-label";
					       span_oscar.innerHTML = "Green: Normal";

					   span_november.appendChild(span_oscar);     ///


					   span_alpha.appendChild(span_jackalope);
					   span_alpha.appendChild(span_lima);
					   span_alpha.appendChild(span_november);

					   //Now stuff span_alpha before continuing onwards..
					   span_alpha.appendChild(span_bravo);
					   span_alpha.appendChild(span_delta);
					   span_alpha.appendChild(span_foxtrot);
					   span_alpha.appendChild(span_hulu);
					   span_alpha.appendChild(span_rhea);
					  parent_div.appendChild(span_alpha);    //Append to our parent .. 1/2..

					  var span_alphabet = document.createElement("span");

					      //span_alphabet.className = "progress vertical large";
					      span_alphabet.className = "progress vertical large ui-progressbar ui-widget ui-widget-content ui-corner-all";

					      span_alphabet.style.height = "200px";
					      span_alphabet.style.marginRight = "15px";

					      span_alphabet.style.float = "left";
					      //span_alphabet.style.float = "none";

					  var span_letter_a = document.createElement("span");
					      span_letter_a.className = "inner-mark";
					      span_letter_a.style.bottom = "25%";

					  span_alphabet.appendChild(span_letter_a);

					  var span_letter_b = document.createElement("span");
					      span_letter_b.className = "inner-mark";
					      span_letter_b.style.bottom = "45%";

					  span_alphabet.appendChild(span_letter_b);

					  var span_letter_c = document.createElement("span");
					      span_letter_c.className = "inner-mark";
					      span_letter_c.style.bottom = "72%";

					  span_alphabet.appendChild(span_letter_c);

					  var span_letter_d = document.createElement("strong");
					      span_letter_d.className = "progress-text alarmTextHead";
					      span_letter_d.innerHTML = elementType;

					  span_alphabet.appendChild(span_letter_d);

					  var span_letter_e = document.createElement("span");
					      span_letter_e.className = "progress-bar " + currentColor + "-gradient glossy";
					      span_letter_e.style.height = pbar_height + "%";

					  var span_letter_f = document.createElement("span");
					      span_letter_f.className = "stripes animated";

					  span_letter_e.appendChild(span_letter_f);

					  var span_letter_g = document.createElement("span");
					      span_letter_g.className = "progress-text alarmTextBar";
					      span_letter_g.innerHTML = dataValue ; //+ " " + totalPercentage + "% ";

					  var span_letter_h = document.createElement("span");
					      span_letter_h.className = "progress-text alarmTextTopsBar";
					      span_letter_h.innerHTML = totalPercentage + "% ";

					  span_letter_e.appendChild(span_letter_h);
					  span_letter_e.appendChild(span_letter_g);
					  span_alphabet.appendChild(span_letter_e);

					  parent_div.appendChild(span_alphabet);    //Append to our parent .. 2/2..


					 } //end for loop..

					 // ++legacy_record_count_alerts;

					}  //end if..

				// END SHOW FIRST RECORD only. END (BEGIN started up top..)

				      } //end success..
				     }); //end ajax for 1st record..

				     /*
									  $.ajax({
				      type: "POST",
				      //url: "/Helios_Jan_4/dash/Dash",
				      url: "'. Yii::app()->baseUrl .'/dash/Dash",
				      data: {"ajaxForwardRequest" : fetchDashboardElements, "gadgetType" : gadgetType, "widgetsPos" : widgetsPos},
				      success: function(stringified){
				       //alert(stringified + " alert");

				       var data_obj = $.parseJSON(stringified);
				       console.log(data_obj);

				       var legacy_record_count_alerts = 1;   //Start at the 1th (2nd) index..

				       //BEGIN start to fetch at record 2 and beyond..
				       //var alert_b = 0;
				       var alert_b = setInterval(function()
				       { //Start the clock before we show the user another record..this time starting with data_obj[0].data_value.length -1 ...

					if(legacy_record_count_alerts == data_obj[0].data_value.length) //If we are over the length of our data array..
					{
					 clearInterval(alert_b);
					}
					else{
					 if( data_obj[0].data_value.length > 1 )
					 {

					 var data_count   = data_obj.length;
					 var parent_div = document.getElementById("alerts_'.$j.'");
					     parent_div.innerHTML = "";

					 for(var hb_i=0; hb_i < data_count; ++hb_i){
					  var elementType  = data_obj[hb_i].element_type;
					  var currentColor = data_obj[hb_i].data_value[legacy_record_count_alerts][elementType][1];      //Abhinandan. Asserting that there is only 1x record fetched from server..
					  var dataValue    = data_obj[hb_i].data_value[legacy_record_count_alerts][elementType][0];

					  var upperLimit   = data_obj[hb_i].element_setpoint[0][0];



					  var pbar_height  = 0;

					  var division_output = (dataValue / upperLimit);
					  var totalPercentage = ( division_output * 100).toFixed(0);

					  if( totalPercentage >= 100 ){
					   pbar_height = 100;
					  }else{
					   pbar_height = totalPercentage;
					  }                                                                                                                       //bravo                                        //charlie                                                    //delta                                       //echo                                         //foxtrot                                     //golf                                        //hulu                                        //india                                       //jackalope                                    //kitten                                                     //lima                                         //mike                                          //november                                     //oscar                                           //parrot                                      //quebec                                        //end alpha     //alphabet                                                                                   //letter_a                                             //letter_b                                              //letter_c                                              //letter_d                                                      //letter_e                                                                                             //letter_f                               //letter_g

					   var span_alpha = document.createElement("span");
					       span_alpha.className = "vertical large";
					       span_alpha.style.height = "200px";
					       span_alpha.style.marginRight = "15px";
					       span_alpha.style.width = "15px";
					       span_alpha.style.cssFloat = "left";

					   var span_bravo = document.createElement("span");
					       span_bravo.className = "left-mark";
					       span_bravo.style.bottom = "1px";

					   var span_charlie = document.createElement("span");
					       span_charlie.className = "mark-label align-bottom";
					       span_charlie.innerHTML = "0%";

					   span_bravo.appendChild(span_charlie);           ///

					   var span_delta = document.createElement("span");
					       span_delta.className = "left-mark";
					       span_delta.style.bottom = "30%";

					   var span_echo = document.createElement("span");
					       span_echo.className = "mark-label";
					       span_echo.innerHTML = "30%";

					   span_delta.appendChild(span_echo);            //////

					   var span_foxtrot = document.createElement("span");
					       span_foxtrot.className = "left-mark";
					       span_foxtrot.style.bottom = "60%";

					   var span_golf = document.createElement("span");
					       span_golf.className = "mark-label";
					       span_golf.innerHTML = "60%";

					   span_foxtrot.appendChild(span_golf);        ////

					   var span_hulu = document.createElement("span");
					       span_hulu.className = "left-mark";
					       span_hulu.style.bottom = "75%";

					   var span_india = document.createElement("span");
					       span_india.className = "mark-label";
					       span_india.innerHTML = "100%";

					   span_hulu.appendChild(span_india);             /////

					   var span_jackalope = document.createElement("span");
					       span_jackalope.className = "right-mark";
					       span_jackalope.style.bottom = "1px";

					   var span_kitten = document.createElement("span");
					       span_kitten.className = "mark-label align-bottom";
					       span_kitten.innerHTML = "0 Ton";

					   span_jackalope.appendChild(span_kitten);        ///

					   var span_lima = document.createElement("span");
					       span_lima.className = "right-mark";
					       span_lima.style.bottom = "25%";

					   var span_mike = document.createElement("span");
					       span_mike.className = "mark-label";
					       span_mike.innerHTML = "200 Ton";

					   span_lima.appendChild(span_mike);          ///

					   var span_november = document.createElement("span");
					       span_november.className = "right-mark";
					       span_november.style.bottom = "50%";

					   var span_oscar = document.createElement("span");
					       span_oscar.className = "mark-label";
					       span_oscar.innerHTML = "500 Ton";

					   span_november.appendChild(span_oscar);     ///

					   var span_parrot = document.createElement("span");
					       span_parrot.className = "right-mark";
					       span_parrot.style.bottom = "75%";

					   var span_quebec = document.createElement("span");
					       span_quebec.className = "mark-label";
					       span_quebec.innerHTML = "1000 Ton";

					   span_parrot.appendChild(span_quebec);      ///

					   //Now stuff span_alpha before continuing onwards..
					   span_alpha.appendChild(span_bravo);
					   span_alpha.appendChild(span_delta);
					   span_alpha.appendChild(span_foxtrot);
					   span_alpha.appendChild(span_hulu);
					   span_alpha.appendChild(span_jackalope);
					   span_alpha.appendChild(span_lima);
					   span_alpha.appendChild(span_november);
					   span_alpha.appendChild(span_parrot);
					  parent_div.appendChild(span_alpha);    //Append to our parent .. 1/2..

					  var span_alphabet = document.createElement("span");

					      //span_alphabet.className = "progress vertical large";
					      span_alphabet.className = "progress vertical large ui-progressbar ui-widget ui-widget-content ui-corner-all";

					      span_alphabet.style.height = "200px";
					      span_alphabet.style.marginRight = "15px";

					      span_alphabet.style.float = "left";
					      //span_alphabet.style.float = "none";

					  var span_letter_a = document.createElement("span");
					      span_letter_a.className = "inner-mark";
					      span_letter_a.style.bottom = "25%";

					  span_alphabet.appendChild(span_letter_a);

					  var span_letter_b = document.createElement("span");
					      span_letter_b.className = "inner-mark";
					      span_letter_b.style.bottom = "50%";

					  span_alphabet.appendChild(span_letter_b);

					  var span_letter_c = document.createElement("span");
					      span_letter_c.className = "inner-mark";
					      span_letter_c.style.bottom = "75%";

					  span_alphabet.appendChild(span_letter_c);

					  var span_letter_d = document.createElement("span");
					      span_letter_d.className = "progress-text";
					      span_letter_d.innerHTML = totalPercentage + "%";

					  span_alphabet.appendChild(span_letter_d);

					  var span_letter_e = document.createElement("span");
					      span_letter_e.className = "progress-bar " + currentColor + "-gradient glossy";
					      span_letter_e.style.height = pbar_height + "%";

					  var span_letter_f = document.createElement("span");
					      span_letter_f.className = "stripes animated";

					  span_letter_e.appendChild(span_letter_f);

					  var span_letter_g = document.createElement("span");
					      span_letter_g.className = "progress-text";
					      span_letter_g.innerHTML = totalPercentage + "% (" + dataValue + ")";

					  span_letter_e.appendChild(span_letter_g);
					  span_alphabet.appendChild(span_letter_e);

					  parent_div.appendChild(span_alphabet);    //Append to our parent .. 2/2..


					 } //end for loop..

					 ++legacy_record_count_alerts;

					 } //end if data_obj[0].data_value.length is greater than 1..
					}  //end else..
				       //}, 600);    //How often we want EACH table record (for that element) to show up..
				       } //alert_b

				      } //end success..
				     }); //end ajax..
				    */


				    // }, 120000);  //end setInterval @alert_a ..      //July15th 2013; Might not need this, what it does is re-fetch entire table contents say at 2minutes...
		    } //A_execute
    }; //A_callback

       A_callback.A_execute();

	      </script>' ;



	     }
	     //end Alerts gadget..



	      //March12th, works.. jdr.
	   if( ($divIdVal1 == "Charts") )
       {
	  if($_REQUEST["test"])
			echo "1";
	      echo
		   '<div class="grid_'. $gridColVal1.' portlet ui-sortable clearfix padMargin collapsible" draggable="true" id="'.$row["id"].'_'.$j.'">
			<header>
				<h2>
							'.Yii::t('dash',$row["title"]).'
				</h2>
			</header>
			<section id = "section_'.$j.'"> ';


				 //echo $row['content'];       //Abhinandan. March12th: Was originally invoking the '_d_chart.php' ...


			  //echo ' <div class="ui-widget portlet-content"> '.$j.'</div> ' ;

			  echo ' <div class="ui-widget portlet-content">

		  <div id="containerHC_'.$j.'" style="min-width: auto; height: 300px; margin: 0 auto;width:100%;"></div>


	       </div>
	     ' ;

	      echo '</section></div>';

    if(!$jsIncluded)
    {
	    $jsIncluded = 1;
    echo '
	 <script type="text/javascript">
	  $("header").addClass( function(index, currentClass){
	       var addedClass;
	       if( currentClass === "" )
	       {
		addedClass = "ui-widget-header ui-corner-top";
	       }
	       return addedClass;
	      });
	 </script>
	 <script src="' . Yii::app()->baseUrl . '/themes/tutorialzine1/js/highCharts/highstock.js" type="text/javascript"></script>
	 <script src="' . Yii::app()->baseUrl . '/themes/tutorialzine1/js/highCharts/modules/exporting.js" type="text/javascript"></script>
	';
   }
	$curTime 	= NULL;
	$prevTime	= NULL;
	$tagId		= NULL;
	$tagGrpId	= NULL;
	$tagStat	= NULL;

    //TODO Change TIMERANGE
	if ((isset($_REQUEST['pageType'])) && ($_REQUEST['pageType']=="timeRange") && (isset($_REQUEST['sTime'])) && (isset($_REQUEST['eTime'])))
	{

		
		$prevTime = strtotime($_REQUEST['sTime']);
		$prevTime = date("Y-m-d H:i:s",$prevTime);

		$curTime = strtotime($_REQUEST['eTime']);
		
		$sysTime = strtotime(date("Y-m-d H:i:s"));
		if($sysTime <= $curTime)
			$curTime = $sysTime;
		
		$curTime = date("Y-m-d H:i:s",$curTime);
		$pageType	= "timeRange";
		$postString     = '"pageType":"timeRange", "sTime": "'.$prevTime.'", "eTime" : "'.$curTime.'"';
	}
	else if (($_REQUEST['pageType']=="tagIndex") && (isset($_REQUEST['tag']))  && (isset($_REQUEST['tagStatus'])))
	{
		//$curTime = strtotime($_REQUEST['eTime']);
		//$curTime = date("Y-m-d h:i:s",$curTime);

		//$prevTime = strtotime($_REQUEST['sTime']);
		//$prevTime = date("Y-m-d h:i:s",$prevTime);

	$tagId		 	= ($_REQUEST['tag']);
	$tagGrpId     	= ($_REQUEST['tGrp']);
	$tagStat     	= ($_REQUEST['tagStatus']);
		$pageType	= "tagIndex";
	$postString     = '"pageType":"tagIndex","tagStatus":"'.$tagStat.'",'.						//"sTime": "'.$curTime.'", "eTime" : "'.$prevTime.'",
					  '"tag":"'.$tagId.'","tGrp":"'.$tagGrpId.'"';
	}
	else
	{
	$postString     = "";
	}


	//TODO get Uid and Default
	$uid = Yii::app()->user->id;
	$default = 1;

	//ABHITBD
	$controllerid = $_SERVER["REQUEST_URI"];
	$pos = strpos($controllerid , "rawmix");

		// The !== operator can also be used.  Using != would not work as expected
	// because the position of 'a' is 0. The statement (0 != false) evaluates
	// to false.
	if ($pos !== false) {
	    $default = 4;
	    $uid = 999;
	}

	$outString = DashHelper::getMeChartsInfo($j, $uid, $pageType,$default, $prevTime,$curTime,  $tagId, $tagGrpId, $tagStat);

	
    /*
     *          //Data will be split based on the format shown below
     * 		$outData = "SUC::cstyle:$c_display_style##gstyle:$c_group_style##elemName:$element_name";
		$outData .="##timeStart:" . strtotime($prevTime) . "##timeEnd:" . strtotime($curTime);

		foreach ($top_arr as $elemn => $vall) {
		    $outData .="##" . $elemn . "**" . str_replace("\"", "", CJSON::encode($vall)) . "";
		}
     *
     *
     * SUC::cstyle:aspline##gstyle:grouped##elemName:;SiO2;Al2O3;Fe2O3##timeStart:1425801780##timeEnd:1425884580
	##SiO2**[[Date.UTC(2015,03,08,03,56),18.440],[Date.UTC(2015,03,08,03,57),20.100],[Date.UTC(2015,03,08,03,58),16.310]]
	##Fe2O3**[[Date.UTC(1969,12,31,17,23),6.730],[Date.UTC(1969,12,31,17,23),6.180],[Date.UTC(1969,12,31,17,23),6.480]]
     */
	 
	 $sp_graph_query = " SELECT * FROM `rm_set_points` where 1 "; // LIMIT 100
	$sp_command = Yii::app()->db->createCommand($sp_graph_query);
	$sp_resultAr = $sp_command->queryAll(); ////
	$temp_arr = array();
	$cur_sp_value = 1;
	$spString = "";
	
	foreach ($sp_resultAr as $rs_k1 => $rs_v1) {
		$cur_sp_value  = (float)round($rs_v1["sp_value_num"],2);
		$spString .= $rs_v1["sp_name"] . ":" . $cur_sp_value . ", ";
	}
	 

   echo '<script type="text/javascript">
	 var stringified = \''.$outString.'\';

	//alert(stringified);
	Highcharts.setOptions({
		global: {
			useUTC: false
		}
	});
    var fetchDashboardElements = "fetchDashboardElements";
    var gadgetType             = "Charts";
    var widgetsPos             = '.$j.';

	var setpointsGlobalAr 			= {'. $spString .'};
	
     {

	//Get all the different name-value pairs
	//First token tells us, whether there was an error (SUC OR ERR)
	var responseSplitAr = stringified.split("::");

	if(responseSplitAr[0] == "ERR")
	{
		$("#"+"containerHC_'.$j.'").html("<span style=\'color:red;background:lightgray;\'>'.Yii::t('dash','No data available for the given time period!').'</span>");
	}
	else
	{
	//All other strings are split by ##
	var sp_stringfield  = responseSplitAr[1];
	var chart_'.$j.';
	var responseSplitAr = sp_stringfield.split("##");

	//## responseSplitAr[0] = chartStyle
	//## responseSplitAr[1] = groupStyle
	//## responseSplitAr[2] = Element-Names
	//## responseSplitAr[3] = Time-Start Timestamp
	//## responseSplitAr[4] = Time-End  Timestamp
	//## responseSplitAr[5] = Element-Name**[DataArray] json Data Array

	//check if chart style and group style are present initialize arrays
	//## responseSplitAr[0] = chartStyle
	if(null != responseSplitAr[0])
	{
	    var chartStyleAr    = responseSplitAr[0].split(":");
	    var chartStyle      = chartStyleAr[1];

	    //## responseSplitAr[1] = groupStyle
	    if(null != responseSplitAr[1])
	    {
		var groupStyleAr     = responseSplitAr[1].split(":");
		var groupStyle		 = groupStyleAr[1];
		//## responseSplitAr[3] = Time-Start Timestamp
		if(null != responseSplitAr[3]) {
			var startTimeAr		 = responseSplitAr[3].split(":");
			var startTimeStamp	 = startTimeAr[1];
			//## responseSplitAr[4] = Time-End  Timestamp
			if(responseSplitAr[4]){
			    var endTimeAr	 = responseSplitAr[4].split(":");
			    var endTimeStamp	 = endTimeAr[1];
			    var elementsArList 	 = new Array();
			}
		}
	    }
	}

	//if length =6 this means that it is a simple graph with 1 element
	if(groupStyle != "")
	{
		//## responseSplitAr[2] = Element-Names
		if(responseSplitAr[2]){
		    var elemTitleAr     = responseSplitAr[2].split(":");
		    elemTitleAr[1]      = elemTitleAr[1].slice(1,elemTitleAr[1].length);
		    var elemTitleSubAr	= elemTitleAr[1].split(";");

		    //responseSplitAr[5] = Element-Name**[DataArray] json Data Array
		    //loop through the string and form a json array: index=5
		    for(var lk=6,oi=0;lk<responseSplitAr.length;lk++,oi++)
		    {

				var mdataAr         = responseSplitAr[lk];
				var mdataArSubAr    = mdataAr.split("**");

				var elemNameTmp     = mdataArSubAr[0];

				if(chartStyle == "aspline"){
					var dataTmp         = $.parseJSON(mdataArSubAr[1]);
				}else {
					var dataTmp         = $.parseJSON(mdataArSubAr[1]);
				}				
				
				var arrTemp = [];
				var cusSpVal = 0;
				if(elemNameTmp == "C3S")
					cusSpVal = (setpointsGlobalAr.C3S);
				if(elemNameTmp == "Al2O3")
					cusSpVal = (setpointsGlobalAr.Al2O3);
				if(elemNameTmp == "Fe2O3")
					cusSpVal = (setpointsGlobalAr.Fe2O3);
				if(elemNameTmp == "SiO2")
					cusSpVal = 0;
				
				if(cusSpVal > 0){
					for (var i = 0; i < dataTmp.length; i++) {
						arrTemp.push(cusSpVal);
					}
				}
				var elemObj_temp    = {name: elemNameTmp, data :dataTmp, data_sp : arrTemp};
				elementsArList[oi]  = elemObj_temp;		
		    }
		}
		
	}
	else
	{
		//Display there was a problem with displaying Data.
		$("#"+"containerHC_'.$j.'").html("Problem with gathering data for the time period!");
	}
	//console.debug(elementsArList);
	if(chartStyle =="saspline")
	{
		for(var oi=0,lk=3;lk<(responseSplitAr.length);lk++,oi++)
		{
			elementsArList[oi].type ="areaspline";
			elementsArList[oi].threshold = null;
			elementsArList[oi].tooltip = {valueDecimals : 2};
			elementsArList[oi].fillColor = {
												linearGradient : {
													x1: 0,
													y1: 0,
													x2: 0,
													y2: 1
												},
												stops : [[0, Highcharts.getOptions().colors[0]], [1, "rgba(0,0,0,0)"]]
											};
		}//foreach
	}

	if(groupStyle =="hgrouped")
	{
		//console.debug(elementsArList);
		$("#containerHC_'.$j.'").highcharts("StockChart", {
		    chart: {
		    },

		    rangeSelector: {
			selected: 4
		    },

		    yAxis: {
			labels: {
			    formatter: function() {
					return (this.value > 0 ? "+" : "") + this.value + "%";
			    }
		    },
			plotLines: [{
				value: 0,
				width: 2,
				color: "silver"
			}]
		    },
		    plotOptions: {
			series: {
				compare: "percent"
			    }
		    },
		    tooltip: {
			pointFormat: "<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>",
			valueDecimals: 0
		    },
		    series: elementsArList
		});

		//return;
	}

	//if ((chartStyle =="simple") || (chartStyle =="aspline"))
	if ((chartStyle =="simple"))
	{
		var oi=0;
		var data = elementsArList[oi].data;
		var dataT = elementsArList[oi].data_sp;
		
		// Create a timer
		var start = + new Date();
		var now = new Date;
		var utc_timestamp = startTimeStamp * 1000;

		// Create the chart
		$("#containerHC_'.$j.'").highcharts("StockChart",
		{
		    chart: {
				events: {
					load: function(chart) {
						this.setTitle(null, {
							text: "Built chart at "+ (new Date() - start) +"ms"
						});
					}
				},
				zoomType: "x"
		    },

		    rangeSelector: false,

			yAxis: {
				title: {
					text: "Weight Percentage (%)"
				}
			},

		    title: {
				text: "Analysis Information for "+ elementsArList[oi].name
			},

			subtitle: {
				text: "Built chart at..." // dummy text to reserve space for dynamic subtitle
			},

			series: [{
			name: "Weight %",
			data: data,
			pointStart: utc_timestamp,
			pointInterval: 60 * 1000,
			tooltip: {
				valueDecimals: 1,
				valueSuffix: "%"
			}},	
			{
			name: "Set Point",
			data: dataT,
			pointStart: utc_timestamp,
			pointInterval: 60 * 1000,
			tooltip: {
				valueDecimals: 1,
				valueSuffix: "%"
			}},			
			] /*series*/

		}); //HighStocks


	}//if simple
	else if(chartStyle == "aspline"){
		var oi=0;
		var start = + new Date();
		var now = new Date;
		var utc_timestamp = startTimeStamp * 1000;
		
		var datax = elementsArList[oi].data;
		
		$("#containerHC_'.$j.'").highcharts({
			title: {
				text: "Analysis Resluts for :" + elemTitleSubAr,
				x: -20 //center
			},
			subtitle: {
				text: "Source: Sabia Analyzer",
				x: -20
			},
			/*
			xAxis: {
				categories: ["12:00","1:00","2:00","3:00","4:00","5:00","6:00","7:00","8:00","9:00","11:00","12:00",]
			},
			*/
			yAxis: {
				title: {
					text: "Weight Percentage (%)"
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: "#808080"
				}]
			},
			tooltip: {
				valueSuffix: "%"
			},
			legend: {
				layout: "vertical",
				align: "right",
				verticalAlign: "middle",
				borderWidth: 0
			},
			series: [{
				name: "Set-Point",
				data: elementsArList[oi].data_sp,
				pointStart: utc_timestamp,
				pointInterval: 60 * 1000,
				}, {
				name: "Analysis Results for "  + elemTitleSubAr,
				data: elementsArList[oi].data,
				pointStart: utc_timestamp,
				pointInterval: 60 * 1000,
			}]
		});
		
	}
	else if(chartStyle =="adspline")
	{
	    $("#containerHC_'.$j.'").highcharts({
		chart: {
		    type: "spline"
		},
		title: {
		    text : "Analysis Data for " + elemTitleSubAr
		},
		subtitle: {
		    text: "Source: Sabia Inc"
		},
		xAxis: {
		    type: "datetime",
		    dateTimeLabelFormats: { // donot display the dummy year
			millisecond: "%H:%M:%S.%L",
			second: "%H:%M:%S",
			minute: "%H:%M",
			hour: "%H:%M",
			day: "%m-%d-%y %H:%M",
			week: "%m-%d-%y %H:%M",
			month: "%m-%d-%y %H:%M",
			year: "%Y"
		    },
		    title: {
			text: "Date"
		    }
		},
		yAxis: {
		    title: {
			text: "Weight Percentage (%)"
		    },
		    min: 0
		},
		tooltip: {
		    headerFormat: "<b>Weight %</b><br>",
		    pointFormat: "{point.x:%m-%d-%y %H:%M} <b>{series.name}</b> : {point.y:.2f}  %"
		},

		plotOptions: {
		    spline: {
			marker: {
			    enabled: true
			}
		    }
		},
		series: elementsArList
	    });
	}
	else if(chartStyle =="areaspline")
	{
	var start = + new Date();

	var now = new Date;

	var utc_timestamp = startTimeStamp * 1000;
	//alert(utc_timestamp);
	chart_'.$j.' = new Highcharts.Chart({
	    chart: {
				renderTo : "containerHC_'.$j.'",
		type: "areaspline"
	    },
	    title: {
		text : "Analysis Data for " + elemTitleSubAr
	    },
	    subtitle: {
		text: "Source: Sabia Inc"
	    },
	    xAxis: {
		tickmarkPlacement: "on",
		title: {
		    enabled: false
		},
		labels: {
		    formatter: function() {
						var ndT = new Date();
						ndT.setTime(this.value);
						var nTemp = ndT.getHours() + ":" + ndT.getMinutes() + ":" + ndT.getSeconds();
			return  nTemp;
		    }
		}
	    },
	    yAxis: {
		title: {
		    text: "% Weight Average"
		},
	    },
	    tooltip: {
		formatter: function() {
					var ndT = new Date();
					ndT.setTime(this.x);
					var nTemp = ndT.getHours() + ":" + ndT.getMinutes() + ":" + ndT.getSeconds();
		    return  this.y + "% at " + nTemp ;
		}
	    },
	    plotOptions: {
		area: {
		    stacking: "normal",
		    lineColor: "#666666",
		    lineWidth: 1,
		    marker: {
			lineWidth: 1,
			lineColor: "#666666"
		    }
		}
	    },
	    series : elementsArList
	 });

	}//if aspline
	else if(chartStyle =="saspline")
	{
		// Create the chart
		window.chart_'.$j.' = new Highcharts.StockChart({
			chart : {
				renderTo : "containerHC_'.$j.'"
			},

			rangeSelector : {
				selected : 1
			},

			title : {
				text : "Analysis Data for " + elemTitleSubAr
			},

			series : elementsArList
		});

	}//else

	//Close the Ajax loop and all other blocks
	}  //end ajax success..

    //}); //end ajax..

    /* }); //end document.ready.. */
	}
	</script>';

 }
 //end Charts..

   //Works!.. jdr.. Table gadget..  Commenting out for now, working on Charts gadget..
   if( ($divIdVal1 == "System_Messages") )
   {
		$features = Yii::app()->params["features"];
		if (@($features["import_export"]) && (1 == $features["import_export"])) {
			$importExportFeature = 'dom: "T<\'clear\'>lfrtip",'.
								   '"tableTools": {
													"sSwfPath": "'. Yii::app()->theme->baseUrl .'/js/swf/copy_csv_xls_pdf.swf",
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
									}';
		}else {
			$importExportFeature = '';
		}
		if($gridColVal1 == 4)
			$gridColVal1 = 3;

		$heightPx = "height:auto !important;";
		$aTag  = '';

		if($j == 0) {
			$title = "Analysis Results";
			$aTag  = '<a data-icon-primary="ui-icon-circle-refresh" href="#" style="margin-right:5px;float:right;" class="button pull-left ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary minChanger" alt="0#fives" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text"> 5 M </span></a>' .
					 '<a data-icon-primary="ui-icon-circle-refresh" href="#" style="margin-right:5px;float:right;" class="button pull-left ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary minChanger" alt="0#tens" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text"> 10 M </span></a>' .
					 '<a data-icon-primary="ui-icon-circle-refresh" href="#" style="margin-right:5px;float:right;" class="button pull-left ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary minChanger" alt="0#fifteens" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text"> 15 M </span></a>'.
					 '<a data-icon-primary="ui-icon-circle-refresh" href="#" style="margin-right:5px;float:right;" class="button pull-left ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary minChanger" alt="0#gradeX" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span><span class="ui-button-text"> ALL </span></a>';
		}
		else if($j == 1) {
			$title = "Lab Results";
		}
		else if($j == 2) {
			$title = "DCS Feed Rates";
		}
		else if($j == 3) {
			$title = "Proposed Feed Rates";
		}
		else
			$title = $row["title"];

		echo
		   '<div class="padding clearfix">'.$aTag.'<br/></div><br/>
		   <div  style="'.$heightPx.'" class="grid_'. $gridColVal1.' portlet ui-sortable clearfix padMargin collapsible" draggable="true" id="'.$row["id"].'_'.$j.'">
			<header>
				<h2>
						'.Yii::t('dash',$title).'
				</h2>
			</header>
		      <section id = "section_'.$j.'" class="no-padding clearfix" >';




	   echo '
			<script type="text/javascript" src="'. Yii::app()->theme->baseUrl .'/js/jquery.min_new.js'. '" ></script>
			<script type="text/javascript" src="'. Yii::app()->theme->baseUrl .'/js/jquery.dataTables.min.js'. '" ></script>
			<script type="text/javascript" src="'. Yii::app()->theme->baseUrl .'/js/dataTables.tableTools.min.js'. '" ></script>
		   <table id="example_'.$j.'" style="width:100% !important;" class="customStyle">
			<thead id="thead_'.$j.'">
			</thead>
			<tbody id="tbody_'.$j.'">
			</tbody>
		      </table>' ;

	   echo '
		   <br/><br/>
		   <div class="ui-state-highlight" style="padding-left:5px;font-weight:bold;margin-top:20px;">'.Yii::t('dash','Average(s) for the given Analysis Results Set').'</div>
		   <table id="example_'.$j.'_avg" class="customStyle" style="width:100% !important;height:auto !important;">
			<thead id="thead_'.$j.'_avg">
			</thead>
			<tbody id="tbody_'.$j.'_avg">
			</tbody>
		      </table>' ;

		echo '</section></div>';


	    if(!$jsTIncluded)
	    {
		    $jsTIncluded = 1;
		       echo '
			    <script type="text/javascript">
			      $("header").addClass( function(index, currentClass){
		       var addedClass;
		       if( currentClass === "" )
		       {
			addedClass = "ui-widget-header ui-corner-top";
		       }
		       return addedClass;
		      });
			    </script>
		     ' ;

		}
			$curTime 	= NULL;
			$prevTime	= NULL;
			$tagId		= NULL;
			$tagGrpId	= NULL;
			$tagStat	= NULL;

			if ((isset($_REQUEST['pageType'])) && ($_REQUEST['pageType']=="timeRange") && (isset($_REQUEST['sTime'])) && (isset($_REQUEST['eTime'])))
			{
				
				
				$curTime = strtotime($_REQUEST['eTime']);
				
				$sysTime = strtotime(date("Y-m-d H:i:s"));
				if($sysTime <= $curTime)
					$curTime = $sysTime;				
				
				$curTime = date("Y-m-d H:i:s",$curTime);

				$prevTime = strtotime($_REQUEST['sTime']);
				$prevTime = date("Y-m-d H:i:s",$prevTime);
				$pageType		= "timeRange";
				$postString     = '"pageType":"timeRange", "sTime": "'.$prevTime.'", "eTime" : "'.$curTime.'"';
				
				

			}
			else if (($_REQUEST['pageType']=="tagIndex") && (isset($_REQUEST['tag'])) && (isset($_REQUEST['tagStatus'])))
			{

			$tagId		 	= ($_REQUEST['tag']);
			$tagGrpId     	= ($_REQUEST['tGrp']);
			$tagStat     	= ($_REQUEST['tagStatus']);
				$pageType		= "tagIndex";
			$postString     = '"pageType":"timeRange","tagStatus":"'.$tagStat.'",'.						//"sTime": "'.$curTime.'", "eTime" : "'.$prevTime.'",
							  '"tag":"'.$tagId.'","tGrp":"'.$tagGrpId.'"';
			}

			$uid = Yii::app()->user->id;
			$default = 1;
			//ABHITBD
			$controllerid = $_SERVER["REQUEST_URI"];
			$pos = strpos($controllerid , "rawmix");

			// The !== operator can also be used.  Using != would not work as expected
			// because the position of 'a' is 0. The statement (0 != false) evaluates
			// to false.
			if ($pos !== false) {
			    $default = 4;
			    $uid = 999;
			}
			if (Yii::app()->controller->id == 'dash2') {
				$default = 2;
			} else if (Yii::app()->controller->id == 'dash3') {
				$default = 3;
			}

			$outString = DashHelper::getMeTablesInfo($j, $uid, $pageType,$default, $prevTime, $curTime, $tagId, $tagGrpId, $tagStat);

	    echo '
		 <script type="text/javascript">
			 var stringified = \''.$outString.'\';
	     var SM_callback_'.$j.' = {

	      SM_execute : function(SM_stored_values){
		 var fetchDashboardElements = "fetchDashboardElements";
		 var gadgetType             = "System_Messages";
		 var widgetsPos             = '.$j.';

		  {
		    //alert(stringified);
		    var tableStr = stringified.split("##");
					$("#thead_'.$j.'").html(tableStr[0]);
					$("#tbody_'.$j.'").html(tableStr[1]);

					var oTable'.$j.' = $("#example_'.$j.'").dataTable( {
						"sPaginationType": "full_numbers",
					    "bLengthChange": true,
					    "bFilter": false,
					    "bSort": true,
					    "bInfo": true,
					    "bAutoWidth": true,
					    "bDestroy": true,
						"iDisplayLength": 25,
					    '.$importExportFeature.'
					} );

					$("#thead_'.$j.'_avg").html(tableStr[0]);
					$("#tbody_'.$j.'_avg").html(tableStr[2]);
					oTable'.$j.'.fnSort( [ [0,"desc"] ] );

					var aoTable'.$j.' = $("#example_'.$j.'_avg").dataTable( {
						"bPaginate": false,
					    "bLengthChange": false,
					    "bFilter": false,
					    "bSort": true,
					    "bInfo": false,
					    "bAutoWidth": true,
					    "bDestroy": true,
					    "bheight":"auto",
					} );
					aoTable'.$j.'.fnSort( [ [0,"desc"] ] );

					$(".dataTables_wrapper table tr:first th:first").css("width","160px");
					$("#example_'.$j.'_avg_wrapper").css("height","auto !important");
					$("#example_'.$j.'_avg_wrapper").css("margin-top","20px !important");
					$("#example_'.$j.'_avg_wrapper table tr:first th:first").css("width","160px");

		  }     //success..

		 return true;

	      }

	     }

			  SM_callback_'.$j.'.SM_execute();


			  $(".minChanger").click(function() {
					var rN = $(this).attr("alt");
					var resAr = rN.split("#");
					$("#example_"+resAr[0]+" tr.gradeX").hide();
					$("#example_"+resAr[0]+" tr."+resAr[1]).show();

			  });
	    </script>

	' ;


	     }
	     //end Table gadget..

      } //end $j loop subarray..

    } //end if our $portlets container is not empty..
	 } //end top for $i loop..

	 return true;

	}//end createDisplay_renderLogic()..


	public static function getMeChartsInfo($widgetsPos, $uid, $pageType,$default, $sTime, $eTime, $tag=NULL, $tGrp=NULL, $tagStatus=NULL) {

    $layoutsTable = 'gadlay_layouts';
    $gadgetsDataTable = 'gadlay_gadgets_data';
    $elementsTable = 'gadlay_elements';
    $chartsTable = 'gadlay_charts';
    $tablesTable = 'gadlay_table_elements';


		$get_element = "SELECT
			 g.data_source,
			 g.detector_source,
			 g.display_style,
			 g.group_style,
			 g.lay_id,
			 l.lay_id,
			 l.default_layout
			FROM $gadgetsDataTable AS g
			INNER JOIN $layoutsTable AS l
			 ON l.lay_id = g.lay_id
			WHERE g.widgetsPos = '$widgetsPos'
			 AND l.user_id = $uid
			 AND l.default_layout = $default";
		//echo "getelement:" . $get_element;exit();
		$element_command = Yii::app()->db->createCommand($get_element);

		$element_rs = $element_command->queryAll();
		//echo "read:".print_r($element_rs);
		if (count($element_rs) > 0) {
		    $temp_element_arr = array();
		    foreach ($element_rs as $element_rs_k1 => $element_rs_v1) {
			foreach ($element_rs_v1 as $element_rs_k2 => $element_rs_v2) {
			    if (($element_rs_k2 == 'data_source') || ($element_rs_k2 == 'detector_source') || ($element_rs_k2 == 'display_style') || ($element_rs_k2 == 'group_style')) {
				$temp_element_arr[$element_rs_k2] = $element_rs_v2;
			    }
			}
		    }
		}

		$element_name = $temp_element_arr['data_source'];
		$detector_source = $temp_element_arr['detector_source'];
		$c_display_style = $temp_element_arr['display_style'];
		$c_group_style = $temp_element_arr['group_style'];

		$elemArray = explode(";", $element_name);
		$dispElem = "";
		$cntElem = 0;

		foreach ($elemArray as $elem) {
		    if ($elem) {
				//AS03222015
				//(CaO - 1.65 * Al2O3 - 0.35 * Fe2O3) / (2.80 * sio2)				

				if($elem == "KH")
					$elem = "IF( (CaO <= 0), 0 , ROUND(((CaO - 1.65 * Al2O3 - 0.35 * Fe2O3) / (2.80 * sio2)),3) ) as KH";				
				else if($elem == "LSF")
					$elem = "IF( (CaO <= 0), 0 , ROUND((CaO/(2.8*SiO2+1.18*Al2O3+0.65*Fe2O3) * 100),3) ) as LSF";
				else if ($elem == "C3S")
					$elem = "IF( (CaO <= 0), 0 , ROUND((4.07*CaO - 7.6*SiO2 - 6.72*Al2O3 - 1.43*Fe2O3 - 2.852*SO3 ),3) ) as C3S";
				//else if ($elem == "NAEQ")
				//	$elem = "IF( (K2O <= 0), 0 , ROUND((K2O * 6.58 + Na2O ),3) ) as NAEQ";//NAEQ Equation Correction JW160412
				else if ($elem == "NAEQ")
					$elem = "IF( (K2O <= 0), 0 , ROUND((K2O * 0.658 + Na2O ),3) ) as NAEQ";
				else if ($elem == "SM")
					$elem = "IF( (SiO2 <= 0), 0 , ROUND((SiO2/(Al2O3+Fe2O3)),3) ) as SM";
				else if ($elem == "IM")
					$elem = "IF( (Al2O3 <= 0), 0 , ROUND((Al2O3/Fe2O3),3) ) as IM";
				else
					$elem = "`$elem`";

				$dispElem .= ",$elem";
				$cntElem++;
		    }
		}


		$top_arr = array();
		$ind = 0;
		$elem_list = explode(";", $element_name);

		foreach ($elem_list as $eval) {
		    if (!empty($eval)) {
			$top_arr[$eval] = array();
		    }//set eval
		}

		//date_default_timezone_set('America/Los_Angeles');
		//TODO Change TIMERANGE
		    if (($pageType == "timeRange") && (isset($sTime)) && (isset($eTime))) {
			$prevTime = ($sTime);
			$curTime = ($eTime);
		    } else if (($pageType == "tagIndex") && (isset($tag)) && (isset($tagStatus))) {

			$tagId = ($tag);
			$tagGrpId = ($tGrp);
			$tagStat = ($tagStatus);

			if ($tagStat == "queued") {
			    $tagStatTable = "rta_tag_index_" . $tagStat;
			} else {
			    $tagStatTable = "rta_tag_index_completed";
			}

			$tGrpSet = 1;
		    } else {
		    //TODO Change TIMERANGE
		    $curTime = date("Y-m-d H:i:s");
		    $curTimeSt = strtotime($curTime);

		    $prevTimeSt = $curTimeSt - (60 * 60); //60 mins worth Information
		    $prevTime = date("Y-m-d H:i:s", $prevTimeSt);
		}

		//Selecting Tagged Data and Take Out Limiting
		if ($tGrpSet) {
		    //Get the analysis Table Name
		    $tableNameQuery = "SELECT DB_ID_string,LocalstartTime as sTime,LocalendTime as eTime FROM `rta_config_master` rcm WHERE rcm.`rtaMasterID` IN (SELECT tag.`rtaMasterID` FROM $tagStatTable tag WHERE tagID=$tagId)";
		    $tcommand = Yii::app()->db->createCommand($tableNameQuery)->queryRow();

		    $analysisTableName = "analysis_" . $tcommand['DB_ID_string'];
		    $prevTime = $tcommand['sTime'];
		    $curTime = $tcommand['eTime'];

		    $cntRows = Yii::app()->db->createCommand("SELECT count(dataID) as cnt FROM `$analysisTableName`")->queryRow();

		    if ($cntRows["cnt"] > 0) {
			$query = "SELECT `LocalendTime` $dispElem FROM `$analysisTableName` WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC"; // LIMIT 100
		    } else {
			$outData = "ERR::";
			return $outData;
		    }
		} else {
		    //Abhinandan. Feb26th: Left off here.. NEED to fetch from db and then pass back..
		    //$query = "SELECT `created`, `Calcium` FROM `Detector-1` ";             //Abhinandan. made `Detector-1` uppdercase D because Linux @ castor is sensitive and needs this uppercase D.
		    $query = "SELECT `LocalendTime` $dispElem FROM `$detector_source` WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC"; // LIMIT 100
		}

		//if ($c_group_style == "hgrouped")
		//    $query .= " LIMIT 10";

		//$query = "SELECT `LocalendTime` ,`Al2O3`,`SiO2`,`Fe2O3` FROM `Analysis_detector_1` WHERE LocalendTime".
		//			" BETWEEN( '2014-01-12 01:07:44') AND ('2014-01-17 02:07:44') ORDER BY LocalendTime ASC LIMIT 1500";

		$command = Yii::app()->db->createCommand($query);

		//echo $query."<br/>";
		$rs = $command->queryAll(); ////

		$num_rows   = 0;
		$minTimeStamp = 0;
		$maxTimeStamp = 0;
		if (count($rs) > 0) {
		    $temp_arr = array();
		    foreach ($rs as $rs_k1 => $rs_v1) {

			foreach ($rs_v1 as $rs_k2 => $rs_v2) {
			    //debugg..

			    if ($c_group_style == "individual") { //TODO
				$key = 1;   //Individual tag
			    } else
				$key = array_search($rs_k2, $elemArray); // $key = 2;


							//if($rs_k2 == 'endTic')
			    if ($rs_k2 == 'LocalendTime') {
				//$temp_arr[$rs_k1][1] = $rs_v2;
				$rs_v2 = strtotime($rs_v2);     //Date to time:TIMERANGE
				$temp_arr[$rs_k1][$rs_k2] = $rs_v2 * 1000;
				$lastTimeStamp = $rs_v2 * 1000;

				if($minTimeStamp == 0)$minTimeStamp = $lastTimeStamp;

				$minTimeStamp = ($lastTimeStamp <= $minTimeStamp)?$lastTimeStamp:$minTimeStamp;
				$maxTimeStamp = ($lastTimeStamp >= $maxTimeStamp)?$lastTimeStamp:$maxTimeStamp;
			    } else {
				$temp_arr[$rs_k1][$rs_k2] = $rs_v2;       //Abhinandan. need to multiply x 1000 @ client now..

				if ($c_group_style == "hgrouped") {
				    $top_arr[$rs_k2][$rs_k1] = array($lastTimeStamp, $rs_v2); //Send the Values Directly.
				}
				else if ($c_display_style == "aspline") {
				    $top_arr[$rs_k2][$rs_k1] = array($lastTimeStamp, $rs_v2); //Send the Values Directly.
				}
				else if ($c_display_style == "daspline") {
				    //Date.UTC(1970,9,18, 10, 00)
				    $UTC_date_string = "Date.UTC()";
				    if($lastTimeStamp > 0){
					$lastTimeStamp = $lastTimeStamp / 1000;
					$dateString    = Date("Y,m,d,H,i",$lastTimeStamp);
					$UTC_date_string = "Date.UTC($dateString)";
				    }
				    $top_arr[$rs_k2][$rs_k1] = array($UTC_date_string, $rs_v2);
				}else {
				    $top_arr[$rs_k2][$rs_k1] = $rs_v2;
				}
				$num_rows++;
			    }
			}
		    }
		} else if (count($rs) <= 0) {
		    $outData = "ERR::";
		    return $outData;
		}
		
		$sp_name_val = str_replace("`","",$dispElem);
		$sp_name_val = str_replace(",","",$sp_name_val);
		
		$last3 = substr($sp_name_val, -3, 3);
		$last4 = substr($sp_name_val, -4, 4);
		$last2 = substr($sp_name_val, -2, 2);

		if($last2 == "SM" || $last2 == "IM")
			$sp_name_val = $last2;
		if($last3 == "LSF" || $last3 == "C3S" || $last3 == "KH" )
			$sp_name_val = $last3;
		if($last4 == "NAEQ")
			$sp_name_val = $last4;		
		
		$sp_graph_query = " SELECT * FROM `rm_set_points` where sp_name='{$sp_name_val}' "; // LIMIT 100
		$sp_command = Yii::app()->db->createCommand($sp_graph_query);
		$sp_resultAr = $sp_command->queryAll(); ////
		$temp_arr = array();
		$cur_sp_value = 1;
		
		foreach ($sp_resultAr as $rs_k1 => $rs_v1) {
			$cur_sp_value  = (float)round($rs_v1["sp_value_num"],2);
		}
		
		//Adding Set-Points graph
		if($c_display_style == "ddsimple" && ($num_rows > 0) )  {
			
		    $query_firstTimeStamp = ($minTimeStamp > 0)? ($minTimeStamp/ 1000):0;
		    $query_lastTimeStamp  = ($maxTimeStamp > 0)? ($maxTimeStamp/ 1000):0;

		    //echo " First : ". $query_firstTimeStamp . " + 0 <br/>";
		    //echo " Last : ". $query_lastTimeStamp . " + $num_rows <br/>";

		    $query_firstTimeStamp   = Date("Y-m-d H:i:00",$query_firstTimeStamp);
		    $query_lastTimeStamp    = Date("Y-m-d H:i:00",$query_lastTimeStamp);

		    //echo " First : ". $query_firstTimeStamp . " + 0 <br/>";
		    //echo " Last : ". $query_lastTimeStamp . " + $num_rows <br/>";
		    //print_r($top_arr[$sp_name_val]);

		    $sp_graph_query = " (SELECT `sp_updated` as LocalendTime, sp_name, sp_value, 1 as FirstRow".
				      " FROM `rm_set_points_log` WHERE sp_name='{$sp_name_val}' AND ".
				      " sp_updated <= ( '$query_firstTimeStamp') ORDER BY sp_updated DESC LIMIT 1)".
				      " UNION ".
				      " (SELECT `sp_updated` as LocalendTime, sp_name, sp_value, 0 as FirstRow".
				      " FROM `rm_set_points_log` WHERE sp_name='{$sp_name_val}' AND ".
				      " sp_updated BETWEEN( '$query_firstTimeStamp') AND ('$query_lastTimeStamp') ORDER BY sp_updated ASC)"; // LIMIT 100
		    $sp_command = Yii::app()->db->createCommand($sp_graph_query);
		    $sp_resultAr = $sp_command->queryAll(); ////
		    //echo $sp_graph_query;
			
		    $FirstRowSetFlag = 0;

		    if (count($sp_resultAr) > 0) {
				$temp_arr = array();
				foreach ($sp_resultAr as $rs_k1 => $rs_v1) {

					$row_timeStamp = $rs_v1["LocalendTime"];
					$row_sp_name   = $rs_v1["sp_name"] . "-SP"; //Add _SP to elementName
					$row_sp_value  = $rs_v1["sp_value"];

					if($rs_v1["FirstRow"] == 1){
					$FirstRowSetFlag = 1;
					$row_timeStamp = strtotime($query_firstTimeStamp);     //Date to time:TIMERANGE
					$lastTimeStamp = $row_timeStamp * 1000;
					$top_arr[$row_sp_name][0] = array($lastTimeStamp, $row_sp_value);
					}else {
					$row_timeStamp = strtotime($row_timeStamp);     //Date to time:TIMERANGE
					$lastTimeStamp = $row_timeStamp * 1000;
					$temp_arr[$row_sp_name][$rs_k1] = array($lastTimeStamp, $row_sp_value);
					}

				}//foreach
		    }//if count
			else {
				$sp_graph_query = " SELECT * FROM `rm_set_points` where sp_name='{$sp_name_val}' "; // LIMIT 100
				$sp_command = Yii::app()->db->createCommand($sp_graph_query);
				$sp_resultAr = $sp_command->queryAll(); ////
				$temp_arr = array();
				$cur_sp_value = 0;
				
				foreach ($sp_resultAr as $rs_k1 => $rs_v1) {

					$row_timeStamp = $rs_v1["LocalendTime"];
					$row_sp_name   = $rs_v1["sp_name"] . "-SP"; //Add _SP to elementName
					$row_sp_value  = $rs_v1["sp_value_num"];
					$cur_sp_value =  $row_sp_value;
					if($rs_v1["FirstRow"] == 1){
					$FirstRowSetFlag = 1;
					$row_timeStamp = strtotime($query_firstTimeStamp);     //Date to time:TIMERANGE
					$lastTimeStamp = $row_timeStamp * 1000;
					$top_arr[$row_sp_name][0] = array($lastTimeStamp, $row_sp_value);
					}else {
					$row_timeStamp = strtotime($row_timeStamp);     //Date to time:TIMERANGE
					$lastTimeStamp = $row_timeStamp * 1000;
					$temp_arr[$row_sp_name][$rs_k1] = array($lastTimeStamp, $row_sp_value);
					}

				}//foreach
			}

		    /*
		    echo "<br/><br/>";
		    foreach($temp_arr as $elem=>$elemAr){
			echo $elem . " --- <br/>";
			foreach($elemAr as $varr){
			    print_r($varr);
			    echo "<br/>";
			}
		    }
		    */

		    if(!$FirstRowSetFlag){
			$FirstRowSetFlag = 1;
			$row_timeStamp = strtotime($query_firstTimeStamp);     //Date to time:TIMERANGE
			$lastTimeStamp = $row_timeStamp * 1000;

			$top_arr[$sp_name_val . "-SP"][0] = array($lastTimeStamp, 1);
		    }

		    if(count($temp_arr) <= 0){
			$row_timeStamp = strtotime($query_lastTimeStamp);     //Date to time:TIMERANGE
			$lastTimeStamp = $row_timeStamp * 1000;
			$top_arr[$sp_name_val . "-SP"][1] = array($lastTimeStamp, 1);
		    }

		    $rowCounter = 1;
		    foreach ($temp_arr as $elemn => $valAr){
			foreach($valAr as $index=>$subValAr){
			    $timeSt = $subValAr[0];
			    $spval  = $subValAr[1];
			    //Get the Previous timestamp array sp_value
			    $prevTimeStampValue = $top_arr[$sp_name_val . "-SP"][$rowCounter-1][1];
			    //Go back 1 minute and add the previous set point value
			    $top_arr[$sp_name_val . "-SP"][$rowCounter] = array($timeSt - 60000, $prevTimeStampValue);
			    //echo " $rowCounter : " . ($timeSt - 60000) . " = $prevTimeStampValue <br/>";
			    $rowCounter++;
			    $top_arr[$sp_name_val . "-SP"][$rowCounter] = array($timeSt, $spval);
			    //echo " $rowCounter : " . ($timeSt) . " = $spval <br/>";
			    $rowCounter++;
			}
		    }

		    //Put the last point where the last point for the graph is.
		    $top_arr[$sp_name_val . "-SP"][$rowCounter] = array($maxTimeStamp, $spval);
		    /*
		    echo "<br/><br/>";
		    foreach($top_arr as $elem=>$elemAr){
			echo $elem . " --- <br/>";
			foreach($elemAr as $varr){
			    print_r($varr);
			    echo "<br/>";
			}
		    }
		    */
		}//if c_display_style


		$outData = "SUC::cstyle:$c_display_style##gstyle:$c_group_style##elemName:$element_name";
		$outData .="##timeStart:" . strtotime($prevTime) . "##timeEnd:" . strtotime($curTime);
		$outData .="##cur_sp_value:" .  ($cur_sp_value) . "";
		foreach ($top_arr as $elemn => $vall) {
		    $outData .="##" . $elemn . "**" . str_replace("\"", "", CJSON::encode($vall)) . "";
		}		
		
		return $outData;
	}//getMeChartsInfo
	
	public static function getMeTablesInfo($widgetsPos, $uid, $pageType,$default, $sTime, $eTime, $tag=NULL, $tGrp=NULL, $tagStatus=NULL) {

	    //TODO Change TIMERANGE
	    $curTime = date("Y-m-d H:i:s");
	    $curTimeSt = strtotime($curTime);

	    $prevTimeSt = $curTimeSt - (60 * 60); //60 mins worth Information
		$prevTime = date("Y-m-d H:i:s", $prevTimeSt);
		
		//Get source information to change src_X values to actual source names
		$srcArray = array();

		if(isset($_REQUEST["simulate"])) {
			$sourceTable = "rm_source_sim";
		}else
			$sourceTable = "rm_source";

		if($debugFlag)
			echo $g_currenTime;
		
		if($testing) {
			$query =  "SELECT src_id,src_name FROM $sourceTable WHERE product_id=1";
		}else {
			$query =  "SELECT src_id,src_name FROM $sourceTable WHERE product_id=1";
		}
		$tcommand = Yii::app()->db->createCommand($query);
		$tresult  = $tcommand->query()->readAll();

		if(count($tresult)> 0){
			foreach($tresult as $roAr) {
				$srcArray[$roAr["src_id"]]=$roAr["src_name"];
			}
		}

	    //Abhinandan. March12th.. Future TODO: Dave you will want to set a global 'detector' variable being passed from the client.. this way we do not have to statically assign the detector name in the SQL query..
	    //$get_detector = "SELECT detector_source FROM gadlay_gadgets_data WHERE widgetsPos = $widgetsPos ";

	    $select_current_layout = Yii::app()->db->createCommand("SELECT lay_id FROM gadlay_layouts WHERE user_id = $uid AND default_layout = $default")->queryRow();

	    $lay_id = $select_current_layout['lay_id'];

	    $get_detector = "SELECT data_source,
		     detector_source,
		     display_style,
		     group_style
					FROM gadlay_gadgets_data WHERE widgetsPos = $widgetsPos AND lay_id = $lay_id ";
			//echo $get_detector;
	    $element_command = Yii::app()->db->createCommand($get_detector);

	    $element_rs = $element_command->queryAll();

	    if (count($element_rs) > 0) {
		$temp_element_arr = array();
		foreach ($element_rs as $element_rs_k1 => $element_rs_v1) {
		    foreach ($element_rs_v1 as $element_rs_k2 => $element_rs_v2) {
			if (($element_rs_k2 == 'data_source') || ($element_rs_k2 == 'detector_source') || ($element_rs_k2 == 'display_style') || ($element_rs_k2 == 'group_style')) {
			    $temp_element_arr[$element_rs_k2] = $element_rs_v2;
			}
		    }
		}
	    }

	    $element_name = $temp_element_arr['data_source'];
	    $detector_source = $temp_element_arr['detector_source'];
	    $c_display_style = $temp_element_arr['display_style'];
	    $c_group_style = $temp_element_arr['group_style'];

	    $elemArray = explode(";", $element_name);
	    $dispElem = "";
	    $cntElem = 0;

	    foreach ($elemArray as $elem) {
			if ($elem) {
				//AS03222015
				if($elem == "KH")
					$elem = "IF( (CaO <= 0), 0 , ROUND(((CaO - 1.65 * Al2O3 - 0.35 * Fe2O3) / (2.80 * sio2)),3) ) as KH";				
				else if($elem == "LSF")
					$elem = "IF( (CaO <= 0), 0 , ROUND((CaO/(2.8*SiO2+1.18*Al2O3+0.65*Fe2O3) * 100),3) ) as LSF";
				else if ($elem == "C3S")
					$elem = "IF( (CaO <= 0), 0 , ROUND((4.07*CaO - 7.6*SiO2 - 6.72*Al2O3 - 1.43*Fe2O3 - 2.852*SO3 ),3) ) as C3S";
				//else if ($elem == "NAEQ")
				//	$elem = "IF( (K2O <= 0), 0 , ROUND((K2O * 6.58 + Na2O ),3) ) as NAEQ";//NAEQ Equation Correction JW160412
				else if ($elem == "NAEQ")
					$elem = "IF( (K2O <= 0), 0 , ROUND((K2O * 0.658 + Na2O ),3) ) as NAEQ";
				else if ($elem == "SM")
					$elem = "IF( (SiO2 <= 0), 0 , ROUND((SiO2/(Al2O3+Fe2O3)),3) ) as SM";
				else if ($elem == "IM")
					$elem = "IF( (Al2O3 <= 0), 0 , ROUND((Al2O3/Fe2O3),3) ) as IM";
				else
					$elem = "`$elem`";

				$dispElem .= ",$elem";
				$cntElem++;
		    }
	    }


	    $ind = 0;
	    $elem_list = explode(";", $element_name);
	    $theadStr = '<tr>';
	    $tbodyStr = '';

	    $widthStr = floor(100 / (count($elem_list) + 1));
	    $theadStr .='<th class="ui-state-default" style="width:' . $widthStr . '%;" ><div class="DataTables_sort_wrapper" >Time-Stamp</div></th>';

	    foreach ($elem_list as $eval) {
		if (!empty($eval)) {
			if(substr($eval, -2,2) == "_m") 
				$suff = "_m";
			else if(substr($eval, -3,3) == "_sp") 
				$suff = "_sp";

			if(substr($eval, 0, 4) == "src_"){
				$tsrcId = substr($eval, 4, 1);
				$eval = (isset($srcArray[$tsrcId]))?$srcArray[$tsrcId]:$eval;
			}
			
		    $theadStr .='<th class="ui-state-default" style="width:' . $widthStr . '%;" ><div class="DataTables_sort_wrapper" >' . $eval. $suff.  '</div></th>';
		}//set eval
	    }
	    $theadStr .='</tr>';

	    //'System_Messages'
	    //date_default_timezone_set('America/Los_Angeles');

	    if (($pageType == "timeRange") && (isset($sTime)) && (isset($eTime))) {
		$prevTime = ($sTime);
		$curTime = ($eTime);
	    } else if (($pageType == "tagIndex") && (isset($tag)) && (isset($tagStatus))) {

		$tagId = ($tag);
		$tagGrpId = ($tGrp);
		$tagStat = ($tagStatus);

		if ($tagStat == "queued") {
		    $tagStatTable = "rta_tag_index_" . $tagStat;
		} else {
		    $tagStatTable = "rta_tag_index_completed";
		}

		$tGrpSet = 1;
	    }

	    //echo $curTime . " : " . $prevTime;exit();
	    //Selecting Tagged Data and Take Out Limiting
	    if ($tGrpSet) {
		//Get the analysis Table Name
		$tableNameQuery = "SELECT DB_ID_string,LocalstartTime as sTime,LocalendTime as eTime FROM `rta_config_master` rcm WHERE rcm.`rtaMasterID` IN (SELECT tag.`rtaMasterID` FROM $tagStatTable tag WHERE tagID=$tagId)";
		$tcommand = Yii::app()->db->createCommand($tableNameQuery)->queryRow();

		$analysisTableName = "analysis_" . $tcommand['DB_ID_string'];
		$prevTime = $tcommand['sTime'];
		$curTime = $tcommand['eTime'];

		$cntRows = Yii::app()->db->createCommand("SELECT count(dataID) as cnt FROM `$analysisTableName`")->queryRow();

		if ($cntRows["cnt"] > 0) {
		    $query = "SELECT `LocalendTime` $dispElem FROM `$analysisTableName` WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC";
		} else {
		    $outData = "ERR::";
		    return $outData;
		}
	    }else if($default == 3) {
			
		     $query = "SELECT `updated` $dispElem FROM `$detector_source` WHERE updated BETWEEN( '$prevTime') AND ('$curTime') ORDER BY updated ASC";
		}
		else {
		     //Abhinandan. Feb26th: Left off here.. NEED to fetch from db and then pass back..
		     $query = "SELECT `LocalendTime` $dispElem FROM `$detector_source` WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC";
	    }
	    $command = Yii::app()->db->createCommand($query, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY);

	    $rs = $command->queryAll();

	    if (count($rs) > 0) {
		$rowCntr = 0;
				$avgArr  = array();
				$avgInd  = array();
		foreach ($rs as $rs_k1 => $rs_v1) {
		    $rowCntr++;

			if ($rowCntr % 60 == 0)
			$classTag = 'gradeX odd fives tens fifteens twenties thirties';
		    else if ($rowCntr % 30 == 0)
			$classTag = 'gradeX odd fives tens fifteens thirties';
		    else if ($rowCntr % 15 == 0)
			$classTag = 'gradeX odd fives tens fifteens';
		    else if ($rowCntr % 10 == 0)
			$classTag = 'gradeX even fives tens';
		    else if ($rowCntr % 5 == 0)
			$classTag = 'gradeX odd fives';
		    else if ($rowCntr % 2 == 0)
			$classTag = 'gradeX even';
		    else
			$classTag = 'gradeX odd';

		    $tbodyStr .= '<tr class="'.$classTag.'">';

		    if (is_array($rs_v1)) {

			foreach ($rs_v1 as $rs_k2 => $rs_v2) {

				if(!isset($avgArr[$rs_k2])){
					$avgArr[$rs_k2]=0;
				$avgInd[$rs_k2]=0;
				}
				if($rs_v2 == ''){
					$rs_v2 = 0;
				}

			    if ($rs_k2 == 'LocalendTime' || $rs_k2 == 'updated' ) {
								$date = new DateTime($rs_v2);
								$trs_v2 = $date->format('m/d/y H:i');
				//$trs_v2 = date("m/d/y h:i:s",$rs_v2);
				$tbodyStr .= '<td style="width: 160px;">' . $trs_v2 . '</td>';
			    } else if ($rs_v2 != ' ') {
				$tbodyStr .= '<td >' . $rs_v2 . '</td>';

				if($rs_v2 > 0 && isset($avgArr[$rs_k2])){
					$avgArr[$rs_k2] = $avgArr[$rs_k2] + $rs_v2;
					$avgInd[$rs_k2] = $avgInd[$rs_k2]+1;
								}
			    }else {
				$tbodyStr .= '<td >0.000</td>';
			    }
			}
			$tbodyStr .= '</tr>';
		    }
		}
	    }

	    $outPutVar = $theadStr . "##" . $tbodyStr. "##";

			if(count($avgArr) > 0) {
		    $tavgbodyStr = '<tr class="gradeX odd">';
				$date = new DateTime($prevTime);
				$trs_p = $date->format('m/d/y H:i:s');
				$date = new DateTime($curTime);
				$trs_c = $date->format('m/d/y H:i:s');
		     //Abhi changed from previous to current time
		    $tavgbodyStr .= '<td style="width: 160px;">' . $trs_p . " To <br/>". $trs_c . '</td>';

		    foreach($avgArr as $id=>$va){
			if($avgInd[$id] > 0)
			{
				$tString = round(($va/$avgInd[$id]),3);
				    $tavgbodyStr .= '<td >' . $tString . '</td>';
			}else if($id != "LocalendTime" && $id != "updated" ) {
				$tavgbodyStr .= '<td >-</td>';
			}
		    }
				$tavgbodyStr .= '</tr>';
				$outPutVar .= $tavgbodyStr;
			}
	    return ( $outPutVar );

	}

	 /*
	 *  Method:  array_push_associative()
	 *   Purpose: Trap $arr, and continue to populate it with elements from the $sitemsArray array..
	 *   $arr  array  The very first time, $arr is empty array..
	 *               Afterwards, it continues to receive multiple stuffings..
	  *
	  *  $args array  Represents a numerically indexed array of the parameters being passed into the method:
	  *   i.)  0 => An archive of data that has already been pushed to $arr
	  *   ii.) 1 => The freshest, most current element before it is pushed to $arr
	 *
	 */
	public static function array_push_associative(&$arr)
	{
	 $ret = true;
	 $args = func_get_args();

	 /* //Debugg only..
	 Controller::fb('start');
	  Controller::fb($arr);
	  Controller::fb($args);
	 Controller::fb('end');
	 */

	 foreach($args as $arg){
	  if( is_array($arg) )
	  {
	   foreach($arg as $key => $value){
	    $arr[$key] = $value;
	    $ret++;
	   }
    }else{
       $arr[$arg] = "";
    }
   }//end top foreach..

   return $ret;

	}//end array_push_associative()..


	public static function showDump($disp_string,$var = NULL)
	{
		if(!DEBUG_ON) return;

		if(php_sapi_name() == 'cli' || PHP_SAPI == 'cli'){
			$separator = "\n";
		} else {
			$separator = "<br/>";
		}

		if($var != NULL) {
			 echo "-------------------------------------".$separator;
			echo "{$disp_string}".$separator;
			if(is_array($var)) {
			 echo "-------------------------------------".$separator;
				foreach($var as $id=>$val) {
					echo "--> " . $id . " :" . $val . $separator;

					if($id == "elements" || $id == "m_feedRates" || $id == "c_analysisResults")
						$justDump = 0;

					if(is_array($val)) {
						foreach($val as $idd=>$vall) {
							echo "------> " . $idd . " :" . $vall . $separator;
							if(is_array($vall)) {
								if($justDump) {
									echo "--------------". $separator;
									foreach($vall as $iddd=>$valll) {
										echo "" . $iddd . " :" . $valll . ";;";
									}//foreach
									echo $separator ."--------------". $separator;
								}
								else {
									foreach($vall as $iddd=>$valll) {
										echo "-------------->>> " . $iddd . " :" ;
										if(is_array($valll)) {
											print_r($valll);
										}else
										echo $valll ;
										echo $separator;
									}//foreach
								}//else
							}//count vall
						}//foreach val
						$justDump = 0;

					}//if count val
				}//foreach var
			 echo "-------------------------------------".$separator;
			}//if count var
			else
				echo $var;
			echo $separator;
		}
		else
			echo "{$disp_string}".$separator;
	}

	public static function getMeIndChartAvgInfo($element_name, $givenTime="") {
		$c_display_style = "simple";
		$c_group_style	 = "individual";		


		if(strtotime($givenTime) > 0){
			$curTime 		 = $givenTime;
			$curTimeSt 		 = strtotime($givenTime);
			$temp_curTimest  = $curTimeSt;
			$curTimeSt 	 	 = $curTimeSt + (60 * 60); //Go back 15 mins worth Information
			$curTime 		 = date("Y-m-d H:i:s", $curTimeSt);

			$prevTimeSt 	 = $temp_curTimest - (600 * 60); //60 mins worth Information
			$prevTime 		 = date("Y-m-d H:i:s", $prevTimeSt);
		}else {
			$curTime 		 = date("Y-m-d H:i:s");
			$curTimeSt 		 = strtotime($curTime);

			$prevTimeSt 	 = $curTimeSt - (600 * 60); //60 mins worth Information
			$prevTime 		 = date("Y-m-d H:i:s", $prevTimeSt);
		}

		//$query 	 = "SELECT LocalendTime, $element_name FROM $detector_source WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC";
		$query 	 = "SELECT rm_updated, rm_set_points, rm_input_analysis FROM rm_inputoutputdump WHERE rm_updated BETWEEN( '$prevTime') AND ('$curTime') ORDER BY rm_updated ASC";
		$command = Yii::app()->db->createCommand($query);

		//echo $query;
		$rs = $command->queryAll(); ////

		if (count($rs) > 0) {
			$temp_arr = array();
			foreach ($rs as $rs_k1 => $rs_v1) {
				
				$unserEl = (unserialize($rs_v1["rm_input_analysis"]));
				$unserSp = (unserialize($rs_v1["rm_set_points"]));
				$time	 = $rs_v1["rm_updated"];
				
				if(count($unserEl) > 0){
					$unserEl = $unserEl["Averages"];
				}
				
				//print_r($unserEl);echo "<br/>";echo "<br/>";
				
				//print_r($unserSp);echo "<br/>";echo "<br/>";
				$newArr = array('LocalendTime'=> $time,
								"$element_name"=> $unserEl[$element_name],
								"$element_name"."-SP" => $unserSp[$element_name]);
				//print_r($newArr);
				//exit();
				foreach ($newArr as $rs_k2 => $rs_v2) {
					//debugg..

					if ($c_group_style == "individual") { //TODO
						$key = 1;   //Individual tag
					} else
						$key = array_search($rs_k2, $elemArray); // $key = 2;


					//if($rs_k2 == 'endTic')
					if ($rs_k2 == 'LocalendTime') {
						//$temp_arr[$rs_k1][1] = $rs_v2;
						$rs_v2 = strtotime($rs_v2);     //Date to time:TIMERANGE
						$temp_arr[$rs_k1][$rs_k2] = $rs_v2 * 1000;
						$lastTimeStamp = $rs_v2 * 1000;
					} else {
						$temp_arr[$rs_k1][$rs_k2] = $rs_v2;       //Abhinandan. need to multiply x 1000 @ client now..

						if ($c_group_style == "hgrouped") {
							$top_arr[$rs_k2][$rs_k1] = array($lastTimeStamp, $rs_v2); //Send the Values Directly.
						} else {
							$top_arr[$rs_k2][$rs_k1] = array($lastTimeStamp, $rs_v2); //Send the Values Directly.
						}
					}
				}
			}
		} else if (count($rs) <= 0) {
			$outData = "ERR::";

			return $outData;
		}

		$outData = "SUC::cstyle:$c_display_style##gstyle:$c_group_style##elemName:$element_name";
		$outData .="##timeStart:" . strtotime($prevTime) . "##timeEnd:" . strtotime($curTime);

		foreach ($top_arr as $elemn => $vall) {
			$outData .="##" . $elemn . "**" . str_replace("\"", "", CJSON::encode($vall)) . "";
		}
		//print_r($outData);

		return $outData;
	}//getMeIndChartAvgInfo

	public static function getMeIndChartInfo($element_name, $givenTime="", $sim=0) {
		$c_display_style = "simple";
		if($sim) {
			$detector_source = "analysis_a1_a2_blend_sim";
		}else {
			$detector_source = "analysis_A1_A2_Blend";
		}
		$c_group_style	 = "individual";

		if(strtotime($givenTime) > 0){
			$curTime 		 = $givenTime;
			$curTimeSt 		 = strtotime($givenTime);
			$temp_curTimest  = $curTimeSt;
			$curTimeSt 	 	 = $curTimeSt + (60 * 60); //Go back 15 mins worth Information
			$curTime 		 = date("Y-m-d H:i:s", $curTimeSt);

			$prevTimeSt 	 = $temp_curTimest - (15 * 60); //60 mins worth Information
			$prevTime 		 = date("Y-m-d H:i:s", $prevTimeSt);
		}else {
			$curTime 		 = date("Y-m-d H:i:s");
			$curTimeSt 		 = strtotime($curTime);

			$prevTimeSt 	 = $curTimeSt - (60 * 60); //60 mins worth Information
			$prevTime 		 = date("Y-m-d H:i:s", $prevTimeSt);
		}

		
		if ($element_name) {
			if($element_name == "KH")
				$element_name = "IF( (CaO <= 0), 0 , ROUND(((CaO - 1.65 * Al2O3 - 0.35 * Fe2O3) / (2.80 * sio2)),3) ) as KH";				
			else if($element_name == "LSF")
				$element_name = "IF( (CaO <= 0), 0 , ROUND((CaO/(2.8*SiO2+1.18*Al2O3+0.65*Fe2O3) * 100),3) ) as LSF";
			else if ($element_name == "C3S")
				$element_name = "IF( (CaO <= 0), 0 , ROUND((4.07*CaO - 7.6*SiO2 - 6.72*Al2O3 - 1.43*Fe2O3 - 2.852*SO3 ),3) ) as C3S";
			//else if ($element_name == "NAEQ")
			//	$element_name = "IF( (K2O <= 0), 0 , ROUND((K2O * 6.58 + Na2O ),3) ) as NAEQ";//NAEQ Equation Correction JW160412
			else if ($element_name == "NAEQ")
				$element_name = "IF( (K2O <= 0), 0 , ROUND((K2O * 0.658 + Na2O ),3) ) as NAEQ";
			else if ($element_name == "SM")
				$element_name = "IF( (SiO2 <= 0), 0 , ROUND((SiO2/(Al2O3+Fe2O3)),3) ) as SM";
			else if ($element_name == "IM")
				$element_name = "IF( (Al2O3 <= 0), 0 , ROUND((Al2O3/Fe2O3),3) ) as IM";
			else
				$element_name = "`$element_name`";
		}

		$query 	 = "SELECT LocalendTime, $element_name FROM $detector_source WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC";
		$command = Yii::app()->db->createCommand($query);

		//echo $query;
		$rs = $command->queryAll(); ////

		if (count($rs) > 0) {
			$temp_arr = array();
			foreach ($rs as $rs_k1 => $rs_v1) {

				foreach ($rs_v1 as $rs_k2 => $rs_v2) {
					//debugg..

					if ($c_group_style == "individual") { //TODO
						$key = 1;   //Individual tag
					} else
						$key = array_search($rs_k2, $elemArray); // $key = 2;


					//if($rs_k2 == 'endTic')
					if ($rs_k2 == 'LocalendTime') {
						//$temp_arr[$rs_k1][1] = $rs_v2;
						$rs_v2 = strtotime($rs_v2);     //Date to time:TIMERANGE
						$temp_arr[$rs_k1][$rs_k2] = $rs_v2 * 1000;
						$lastTimeStamp = $rs_v2 * 1000;
					} else {
						$temp_arr[$rs_k1][$rs_k2] = $rs_v2;       //Abhinandan. need to multiply x 1000 @ client now..

						if ($c_group_style == "hgrouped") {
							$top_arr[$rs_k2][$rs_k1] = array($lastTimeStamp, $rs_v2); //Send the Values Directly.
						} else {
							$top_arr[$rs_k2][$rs_k1] = $rs_v2;
						}
					}
				}
			}
		} else if (count($rs) <= 0) {
			$outData = "ERR::";

			return $outData;
		}

		$outData = "SUC::cstyle:$c_display_style##gstyle:$c_group_style##elemName:$element_name";
		$outData .="##timeStart:" . strtotime($prevTime) . "##timeEnd:" . strtotime($curTime);

		foreach ($top_arr as $elemn => $vall) {
			$outData .="##" . $elemn . "**" . str_replace("\"", "", CJSON::encode($vall)) . "";
		}

		return $outData;
	}//getMeIndChartInfo

	public static function getMeIndvChart($elementName ="", $jterm = 0, $givenTime="", $sim=0) {
	   $j = 3333 + $jterm;
		echo '<div class="grid_3 portlet ui-sortable clearfix padMargin collapsible" draggable="true" id="GraphCont_'.$j.'">
			<header>
				<h2>
							'.Yii::t('dash','Charts').'
				</h2>
			</header>
			<section id = "section_'.$j.'"> 
			 <div class="ui-widget portlet-content">
				<div id="containerHC_'.$j.'" style="min-width: auto; height: 160px; margin: 0 auto;width:100%;"></div>
			 </div>
			</section>
			</div>' ;

	    if(!$jsIncluded)
	    {
		    $jsIncluded = 1;
		echo '
		 <script type="text/javascript">
		  $("header").addClass( function(index, currentClass){
		       var addedClass;
		       if( currentClass === "" )
		       {
			addedClass = "ui-widget-header ui-corner-top";
		       }
		       return addedClass;
		      });
		 </script>
		 <script src="' . Yii::app()->baseUrl . '/themes/tutorialzine1/js/highCharts/highstock.js" type="text/javascript"></script>
		 <script src="' . Yii::app()->baseUrl . '/themes/tutorialzine1/js/highCharts/modules/exporting.js" type="text/javascript"></script>
		';
	   }

	   if($sim == "10minAVg"){
		$outString = DashHelper::getMeIndChartAvgInfo($elementName, $givenTime);		   
	   }
		else
	   $outString = DashHelper::getMeIndChartInfo($elementName, $givenTime, $sim);


	   echo '<script type="text/javascript">
		 var stringified = \''.$outString.'\';

		Highcharts.setOptions({
			global: {
				useUTC: false
			}
		});
		var fetchDashboardElements = "fetchDashboardElements";
		var gadgetType             = "Charts";
		var widgetsPos             = '.$j.';

	     {

		//alert(stringified);	return;

		var responseSplitAr = stringified.split("::");

		if(responseSplitAr[0] == "ERR")
		{
			$("#"+"containerHC_'.$j.'").html("<span style=\'color:red;background:lightgray;\'>'.Yii::t('dash','No data available for the given time period!').'</span>");
			//return;
		}
		else
		{
		if(null != responseSplitAr[1]){
		var sp_stringfield  = responseSplitAr[1];
		var chart_'.$j.';
		var responseSplitAr = sp_stringfield.split("##");

		var chartStyleAr     = responseSplitAr[0].split(":");
		var chartStyle		 = chartStyleAr[1];

		var groupStyleAr     = responseSplitAr[1].split(":");
		var groupStyle		 = groupStyleAr[1];
			if(null != responseSplitAr[3]){
		var startTimeAr		 = responseSplitAr[3].split(":");
		var startTimeStamp	 = startTimeAr[1];
				if(null != responseSplitAr[4]){
		var endTimeAr	 	 = responseSplitAr[4].split(":");
		var endTimeStamp	 = endTimeAr[1];
		var elementsArList 	 = new Array();
				}
			}
		}
		//if length =6 this means that it is a simple graph with 1 element
		if(groupStyle != "")
		{
			var elemTitleAr     = responseSplitAr[2].split(":");
			elemTitleAr[1]      = elemTitleAr[1].slice(1,elemTitleAr[1].length);
			var elemTitleSubAr	= elemTitleAr[1].split(";");

			for(var lk=5,oi=0;lk<responseSplitAr.length;lk++,oi++)
			{
				var mdataAr          = responseSplitAr[lk];
				var mdataArSubAr	 = mdataAr.split("**");

				var elemNameTmp		 = mdataArSubAr[0];
				var dataTmp			 = $.parseJSON(mdataArSubAr[1]);

				var elemObj_temp = {name: elemNameTmp, data :dataTmp};
				elementsArList[oi] = elemObj_temp;
				//elementsArList[oi] = dataTmp;
			}
		}
		else
		{
			//Display there was a problem with displaying Data.
			$("#"+"containerHC_'.$j.'").html("Problem with gathering data for the time period!");
		}

		if(chartStyle =="saspline")
		{
			for(var oi=0,lk=3;lk<responseSplitAr.length;lk++,oi++)
			{
				elementsArList[oi].type ="areaspline";
				elementsArList[oi].threshold = null;
				elementsArList[oi].tooltip = {valueDecimals : 2};
				elementsArList[oi].fillColor = {
													linearGradient : {
														x1: 0,
														y1: 0,
														x2: 0,
														y2: 1
													},
													stops : [[0, Highcharts.getOptions().colors[0]], [1, "rgba(0,0,0,0)"]]
												};
			}//foreach
		}

		if(groupStyle =="hgrouped")
		{
			//console.debug(elementsArList);
			$("#containerHC_'.$j.'").highcharts("StockChart", {
			    chart: {
			    },

			    rangeSelector: {
				selected: 4
			    },

			    yAxis: {
				labels: {
				    formatter: function() {
						return (this.value > 0 ? "+" : "") + this.value + "%";
				    }
			    },
				plotLines: [{
					value: 0,
					width: 2,
					color: "silver"
				}]
			    },
			    plotOptions: {
				series: {
					compare: "percent"
				    }
			    },
			    tooltip: {
				pointFormat: "<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>",
				valueDecimals: 0
			    },
			    series: elementsArList
				});

			//return;
		}

		if ((chartStyle =="simple") || (chartStyle =="aspline"))
		{
			var oi=0;
			var data = elementsArList[oi].data;
			//alert(data);
			// Create a timer
			var start = + new Date();

			var now = new Date;

			var utc_timestamp = startTimeStamp * 1000;

			// Create the chart
			$("#containerHC_'.$j.'").highcharts("StockChart",
			{
			    chart: {
					events: {
						load: function(chart) {
							this.setTitle(null, {
								text: "Built chart at "+ (new Date() - start) +"ms"
							});
						}
					},
					zoomType: "x"
			    },

			    rangeSelector: {
				buttons: [{
				    type: "hour",
				    count: 3,
				    text: "3h"
				}, {
				    type: "hour",
				    count: 5,
				    text: "5h"
				}, {
				    type: "minute",
				    count: 15,
				    text: "15m"
				},{
				    type: "all",
				    text: "All"
				}],
				selected: 3
			    },

				yAxis: {
					title: {
						text: "Weight Percentage (%)"
					}
				},

			    title: {
					text: "Analysis Information for "+ elementsArList[oi].name
				},

				subtitle: {
					text: "Built chart at..." // dummy text to reserve space for dynamic subtitle
				},

				series: [{
				name: "Weight %",
				data: data,
				pointStart: utc_timestamp,
				pointInterval: 60 * 1000,
				tooltip: {
					valueDecimals: 1,
					valueSuffix: "%"
				}
			    },
				// the event marker flags
				/*
				{
					type : "flags",
					data : [{
						x : Date.UTC(2014, 0, 11),
						title : "H",
						text : "Tag-1"
					}, {
						x : Date.UTC(2014, 0, 12),
						title : "G",
						text : "Tag-2"
					}, {
						x : Date.UTC(2014, 0, 13),
						title : "F",
						text : "Tag-3"
					}, {
						x : Date.UTC(2014, 0, 14),
						title : "E",
						text : "Tag-4"
					}, {
						x : Date.UTC(2014, 0, 15),
						title : "D",
						text : "Tag-5"
					}, {
						x : Date.UTC(2014, 0, 16),
						title : "C",
						text : "Tag-6"
					}, {
						x : Date.UTC(2014, 0, 17),
						title : "B",
						text : "Tag-7"
					}],
					onSeries : "dataseries",
					shape : "circlepin",
					width : 16
				}*/
				] /*series*/

			}); //HighStocks


		}//if simple
		else if(chartStyle =="aspline-TODO")
		{

		chart_'.$j.' = new Highcharts.Chart({
		    chart: {
					renderTo : "containerHC_'.$j.'",
			type: "areaspline"
		    },
		    title: {
					text : "Analysis Data for " + elemTitleSubAr
		    },
		    subtitle: {
			text: "Source: Sabia Inc"
		    },
		    xAxis: {
			tickmarkPlacement: "on",
			title: {
			    enabled: false
			},
			labels: {
			    formatter: function() {
							var ndT = new Date();
							ndT.setTime(this.value);
							var nTemp = ndT.getHours() + ":" + ndT.getMinutes() + ":" + ndT.getSeconds();
				return  nTemp;
			    }
			}
		    },
		    yAxis: {
			title: {
			    text: "% Weight Average"
			},
		    },
		    tooltip: {
			formatter: function() {
						var ndT = new Date();
						ndT.setTime(this.x);
						var nTemp = ndT.getHours() + ":" + ndT.getMinutes() + ":" + ndT.getSeconds();
			    return  this.y + "% at " + nTemp ;
			}
		    },
		    plotOptions: {
			area: {
			    stacking: "normal",
			    lineColor: "#666666",
			    lineWidth: 1,
			    marker: {
				lineWidth: 1,
				lineColor: "#666666"
			    }
			}
		    },
				series : elementsArList
		 });

		}//if aspline
		else if(chartStyle =="saspline")
		{
			// Create the chart
			window.chart_'.$j.' = new Highcharts.StockChart({
				chart : {
					renderTo : "containerHC_'.$j.'"
				},

				rangeSelector : {
					selected : 1
				},

				title : {
					text : "Analysis Data for " + elemTitleSubAr
				},

				series : elementsArList
			});

		}//else

		//Close the Ajax loop and all other blocks
		}  //end ajax success..

	    //}); //end ajax..

	    /* }); //end document.ready.. */
		}//else no data
		</script>';
	}//getMeIndvChart


	public static function createDetInfoUls() {
		$detInfoArray = array("Daemon_Running" 		=> array("green" =>"yes", "orange"=>"", "red"=>"no"),
							  "Detector_Temp"  		=> array("green" =>"39:42",  "orange"=>"35:39", "red"=>"35:42"),
							  "Count_Rate_(aligned)"=> array("green" =>"2000000:3000000",  "orange"=>"175000000:2000000",
															 "red"=>"175000000:3000000"),
							  "Good_Data_Secs"		=> array("green" =>"60", "orange"=>"55:60", "red"=>"0:55"),
							  "H_Peak_Raw_Channel"  => array("green" =>"99:100", "orange"=>"96:99", "red"=>"96:100"),
							  "PMT_Voltage_Readback"  	=> array("green" =>"700:800", "orange"=>"700:820", "red"=>"650:820")
						);

		$detInfoArray_keys = array("Daemon_Running" 		=> "System",
							  "Detector_Temp"  		=> "Temperature",
							  "Count_Rate_(aligned)"=> "Count Rate",
							  "Good_Data_Secs"		=> "Data Quality",
							  "H_Peak_Raw_Channel"  => "H Peak",
							  "PMT_Voltage_Readback"  	=> "HV"
						);
		echo '<ul class="isotope-widgets isotope-container2">';
		for($i=1;$i<3;$i++) {

				$colstr = '';
				foreach($detInfoArray  as $id=>$vl) $colstr .="`$id`,";

				$colstr = substr($colstr,0,-1);

			    $sql =  "SELECT $colstr FROM `analysis_status_info` WHERE Detector_ID = 'datad$i' ORDER BY LocalEndTime DESC LIMIT 1";
			    $command = Yii::app()->db->createCommand($sql);
			    //echo $sql;

			    $settList = array();

			    $dInfoList = $command->query()->readAll();
				if(count($dInfoList )>0) {

				    foreach($dInfoList[0] as $sid=>$val) {
					$aval = $val;
					$val = str_replace(",","",$val);
					$val = str_replace("C","",$val);
					$val = str_replace("V","",$val);
					$val = str_replace(" ","",$val);

					if(isset($detInfoArray[$sid])) {

						$colorArray = $detInfoArray[$sid];
						foreach($colorArray as $cid=>$cval) {
								if((strpos($cval, ":")) === false)	{
										if(strtolower($cval) == strtolower($val)){
											$colorSel = $cid;
											break;
										}
								}//if
								else {
									list($lbound,$rbound) = explode(":",$cval);
									if($cid == "green") {
										if (($val >= $lbound) || ($val <= $rbound)) {
											$colorSel = $cid;
											break;
										}
									}

									if (($val <= $lbound) || ($val >= $rbound)) {
										$colorSel = $cid;
										break;
									}
								}
						}//foreach
						echo '<li class="dash-det'.$i.'" style="width:175px;">';
						echo '<a class="button-'.$colorSel.' ui-corner-all" href="#">';
						//echo '<strong>'.$aval.'</strong>';
						echo '<span style="border:none;font-color:black;font-weight:bold;">'.Yii::t('alerts',$detInfoArray_keys[$sid]).'</span>';
						echo '</a>';
						echo '</li>';

					}//if
					else
						continue;
				    }//foreach
			    }//if count
		}//foreach 4 runs
		echo '</ul>';
	}//function
 }//class
?>