$(document).ready(addErrorMessage);

function addErrorMessage(){

    $("span#add_r_nau").text("Username not found.").show();
    $("span#add_r_nau").delay(1000).fadeOut(2500);
}