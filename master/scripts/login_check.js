$(document).ready(checkSubmission);

function checkSubmission() {
	$(".main").css({"background-image":"url('resources/background.jpg')",
		"background-size":"100% 100%",
		"min-height":"400px"});

	$("form#login_form").submit(function(event) {
			var username = $("#login_form input#username").val();
			var password = $("#login_form input#password").val()

			if(username == "" || password =="") {

				$("form#login_form .login_span").text("Incorret username and/or password").show();
				$("form#login_form .login_span").delay(1000).fadeOut(2500);
				event.preventDefault();
			}

		}
)};


