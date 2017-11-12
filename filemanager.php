<?php
/* -------------------------------------------------------------------------------*
   Program: filemanager.php

   Purpose: Manage files - uploading and downloading .pdf & .pptx RO presentations

	History:
    Date			Description										by
	08/02/2014		Initial design and coding						Matt Holland
	12/11/2014		Updated car picture with php constant			Matt Holland
	12/11/2014		Added db_cxn.php template file					Matt Holland
	02/10/2015		Revamped file upload process to direct server	Matt Holland
					file access	instead of storing files in tables 
					Also added form inputs for description and title
 *--------------------------------------------------------------------------------*/
require_once("functions.inc");
include ('templates/login_check.php');
include('templates/lastpagevariable_filebin.php');  // Sets page variable for addfile_process.php

// Connect to the database
include('templates/db_cxn.php');

/* Set dealer ID */	
$dealerID = $_SESSION['dealerID'];

/* Set user ID */
$userID = $user->userID;
 
// Query for a list of all existing files
$query = "SELECT title, description, tmp_name, file_name, file_type, size, create_date FROM files ORDER BY create_date DESC";
$result = $mysqli->query($query);
if(!$result) {
	$_SESSION['error'][] = 'An error occurred while retrieving file information.  Please see the administrator.';
}
$rows = $result->num_rows; 
?>

