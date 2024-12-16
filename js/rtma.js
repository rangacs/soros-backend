/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var formLink = {"rta-config-db-form": {
        'next': 'rta-physical-config',
        save: function() {
            alert('trr')
        }

    },
    "rta-physical-config-form": {
        'next': 'rta-db-config',
        'previous': 'rta-config-db'

    },
    "rta-db-config-form": {
        'next': 'rta-derived-config',
        'previous': 'rta-physical-config'

    },
    "rta-derived-config-form": {
        'next': 'rta-avg-config',
        'previous': 'rta-db-config'

    },
    "rta-avg-config-form": {
        'next': 'rta-avg-group-config',
        'previous': 'rta-derived-config'

    },
    "rta-avg-group-config-form": {
        'next': 'rta-shift-times',
        'previous': 'rta-avg-config'

    },
    "rta-shift-times-from":{
        'next': 'rta-job-id',
        'previous': 'rta-avg-group-config'
    },
    "rta-job-id-form": {
        'next': '#',
        'previous': 'rta-shift-times'

    }

}
$(function() {


    $('.pagination li a').live('click', function(e) {

        var selector = $(this).attr('href');





        if (selector !== '#') {

            var selector = $(this).attr('href');

            var cselector = selector + "-form";

            cselector = cselector.slice(1);

            var hasClassNext = $(this).parent().hasClass('next');
            var currentForm = formLink[cselector]['previous'];

            currentForm = "#" + currentForm + "-form"


            if (hasClassNext) {

                console.log(currentForm);

                $(currentForm).submit();

                formSubmitionHandler($(currentForm));
            }
            else {

                $('.form-step').removeClass('form-active');

                $(selector).addClass('form-active');
            }

            // formSubmitionHandler();
        }


    });

    $('form').live('submit', function(e) {

        e.preventDefault();

        formSubmitionHandler(this)
    })

});


function formSubmitionHandler(form) {


    var formdata = $(form).serialize();
    var formId = $(form).attr('id');
    // console.log(formId)



    var next = formLink[formId]['next'];



    var hasError = simpleValidator('#' + formId);

    // console.log(hasError);

    if (!hasError) {
        return false;
    }


    $.ajax({
        url: form_submision_url,
        'type': "POST",
        data: {formdata: formdata},
        success: function(message) {

            var msgObject = JSON.parse(message);
            if(msgObject['rtaMasterID']){
                
                var option = '<option value="'+msgObject['rtaMasterID']+'">'+msgObject['db_string']+'</option>';
              $('.rta-master-id').append(option);
            }
            
          //  if(msgObject.rtaMasterID)
            if (next !== '#') {
                $('.form-step').removeClass('form-active');

                $('#' + next).addClass('form-active');

            }
        }

    });//End of $.ajax 

}//End of formSubmitionHandler

function handleNavigation() {


}

function setDefaultLayout(e) {

    var val = e.value;

    $.ajax({
        url: set_default_layout_url,
        'type': "POST",
        data: {lay_id: val},
        success: function() {



        }

    });//End of $.ajax 

}


function selectTableType(e) {

    var value = e.value;

    if (value === 'analysis') {

        $('#analysis-settings-table').removeClass('hide');
        $('#average-settings-table').addClass('hide');

    } else if (value === 'averages') {

        $('#average-settings-table').removeClass('hide');
        $('#analysis-settings-table').addClass('hide');
    }
}


function simpleValidator(selector) {

    // $('#'+selector).submit();

    var hasError = true
    $(selector).find('input').each(function() {

        //console.log(  $(this).attr('class') )
        //$(this).attr('class')
        if ($(this).hasClass('invalid')) {


            hasError = false;

        }
    });


    return hasError;
}