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

	
$apiSabiaEndPoint = RmSettings::getValueFromKey('SABIA_API_ENDPOINT', 'http://192.168.99.193/rest/client/sabRemRFCl.php');

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


        <div class="grid_6 leading" style="min-height:950px !important;">

            <section class="container_6 clearfix">

                <!-- Tabs inside Portlet -->
                <div class="grid_6 leading">
                    <header class="ui-widget-header ui-corner-top form padding-5"> 
                        <h3 style="padding-left:10px;"> Please provide new trip information</h3>

                    </header>

                    <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:800px;border:0px !important">

                        <section id="portlet-set-point"  class=" ui-tabs-panel ui-widget-content ui-corner-bottom">
                            <a data-icon-primary="ui-icon-circle-plus" href="<?= Yii::app()->createAbsoluteUrl("truckInfo/unloading")?>" class="pull-right ui-button ui-widget ui-state-highlight ui-corner-all ui-state-focus ui-button-text-icon-primary" role="button"><span class="ui-button-icon-primary ui-icon ui-icon-plus"></span>
                            <span class="ui-button-text " style="color:red">PB-3/PB-4</span>
                            </a>
                            
                            <?php $this->renderPartial('_form', array('model' => $model)); ?>
                        </section>

                </div>
            </section>

        </div>

        <div class="clear"><br/></div>



    </div>
</section>

<script src="<?php echo Yii::app()->baseUrl ?>/js/axios.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl ?>/js/jquery.js"></script>
<script src="<?php echo Yii::app()->baseUrl ?>/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->baseUrl ?>/js/jquery.validate.js"></script>
<!-- <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/jquery.ui.min.js"></script>-->
<script type="text/javascript">

    $("#truck-info-form").validate({
        showErrors: function(errorMap, errorList) {
            $("#esummary").html("The form contains "
              + this.numberOfInvalids()
              + " errors, see details below.");
            this.defaultShowErrors();
        }
    });
    
    $( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd' , minDate: -1, maxDate: +1 });

</script>
<script type="text/javascript">
    
    window.onload = function () {

        $("#truck-info-form").live("submit", function (e) {
            e.preventDefault();
            var timeVar = $("#w_timestamp").val();
            var dateTimeVar = $("#datepicker").val() + " " + timeVar + ":00"; //Make a datetime string
            //alert(dateTimeVar);
            $("#w_timestamp").val(dateTimeVar);
	    //alert($("#w_timestamp").val());

            const form = document.getElementById('truck-info-form');

            const json_data = formToJSON(form.elements);

            //console.log(json_data);

            //var url = "http://localhost/wonder_rest/server/sabRemRFServ.php";
            //var url = "http://localhost/wonder_rest/client/sabRemRFCl.php";
            var url = "<?php echo $apiSabiaEndPoint; ?>"; 
            
             axios.post(url, {
                evntTruckUnLoStartInfo: json_data,
             }).then(function (response) {
                //console.log(response);
                //console.log(response.data);
                alert("New Truck information is saved");
                window.location.reload(true);
             })
             .catch(function (error) {
             	console.log(error);
                alert(error);
             }); //END :: Axios 
             
             
        }
        );//End :: Live Handler

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


    }//End :: onload


</script>
