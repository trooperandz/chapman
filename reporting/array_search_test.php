<?php


echo getSvcTable();


/* FUNCTIONS */

function getSvcTable() {	
	$update_form = false;
	$search_params = false;
	
	// Initialize empty arrays for each checkbox if $update_form == false
	if (!$update_form) {
		$array = array('svc_reg'=>array(), 'svc_add'=>array(), 'svc_dec'=>array());
	}
	
	$svc_tables ='
		<table>
			<tr>
				<td>LOF</td>'.
				getRegBox(1, 0, $array['svc_reg'], $array['svc_add'], $array['svc_dec'], $search_params).
				getAddBox(1, 0, $array['svc_add'], $array['svc_reg'], $search_params).
				getDecBox(1, 0, $array['svc_dec'], $array['svc_reg'], $search_params).
				getHiddenInput(1, $search_params).'
			</tr>
		</table>';
		
	return $svc_tables;
}

/* Note:
 * This is the fix that I had to add in order to remove the array_search warning: 'parameter two expecting array, null given':
 * if ($svc_reg == null) {
 *		$svc_reg = array();
 *	}
 * The above if(!$update_form) has already set $svc_reg to an empty array, so why is the warning appearing?
**/

function getRegBox ($svc_id, $id, $svc_reg, $svc_add, $svc_dec, $search_params) {
	// Set names and ids based on search_params (so that modal and main forms do not conflict with ids and names)
	$id = $name = (!$search_params) ? 'svc_reg[]' : 'svc_reg';
	
	// See if this service box is in services rendered table 
	$key = array_search($svc_id, $svc_reg);	
	
	if ($key == FALSE) {
		$html ='<td><input type="checkbox" id="'.$id.'" name="'.$name.'" onclick="check_checkboxes('.$i.');" value="'.$svc_id.'"></td>';
	} else {
		if ($svc_add[$key] == 0 && $svc_dec[$key] == 0) {
			$html ='<td><input type="checkbox" id="'.$id.'" name="'.$name.'" onclick="check_checkboxes('.$i.');" value="'.$svc_id.'" checked></td>';
		} else {
			$html ='<td><input type="checkbox" id="'.$id.'" name="'.$name.'" onclick="check_checkboxes('.$i.');" value="'.$svc_id.'"></td>';
		}
	}
	return $html;
}

function getAddBox ($svc_id, $i, $svc_add, $svc_reg, $search_params) {
	// Set names and ids based on search_params (so that modal and main forms do not conflict with ids and names)
	$id = $name = (!$search_params) ? 'svc_add[]' : 'svc_add';
	
	// See if this add box is associated with row in services rendered table
	$key = array_search($svc_id, $svc_reg);	
	
	if ($key == FALSE) {
		$html ='<td><input type="checkbox" id="'.$id.'" name="'.$name.'" onclick="check_checkboxes('.$i.');" value="'.$svc_id.'"></td>'; 
	} else {
		if ($svc_add[$key] == 0) {
			$html ='<td><input type="checkbox" id="'.$id.'" name="'.$name.'" onclick="check_checkboxes('.$i.');" value="'.$svc_id.'"></td>';  
		} else {
			$html ='<td><input type="checkbox" id="'.$id.'" name="'.$name.'" onclick="check_checkboxes('.$i.');" value="'.$svc_id.'" checked></td>'; 
		}
	}
	return $html;
}

function getDecBox ($svc_id, $i, $svc_dec, $svc_reg, $search_params) {
	// Set names and ids based on search_params (so that modal and main forms do not conflict with ids and names)
	$id = $name = (!$search_params) ? 'svc_dec[]' : 'svc_dec';
	
	// See if this add box is associated with row in services rendered table
	$key = array_search($svc_id, $svc_reg);	
	
	if ($key == FALSE) {
		$html ='<td><input type="checkbox" id="'.$id.'" name="'.$name.'" onclick="check_checkboxes('.$i.');" value="'.$svc_id.'"></td>'; 
	} else {
		if ($svc_dec[$key] == 0) {
			$html ='<td><input type="checkbox" id="'.$id.'" name="'.$name.'" onclick="check_checkboxes('.$i.');" value="'.$svc_id.'"></td>';  
		} else {
			$html ='<td><input type="checkbox" id="'.$id.'" name="'.$name.'" onclick="check_checkboxes('.$i.');" value="'.$svc_id.'" checked></td>'; 
		}
	}
	return $html;
}

function getHiddenInput ($svc_id, $search_params) {
	$html ='
	<input type="hidden" id="svc_hidden[]" name="svc_hidden[]" value="'.$svc_id.'">';
	return (!$search_params) ? $html : null;
}
?>