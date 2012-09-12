var url_modified = false;

window.addEvent('domready', function() {
	var title_field = $('Press_Article_title');
	if (title_field && $('new_record_flag')) {
		title_field.addEvent('keyup', update_url_title.pass(title_field));
		title_field.addEvent('change', update_url_title.pass(title_field));
		title_field.addEvent('paste', update_url_title.pass(title_field));
	}

	if ($('new_record_flag')) {
		var url_element = $('Press_Article_slug');
		url_element.addEvent('change', function(){url_modified=true;});
	}
});

function update_url_title(field_element)
{
	if (!url_modified)
		$('Press_Article_slug').value =  convert_text_to_url(field_element.value);
}