$(document).ready(checkSub);

function validPhoneNumber(phoneNumber)
{
	var phoneno = /^\+?([0-9]{12})$/;
	var phoneFormat = /^\d{9}$/;
	if(phoneno.test(phoneNumber) || phoneFormat.test(phoneNumber))
		return true;
	else
		return false;
}

function validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}
function checkSub()
{
	$("form#manage_acc").submit(function(event) {
		var email = $("input#email").val();

		if(email != "") {
			if (!validateEmail(email)) {
				$("span#emailmessage").text(" Invalid Email...").show();
				$("span#emailmessage").delay(1000).fadeOut(2500);
				event.preventDefault();
			}
		}
		else{
			$("span#emailmessage").hide();
		}

		var old_password = $("input#old_password").val();
		var password1 = $("input#new_password").val();
		var password2 = $("input#new_password2").val();
		if(password1 != password2)
		{
			$("span#passwordmessage2").text(" Passwords don't match...").show();
			$("span#passwordmessage2").delay(1000).fadeOut(2500);
			event.preventDefault();
		}
		else
		{
			$("span#passwordmessage2").hide();
		}

		if(password1.length < 5 && password1.length > 1)
		{
			$("span#passwordmessage").text(" Password is too short...").show();
			$("span#passwordmessage").delay(1000).fadeOut(2500);
			event.preventDefault();
		}
		else
		{
			$("span#passwordmessage").hide();
		}

		if(old_password == "" && (password1 != "" || password2 != ""))
		{
			$("span#oldpasswordmessage").text(" Old password must be inserted!").show();
			$("span#oldpasswordmessage").delay(1000).fadeOut(2500);
			event.preventDefault();
		}
		else if (old_password != "<?php echo $user_info['password']; ?>"  && password1 != "")
		{
			$("span#oldpasswordmessage").text(" Old password does not match!").show();
			$("span#oldpasswordmessage").delay(1000).fadeOut(2500);
			event.preventDefault();
		}
		else
			$("span#oldpasswordmessage").hide();



		var phone_number = $("input#phone_number").val();

		if (!validPhoneNumber(phone_number) && (phone_number != "" && phone_number != 0)) {
			$("span#phonemessage").text(" Invalid phone number...").show();
			$("span#phonemessage").delay(1000).fadeOut(2500);
			event.preventDefault();
		}
		else{
			$("span#phonemessage").hide();
		}

	});
}