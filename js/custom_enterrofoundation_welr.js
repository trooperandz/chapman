$(document).ready(function() {
	$("#enterrotable").DataTable({
		paging: false,
		searching: false
	});
	
	$("#viewallros_table").DataTable({
		paging: true,
		searching: true
	});
	
	// The following code processes service entries from the main form via AJAX
	$('form#service_form').submit(function(event) {
		
		$('.form_error').hide();
		$('.ro_success').hide();
		
		var ronumber 		= document.getElementById('ronumber')		;
		var ro_date			= document.getElementById('ro_date')		;
		var yearmodelID 	= document.getElementById('yearmodelID')	;
		var mileagespreadID = document.getElementById('mileagespreadID');
		var vehicle_make_id	= document.getElementById('vehicle_make_id');
		var labor 			= document.getElementById('labor')			;
		var parts 			= document.getElementById('parts')			;
		var comment			= document.getElementById('comment')		;
		
		var svc_reg = new Array();
			$('input[id="svc_reg[]"]:checked').each(function(){
				svc_reg.push($(this).val());
			});
			
		var svc_add = new Array();
			$('input[id="svc_add[]"]:checked').each(function(){
				svc_add.push($(this).val());
			});	
			
		var svc_dec = new Array();
			$('input[id="svc_dec[]"]:checked').each(function(){
				svc_dec.push($(this).val());
			});	
			
		var svc_hidden = new Array();
			$('input[id="svc_hidden[]"]').each(function(){
				svc_hidden.push($(this).val());
			});
		
		var ronumber_req    = /^[0-9]{1,}$/;
		var ro_date_req		= /^([0-1][0-9])\/([0-3][0-9])\/([0-9]{4})$/;
		//var ro_date_req		= /^([0-9]{4})-([0-1][0-9])-([0-3][0-9])$/;
		//var labor_req   	= /^(?:|\d{1,5}(?:\.\d{2,2})?)$/; orig (allows null)
		//var parts_req   	= /^(?:|\d{1,5}(?:\.\d{2,2})?)$/; orig (allows null)
		var labor_req 		= /^[0-9]+(\.[0-9][0-9])?$/; // New pattern: forces user to at least enter a zero
		var parts_req 		= /^[0-9]+(\.[0-9][0-9])?$/; // New patter: forces user to at least enter a zero
		
		var errors = [];
		var focus = [];
		
		if (!ronumber_req.test(ronumber.value)) {
			errors.push("ro_error");
			focus.push("ronumber");
		}
		
		if (!ro_date_req.test(ro_date.value)) {
			errors.push("date_error");
			focus.push("ro_date");
		}
		
		if (yearmodelID.value == "") {
			errors.push("year_error");
			focus.push("yearmodelID");
		}
		
		if (mileagespreadID.value == "") {
			errors.push("mileage_error");
			focus.push("mileagespreadID");
		}
		
		if (vehicle_make_id.value == "") {
			errors.push("vehicle_error");
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
		
		if (svc_reg == '') {
			errors.push("service_error");
			focus.push("service_error");
		}
		
		if (labor.value > 1000 || parts.value > 1000) {
			if(!confirm('Your labor or parts figure is over $1,000.  Do you want to proceed?')) {
				return false;
			}
		}
		
		var dataString = {ronumber:ronumber.value, ro_date:ro_date.value, yearmodelID:yearmodelID.value, mileagespreadID:mileagespreadID.value,
						  vehicle_make_id:vehicle_make_id.value, labor:labor.value, parts:parts.value, comment:comment.value, svc_reg:svc_reg, 
						  svc_add:svc_add, svc_dec:svc_dec, svc_hidden:svc_hidden};
						  
		console.log(dataString);
		
		if(errors.length > 0 ){
			for(var i=0;i<errors.length;i++){
				document.getElementById(errors[i]).style.display="inline";
			}
			document.getElementById(focus[0]).focus();
			return false;
		} else {
			// AJAX Code To Submit Form.
			$.ajax({
				type: "POST",
				url: "enterrofoundationadd_process_welr.php",
				data: dataString,
				cache: false,
				success: function(returndata){
					console.log(returndata);
					if (returndata == "error_ro_dupe") {
						document.getElementById("error_ro_dupe").style.display="inline";
						document.getElementById("ronumber").focus();
						alert('That repair order already exists!');
						console.log(returndata);
					} else if (returndata == "error_session_timeout") {
						if(confirm('For security purposes, your session has timed out due to two hours of inactivity! \n Select \'Okay\' to be directed back to the login page.')) {
							window.location.assign('index.php');
						}
						console.log(returndata);
					} else if (returndata == "error_query") {
						document.getElementById("error_query").style.display="inline";
						document.getElementById("ronumber").focus();
						console.log(returndata);
					} else if (returndata == "error_login") {
						document.getElementById("error_login").style.display="inline";
						document.getElementById("ronumber").focus();
						console.log(returndata);
					} else if (returndata == "error_insert") {
						document.getElementById("error_insert").style.display="inline";
						document.getElementById("ronumber").focus();
						console.log(returndata);
					} else if (returndata == "error_survey_lock") {
						document.getElementById("error_survey_lock").style.display="inline";
						document.getElementById("ronumber").focus();
						console.log(returndata);
					} else {
						if (($(window).width() > 767)) {
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
							$('input:checkbox').removeAttr('checked');
							$('input:text').val('');
							$('select#yearmodelID').val('').prop('selected', true);
							$('select#mileagespreadID').val('').prop('selected', true);
							$('select#vehicle_make_id').val('').prop('selected', true);
							$('textarea').val('');
							document.getElementById("ronumber").focus();
						} else {
							window.location.reload(true);
						}
						console.log(returndata);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					var error_msg = "We are sorry, but your order was not processed by the server.\nPlease try again.  If the error persists, please see the administrator.\nError type: " + errorThrown;
					alert(error_msg);
				}
			});	
		}
		event.preventDefault();
	});
	
	// The following code processes the cycle time form via AJAX
	$('#cycletime_form').submit(function(event) {
	
		$('.form_error').hide();
		$('.cycletime_success').hide();
		
		var sample_date = document.getElementById('sample_date');
		var reception_time = document.getElementById('reception_time');
		var roc_time = document.getElementById('roc_time');
		var bay_time = document.getElementById('bay_time');
		var cycle_time = document.getElementById('cycle_time');
		
		var sample_date_req	= /^([0-1][0-9])\/([0-3][0-9])\/([0-9]{4})$/;
		//var sample_date_req = /^([0-9]{4})-([0-1][0-9])-([0-3][0-9])$/;
		var time_req = /^([0-9]{3}|[0-9]{2}):([0-5][0-9])$/;
		
		var errors = [];
		var focus = [];
		
		if (!sample_date_req.test(sample_date.value)) {
			errors.push("sample_date_error");
			focus.push("sample_date");
		}
		
		if (!time_req.test(reception_time.value)) {
			errors.push("reception_time_error");
			focus.push("reception_time");
		}
		
		if (!time_req.test(roc_time.value)) {
			errors.push("roc_time_error");
			focus.push("roc_time");
		}
		
		if (!time_req.test(bay_time.value)) {
			errors.push("bay_time_error");
			focus.push("bay_time");
		}
		
		if (!time_req.test(cycle_time.value)) {
			errors.push("cycle_time_error");
			focus.push("cycle_time");
		}
		
		var dataString = {sample_date:sample_date.value, reception_time:reception_time.value, 
						roc_time:roc_time.value, bay_time:bay_time.value, cycle_time:cycle_time.value};
		
		console.log(dataString);
		
		if(errors.length > 0 ){
			for(var i=0;i<errors.length;i++){
				document.getElementById(errors[i]).style.display="inline";
			}
			document.getElementById(focus[0]).focus();
			return false;
		} else {
			$.ajax({
				type: "POST",
				url: "cycletime_process.php",
				data: dataString,
				cache: false,
				success: function(returndata){
					console.log(returndata);
					if (returndata == "error") {
						$("#cycletime_response").html("<h6 style='color: red; line-height: 1; text-align: center;'>*Error: the entry was not processed.  See administrator.</h6>");
						$("#cycletime_response").fadeIn(400).delay(5000).fadeOut(600);
						console.log(returndata);
					} else if (returndata == "error_login") {
						$("#cycletime_response").html("<h6 style='color: red; line-height: 1; text-align: center;'>*Error: You are no longer logged in!</h6>");
						$("#cycletime_response").fadeIn(400).delay(5000).fadeOut(600);
					} else if (returndata == "error_session_timeout") {
							if(confirm('For security purposes, your session has timed out due to two hours of inactivity! \n Select \'Okay\' to be directed back to the login page.')) {
							window.location.assign('index.php');
						}
						console.log(returndata); 
					} else {
						$('#cycletime_response').html($('#cycletime_response' , returndata).html());
						$("#cycletime_response").fadeIn(400).delay(5000).fadeOut(600);
						$('input:text').val('');
						//document.getElementById("ronumber").focus();
						console.log(returndata);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					var error_msg = "We are sorry, but a processing error has occurred.\nPlease see the administrator with the following:\nError Type: " + errorThrown;
					alert(error_msg);
				}
			});	
		}
		event.preventDefault();
	});
	
	// The following code changes the $userID for enterrofoundationadd_process_welr via AJAX so that users may change advisor on the fly (this does not affect the $_SESSION['userID'] global)
	$("#advisor_enterro").change(function(event){
		$('.advisor_success').hide();
		var advisor_enterro = document.getElementById('advisor_enterro').value;
		var dataString = {advisor_enterro:advisor_enterro};
		console.log(dataString);
		// AJAX Code To Submit Form.
		$.ajax({
			type: "POST",
			url: "setuser_enterro_process.php",
			data: dataString,
			cache: false,
			success: function(returndata){
				if (returndata == "post_error") {
					alert("Error: Advisor selection has failed.  See administrator.");
				} else if (returndata == "error_session_timeout") {
					if(confirm('For security purposes, your session has timed out due to two hours of inactivity! \n Select \'Okay\' to be directed back to the login page.')) {
						window.location.assign('index.php');
					}
					console.log(returndata);
				} else {
					$( "#advisor_success" ).fadeIn( 300 ).delay( 3500 ).fadeOut( 400 );
				}	
			},
			error: function() {
				alert("Error: The system was unable to change the advisor! Please try again.\n See the administrator if the problem persists.");
			}
		});
		event.preventDefault();
	});
	
	// The following ensures that user cannot select LOF 'Add' or 'Dec'
	$("input:checkbox[name='svc_add\[\]'][value='1']").attr('disabled', true);
	$("input:checkbox[name='svc_dec\[\]'][value='1']").attr('disabled', true);	
});
	
// The following function ensures that only one checkbox may be checked at a time for each service
function check_checkboxes(i) {
	var svc_reg_array = document.getElementById('service_form')['svc_reg[]'];
	var svc_add_array = document.getElementById('service_form')['svc_add[]'];
	var svc_dec_array = document.getElementById('service_form')['svc_dec[]'];
	if (svc_reg_array[i].checked == true) {
		svc_add_array[i].checked = false;
		svc_dec_array[i].checked = false;
	} else if (svc_add_array[i].checked == true) {
		svc_reg_array[i].checked = false;
		svc_dec_array[i].checked = false;
	} else if (svc_dec_array[i].checked == true) {
		svc_reg_array[i].checked = false;
		svc_add_array[i].checked = false;
	}
}