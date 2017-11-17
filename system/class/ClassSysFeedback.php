<?php 
/**
 * File: ClassSysFeedback.php
 * Purpose:  Provide system process feedback for the following occurrences:
 *				- Display sys error msg to system users, & email error msg to system admin
 *				- Display sys success msg to system users for any successful UPDATEs, INSERTSs, etc.
 *
 * PHP version 5.5.29
 * @author Matthew Holland
 *
 *History:
 *	Date			Description												By
 * 	06/24/16		Initial design and coding								Matt Holland
 *  08/09/16		Revamp class properties, rename class to SysFeedback,	Matt Holland
 *					add emailError() method, rename displayError() to
 *					displaySysFeedback()
 */

class SysFeedback {

	//static public $manuf;
	//static public $admin_email;
	public $error_msg;
	public $success_msg;
	public $msg_color;

	/**
	 * Defines class properties on class invocation
	 * @param N/A
	 * @return array array of feedback messages
	 */
	public function __construct() {
		// Define manufacturer from system constant MANUF (Nissan, Acura, Subaru, etc)
		//self::$manuf = MANUF;

		// Define system administrator email from system constant for fatal error reporting
		//self::$admin_email = ADMIN_EMAIL;

		// Assign error/success arrays at class instantiation
		if (isset($_SESSION['error'])) {
			$this->error_msg = $_SESSION['error'];
			$this->msg_color = '#FF0000;';
		} elseif (isset($_SESSION['success'])) {
			$this->success_msg = $_SESSION['success'];
			$this->msg_color = '#228B22;';
		}
	}

	/**
	 * Creates string of user feedback html for success or error messages
	 * @param N/A
	 * @return string user feedback html
	 * Note: Make static, as function will be used extensively for all system queries
	 */
	public static function displaySysFeedback() {
		$msg = (is_array($this->error_msg)) ? $this->error_msg : $this->success_msg;
		$html ='
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">';
				foreach($msg as $m) {
					$html .='
					<p style="color: '.$this->msg_color.'; font-size: 15px; margin-left: 16px;">'.$m.'</p>';
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
	
	/**
	 * Email system admin important system errors (db failures etc)
	 * @param string,string,string (line of error, file error occurred in, db error message)
	 * @return function mail (email system admin)
	 */
	public static function emailError($line, $file, $error) {
		$subject = MANUF. 'RO Survey Error Feedback';
		$msg  = "Dear admin, an important error has occurred on line ".$line." in file ".$file.": \n";
		$msg .= $error."\n";
		mail(ADMIN_EMAIL, $subject, $msg);
		return;
	}
} // end class SysFeedback