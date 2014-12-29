jQuery(document).ready(function($) {

	// Activate jquery ui tabs
    $("#dh_ptp_tabs_container").tabs();
	
	// Save tab state to dh_ptp_tab
	$("a[href=#dh_ptp_tabs_1], a[href=#dh_ptp_tabs_2]").on('click', function(){
		$('#dh_ptp_tab').val($(this).attr('href'));
	});

    //drag and drop for columns
    $("#wpa_loop-column").sortable({ axis: "x" });

	//activate color pickers
    $('.button-color').wpColorPicker({
	    palettes: ['#1abc9c', '#2ecc71','#3498db', '#9b59b6', '#34495e', '#f1c40f', '#e67e22', '#e74c3c', '95a5a6']
    });
    $('.button-border-color').wpColorPicker({
    	    palettes: ['#16a085', '#27ae60','#2980b9', '#8e44ad', '#2c3e50', '#f39c12', '#d35400', '#c0392b', '7f8c8d']
    });
    $('.colorpicker-no-palettes').wpColorPicker();   


	 //make sure that only decimal numbers are allowed to input. 
	 //source: http://jqueryexamples4u.blogspot.in/2013/09/validate-input-field-allows-only-float.html
	 $('.float-input').keypress(function(event) {
	      if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
	        event.preventDefault();
	      } 
	});

    //enable lightbox
    $(".inline-lightbox").colorbox({inline:true, width:"50%", speed: 0, fadeOut: 0});
	
	// Save & Preview button
	$('#dh_ptp_save_preview').on('click', function(event) {
        event.preventDefault();
		
		// Add target
		var form = $(this).closest('form');
		form.prop('target', '_blank');
		
		// Add preview_url parameter
		var url = $(this).attr('data-url');
		if ($('#dh_ptp_preview_url')) {
			$('#dh_ptp_preview_url').remove();
		}
		var preview_url_input = '<input type="hidden" name="dh_ptp_preview_url" id="dh_ptp_preview_url" value="' + url + '"/>';
		$(this).after(preview_url_input);
		
		// Submit form
		form.submit();
		  
        return false;
	});
	
	$('#dh_ptp_save').on('click', function(event) {
        event.preventDefault();
		
		// Add target
		var form = $(this).closest('form');
		form.removeAttr('target');

		// Remove preview url
		$('#dh_ptp_preview_url').remove();
		
		// Submit form
		form.submit();
		  
        return false;
	});
});

//activate twitter bootstrap popover
jQuery(function ($)
  { 
    $(".ptp-icon-help-circled").popover();  
    $(".plan-title #delete-button").popover({placement:'top'});  
    $(".plan-title .feature-button").popover({placement:'top'});  
  });  


// handle clicks on featured button
function buttonHandler(el)
{
	// required for wordpress
	var $ = jQuery;

	// toggle active button via css
	function toggleButtonClasses(el)
	{
		$(el).toggleClass('ptp-icon-star-empty');
		$(el).toggleClass('ptp-icon-star');
	}
	
	//toggle the value of our hidden input
	function setInputValue(el)
	{
		if($(el).val()=="unfeatured" || $(el).val()=="")
			$(el).val("featured");
		else if($(el).val()=="featured")
			$(el).val("unfeatured");
	}

	// toggles the elements class and value
	function myButtonClickHandler(el)
	{
		
		toggleButtonClasses(el);
		setInputValue(el.prev());

	}

	// use hasClass to figure out if current item is selected or not
	if (!$(el).hasClass('ptp-icon-star')) {
		// if the clicked item is not featured, unfeature the currently featured item ('.ptp-icon-star') by sending it to myButtonClickHandler
		myButtonClickHandler($('.ptp-icon-star'));
	}

	//	feature the clicked item by sending it to myButtonClickHandler
	myButtonClickHandler( $(el));

 	return false;
}


// handle clicks on featured button
function templateSelectorClickedHandler(el)
{
	// required for wordpress
	var $ = jQuery;

	// toggle active button via css
	function toggleButtonClasses(el)
	{
		$(el).toggleClass('template-selected');
	}
	
	//toggle the value of our hidden input
	function setInputValue(el)
	{
		if($(el).val()=="not-selected" || $(el).val()=="")
			$(el).val("selected");
		else if($(el).val()=="selected")
			$(el).val("not-selected");
	}

	// toggles the elements class and value
	function myButtonClickHandler(el)
	{
		
		toggleButtonClasses(el);
		setInputValue(el.find('.template-hidden-input'));

		//change visibility of advanced design settings
		setAdvancedDesignSettingsVisibility(el);


		//changeButtonText(el.find('.template-button'));

	}

	//toggle button text - disabled for now
	/*
	function changeButtonText(el)
	{
		if($(el).text()=="Use This Template")
			$(el).text("In Use");
		else if($(el).text == "In Use")
			$(el).text("Use This Template");
	}*/

	// use hasClass to figure out if current item is selected or not
	if (!$(el).parent().hasClass('template-selected')) {
		// if the clicked item is not featured, unfeature the currently featured item ('.ptp-icon-star') by sending it to myButtonClickHandler
		myButtonClickHandler($('.template-selected'));

		//now feature the current item
		myButtonClickHandler( $(el).parent());

	}

	return false;
}

//set settings visibility 
function setAdvancedDesignSettingsVisibility(el)
{
	// required for wordpress
	var $ = jQuery;

	if ($(el).attr('id') == "simple-flat-selector")
	{
		$('#simple-flat-advanced-design-settings').show();
		$('#fancy-flat-advanced-design-settings').hide();
		$('#stylish-flat-advanced-design-settings').hide();
	}
	else if ($(el).attr('id') == "fancy-flat-selector")
	{
		$('#simple-flat-advanced-design-settings').hide();
		$('#fancy-flat-advanced-design-settings').show();
		$('#stylish-flat-advanced-design-settings').hide();
	}
	else if ($(el).attr('id') == "stylish-flat-selector")
	{
		$('#simple-flat-advanced-design-settings').hide();
		$('#fancy-flat-advanced-design-settings').hide();
		$('#stylish-flat-advanced-design-settings').show();
	}
}