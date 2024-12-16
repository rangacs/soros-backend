/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

window.onload = function() {

    // $('#product-profile').hide();



    init();
    $('#product-profile').live('submit', function(e) {


        e.preventDefault();
        var name = $('#product_name').val();



        $.ajax({
            url: layout_name_check_url,
            method: "GET",
            data: {name: name},
            success: function(message) {
                var msgObj = JSON.parse(message);
                console.log(msgObj.error);
                if (msgObj.error == 1) {
                    //  console.log('profile name exits')

                    alert('profile name already exits please chose different name');

                } else {

                    createProfile(name);

                    //$('a[href="##portlet-pane-2"]').trigger('click');

                }

            }
        });

    }); // #product-profile send form end



    //detailed form submission

    $('#product-profile-form').live('submit', function(e) {


        tempValidate('#product-profile-form');
        e.preventDefault();



        if (tempValidate('#product-profile-form')) {



            var data = $('#product-profile-form').serialize();
            var profile_id = $('#ProductProfile_product_id').val();




            $.ajax({
                url: update_product_profile_url,
                type: "POST",
                data: {data: data, profile_id: profile_id},
                success: function(message) {



                    profileNext();


                    getStatusBar($('#ProductProfile_product_id').val());



                }
            });
        }


    }); // #product-profile send form end





    $("#setpoint-dialog-form").dialog({
        autoOpen: false,
        height: 400,
        width: 650,
        modal: true

    });


    $("#source-dialog-form").dialog({
        autoOpen: false,
        height: 450,
        width: 650,
        modal: true,
        open: function(event, ui) {




        },
        buttons: {
            "Save": function() {


                createSetPoint();
                $(this).dialog("close");
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {

        }
    });

    $("#element_composition-dialog-form").dialog({
        autoOpen: false,
        height: 500,
        width: 650,
        modal: true,
        open: function(event, ui) {



        },
        close: function() {

        }
    });



    $('#add-source').button({icons: {
            primary: "ui-icon-circle-plus"
        }});


    $('#selected-source-id').live('change', function() {

        loadElementCompositionList($(this).val());
    })



    getStatusBar($('#ProductProfile_product_id').val());


    $('#set-point-prev').live('click', setPointPrev)
    $('#set-point-next').live('click', setPointNext)

    $('#source-next').live('click', sourceNext);
    $('#source-prev').live('click', sourcePrev)

    $('#elelemt-prev').live('click', elementPrev);


    $('input:checkbox[name="checkbox[]"]').live('click', function() {


        $('input[name="checkbox[]"]').each(function() {

            console.log(this);
        })

    });

} // End of  window.onlod()




function init() {




    $("#setpoint-dialog-form").live('submit', function(e) {
        e.preventDefault();
    });
    // $('#portlet-product_profile');


    $("#product-profile-form").live('submit', function(e) {
        e.preventDefault();
    });

    $('#element_compossition_form').live('submit', function(e) {
        e.preventDefault();

    })

    $('#source-form').live('submit', function(e) {

        e.preventDefault();
    })


}

function createProfile(name) {



    $.ajax({
        url: creat_profile_url,
        method: "GET",
        data: {name: name},
        success: function(message) {

            $('#portlet-product_profile').html(message);

            removePreviousState();
            $('#progress_bar').css('width', '10%');
            getStatusBar($('#ProductProfile_product_id').val());

        }
    });

}

//Invoked form add Setpoint button
function addSetpoint() {

    //load_source_form_url



    $("#setpoint-dialog-form").dialog('open');

    $.ajax({
        url: load_setpoint_form_url,
        method: "GET",
        //data:{name:name},
        success: function(message) {

            $('#setpoint-dialog-form').html(message);
            $("#setpoint-dialog-form").dialog({buttons: [
			{text: "Create", click: createSetPoint}, 
			{text: "Cancel", click: function() {
                            $(this).dialog("close");
            }}]});

            $('#setpoint-dialog-form').dialog('open');


        }
    });


}

function createSetPoint() {
	
    if (tempValidate('#set-points-form')) {
		
        var product_id = $('#ProductProfile_product_id').val();
        $('#SetPoints_product_id').val(product_id);


        var data = $('#set-points-form').serialize();
        //console.log($('#set-points-form').serialize())
		//alert(data);
        $.ajax({
            url: creat_set_point_url,
            type: "POST",
            data: {data: data},
            success: function(message) {
				var len = message.length;

                if (len < 200) {
					var error = JSON.parse(message);
                    alert("Error creating set-point. " + error["detail"]);

                    $("#setpoint-dialog-form").dialog('close');
                } else {

                    $('#set-point-list').html(message)
                    $("#setpoint-dialog-form").dialog('close');

                }


            }
        });
    }


}
function updateSetPoint(id) {
    //update_setpoint_url
    $.ajax({
        url: update_setpoint_url,
        type: "GET",
        data: {id: id},
        success: function(message) {

            //
            //saveSetPoint
            $('#setpoint-dialog-form').html(message);// $('#element-composition-list').html(message)

            $("#setpoint-dialog-form").dialog({buttons: [{text: "Update", click: saveSetPoint}, {text: "Cancel", click: function() {
                            $(this).dialog("close");
                        }}]});
            $("#setpoint-dialog-form").dialog('open');
            ;
            getStatusBar($('#ProductProfile_product_id').val());
        }

    });
}
function deleteSetPoint(id) {

    $.ajax({
        url: delete_set_point_url,
        type: "GET",
        data: {id: id},
        success: function(message) {

            $('#set-point-list').html(message);
            getStatusBar($('#ProductProfile_product_id').val());

        }
    });
    $('#set-point-delete-dialog-form').dialog('close');

}

function saveSetPoint() {


    var id = $("#SetPoints_sp_id").val();
    var data = $('#set-points-form').serialize();
    //console.log($('#set-points-form').serialize())
    $.ajax({
        url: update_setpoint_url,
        type: "GET",
        data: {data: data, id: id},
        success: function(message) {

            $('#set-point-list').html(message);

            $("#setpoint-dialog-form").dialog('close');

        }
    });
}



function addSource() {


    //load_source_form_url

    $.ajax({
        url: load_source_form_url,
        method: "GET",
        //data:{name:name},
        success: function(message) {
            $('#source-dialog-form').html(message);


        }
    });


    $("#source-dialog-form").dialog({buttons: [{text: "Create", click: createSource}, {text: "Cancel", click: function() {
                    $(this).dialog("close");
                }}]});
    $("#source-dialog-form").dialog("open");
}
function createSource() {


    var product_id = $('#ProductProfile_product_id').val()
    $('#Source_product_id').val(product_id);

    //    TO bind submit event to form
    init();
    $('#source-form').submit();
    if (tempValidate('#source-form')) {

        var data = $('#source-form').serialize();

        $.ajax({
            url: create_source_url,
            method: "POST",
            data: {data: data},
            success: function(message) {

                  $("#source-dialog-form").dialog("close");

                $('#source-list').html(message);
                var error = JSON.parse(message);

                if (error['error']) {

                    alert(error['detail']);

                    $("#source-dialog-form").dialog("close");
                } else {
                    $('#source-list').html(message);
                    // $('a[href="#portlet-source-element"]').trigger('click');//TO Do: change tab

                  
                    $('#source-next').live('click', sourceNext);


                    sourceSelectList($('#ProductProfile_product_id').val());
                    getStatusBar($('#ProductProfile_product_id').val());

                }


            }
        });

    }
}

function updateSource(id) {

    //update_source_url

    //    TO bind submit event to form
    init();
    $('#source-form').submit();
    if (tempValidate('#source-form')) {


    }
    $.ajax({
        url: update_source_url,
        type: "GET",
        data: {sourceid: id},
        success: function(message) {

            $('#source-dialog-form').html(message);

            $("#source-dialog-form").dialog({buttons: [{text: "Save", click: function() {
                            saveSource(id)
                        }}, {text: "Cancel", click: function() {
                            $(this).dialog("close");
                        }}]});
            $("#source-dialog-form").dialog("open");

            getStatusBar($('#ProductProfile_product_id').val());
            sourceSelectList($('#ProductProfile_product_id').val());


        }
    });
}

function saveSource(id) {

    //var product_id = $('#ProductProfile_product_id').val()
    //  $('#Source_product_id').val(product_id);


    init();
    $('#source-form').submit();
    if (tempValidate('#source-form')) {



        var data = $('#source-form').serialize();



        $.ajax({
            url: update_source_url,
            method: "GET",
            data: {data: data, sourceid: id},
            success: function(message) {

                $('#source-list').html(message);
                //$('a[href="#portlet-source-element"]').trigger('click');//TO Do: change tab

                $("#source-dialog-form").dialog("close");

                getStatusBar($('#ProductProfile_product_id').val());
                sourceSelectList($('#ProductProfile_product_id').val());

            }
        });
    }

}


///Element composition

function updateElementComposition(id) {

    //update_source_url


    $.ajax({
        url: update_element_composition_url,
        type: "GET",
        data: {elementid: id},
        success: function(message) {

            $('#element_composition-dialog-form').html(message);

            $("#element_composition-dialog-form").dialog({buttons: [{text: "Save", click: function() {
                            console.log('trr');
                            saveElementComposition(id);
				$(this).dialog("close");
                        }}, {text: "Cancel", click: function() {
                            $(this).dialog("close");
                        }}]});
            $("#element_composition-dialog-form").dialog("open");

            getStatusBar($('#ProductProfile_product_id').val());


        }
    });
    $(this).dialog("close");
}

function saveElementComposition(id) {

    //var product_id = $('#ProductProfile_product_id').val()
    //  $('#Source_product_id').val(product_id);
    var data = $('#element_compossition_form').serialize();


    $.ajax({
        url: update_element_composition_url,
        method: "GET",
        data: {data: data, elementid: id},
        success: function(message) {

            $('#element-composition-list').html(message);


            
            getStatusBar($('#ProductProfile_product_id').val());
		$(this).dialog("close");

        }
    });

    $(this).dialog("close");
}




function addElementComposition() {


    $.ajax({
        url: load_element_composition__form_url,
        method: "GET",
        //data:{name:name},
        success: function(message) {
            //var msgObj = JSON.parse(message);
            $('#element_composition-dialog-form').html(message);
            getStatusBar($('#ProductProfile_product_id').val());
        }
    });
    $("#element_composition-dialog-form").dialog({buttons: [{text: "Create", click: createElementComposition}, {text: "Cancel", click: function() {
                    $(this).dialog("close");
                }}]});
    $("#element_composition-dialog-form").dialog("open");



}



function createElementComposition() {
    var source_id = $('#selected-source-id').val();
    ;
    $('#ElementComposition_source_id').val(source_id);
    var data = $('#element_compossition_form').serialize();
    //console.log($('#set-points-form').serialize())
    $.ajax({
        url: creat_element_composition_url,
        type: "POST",
        data: {data: data},
        success: function(message) {



                $('#element-composition-list').html(message)
            var error = JSON.parse(message);

            if (error['error']) {

                alert(error['detail']);

               $("#element_composition-dialog-form").dialog("close");
            } else {
                $('#element-composition-list').html(message)
                $("#element_composition-dialog-form").dialog("close");

                getStatusBar($('#ProductProfile_product_id').val());
		  $(this).dialog("close");
            }

        }

    });
   getStatusBar($('#ProductProfile_product_id').val());
   $(this).dialog("close");
}




$('document').ready(function() {



    //validate product profile
    $('#product-profile').on('submit', function(e) {

        e.preventDefault();

    })


});



function setpointDeleteConfirm(id, name) {

    $('#set-point-delete-dialog-form').dialog({buttons: [{text: "Delete", click: function() {
                    deleteSetPoint(id)
                }}, {text: "Cancel", click: function() {
                    $(this).dialog("close");
                }}]});
}

function loadElementCompositionList(source_id) {


    //console.log($('#set-points-form').serialize())
    $.ajax({
        url: load_element_compostion_list,
        type: "GET",
        data: {sourceid: source_id},
        success: function(message) {

            $('#element-composition-list').html(message);

            getStatusBar($('#ProductProfile_product_id').val());



        }
    });

}

function deleteSource(sourceid, name) {


    $.ajax({
        url: delete_source_url,
        type: "GET",
        data: {sourceid: sourceid},
        success: function(message) {

            $('#source-list').html(message);


            $('#delete-confirm-dialog-form').dialog('close')



        }
    });

}




function deleteElementComposition(ele_id, name) {


    $.ajax({
        url: delete_element_composition_url,
        type: "GET",
        data: {elementid: ele_id},
        success: function(message) {

            $('#element-composition-list').html(message);


            $('#delete-confirm-dialog-form').dialog('close');
            getStatusBar($('#ProductProfile_product_id').val());



        }
    });

}


function deleteConfirm(type, id, name) {


    if (type === 'source') {

        $('#delete-confirm-dialog-form').dialog({buttons: [{text: "Delete", click: function() {
                        deleteSource(id)
                    }}, {text: "Cancel", click: function() {
                        $(this).dialog("close");
                    }}]});

    } else if (type === 'setpoint') {
        $('#delete-confirm-dialog-form').dialog({buttons: [{text: "Delete", click: function() {
                        deleteSetPoint(id)
                    }}, {text: "Cancel", click: function() {
                        $(this).dialog("close");
                    }}]});


    } else if (type === 'element_composition') {

        $('#delete-confirm-dialog-form').dialog({buttons: [{text: "Delete", click: function() {
                        deleteElementComposition(id, name)
                    }}, {text: "Cancel", click: function() {
                        $(this).dialog("close");
                    }}]});


    }



}


function profileNext() {


    var setPointTab = $('a[data-tab="#portlet-set-point"]');

    $(setPointTab).attr('href', '#portlet-set-point');

    $('#portlet-set-point').removeClass('hide');


    $('a[href="#portlet-set-point"]').trigger('click');
}

function setPointNext() {

    //alert('set point next')

    $('a[href="#portlet-source"]').trigger('click');
    //$('a[href="##portlet-pane-2"]').trigger('click');

}

function setPointPrev() {



    $('a[href="#portlet-product_profile"]').trigger('click');
}

function sourceNext() {


    $('a[href="#portlet-source-element"]').trigger('click');
}

function sourcePrev() {

    $('a[href="#portlet-set-point"]').trigger('click');
}

function elementPrev() {


    $('a[href="#portlet-source"]').trigger('click');
}


function removePreviousState() {

    $('#progress_bar').removeClass('ui-state-error');

    $('#progress_bar').removeClass('ui-state-success');
    $('#progress_bar').removeClass('ui-state-highlight');

}


function getStatusBar(pid) {


    //console.log(get_progress_url)

    $.ajax({
        url: get_progress_url,
        type: "GET",
        data: {productid: pid},
        success: function(message) {


            $('#progress-bar').html(message);

            //getStatusBar($('#ProductProfile_product_id').val());
            //$('#delete-confirm-dialog-form').dialog('close')



        }
    });


}



function sourceSelectList(pid) {


    //console.log(get_progress_url)

    $.ajax({
        url: update_source_select_url,
        type: "GET",
        data: {productid: pid},
        success: function(message) {


            $('#source-select').html(message);

            //getStatusBar($('#ProductProfile_product_id').val());
            //$('#delete-confirm-dialog-form').dialog('close')



        }
    });


}


