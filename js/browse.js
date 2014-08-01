function get()
{
	if( window.letter == '' ) return;
	$('#output').html("<div class='alert alert-success'>Searching...</div>").show();
	$.post('browse_script.php', { let: window.letter, page: window.page_num, max_dash: $('#num_entr option:selected').val() },
		function(output)
		{
			$('#output').html(output).show();
		});
}
    	
function query_db()
{
	$('#output').html("");	
	$.post('browse_query.php', { name: window.prot_name, page: window.page_num, max_dash: $('#num_entr option:selected').val() },
		function(output)
    	{
			$('#output').html(output).show();
		});
}
    	
function next_page()
{
	if( window.letter === '' ) return;
	window.page_num++;
	if(window.prot_name === '') {
		get();
	} else {
		query_db();
	}
}
    	
function prev_page()
{
	if(window.page_num == 0 || window.letter == '') return;
	window.page_num--;
	if(window.prot_name === '') {
		get();
	} else {
		query_db();
	}
}

function add_letter_nav() {
	var abc = "#ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	
	var navBar = "";
	for(var i = 0; i < abc.length; i++)
	{
		var letter = abc.charAt(i);
		navBar += "<input type='button' class='btn btn-mini btn-primary' value="+ letter + " onClick=\"window.page_num=0; letter=\'" + letter + "\'; add_level2_nav(\'"+letter+"\'); get();\"/>";
	}
	
	$('#lev1').html(navBar);
}

function add_level2_nav(seedLetter) {
	var abc = "#ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	
	var navBar = "";
	for(var i = 0; i < abc.length; i++)
	{
		var letter = seedLetter + abc.charAt(i);
		navBar += "<input type='button' class='btn btn-mini btn-primary' value="+ letter + " onClick=\"window.page_num=0; letter=\'" + letter + "\'; add_level3_nav(\'"+letter+"\'); get();\"/>";
	}
	
	$('#lev2').html(navBar);
	$('#lev3').html("");
}

function add_level3_nav(seedLetter) {
	var abc = "#ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	
	var navBar = "";
	for(var i = 0; i < abc.length; i++)
	{
		var letter = seedLetter + abc.charAt(i);
		navBar += "<input type='button' class='btn btn-mini btn-primary' value="+ letter + " onClick=\"window.page_num=0;letter=\'"+letter+"\';get();\"/>";
	}
	
	$('#lev3').html(navBar);
}


$(document).ready(function()
	{
		$('.nav-tabs').button();
		window.page_num = 0;
		window.letter = '';
		window.prot_name = '';
		add_letter_nav();
	});