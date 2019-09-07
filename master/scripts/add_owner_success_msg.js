$(document).ready(addSuccessMsg);

function addSuccessMsg(){

    $("span#add_r_success").text("Owner successfully added.").show();
    $("span#add_r_success").delay(1000).fadeOut(2500);
}