<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RO Survey - File Manager</title>
    <link rel="stylesheet" href="css/foundation.css" />
	<link rel="stylesheet" type="text/css" href="css/dataTables.foundation.css" />
	<link rel="stylesheet" href="css/responsive-tables.css" media="screen" />
	<link rel="stylesheet" href="css/sticky_footer.css" />
	<style>
		.error {
			color: #FF0000;
			font-size: 15px;
		}
		
		.success {
			color: #228B22; 
			font-size: 15px;
		}
		
		.notice {
			color: blue; 
			font-size: 12px;
		}
		
		table.display {
			font-family: helvetica;
			font-size: 8pt;
			border-collapse: collapse;  
			margin-right: auto;
			margin-left: auto;
		}
		
		table.display thead tr th {
			background-color: whitesmoke;
			font-size: 10pt;
			text-align: center;
			border-left: 1px solid #CCCCCC;  
			width: 150px;
			height: 35px;
		}
		
		table.display tbody td {
			color: #3D3D3D;
			padding: 4px;
			vertical-align: center;
			border-left: 1px solid #CCCCCC;  
			height: 50px;
			text-align: center; 
			border-bottom: 1px solid #CCCCCC; 
		}
		
		.desc_td {
			width: 100px;
		}
		
		@media (min-width: 40.063em) {
			table.display thead tr th  {
				background-image: url(css/bg.gif);
			}
		}

		table.display thead tr th { 
			background-repeat: no-repeat;
			background-position: center right;
			cursor: pointer;
		}

		@media(min-width: 40.063em) {
			.hide_parent {
				text-align: right;
		}
		
		.hide_button {
			font-size: 14px;
			font-weight: bold;
		}
	</style>
    <script src="js/vendor/modernizr.js"></script>
	<script src="js/vendor/jquery.js"></script>
	<!--<script type="text/javascript" src="js/tablesorter.js"></script>-->
	<script>
		/*$(document).ready(function() { 
			$("#dealertable").tablesorter(); 
			} 
		); */
		
		$(document).ready(function() { 
			$("#filetable").DataTable(); 
		});
	</script>
</head>
<body>
<div class="wrapper">
<div class="fixed">
<nav class="top-bar" data-topbar>
  <!-- Title -->
	<ul class="title-area">
		<li class="name"><h1><a><?php echo constant('MANUF');?> - File Bin </a></h1></li>
		<!-- Mobile Menu Toggle -->
		<li class="toggle-topbar menu-icon"><a><span>Menu</span></a></li>
	</ul>
  <!-- Top Bar Section -->
	<section class="top-bar-section">
    <!-- Top Bar Right Nav Elements -->
    <ul class="right">
		<!-- Divider -->
		<li class="divider"></li>
		<!-- Dropdown -->
		<li class="has-dropdown">
			<a><?php echo "Welcome, {$user->firstName}"; ?></a>
			<ul class="dropdown">
				<li class="has-dropdown">
				<?php
				include('templates/menubar_sidecontents.php');
				?>
			</ul>
        </li>
      </ul>
    </section>
</nav> 
</div>

<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<h5 class="hide_parent"><a class="hide_button" id="hide_button" data-text-swap="Show Form">Hide Form</a><h5>
	</div>
</div>	
	
<div id="hide"> <!-- jQuery hide form element div-->
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<h2> Upload New File </h2>
		<?php
			if (isset($_SESSION['error'])) {
				$num_errors = sizeof($_SESSION['error']);
				for ($i=0; $i < $num_errors; $i++) {
					echo '<h6 class="error">' .$_SESSION['error'][$i]. '</h6><br />';
				} //end for 
			unset($_SESSION['error']);
			}
		
			if (isset($_SESSION['success'])) {
				$num_errors = sizeof($_SESSION['success']);
				for ($i=0; $i < $num_errors; $i++) {
					echo '<h6 class="success">' .$_SESSION['success'][$i]. '</h6><br />';
				} //end for
			unset($_SESSION['success']);
			}
		?>
	</div>
</div>

<form action="add_file.php" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="small-12 medium-5 large-5 columns">
			<p><img src="<?php echo constant('PIC_MENUS');?>"></p>
		</div>
		<div class="small-12 medium-4 large-4 columns">
			<div class="row">
				<div class="small-12 medium-12 large-12 columns">
					<label for="title">Title</label>
					<input type="text" id="title" name="title" value="<?php if(isset($_SESSION['file_title'])) echo $_SESSION['file_title'];?>">
					<!--<input type="hidden" name="MAX_FILE_SIZE" value="10000000">-->
				</div>
				<div class="small-12 medium-12 large-12 columns">
					<label for="description">Description</label>
					<input type="text" id="description" name="description" value="<?php if(isset($_SESSION['file_description'])) echo $_SESSION['file_description'];?>">
				</div>
				<div class="small-12 medium-12 large-12 columns">
					<p class="notice">**File type must be .pdf or .pptx; 15MB Limit</p>
					<input type="file" id="uploaded_file" name="uploaded_file"><br>
				</div>
				<div class="small-12 medium-12 large-12 columns">
					<input type="submit" value="Upload file" class="tiny button radius">
				</div>
			</div>
		</div>
		<div class="small-12 medium-3 large-3 columns">
			
		</div>
	</div>
</form>	
</div> <!--end Div 'hide' -->

<div class="row">
	<div class="small-12 medium-10 large-10 columns">
		<h2> <?php echo constant('MANUF');?> File Bin </h2>
	</div>	
	<div class="small-12 medium-2 large-2 columns">
		<h6>Total Files: <?php echo $rows;?> </h6>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> </p>
	</div>
</div>

<?php
// Make sure there are some files in there
if($rows == 0) {
    echo '
	<div class="row">
		<div class="small-12 medium-12 large-12 columns">
			<h6 style="color: #FF0000; font-size: 15px;">There are no files in the database</h5>
		</div>
	</div>';
}
?>
	<div class="row">
		<div class="small-12 medium-12 large-12 columns">
			<table id="filetable" class="display responsive" style="margin-left: auto; margin-right: auto; border-collapse: collapse;">
				<thead style="height: 35px; padding: 0px;">
					<tr style="background-color: #e5e5e5; height: 35px; padding: 0px; ">
						<th class="table_head"><a>Action		</a></th>
						<th class="table_head"><a>Title			</a></th>
						<th class="table_head"><a>Description	</a></th>   
						<th class="table_head"><a>File Name		</a></th>
						<th class="table_head"><a>Type			</a></th>
						<th class="table_head"><a>Size			</a></th>
						<th class="table_head"><a>Loaded		</a></th>
					</tr>
				</thead>
				<tbody>
<?php	
         // Print each file
        while($row = $result->fetch_assoc()) {
            echo '
					<tr>
						<td class="main_td"><a href="get_file.php?id='.$row['tmp_name'].'">Download</a>  </td>
						<td class="first_td">' 	.$row['title']. 		 	  			'</td>
						<td>' 	.$row['description']. 					'</td>
						<td class="main_td">' 	.$row['file_name']. 					'</td>
						<td class="main_td">' 	.$row['file_type']. 					'</td>
						<td class="main_td">'	.(int)($row['size'] / 1000).'KB			 </td>
						<td class="main_td">'	.substr($row['create_date'], 0, 10).    '</td>
					</tr>';
        }
?>			
				</tbody>
			</table>
		</div>
	</div>
		
<div class="row">
	<div class="small-12 medium-12 large-12 columns">
		<p> &nbsp; </p>
	</div>
</div>
<?php
// Free the result
$result->free();

// Unset sticky form elements upon page reload
unset($_SESSION['file_title']);
unset($_SESSION['file_description']);

// Close the mysql connection
$mysqli->close();
?>

<div class="push"></div>  	
</div> 

<footer>
	<span class="footer_span"><span class="copyright">&copy; <?php echo date('Y');?></span>&nbsp; Service Operations Specialists, Inc.</span>
	<span class="footer_feedback"><a href ="http://www.sosfirm.com" target="_blank"><img src="img/info-24.ico"></a>&nbsp; &nbsp;<a href="mailto: [mtholland10@gmail.com]?subject=Website feedback &body="><img src="img/email_icon.ico"></a></span>
</footer>
<script src="js/foundation.min.js"></script>
<script src="js/responsive-tables.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="js/dataTables.foundation.js"></script>
    <script>
      $(document).foundation();
	  $(document).ready(function() {
	  
		// Focuses the cursor in the RO input field (if browser does not support html 5 'autofocus' (stupid IE)
		$("#hide_button").click(function(){
			$("#hide").toggle(500);
		});
		
		$("#hide_button").on("click", function() {
		  var el = $(this);
		  if (el.text() == el.data("text-swap")) {
			el.text(el.data("text-original"));
		  } else {
			el.data("text-original", el.text());
			el.text(el.data("text-swap"));
		  }
		});
	});
    </script>
  </body>
</html>