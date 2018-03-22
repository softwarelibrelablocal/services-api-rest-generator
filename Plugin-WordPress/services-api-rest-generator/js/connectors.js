// Variables
var engine,
    button,
    connstring,
    servername,
    databasename,
    username,
    password;
var disabledClass = "button-disabled";

var connectors = {
    addEventListeners: function(){
        jQuery("#btn-check-connector").on('click', function(e){

            if( jQuery(this).hasClass("button-disabled")) return;

            var check_connector_nonce = document.getElementById("check_connector_nonce");
            var messok = document.querySelector('.message.success');
            var messko = document.querySelector('.message.failure');

            jQuery.ajax({
                beforeSend: function(){
                    jQuery('#connector-checker .spinner').toggleClass('active');
                    messko.classList.remove('active');
                    messok.classList.remove('active');
                },
                complete: function(){
                    jQuery('#connector-checker .spinner').toggleClass('active');
                },
                type : "post",
                url : ajaxurl,
                data : {
                    action:                 "check_connector",
                    engine:                 engine.value,
                    connstring:             connstring.value,
                    servername:             servername.value,
                    databasename:           databasename.value,
                    username:               username.value,
                    password:               password.value,
                    check_connector_nonce:  check_connector_nonce.value
                },
                success: function(response) {

                    if(response){
                        messok.classList.add('active');
                        messko.classList.remove('active')
                    }
                    else{
                        messko.classList.add('active');
                        messok.classList.remove('active')
                    }

                }
            });

            e.preventDefault();
        });

        engine.addEventListener("change", connectors.showCheckButton);
        connstring.addEventListener("keyup", connectors.showCheckButton);
        servername.addEventListener("keyup", connectors.showCheckButton);
        databasename.addEventListener("keyup", connectors.showCheckButton);
        username.addEventListener("keyup", connectors.showCheckButton);
        password.addEventListener("keyup", connectors.showCheckButton);
    },
    showCheckButton: function(){

        // Remove any message
        var messok = document.querySelector('.message.success');
        var messko = document.querySelector('.message.failure');
        messko.classList.remove('active');
        messok.classList.remove('active');

        switch( engine.value ) {
            case "oracle":
                
                if( connstring.value !== "" && username.value !== "" && password.value !== "" ) {
                    button.classList.remove(disabledClass);
                }
                else {
                    button.classList.add(disabledClass);
                }
                break;
            case "mssql":
            case "mysql":

                if( username.value !== "" && password.value !== "" && servername.value !== "" && databasename.value !== "" ) {
                    button.classList.remove(disabledClass);
                }
                else {
                    button.classList.add(disabledClass);
                }
                break;
        }
    }
}


jQuery(document).ready(function($){
    // Get the elements
    engine = document.getElementById("acf-field_58283a30ff695");
    button = document.getElementById("btn-check-connector");
    connstring = document.getElementById("acf-field_582839fdff694");
    servername = document.getElementById("acf-field_583d3ec576680");
    databasename = document.getElementById("acf-field_583d4166ca441");
    username = document.getElementById("acf-field_5836d90170d5a");
    password = document.getElementById("acf-field_5836d91d70d5b");

    // Check the button
    connectors.showCheckButton();

    // Add the Listeners when the DOM is ready
    connectors.addEventListeners();
});


