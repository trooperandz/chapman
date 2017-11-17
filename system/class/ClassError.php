<?php 

/* --------------------------------------------------------------------------*
	Program:	ClassError.php
	
    Purpose: Display error and success messages
	History:
	Date		Description												by
	06/24/2016	Provide for display of user feedback					Matt Holland
 *---------------------------------------------------------------------------*/
class Error {
	public function displayFeedback() {
		//return 'entered Error class, displayFeedback() method!';
		$html ='
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">';
				if (isset($_SESSION['error'])) {
					for ($i=0; $i < count($_SESSION['error']); $i++) {
						$html .='<h6 style="color: #FF0000; font-size: 15px; margin-left: 16px;">' .$_SESSION['error'][$i]. '</h6>';
					}
				}
				if (isset($_SESSION['success'])) {
					for ($i=0; $i < count($_SESSION['success']); $i++) {
						$html .='<h6 style="color: #228B22; font-size: 15px; margin-left: 16px;">' .$_SESSION['success'][$i]. '</h6>';
					}
				}
			$html .='
			</div>
		</div>';
		if(isset($_SESSION['error']) || isset($_SESSION['success'])) {
			unset($_SESSION['error'], $_SESSION['success']);
			return $html;
		} else {
			return null;
		}
	}
} // end class Error