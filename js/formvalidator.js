/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function tempValidate(selector ){
    
    $(selector).submit();
      
   var hasError = true
   $( selector ).find('input').each(function(){
       
        //console.log(  $(this).attr('class') )
       //$(this).attr('class')
       if($(this).hasClass('invalid')){
           
           
            hasError = false;
            
       }
   });  
    
    
    return hasError;
}

function validate(selector){
    
    var hasError = false;
    $( selector ).find('input').each(function(){
       
        if( $( this ).attr('required')){
            
            hasError = isEmpty( this );
            
        }else if( $( this).attr('type')==='number'){
            
            
        }
       
    })
}


function isEmpty(selector)
{
x = selector.value;
if (x==null || x=="")
  {
 // alert("First name must be filled out");
  return true;
  }
}