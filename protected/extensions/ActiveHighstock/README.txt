This is an extension for highstock that can works with yii's CActiveDataProvider.
It's a improved extension of [http://www.yiiframework.com/extension/highstock](http://www.yiiframework.com/extension/highstock "highstock").
by an adaptation from 
[http://www.yiiframework.com/extension/highcharts/](http://www.yiiframework.com/extension/highcharts/ "highcharts").

##Requirements

- Yii 1.0 or above
- PHP 5.1 or above

##Installation
- Extract the release file under protected/extensions

##Usage
###Basic Usage
You can either use the widget according to the formal usage or use it through a new way.

The basic usage you can refer to [http://www.yiiframework.com/extension/highcharts/](http://www.yiiframework.com/extension/highcharts/ "Highcharts").
[http://www.yiiframework.com/extension/highcharts/](http://www.yiiframework.com/extension/highcharts/ "highcharts").

Here,I just demonstrate how does this extension work with yii's CActiveDataProvider.

- For example,create your data model named 'ChartData'.It has 'id','time' and 'data' attributes.
~~~
[sql]
-- ----------------------------
-- Table structure for `chart_data`
-- ----------------------------
DROP TABLE IF EXISTS `chart_data`;
CREATE TABLE `chart_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `time` INT,    -- it stores UNIX_TIMESTAMP in seconds from 1970-01-01
  `data` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of chart_data
-- ----------------------------
INSERT INTO chart_data VALUES ('1', '1333174685', '2');
INSERT INTO chart_data VALUES ('2', '1333274685', '4');
INSERT INTO chart_data VALUES ('3', '1333374685', '1');
INSERT INTO chart_data VALUES ('4', '1333474685', '8');
INSERT INTO chart_data VALUES ('5', '1333574685', '5');
~~~

- Create controller
~~~
[php]
    /**
	 * a combine view of highcharts
	 */
	public function actionChartView()
	{

        $criteria=new CDbCriteria;
        $dataProvider=new CActiveDataProvider('ChartData',
            array(
                'criteria'=>$criteria,
            )
        );

        //json formatted ajax response to request
        if(isset($_GET['json']) && $_GET['json'] == 1){
            $count = ChartData::model()->count();
            for($i=1; $i<=$count; $i++){
                $data = ChartData::model()->findByPk($i);
                $data->data += rand(-10,10);
                $data->save();
            }
            echo CJSON::encode($dataProvider->getData());
        }else{
            $this->render('ChartView',array(
                    'dataProvider'=>$dataProvider,
            ));
        }

    }
~~~

- Create View
~~~
[php]
<?php
 $this->Widget('ext.ActiveHighstock.HighstockWidget', array(
        'dataProvider'=>$dataProvider,
        'options'=> array(
    		'theme' => 'grid', //dark-blue dark-green gray grid skies
        'rangeSelector'=>array('selected'=>1),
    		'credits' => array('enabled' => true),
        'title'=>array('text'=>'Table title'),
    		'xAxis'=>array('maxZoom'=>'4 * 3600000' ),  //4 hours
    		'yAxis'=>array('title'=>array('text'=>'DataValueTitle')),
            'series'=>array(
                array(
                    'name'=>'SeriesTitle', //title of data
                    'dataResource'=>'data',     //data resource according to MySQL column 'data'
                    'dateResource'=>'time',     //data resource according to MySQL column 'time'
                )
            )
        )
    ));

?>
~~~

- Type http://yoursite/chartdata/chartview to see your chart.Have fun :)

## Change Log
- 2011-12-28 init first version
- 2011-12-31 Change the data source to be binded to only highcharts series 'name' attribute
- 2012-01-15 Change the data source to be binded to only a self-defined attribute 'dataResource'
and improve the chart's ajax action.
- 2012-04-04 Adapted ActiveHighCharts to ActiveHighstock