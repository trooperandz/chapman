<?php
if (isset($_POST['multidealer']) && !empty($_POST['multidealer'])) {
	$multidealer = $mysqli->real_escape_string($_POST['multidealer']);
	/*  Parse multiple dealers entered  */
	$dealerarray = str_getcsv($multidealer, ",");
	$multidealerIDs = "";
	$multidealerID = "";
	/*  Trim whitespace and validate dealers entered  */
	for ($i=0; $i<sizeof($dealerarray); $i++) {
		$dealerarray[$i] = trim($dealerarray[$i]);
		if (validate_dealer($mysqli, $dealerarray[$i], $multidealerID)) {
			if (dealer_is_in_repairorder($mysqli, $multidealerID)) {
				$multidealerIDs .= $multidealerID;
				if ($i < sizeof($dealerarray)-1) {
					$multidealerIDs .= ",";
					}
				}
			else {
				/*  dealerID is not in repair order table  */
				$_SESSION['error'][] = "Dealer ".$dealerarray[$i]." has no records";
				die (header("Location: ".$_SESSION['lastpageglobalreports']));
				}
			}
		else {
			/*  dealerID not in dealer table  */
			$_SESSION['error'][] = "Dealer ".$dealerarray[$i]." does not exist";
			die (header("Location: ".$_SESSION['lastpageglobalreports']));
		}
	}  /* end for */
	/*  Set global for multi-dealer to use in query if all dealers OK  */
	$_SESSION['multidealer'] = $multidealerIDs;
	$_SESSION['multidealercodes'] = $multidealer;
}
?>