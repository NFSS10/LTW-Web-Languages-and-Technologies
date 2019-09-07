$(document).ready(checkResetButton);

function checkResetButton()
{
	$(".filter .f_button_reset").click(function() {
		$(".filter option").removeAttr("selected");

		var region = $(".filter .sel_filter_1").val();
		var rating = $(".filter .sel_filter_2").val();

		if(region != "Any")
			$(".filter .sel_filter_1 option:first").attr("selected", "SELECTED");

		if(rating != "Any")
			$(".filter .sel_filter_2 option:first").attr("selected", "SELECTED");
	});
}
