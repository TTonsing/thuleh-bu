var results_template = null;

function format_offensive(definition) {
	 return definition.replace(
		  '*deemed offensive*', 
		  '<span class="red">*deemed offensive*</span>');
}

function render_results(search_term, results) {
	var num_results = Object.keys(results).length;
	var html = results_template({
		"search_term": search_term, 
		"num_results": num_results, 
		"has_results": num_results > 0,
		"results": results});
	return html;	
}

function lookup(search_term) {
	 var results = {};
	 search_term = search_term.trim();
	 var pattern = new RegExp('^' + search_term + '$', 'i');
	 pattern.compile(pattern);
	 for(var word in dictionary) {
		  if(pattern.test(word)) {
				results[word] = dictionary[word];
		  }
	 }

	 var html = render_results(search_term, results);
	 $('#results').html(html);
}

function submit_lookup() {
	var lookup_obj = $("#lookup");
	var search_term = lookup_obj.val();
	lookup(search_term);

	if(!isMobile.any)
		lookup_obj.focus();
}

$(document).ready(function () {

	$('#lookup')
		.focus(function () {
			$(this).select();
		})
		.keypress(function (e) {
			if (e.which == 13) {
				$("#submit")
					.focus()
					.click();
			}
		})
		.select();

	$("#submit")
		.click(submit_lookup);

	$('[data-toggle="tooltip"]').tooltip();

	var results_script = $('#results-template').html();
	results_template = Handlebars.compile(results_script);

	Handlebars.registerHelper("formatDefinition", function (definition) {
		return new Handlebars.SafeString(format_offensive(definition));
	});
});