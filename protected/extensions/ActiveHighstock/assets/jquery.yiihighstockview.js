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
    
    $.fn.highstockview = function(){
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
    $.fn.highstockview.update = function(id, url, func_callable){
        if(func_callable == 'wikkensUpdate')
        {
         var suffix = '?json=1';  //NOTE: Using this format for '/' delmited ( urlManager ) format.
        }
        if(func_callable == 'FiveMinUpdate')
        {
         var suffix = '&json=5';
        }
        if(func_callable == 'AutoUpdate')
        {
         var suffix = '&json=auto';
        }
        if(func_callable == 'TenMinUpdate')
        {
         var suffix = '&json=10';
          //removed ajax block request, until further notice..
           
        }//end if func_callable == 'TenMinUpdate'...
        
        
      
       //Dave, it works!! but now let us try and implement a solution
       // using 'j' and which does not rely upon the 2nd TRUE parameter of 'addPoint()'...
        function iterator(data, j){
         for(var i=0; i<j; ++i){
          var x = (new Date()).getTime();
          var y = data[i][1];
          
          $.chart.series[0].addPoint([x, y], true, true);    //IMPORTANT: the 2nd TRUE parameter internally sets the array pointer to the next element THEN re-indexes the keys starting from zero.. (This is the behavior, un-verified code lookup).. 
         }
         ++j;
         return j;      
        }
      
        //Create a new 'events' object for our real parameter array, which is '$.chart.options.events' ...
        $.chart.options.events = {
                                  playback : function(data){               //was blank..
                                   var j   = 1;
                                   si_a = setInterval(function(){             
                                    var out = iterator(data, j);
                                    
                                    if(out == 3){clearInterval(si_a);}       // We could possibly place this inside a button click for better user control...
                                    
                                    //var play_time = Math.round(new Date().getTime() /1000 );   //for later..
                                    
                                    /*
                                    var x = (new Date()).getTime();                 //good
                                    var y = Math.round(Math.random() * 100);        //good
                                    
                                    for(var a=0; a<$.chart.options.series.length; ++a){
                                     $.chart.series[a].addPoint([x, y], true, true);            //was i
                                    }
                                    */
                                                               
                                   }, 1000); 
                                  }
        }
        
                 
        
        
        
        $.ajax({    
            url: url + suffix,
            success: function(dataProvider){
                //var len = $.chart.series.length;                      //ORIGINAL.. bad!!                                                    
                //console.log("chart.series.length is " + len + "");                                     // 2
                
                var len = $.chart.options.series.length;                //MODIFIED.. good!! (this works)..
                console.log("chart.OPTIONS.series.length is " + $.chart.options.series.length + "");   // 1 
                
                var datas = $.parseJSON(dataProvider);    
                console.log(datas);
                console.log("datas.length is " + datas.length);
                          
                for( var i=0; i < len; ++i){    //Execute 1x..
                 var data = new Array();
                 var dataY = $.chart.options.series[i]['dataResource'];        // data     
                 var dataX = $.chart.options.series[i]['dateResource'];       // time   
                    
                 for(var j=0; j<datas.length; j++){  //Execute 10x..
                  var turkey = new Array();
                  turkey.push( Number(datas[j][dataX]) );
                  turkey.push( Number(datas[j][dataY]) ); 
                  data.push( turkey );                                  //10x individual subarrays inside data..
                  
                  $.chart.options.series[i]['data'][j]    = [];                         //Nov21st.. updates $.chart.options.series manually..
                  $.chart.options.series[i]['data'][j][0] = Number(datas[j][dataX]);      
                  $.chart.options.series[i]['data'][j][1] = Number(datas[j][dataY]);
                  
                  //Dave, I think you will want the setInterval().. from where you can call $.chart.options.events.shout giving it 2x parameters x & y..
                  // Maybey place an internal var i incrementor inside 'setInterval()', that way it knows to go to the next element in the data array..
                  //Probably better off placing the setInterval() outside the 'for j' loop...
                  //var first = setInterval(function(){
                   //console.log('hellow');
                  //},1000);
                  //clearInterval(first); //Might have to place the clearInterval inside the shout method...
                  //$.chart.options.events.shout( Number(datas[j][dataX]), Number(datas[j][dataY]) );
                  
                 }
                 $.chart.series[i].setData(data, true);    //NOV22nd.. turning off for one-at-a-time... Original location..      //data = options.data,  true = redraw chart..
                                         
                
                                           
                }//end outter for loop..
                
                $.chart.options.events.playback(data);                  
                
                console.log(data);
                console.log($.chart);
                //$.chart.options.series[i].data.push(turkey);
                    
            }   
        });
    };  
    
    //REFERENCES:
    /*
        //Create a new 'events' object for our real parameter array, which is '$.chart.options.events' ...
        $.chart.options.events = {
                                  playback : function(){               //was blank..
                                   setInterval(function(){             
                                    var x = (new Date()).getTime();                 //good
                                    var y = Math.round(Math.random() * 100);        //good
                                    
                                    for(var a=0; a<$.chart.options.series.length; ++a){
                                     $.chart.series[a].addPoint([x, y], true, true);            //was i
                                    }
                                                               
                                   }, 1000); 
                                  }
        }
        
                 
        $.chart.options.events.playback();                  //works..
        */
    
})(jQuery);