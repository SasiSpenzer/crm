function validateData(form) {
    text = " ";
    if(form.first_name.value == ""){
        $("#first_name_error").show();
        form.first_name.focus();
        return (false);
    }
    if (form.last_name.value == ""){
        $("#last_name_error").show();
        $("#first_name_error").hide();
        form.last_name.focus();
        return (false);
    }
    if (form.email.value == ""){
        $("#email_error").show();
        $("#first_name_error").hide();
        $("#last_name_error").hide();
        form.email.focus();
        return (false);
    }else{//validate email format
        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!filter.test(form.email.value)) {
            $("#email_error").show();
            $("#first_name_error").hide();
            $("#last_name_error").hide();
            form.email.focus();
            return (false);
        }
    }
    if (form.telephone.value == ""){
        $("#telephone_error").show();
        $("#first_name_error").hide();
        $("#last_name_error").hide();
        $("#email_error").hide();
        form.telephone.focus();
        return (false);
    } else {
        if(isNumeric(form.telephone.value)){

        } else {
            $("#telephone_error").show();
            $("#first_name_error").hide();
            $("#last_name_error").hide();
            $("#email_error").hide();
            form.telephone.focus();
            return (false);
        }
    }

    return (true);
}
function isNumeric(str) {
    if (typeof str != "string") return false // we only process strings!
    return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
        !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
}