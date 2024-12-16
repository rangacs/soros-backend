This is an extension for highcharts that can works with yii's CActiveDataProvider.
It's a improved extension of [http://www.yiiframework.com/extension/highcharts/](http://www.yiiframework.com/extension/highcharts/ "highcharts").

##Requirements

- Yii 1.0 or above
- PHP 5.1 or above

##Installation
- Extract the release file under protected/extensions

##ScreenCast
![Chart](https://lh5.googleusercontent.com/-Mx8bBMI0aAc/Tv7Mrrw45sI/AAAAAAAAAA0/vEJlm4kbHvk/s640/Chartview%252520Chartdata%2525202011-12-31%25252016-47-48.jpg "Chart")

##Usage
###Basic Usage
You can either use the widget according to the formal usage or use it through a new way.

The basic usage you can refer to [http://www.yiiframework.com/extension/highcharts/](http://www.yiiframework.com/extension/highcharts/ "Highcharts").

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
  `time` time DEFAULT NULL,
  `data` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of chart_data
-- ----------------------------
INSERT INTO chart_data VALUES ('1', '14:27:55', '2');
INSERT INTO chart_data VALUES ('2', '14:28:43', '4');
INSERT INTO chart_data VALUES ('3', '14:28:55', '1');
INSERT INTO chart_data VALUES ('4', '14:29:18', '8');
INSERT INTO chart_data VALUES ('5', '14:29:31', '5');
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
    $this->Widget('ext.ActiveHighcharts.HighchartsWidget', array(
        'dataProvider'=>$dataProvider,
        'template'=>'{items}',
        'options'=> array(
            'title'=>array(
                'text'=>'Chart View'
            ),
            'xAxis'=>array(
                // It cant be 1.a self-defined xAxis array as you want; 
                // 2.a series from datebase such as time series
                "categories"=>'time'            
            ),
            'series'=>array(
                array(
                    'type'=>'areaspline',
                    'name'=>'Data',             //title of data
                    'dataResource'=>'data',     //data resource according to datebase column
                )
            )
        )
    ));
?>
~~~

- Type http://yoursite/chartdata/chartview to see your chart.Have fun :)

###Ajax Update
- Create a refresh button
~~~
[php]
    $chart_id = $chart->getId();
    $refresh_button = $this->widget('zii.widgets.jui.CJuiButton', array(
        'buttonType'=>'button',
        'name'=>'refresh',
        'caption'=>'Refresh',
        'options'=>array(
        ),
        'onclick'=>'js:function(){
            url = window.location.href+"?";
            $.fn.highchartsview.update("'. $chart_id .'", url);
       }'
    ));
~~~

## Change Log
- 2011-12-28 init first version
- 2011-12-31 Change the data source to be binded to only highcharts series 'name' attribute
- 2012-01-15 Change the data source to be binded to only a self-defined attribute 'dataResource'
and improve the chart's ajax action.