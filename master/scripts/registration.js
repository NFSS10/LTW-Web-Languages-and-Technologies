$(document).ready(checkSubmit);

function validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

function validName(name){
	return !/[~`!#$%\^&*+=\-\[\]\\;,/{}|\\":<>\?]/g.test(name);
}

function checkSubmit()
{
	$(".main").css({"background-image":"url('resources/background.jpg')",
		"background-size":"100% 100%",
		"min-height":"450px"});

	$(".login_register").css({"height":"400px"});

	$("form#registration").submit(function(event) {


		var fail_counter = 0;
		var email = $("input#email").val();
		if(!validateEmail(email))
		{
			fail_counter++;
			$("span#emailmessage").text(" Invalid Email...").show();
			event.preventDefault();

		}
		else
		{
			$("span#emailmessage").hide();
		}

		var password1 = $("input#password").val();
		var password2 = $("input#password2").val();
		if(password1 != password2)
		{
			fail_counter++;
			$("span#passwordmessage2").text(" Passwords don't match...").show();
			event.preventDefault();
		}
		else
		{
			$("span#passwordmessage2").hide();
		}

		if(password1.length < 5)
		{
			fail_counter++;
			$("span#passwordmessage").text(" Password is too short...").show();
			event.preventDefault();
		}
		else
		{
			$("span#passwordmessage").hide();
		}

		var username = $("input#username").val();
		if(username.length < 3 || username.length > 15)
		{
			fail_counter++;
			$("span#usernamemessage").text(" Username length must be between 3 and 15 characters...").show();
			event.preventDefault();
		}
		else if(!validName(username))
		{
			fail_counter++;
			$("span#usernamemessage").text(" Username can not contain symbols!").show();
			event.preventDefault();
		}
		else
		{
			$("span#usernamemessage").hide();
		}

		//extend background
		if(fail_counter)
		{
			var background_height = 400 + 40*fail_counter;
			var sign_up_height = 370 + 35*fail_counter;
			$(".main").css({"min-height": background_height});

			$(".login_register").css({"height":sign_up_height});
		}

	});
}