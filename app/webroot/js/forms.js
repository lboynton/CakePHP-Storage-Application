// 
// Provides form related JS functions
//

// calls functions after the page has loaded
Event.observe(window, 'load', function() 
{		
	formHelp();	
	focusFirstFormElement();
});

/**
 * If there is a form on the page, this will loop through each input and stop at and focus on
 * the first empty input or submit button it finds
 */
function focusFirstFormElement()
{
	// loop through each input
	$$('input').each(function(item)
	{
		if(item.value.empty() || item.match('input[type="submit"]'))
		{
			item.focus();
			throw $break;
		}
	});
}

/**
 * This will add observers and show the help (if it exists) for each text input and password input in a form
 */
function formHelp()
{
	// loop through each input type="text" and input type="password"
	$$(['input[type="text"]', 'input[type="password"]']).each(function(item)
	{
		if(!$(item.id + 'Help')) throw $break;
		
		// hide the help on page load
		$(item.id + 'Help').setStyle('visibility:hidden');	
		
		Event.observe(item, 'focus', function(event)
		{
			$(item.id + 'Help').setStyle('visibility:visible');	
		});
		Event.observe(item, 'blur', function(event)
		{
			$(item.id + 'Help').setStyle('visibility:hidden');	
		});
	});
}

function toggleCheckboxes(controller)
{
	$$('input[type="checkbox"]').each(function(item)
	{
	});
}

/**
 * Toggles a form item between disabled and not disabled
 */
Element.Methods.toggleDisable = function(element) 
{
	if (element.hasAttribute('disabled')) 
	{
		element.removeAttribute('disabled');
	} 
	else 
	{
		element.setAttribute('disabled', 'disabled');
	}
}
Element.addMethods();