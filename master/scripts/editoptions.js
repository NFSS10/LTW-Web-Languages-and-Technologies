$().ready(loadOptions);
	

function loadOptions()
{	
	if(!restaurants.length)
	{
		var option = "<option value =\"-1\">none</option>";
			$("#restaurants select").append(option);
	}
	else
		for(var i=0; i< restaurants.length; i++)
		{
			var option = "<option value =\""+restaurants[i].id+"\">"+restaurants[i].name+"</option>";
			$("#restaurants select").append(option);
		}
}
