<?php require_once("functions.inc"); ?> 
<!doctype html> 
<html> 
<head> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
<script type="text/javascript" src="register.js"></script> 
<link rel="stylesheet" type="text/css" href="form.css"> 
<title> A Form </title>
</head>
</body>
<form id="userForm" method="POST" action="register-process.php"> 
<div> 
	<fieldset> 
	<legend> Registration Information </legend> 
	<div id="errorDiv"> 

<?php

	if (isset($_SESSION['error']) && isset($_SESSION['formAttempt'])) {
		unset($_SESSION['formAttempt']);
		print "Errors encountered <br />\n";
		foreach ($_SESSION['error'] as $error) { 
			print $error . "<br />\n"; 
		} // end foreach 
	} // end if 
?> 

</div> 
	<label for="fname">First Name:* </label>
	<input type="text" id="fname" name="fname">
	<span class="errorFeedback errorSpan" id="fnameError">First Name is required</span>
	<br />
	<label for="lname">Last Name:* </label>
	<input type="text" id="lname" name="lname">
	<span class="errorFeedback errorSpan" id="lnameError">Last Name is required</span>
	<br />
	<label for="email"> E-mail Address:* </label> 
	<input type="text" id="email" name="email">
	<span class="errorFeedback errorSpan" id="emailError"> E-mail is required </span> 
	<br />
	<label for="password1"> Password:* </label>
	<input type="password" id="password1" name="password1"> 
	<span class="errorFeedback errorSpan" id="password1Error"> Password required  </span> 
	<br /> 
	<label for="password2">Verify Password:* </label>
	<input type="password" id="password2" name="password2"> 
	<span class="errorFeedback errorSpan" id="password2Error"> Passwords don't match  </span> 
	<br /> 
	<label for="addr">Address: </label>
	<input type="text" id="addr" name="addr"> 
	<br />
	<label for="city">City: </label>
	<input type="text" id="city" name="city"> 
	<br />
	<label for="state"> State: </label>
	<select name="state" id="state">
	<option></option>
	<option value="AL">Alabama</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="FL">Florida</option>
	<option value="IL">Illinois</option>
	<option value="NJ">New Jersey</option>
	<option value="NY">New York</option>
	<option value="WI">Wisconsin</option>
	</select>
	<br />
	<label for="zip">ZIP: </label>
	<input type="text" id="zip" name="zip">
	<br />
	<label for="phone">Phone Number: </label>
	<input type="text" id="phone" name="phone">
	<span class="errorFeedback errorSpan" id="phoneError">Format: xxx-xxx-xxxx</span>
	<br />
	<br />
	<label for="work">Number Type:</label>
	<input class="radiobutton" type="radio" name="phonetype" id="work" value="work">
	<label class="radiobutton" for="work">Work</label>
	<input class="radiobutton" type="radio" name="phonetype" id="home" value="home">
	<label class="radiobutton" for="home">Home</label>
	<span class="errorFeedback errorSpan phoneTypeError" id="phonetypeError">Please choose an option</span>
	<br />
	<input type="submit" id="submit" name="submit"> 
</fieldset>

</div>
</form>
</body>
</html>