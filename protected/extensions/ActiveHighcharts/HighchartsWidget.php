<?php

/**
 * HighchartsWidget class file.
 *
 * @author Gavyn <gf010010@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 0.1
 */

Yii::import('zii.widgets.CBaseListView');

/**
 * HighchartsWidget encapsulates the {@link http://www.highcharts.com/ Highcharts}
 * charting library's Chart object.
 *
 * you can either use this widget according the formal usage such as (1) and (2) or
 * use it in a new way that can works with CActiveDataProvider such as (3)
 *
 * (1) To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->Widget('ext.highcharts.HighchartsWidget', array(
 *    'options'=>array(
 *       'title' => array('text' => 'Fruit Consumption'),
 *       'xAxis' => array(
 *          'categories' => array('Apples', 'Bananas', 'Oranges')
 *       ),
 *       'yAxis' => array(
 *          'title' => array('text' => 'Fruit eaten')
 *       ),
 *       'series' => array(
 *          array('name' => 'Jane', 'data' => array(1, 0, 4)),
 *          array('name' => 'John', 'data' => array(5, 7, 3))
 *       )
 *    )
 * ));
 * </pre>
 *
 * By configuring the {@link $options} property, you may specify the options
 * that need to be passed to the Highcharts JavaScript object. Please refer to
 * the demo gallery and documentation on the {@link http://www.highcharts.com/
 * Highcharts website} for possible options.
 *
 * (2) Alternatively, you can use a valid JSON string in place of an associative
 * array to specify options:
 *
 * <pre>
 * $this->Widget('ext.highcharts.HighchartsWidget', array(
 *    'options'=>'{
 *       "title": { "text": "Fruit Consumption" },
 *       "xAxis": {
 *          "categories": ["Apples", "Bananas", "Oranges"]
 *       },
 *       "yAxis": {
 *          "title": { "text": "Fruit eaten" }
 *       },
 *       "series": [
 *          { "name": "Jane", "data": [1, 0, 4] },
 *          { "name": "John", "data": [5, 7,3] }
 *       ]
 *    }'
 * ));
 * </pre>
 *
 * Note: You must provide a valid JSON string (e.g. double quotes) when using
 * the second option. You can quickly validate your JSON string online using
 * {@link http://jsonlint.com/ JSONLint}.
 *
 * (3) use it with CActiveDataProvider
 *    [Step 1] create you data model
 *       -- ----------------------------
 *       -- Table structure for `chart_data`
 *       -- ----------------------------
 *       DROP TABLE IF EXISTS `chart_data`;
 *       CREATE TABLE `chart_data` (
 *         `id` bigint(20) NOT NULL AUTO_INCREMENT,
 *         `time` time DEFAULT NULL,
 *         `data` float DEFAULT NULL,
 *         PRIMARY KEY (`id`)
 *       ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
 *
 *       -- ----------------------------
 *       -- Records of chart_data
 *       -- ----------------------------
 *       INSERT INTO chart_data VALUES ('1', '14:27:55', '2');
 *       INSERT INTO chart_data VALUES ('2', '14:28:43', '4');
 *       INSERT INTO chart_data VALUES ('3', '14:28:55', '1');
 *       INSERT INTO chart_data VALUES ('4', '14:29:18', '8');
 *       INSERT INTO chart_data VALUES ('5', '14:29:31', '5');
 *
 *    [Step 2]use yii's gii to generate the model
 *
 *    [Step 3]create the controller
 *
 *        public function accessRules()
 *       {
 *           return array(
 *               array(
 *                   'allow',
 *                   'actions'=>array('ChartView'),
 *                   'users'=>array('*'),
 *               ),
 *           );
 *       }
 *
 *       //a combine view of highcharts
 *       public function actionChartView()
 *       {
 *           $criteria=new CDbCriteria;
 *           $dataProvider=new CActiveDataProvider('ChartData',
 *               array(
 *                   'criteria'=>$criteria,
 *               )
 *           );
 *
 *           //json formatted ajax response to request
 *           if(isset($_GET['json']) && $_GET['json'] == 1){
 *               echo CJSON::encode($dataProvider->getData());
 *           }else{
 *               $this->render('ChartView',array(
 *                       'dataProvider'=>$dataProvider,
 *               ));
 *           }
 *
 *       }
 *
 *    [Step 4]create the view
 *       <h1>View ChartData</h1>
 *       <?php $this->Widget('ext.highcharts.HighchartsWidget', array(
 *               'dataProvider'=>$dataProvider,
 *               'template'=>'{items}',
 *               'options'=> array(
 *                   'title'=>array(
 *                       'text'=>'Chart View'
 *                   ),
 *                   'xAxis'=>array(
 *                       "categories"=>'time'
 *                   ),
 *                   'series'=>array(
 *                       array(
 *                           'type'=>'areaspline',
 *                           'name'=>'data',
 *                           'data'=>'data'
 *                       )
 *                   )
 *               )
 *           ));
 *    [Step 5]go to the url to view the chart
 * 
 * Note: You do not need to specify the <code>chart->renderTo</code> option as
 * is shown in many of the examples on the Highcharts website. This value is
 * automatically populated with the id of the widget's container element. If you
 * wish to use a different container, feel free to specify a custom value.
 */

