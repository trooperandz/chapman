<?php
class Validation {
	
	public function validDollarValue($item) {
		// Validate dollar amounts.  Note:  can match null values, and any dollar value with 2 decimals with up to 5 places before the decimal.
		$pattern = '/^(?:|\d{1,5}(?:\.\d{2,2})?)$/';
		return preg_match($pattern, $item) == 1 ? TRUE : FALSE;
	}
	
	public function validWholeNumber($item) {
		$pattern = '/^[0-9]{1,}$/';
		return preg_match($pattern, $item) == 1 ? TRUE : FALSE;
	}
	
	public function validDate($item) {
		// Format is in dd/mm/yyyy
		$pattern = '/^([0-1][0-9])\/([0-3][0-9])\/([0-9]{4})$/';
		return preg_match($pattern, $item) == 1 ? TRUE : FALSE;
	}
	
	public function validDecimal($item) {
		$pattern = '/^[0-9]+(\.[0-9]{1,2})?$/';
		return preg_match($pattern, $item) == 1 ? TRUE : FALSE;
	}
	
	public function validPercentage($item) {
		$pattern = '/^(?:|\d{1,2}(?:\.\d{1,2})?)$/';
		return preg_match($pattern, $item) == 1 ? TRUE : FALSE;
	}
}
?>