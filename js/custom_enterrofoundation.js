$(document).ready(function() { 
		// Establish ajax processing time for spinner
		var timeDelay = 350;

		// Initialize dataTables 
		$("#enterrotable").DataTable({
			paging: false,
			searching: false
		});
		
		$('form#service_form').submit(function(event) {
			
			$('.form_error').hide();
			$('.ro_success').hide();
			
			var ronumber 		= document.getElementById('ronumber')		;
			var yearmodelID 	= document.getElementById('yearmodelID')	;
			var mileagespreadID = document.getElementById('mileagespreadID');
			var singleissue 	= document.getElementById('singleissue')	;
			var labor 			= document.getElementById('labor')			;
			var parts 			= document.getElementById('parts')			;
			var labor_value		= document.getElementById('labor').value	;
			var parts_value		= document.getElementById('parts').value	;
			var comment			= document.getElementById('comment')		;
			
			var servicebox = new Array();
				$('input[id="servicebox[]"]:checked').each(function(){
					servicebox.push($(this).val());
				});
				
			var addsvc = new Array();
				$('input[id="addsvc[]"]:checked').each(function(){
					addsvc.push($(this).val());
				});	
			
			var ronumber_req    = /^[0-9]{1,}$/;
			var labor_req       = /^(?:|\d{1,5}(?:\.\d{2,2})?)$/;
			var parts_req       = /^(?:|\d{1,5}(?:\.\d{2,2})?)$/;
			
			var errors = [];
			var focus = [];
			
			if (!ronumber_req.test(ronumber.value)) {
				errors.push("ro_error");
				focus.push("ronumber");
			}
			
			if (yearmodelID.value == "") {
				errors.push("year_error");
				focus.push("yearmodelID");
			}
			
			if (mileagespreadID.value == "") {
				errors.push("mileage_error");
				focus.push("mileagespreadID");
			}
			
			if (singleissue.value == "") {
				errors.push("single_error");
				focus.push("singleissue");
			}
			
			if (!labor_req.test(labor.value)) {
				errors.push("labor_error");
				focus.push("labor");
			}
			
			if (!parts_req.test(parts.value)) {
				errors.push("parts_error");
				focus.push("parts");
			}
			
			if (servicebox == '') {
				errors.push("service_error");
				focus.push("service_error");
			}
			
			if (servicebox.length == addsvc.length) {
				errors.push("service_error");
				focus.push("service_error");
			}
			
			if (labor_value > 1000 | parts_value > 1000) {
				if(!confirm('Your Labor or Parts amount is greater than $1,000.  Are you sure you want to proceed?')) {
					return false;
				}
			}
			
			var dataString = {ronumber:ronumber.value, yearmodelID:yearmodelID.value, mileagespreadID:mileagespreadID.value,
							  singleissue:singleissue.value, labor:labor.value, parts:parts.value, comment:comment.value, servicebox:servicebox, addsvc:addsvc};
							  
			console.log(dataString);
			
			if(errors.length > 0 ){
				for(var i=0;i<errors.length;i++){
					document.getElementById(errors[i]).style.display="inline";
				}
				document.getElementById(focus[0]).focus();
				return false;
			} else {
				// Load the spinner to indicate processing
				$('div.loader_div').html('<div class="spinner">Loading...</div>');
				
				// The spinner is only removed once the ajax call is complete.
				setTimeout(ajaxCall, timeDelay);
				
				function ajaxCall() {
					// AJAX Code To Submit Form.
					$.ajax({
						type: "POST",
						url: "enterrofoundationadd_process.php",
						data: dataString,
						cache: false,
						success: function(returndata){
							console.log(returndata);
							if (returndata == "error_ro_dupe") {
								// Remove the loading div before the content is updated
								$('.loader_div').empty();
								
								document.getElementById("error_ro_dupe").style.display="inline";
								document.getElementById("ronumber").focus();
								alert('That repair order already exists!');
								console.log(returndata);
							} else if (returndata == "error_query") {
								// Remove the loading div before the content is updated
								$('.loader_div').empty();
								
								document.getElementById("error_query").style.display="inline";
								document.getElementById("ronumber").focus();
								console.log(returndata);
							} else if (returndata == "error_login") {
								// Remove the loading div before the content is updated
								$('.loader_div').empty();
								
								document.getElementById("error_login").style.display="inline";
								document.getElementById("ronumber").focus();
								console.log(returndata);
							} else if (returndata == "error_insert") {
								// Remove the loading div before the content is updated
								$('.loader_div').empty();
								
								document.getElementById("error_insert").style.display="inline";
								document.getElementById("ronumber").focus();
								console.log(returndata);
							} else if (returndata == "error_survey_lock") {
								// Remove the loading div before the content is updated
								$('.loader_div').empty();
								
								document.getElementById("error_survey_lock").style.display="inline";
								document.getElementById("ronumber").focus();
								console.log(returndata);
							} else if (returndata == "error_survey_startyear") {
								// Remove the loading div before the content is updated
								$('.loader_div').empty();
								
								document.getElementById("error_survey_startyear").style.display="inline";
								document.getElementById("ronumber").focus();
								console.log(returndata);
							} else {
								// Remove the loading div before the content is updated
								$('.loader_div').empty();
								
								$('#update_div1').html($('#update_div1' , returndata).html());
								$('#update_div2').html($('#update_div2' , returndata).html());
								$('#update_div3').html($('#update_div3' , returndata).html());
								$('#update_div4').html($('#update_div4' , returndata).html());
								$('#update_div5').remove();
								$("#enterrotable").DataTable({
									paging: false,
									searching: false
								});
								$( "#ro_success" ).fadeIn( 300 ).delay( 3500 ).fadeOut( 400 );
								//alert(returndata);
								$('input:checkbox').removeAttr('checked');
								$('input:text').val('');
								$('select').val('').prop('selected', true);
								$('textarea').val('');
								document.getElementById("ronumber").focus();
							console.log(returndata);
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							var error_msg = "We are sorry, but your order was not processed.\nPlease check your internet connection and then try the order again.\nIf the problem persists, please see the administrator.\n";
							alert(error_msg);
						}
					});	
				}
			}
			event.preventDefault();
		});
	});
	
	function check_addboxes(i) {
		var svcbox_array = document.getElementById('service_form')['servicebox[]'];
		var addbox_array = document.getElementById('service_form')['addsvc[]'];
		if (addbox_array[i].checked == true) {
			svcbox_array[i].checked = true;
		} else if (addbox_array[i].checked == false) {
			svcbox_array[i].checked = false;
		}
	}
	
	function check_svcboxes(i) {
		var svcbox_array = document.getElementById('service_form')['servicebox[]'];
		var addbox_array = document.getElementById('service_form')['addsvc[]'];
		if (svcbox_array[i].checked == false) {
			addbox_array[i].checked = false;
		}
	}