class HighchartsWidget extends CBaseListView {
    
	public $action = array();
	public $options = array();
	public $htmlOptions = array();

	/**
	 * Renders the widget.
	 */
	public function run() {
		$id = $this->getId();
		$this->htmlOptions['id'] = $id;

		echo CHtml::openTag('div', $this->htmlOptions);
		echo CHtml::closeTag('div');

        parent::run();
        
		// check if options parameter is a json string
		if(is_string($this->options)) {
			if(!$this->options = CJSON::decode($this->options))
				throw new CException('The options parameter is not valid JSON.');
			// TODO translate exception message
		}

		// merge options with default values
		$defaultOptions = array('chart' => array('renderTo' => $id), 'exporting' => array('enabled' => true));
		$this->options = CMap::mergeArray($defaultOptions, $this->options);
		$jsOptions = CJavaScript::encode($this->options);
		//$this->registerScripts(__CLASS__ . '#' . $id, "var chart = new Highcharts.Chart($jsOptions);");
        $this->registerScripts(__CLASS__ . '#' . $id, "$.chart = new Highcharts.Chart($jsOptions);");

        $this->registerChartProcessScript($id);

        //self defined extra javascript
        $i = 0;
        $cs = Yii::app()->clientScript;
        foreach($this->action as $ext){
            $cs->registerScript("ext".$i++, $ext, CClientScript::POS_BEGIN);
        }
	}

    public function renderItems(){
        $seriesNames = $this->dataProvider->model->attributeNames();
        //ChromePhp::log($seriesNames);
        $data=$this->dataProvider->getData();
        $series = $this->options['series'];

        //set xAxis
        if(isset($this->options['xAxis']['categories']) && !is_array($this->options['xAxis']['categories'])){
            $xAxis = array();
            foreach($data as $j=>$val){
                //$xAxis[$j] = substr($val[$this->options['xAxis']['categories']], 0, -3);
                $xAxis[$j] = $val[$this->options['xAxis']['categories']];
            }
            //ChromePhp::log($xAxis);
            $this->options['xAxis']['categories_class'] = $this->options['xAxis']['categories'];
            $this->options['xAxis']['categories'] = $xAxis;
        }

        //set datas
        foreach($series as $i=>$v){
            if(isset($v['dataResource']) && !is_array($v['dataResource'])){
                $seriesData = array();
                foreach($data as $j=>$val){
                    //ChromePhp::info($val[$v['data']],$j);
                    //$seriesData[] = floatval($val[$v['data']]);
                    $seriesData[] = floatval($val[$v['dataResource']]);
                }
                $this->options['series'][$i]['data'] = $seriesData;
            }
        }

    }
    
	/**
	 * Publishes and registers the necessary script files.
	 *
	 * @param string the id of the script to be inserted into the page
	 * @param string the embedded script to be inserted into the page
	 */
	protected function registerScripts($id, $embeddedScript) {
		$basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
		$scriptFile = YII_DEBUG ? '/highcharts.src.js' : '/highcharts.js';

		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($baseUrl . $scriptFile);

		// register exporting module if enabled via the 'exporting' option
		if($this->options['exporting']['enabled']) {
			$scriptFile = YII_DEBUG ? 'exporting.src.js' : 'exporting.js';
			$cs->registerScriptFile("$baseUrl/modules/$scriptFile");
		}
		
		// register global theme if specified vie the 'theme' option
		if(isset($this->options['theme'])) {
			$scriptFile = $this->options['theme'] . ".js";
			$cs->registerScriptFile("$baseUrl/themes/$scriptFile");
		}
		$cs->registerScript($id, $embeddedScript, CClientScript::POS_LOAD);
	}

    protected function registerChartProcessScript($id){
		$basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
		$baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        
        //register highcharts script
        $cs->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').highchartsview();");

        //to remove the non-use element style options from the selected class
        //$cs->registerScript(__CLASS__.'#'.$id+1, "$('.highcharts-container').each(function(idx,el){el.style.position='';});", CClientScript::POS_LOAD);

        $cs->registerScriptFile($baseUrl.'/jquery.yiihighchartsview.js',CClientScript::POS_END);
    }
}