<?php
if (isset($_SESSION['multidealercodes']) && $_SESSION['multidealercodes'] != "") {
	echo 'Nissan Global <span style="font-size: 17px; color: #A9A9A9;">(' .$_SESSION['globalsurvey_description']. 's)</span>';
} elseif (isset($_SESSION['regiondealerIDs']) && $_SESSION['regiondealerIDs'] != "") {
	echo 'Nissan ' .$_SESSION['regionname']. ' Region <span style="font-size: 17px; color: #A9A9A9;">(' .$_SESSION['globalsurvey_description']. 's)</span>';
} else {
	echo 'All Nissan Dealers <span style="font-size: 17px; color: #A9A9A9;">(' .$_SESSION['globalsurvey_description']. 's)</span>';
}
?>