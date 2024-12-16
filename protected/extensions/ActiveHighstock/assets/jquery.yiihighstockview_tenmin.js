/**
 * Created by JetBrains PhpStorm.
 * User: GUFENG
 * Date: 11-12-21
 * Adapted: 2012-04-04 by seryi70 for Highstock
 */

;(function($){

//    $('.highstock-container').each(function(idx,el){
//        el.style.position='';
//    });
    
    $.fn.highstockview_tenmin = function(){
        return this.each(function(){
            var $this = $(this);
            var id = $this.attr('id');
        });
    };
    
    
    /*
    *  Wikkens; Nov 15th: 
    *   Fixes:
    *    i.)  Problem:  var len = $.chart.series.length;
    *          - The length counts 2x. 
    *          - When the 'for' loop executes, it stops executing when i = 1  (halting on the 2nd counted object)..              
    *    
    *        The Solution: var len = $.chart.options.series.length;
    *          - The length counts 1x.
    *          - The 'for' loop can perform executing without stopping..
    *                              
    *    ii.) Problem:  data.push( array( Number(datas[j][dataX]), Number(datas[j][dataY]) ) );
    *          - The javascript error:  'array' is not defined..
    *              
    *        The Solution:  
    *         var turkey = new Array();
    *         turkey.push( Number(datas[j][dataX]) );
    *         turkey.push( Number(datas[j][dataY]) );   
    *         data.push( turkey );  
    *         
    *         - (Breaks it up into smaller components)..                     
    *         - This is how a 'data' array looks (single array encapsulating MULTIPLE inner arrays)..:
    *           [ [1333174685, 3] , [1333274685, -18] , [1333374685, 34] , [1333474685, -19] , [1333574685, -9] ]    
    *
    *    iii.) Problem: Lack of code re-usability different user requests (ie fetch 5min, fetch 10min) for data..
    *          
    *          The Solution: Create a JS function which determines the callable function (that is, the function calling update() )..
    *           @param  func_callable  string The name of the JS function calling our JS update() method..
    *                            
    *
    *   JS function 'update'
    *    @params  id             string  The name of the widget.. ie Id => asdf
    *    @params  url            string  The url for the ajax request..
    *    @params  func_callable  string  The name of the JS function calling our JS update() method..
    *                          
    */            
    $.fn.highstockview_tenmin.update = function(id, url, func_callable){
        if(func_callable == 'TenMinUpdate')
        {
         var suffix = '&json=10';
        }
        
        
        $.ajax( {    
            url: url + suffix,
            success: function(dataProvider){
                //var len = $.chart.series.length;                                                       //ORIGINAL.. bad!!
                //console.log("chart.series.length is " + len + "");                                     // 2
                //console.log("chart.OPTIONS.series.length is " + $.chart.options.series.length + "");   // 1 
                
                var len = $.chart.options.series.length;  //MODIFIED.. good!! (this works)..
                var datas = $.parseJSON(dataProvider);    
                console.log(datas);
                          
                for( var i=0; i < len; ++i){
                    var data = new Array();
                    var dataY = $.chart.options.series[i]['dataResource'];        // data     
                    
                    var dataX = $.chart.options.series[i]['dateResource'];       // time   
                    
                    for(var j=0; j<datas.length; j++){
                      var turkey = new Array();
                      turkey.push( Number(datas[j][dataX]) );
                      turkey.push( Number(datas[j][dataY]) ); 
                      
                      data.push( turkey );     
                    }
                    console.log(data);
                    $.chart.series[i].setData(data,true);
                    
                } //for loop..
          
            }   
        });
    };  
    
})(jQuery);