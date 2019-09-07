$(document).ready(checkSubmit);

function checkSubmit()
{

	$("form#restaurant_reply_form").submit(function(event)
	{
		var comment = $.trim($('#comment_review_reply').val());

		if (comment.length == 0) {
			$("span#comment_review_reply_span").css("color", "red");
			$("span#comment_review_reply_span").text("Answer cannot be empty").show();
			$("span#comment_review_reply_span").delay(1000).fadeOut(2500);
			event.preventDefault();
		}
		else
		{
			$("span#comment_review_reply_span").hide();
		}
	});
}
