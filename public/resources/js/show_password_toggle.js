function showPsw() {
    var x = document.getElementsByName("password");
    for (const elem of x) {
        if (elem.type === "password") {
            elem.type = "text";
        } else {
            elem.type = "password";
        }
    }
}