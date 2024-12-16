/**
 * Created by JetBrains PhpStorm.
 * User: GUFENG
 * Date: 11-12-21
 * Time: 下午2:30
 * To change this template use File | Settings | File Templates.
 */

;(function($){

//    $('.highcharts-container').each(function(idx,el){
//        el.style.position='';
//    });
    
    $.fn.highchartsview = function(){
        return this.each(function(){
            var $this = $(this);
            var id = $this.attr('id');
        });
    };
    
    $.fn.highchartsview.update = function(id, url){
        $.ajax({
            url:url + '&json=1',
            success:function(dataProvider){
                ///$('#' + id).replaceWith($(id,'<div>'+data+'</div>'))
                //alert($.chart.options['xAxis']['tt']);
                var len = $.chart.series.length;
                var datas = $.parseJSON(dataProvider);
                //var setData;
                var xAxis_categories_class = $.chart.options['xAxis']['categories_class'];
                var xAxis = new Array();
                for(var j=0; j<datas.length; j++){
                    //alert(datas[j][xAxis_categories_class]);
                    xAxis.push(datas[j][xAxis_categories_class]);
                }
                //$.chart.xAxis[0].setCategories(xAxis);
                for(var i=0; i<len; i++){
                    var data = new Array();
                    var dataResource = $.chart.options.series[i]['dataResource'];
                    for(var j=0; j<datas.length; j++){
                        //data[j] = Number(datas[j][dataResource]);
                        data.push(Number(datas[j][dataResource]));
                    }
                    $.chart.series[i].setData(data,true);
                }
                
                var sidx = 0;
                var labelSteps = Math.floor(xAxis.length/12.0)+1;
                for(var x in xAxis)
                {
                    show_xaxis[xAxis[x]] = sidx % labelSteps == 0;
                    sidx++;
                }
                
                $.chart.xAxis[0].setCategories(xAxis);
            }
        })
        //alert("Update highcharts");
//        $.chart.series[0].setData([5, 2, 1, 3, 4],true);
//        $.chart.series[1].setData([1, 2, 1, 3, 4],true);
//        $.chart.series[2].setData([3, 2, 1, 3, 4],true);
//        $.chart.series[3].setData([2, 2, 1, 3, 4],true);
    //    chart.series = [
    //          {
    //             "type": "column",
    //             "name": "Jane",
    //             "data": [5, 2, 1, 3, 4]
    //          },{
    //             "type": "column",
    //             "name": "John",
    //             "data": [6, 3, 5, 7, 6]
    //          }, {
    //             "type": "column",
    //             "name": "Joe",
    //             "data": [4, 3, 3, 9, 0]
    //          },
    //          {
    //             "type": "spline",
    //             "name": "Average",
    //             "data": [3, 2.67, 3, 6.33, 3.33]
    //          }
    //	   ];
        /*$.each( [0,1,2], function(i, n){
            alert( "Item #" + i + ": " + n );
        });*/

    };
    
})(jQuery);