<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PileGraph
 *
 * @author veda
 */
class PileGraph {

    //put your code here
    public $elements;
    public $data;
    public $style;

    public function __construct($elements, $data, $style) {

        $this->data = $data;
        $this->style = $style;
    }

    public function render() {
    	$timeSuff = "suf" . time() . "#" . rand(0,1000);
    
    	echo '<div class="grid_3 " > ';
		$appType = Yii::app()->db->createCommand("select varValue from rm_settings where varKey ='SOROS_DISPLAY_APP_TYPE'")->queryScalar();
		if($appType == "coal") {
    	           echo'<div class="textBox3D" style="z-index:1000">
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'Moisture: ' . '</span><span style="float:right;font-weight:bold;">'. round($this->data['Moisture'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'Ash: ' 	. '</span><span style="float:right;font-weight:bold;">'. round($this->data['Ash'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'Sulfur: ' . '</span><span style="float:right;font-weight:bold;">'. round($this->data['Sulfur'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'GVC: ' 	. '</span><span style="float:right;font-weight:bold;">'. round($this->data['BTU'],2) 		. '</span></div>
					</div>';  			
		}else {
    	           echo'<div class="textBox3D" style="z-index:1000">
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'SiO2: ' 	. '</span><span style="float:right;font-weight:bold;">'. round($this->data['SiO2'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'Al2O3: ' . '</span><span style="float:right;font-weight:bold;">'. round($this->data['Al2O3'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'CaO: ' 	. '</span><span style="float:right;font-weight:bold;">'. round($this->data['CaO'],2) 		. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'Fe2O3: ' . '</span><span style="float:right;font-weight:bold;">'. round($this->data['Fe2O3'],2) 	. '</span></div>
					</div>';  
		}					
				echo '<div class="cont3d" id="container3d'.$timeSuff.'"></div>
				<script type="text/javascript">
				
				// Set up the chart
				Highcharts.chart("container3d'.$timeSuff.'", {
					chart: {
						type: "pyramid3d",
						options3d: {
							enabled: true,
							alpha: 10,
							depth: 50,
							viewDistance: 50
						}
					},
					title: {
						text: "Tag-'.$this->data['tagName'].'"
					},
					plotOptions: {
						series: {
							dataLabels: {
								enabled: true,
								format: "Tons :'. round($this->data['totalTons'],0) . '",
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || "black",
								allowOverlap: true,
								x: 50,
								y: 25
							},
							width: "60%",
							height: "80%",
							center: ["50%", "45%"]
						}
					},
					credits: {
						enabled: false
					},
					series: [{
						name: "Total-Tons",
						data: [
							["'. $this->data['tagName'] .'" , '. round($this->data['totalTons'],0) . ']
						]
					}]
				});
				</script>'; 
				 	
    		echo ' </div>
    		
    	';
    	
    }


    public function render2() {
    
    	echo '<div class="grid_3">';
	    echo '  <header class="ui-widget-header ui-corner-top">
                    <h2 style="font-weight:bold;text-align:center"> <span >'.$this->data['tagName'].' </h2>
                </header>
                <section class="ui-widget-content ui-corner-bottom" style="height: 250px;position: relative">';
                
        if($this->data['SiO2'] > 0) {
        
			echo '<div class="piled" style="'.$this->style.'">
                 <div>';
            echo'<div class="textBox">
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'SiO2: ' 	. '</span><span style="float:right;">'. round($this->data['SiO2'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'Al2O3: ' . '</span><span style="float:right;">'. round($this->data['Al2O3'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'CaO: ' 	. '</span><span style="float:right;">'. round($this->data['CaO'],2) 		. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'Fe2O3: ' . '</span><span style="float:right;">'. round($this->data['Fe2O3'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'KH: ' . '</span><span style="float:right;">'. round($this->data['KH'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'SM: ' . '</span><span style="float:right;">'. round($this->data['SM'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'IM: ' . '</span><span style="float:right;">'. round($this->data['IM'],2) 	. '</span></div>
						<div class="textElem"><span style="float:left;width:50px !important;"> '. 'Tons: ' 	. '</span><span style="float:right;">'. round($this->data['totalTons'],0) 	. '</span></div>
					</div>';

        }else {
        	echo '<div class="piled" stlye="text-align:center">No data available.</div>';
        }//else
        
        echo '</section></div>';
    }

}
