<?php
/* @var $this TruckInfoController */
/* @var $model TruckInfo */

$this->breadcrumbs = array(
    'Truck Infos' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List TruckInfo', 'url' => array('index')),
    array('label' => 'Manage TruckInfo', 'url' => array('admin')),
);
?>


<section class="main-section grid_8">
    <nav class="">

        <?php
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);
        ?>
    </nav>
    <div class="main-content">


        <div class="grid_6 leading" style="min-height:250px !important;">

            <section class="container_6 clearfix">

                <!-- Tabs inside Portlet -->
                <div class="grid_6 leading">
                    <header class="ui-widget-header ui-corner-top form padding-5">

                        <h5>Health Check</h5>
                    </header>

                    <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:600px;">

                        <section id="portlet-set-point"  class=" ui-tabs-panel ui-widget-content ui-corner-bottom">

                            <form id="truck_info_form" action="" method="post" class="form ">

                                <div class="clearfix">

                                    <label class="form-label" for="form-name"></label>

                                    <div class="form-input">

                                        <p class="note">Fields with <span class="required">*</span> are required.</p> 

                                    </div>
                                </div>



                                <div class="clearfix">

                                    <label class="form-label" for="form-name">Plant Code<em>*</em></label>

                                    <div class="form-input">

                                        <input size="30" maxlength="30" name="w_pl_code" id="w_plantCode" type="text">      
                                    </div>
                                </div>

                                <div class="clearfix">

                                    <label class="form-label" for="form-name">Health Status<em>*</em></label>

                                    <div class="form-input">

                                        <input size="30" maxlength="30" name="w_trip_id" id="w_tripID" type="text">      
                                    </div>
                                </div>



                                <div class="clearfix">

                                    <label class="form-label" for="form-name">Unloader Id<em>*</em></label>

                                    <div class="form-input">

                                        <input size="30" maxlength="30" name="w_unl_id" id="w_unloaderID" type="text">      
                                    </div>
                                </div>


                                <div class="clearfix">

                                    <label class="form-label" for="form-name">Moisture<em>*</em></label>

                                    <div class="form-input">

                                        <input size="30" maxlength="30" name="w_unl_id" id="w_moisture" type="text">      
                                    </div>
                                </div>




                                <div class="clearfix">

                                    <label class="form-label" for="form-name">Ash<em>*</em></label>

                                    <div class="form-input">

                                        <input size="30" maxlength="30" name="w_unl_id" id="w_ash" type="text">      
                                    </div>
                                </div>



                                <div class="clearfix">

                                    <label class="form-label" for="form-name">Sulfur<em>*</em></label>

                                    <div class="form-input">

                                        <input size="30" maxlength="30" name="w_unl_id" id="w_sulfur" type="text">      
                                    </div>
                                </div>


                                <div class="clearfix">

                                    <label class="form-label" for="form-name">Gcv<em>*</em></label>

                                    <div class="form-input">

                                        <input size="30" maxlength="30" name="w_unl_id" id="w_gcv" type="text">      
                                    </div>
                                </div>

                                <div class="clearfix ">
                                    <label class="form-label" for="form-name">Timestamp  <em>*</em></label>
                                    <div class="form-input">
                                        <input type="text" required="required"  readonly="true" name="w_timestamp" id="w_timestamp" autocomplete="off" value="<?= date("Y-m-d H:i:s") ?>" class="hasDatepicker">

                                    </div>
                                </div> 

                                <div class="clearfix ">
                                    <div class="form-input">

                                        <button type="submit"> Submit</button>
                                    </div>
                                </div> 

                            </form>


                        </section>

                </div>
            </section>

        </div>

        <div class="clear"><br/></div>



    </div>
