$(document).ready(function (){

    $("#login-form").one('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        if ($("#Password").val() !== $("#Confirm_Password").val()) {
            alert("The two passwords aren't equal!");
            return;
        }

        formData.append("submitLogin");

        $(this).submit();
    });

});
