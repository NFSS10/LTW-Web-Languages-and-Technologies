$(displayMsg);

function displayMsg(){
	$("form#login_form .login_span").text("Incorret username and/or password").show();
	$("form#login_form .login_span").delay(1000).fadeOut(2500);
}