</section>
<script src="<?php echo Yii::app()->baseUrl ?>/js/axios.min.js"></script>
<script type="text/javascript">

    window.onload = function () {


        $("#w_unloadConf").live("change", function () {

            var val = $(this).val();

            //debugger
            if (val === "3") {
                $("#w_trigType").val("PB-3");
                //  alert("Pb 3")
            } else {
                $("#w_trigType").val("PB-4");
                //    alert("Pb 4")
            }


        })
        $("#truck_info_form").live("submit", function (e) {
            e.preventDefault();


            const form = document.getElementById('truck_info_form');

            const json_data = formToJSON(form.elements);

            console.log(json_data)
            var url = "http://localhost/wonder_rest/server/sabRemRFServ.php";

            $.ajax({
            url : url,
            method : "POST",
            data : { evntHealthTolerenceCheckStatus : json_data} ,
            success:function(){
                
              alert("s");  
            },
            error : function(){
                alert("e")
                
            }
        })
      /*  axios.post(url, {
        evntHealthTolerenceCheckStatus: json_data,
        }).then(function (response) {
        console.log(response);
        })
                .catch(function (error) {
                console.log(error);
                        alert(error)
                }); //END :: Axios 
    */    
    });//End :: Live handler

        /**
         * Checks that an element has a non-empty `name` and `value` property.
         * @param  {Element} element  the element to check
         * @return {Bool}             true if the element is an input, false if not
         */
        const isValidElement = element => {
            return element.name && element.value;
        };

        /**
         * Checks if an element’s value can be saved (e.g. not an unselected checkbox).
         * @param  {Element} element  the element to check
         * @return {Boolean}          true if the value should be added, false if not
         */
        const isValidValue = element => {
            return (!['checkbox', 'radio'].includes(element.type) || element.checked);
        };

        /**
         * Checks if an input is a checkbox, because checkboxes allow multiple values.
         * @param  {Element} element  the element to check
         * @return {Boolean}          true if the element is a checkbox, false if not
         */
        const isCheckbox = element => element.type === 'checkbox';

        /**
         * Checks if an input is a `select` with the `multiple` attribute.
         * @param  {Element} element  the element to check
         * @return {Boolean}          true if the element is a multiselect, false if not
         */
        const isMultiSelect = element => element.options && element.multiple;

        /**
         * Retrieves the selected options from a multi-select as an array.
         * @param  {HTMLOptionsCollection} options  the options for the select
         * @return {Array}                          an array of selected option values
         */
        const getSelectValues = options => [].reduce.call(options, (values, option) => {
                return option.selected ? values.concat(option.value) : values;
            }, []);

        /**
         * A more verbose implementation of `formToJSON()` to explain how it works.
         *
         * NOTE: This function is unused, and is only here for the purpose of explaining how
         * reducing form elements works.
         *
         * @param  {HTMLFormControlsCollection} elements  the form elements
         * @return {Object}                               form data as an object literal
         */
        const formToJSON_deconstructed = elements => {

            // This is the function that is called on each element of the array.
            const reducerFunction = (data, element) => {

                // Add the current field to the object.
                data[element.name] = element.value;

                // For the demo only: show each step in the reducer’s progress.
                console.log(JSON.stringify(data));

                return data;
            };

            // This is used as the initial value of `data` in `reducerFunction()`.
            const reducerInitialValue = {};

            // To help visualize what happens, log the inital value, which we know is `{}`.
            console.log('Initial `data` value:', JSON.stringify(reducerInitialValue));

            // Now we reduce by `call`-ing `Array.prototype.reduce()` on `elements`.
            const formData = [].reduce.call(elements, reducerFunction, reducerInitialValue);

            // The result is then returned for use elsewhere.
            return formData;
        };

        /**
         * Retrieves input data from a form and returns it as a JSON object.
         * @param  {HTMLFormControlsCollection} elements  the form elements
         * @return {Object}                               form data as an object literal
         */
        const formToJSON = elements => [].reduce.call(elements, (data, element) => {



                // Make sure the element has the required properties and should be added.
                if (isValidElement(element) && element.value !== "Create") {

                    console.log(element.value)
                    /*
                     * Some fields allow for more than one value, so we need to check if this
                     * is one of those fields and, if so, store the values as an array.
                     */
                    var id = element.id;

                    var name = id.replace('TruckInfo_', '');

                    if (isCheckbox(element)) {


                        data[name] = (data[element.name] || []).concat(element.value);
                    } else if (isMultiSelect(element)) {
                        data[name] = getSelectValues(element);
                    } else {
                        data[name] = element.value;
                    }
                }

                return data;
            }, {});

        /**
         * A handler function to prevent default submission and run our custom script.
         * @param  {Event} event  the submit event triggered by the user
         * @return {void}
         */
        const handleFormSubmit = event => {

            // Stop the form from submitting since we’re handling that with AJAX.
            event.preventDefault();

            // Call our function to get the form data.
            const data = formToJSON(form.elements);

            // Demo only: print the form data onscreen as a formatted JSON object.
            const dataContainer = document.getElementsByClassName('results__display')[0];

            // Use `JSON.stringify()` to make the output valid, human-readable JSON.
            dataContainer.textContent = JSON.stringify(data, null, "  ");

            // ...this is where we’d actually do something with the form data...
        };

        /*
         * This is where things actually get started. We find the form element using
         * its class name, then attach the `handleFormSubmit()` function to the 
         * `submit` event.
         */
        // const form = document.getElementsByID('contact-form')[0];
        //  form.addEventListener('submit', handleFormSubmit);



    }


</script>

