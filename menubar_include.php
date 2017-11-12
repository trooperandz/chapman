 <div class="fixed">
 <nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href=""> Nissan - Dealer <?php echo $dealercode ?> </a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section">
	<!-- Right Nav Section -->
	<ul class="right">
        <li class="divider"></li>
        <li class="has-dropdown">
          <a href=""><?php echo "Welcome, {$user->firstName}"; ?></a>
          <ul class="dropdown">
            <li class="has-dropdown">
              <a href="" class="">Dealer Reports</a>
              <ul class="dropdown">
				<li><label>Dealer Reports</label></li>
                <li><a href="yearmodelqueryandchart.php">Model Year</a></li>
                <li><a href="mileagespreadqueryandchart.php">Mileage Spread</a></li>
                <li><a href="servicetypequeryandchart.php">Longhorn</a></li>
                <li><a href="lofdemandquery.php">LOF Demand</a></li>
                <li><a href="lofbaselinequery_column.php">LOF Baseline</a></li>
                <li><a href="singleissuequery.php">Single Issue %</a></li>
				<li><a href="singleissuecategory.php">Single Issue Cat</a></li>
				<li><a href="demand1and2query.php">Service Demand</a></li>
				<li><a style="color: #D34836;" href="csvexportall.php">Export All Data</a></li>
              </ul>
            </li>
			<li class="has-dropdown">
              <a href="" class="">Global Reports</a>
              <ul class="dropdown">
				<li><label>All Nissan Dealers</label></li>
				<li><a href="yearmodelqueryandchartglobal.php">Model Year</a></li>
				<li><a href="mileagespreadqueryandchartglobal.php">Mileage Spread</a></li>
				<li><a href="servicetypequeryandchartglobal.php">Longhorn</a></li>
				<li><a href="lofdemandqueryglobal.php">LOF Demand</a></li>
				<li><a href="lofbaselinequery_columnglobal.php">LOF Baseline</a></li>
				<li><a href="singleissuequeryglobal.php">Single Issue %</a></li>
				<li><a href="singleissuecategoryglobal.php">Single Issue Cat</a></li>
				<li><a href="demand1and2queryglobal.php">Service Demand</a></li>
				<li><a style="color: #D34836;" href="csvexportallglobal.php">Export All Data</a></li>
			  </ul>
			</li>
			<li class="has-dropdown">
              <a href="" class="">Comparison Reports</a>
              <ul class="dropdown">
				<li><label>Comparison Reports</label></li>
				<li><a href="yearmodelqueryandchartcomparison.php">Model Year</a></li>
				<li><a href="mileagespreadqueryandchartcomparison.php">Mileage Spread</a></li>
				<li><a href="servicetypequeryandchartcomparison.php">Longhorn</a></li>
				<li><a href="lofdemandquerycomparison.php">LOF Demand</a></li>
				<li><a href="lofbaselinequery_columncomparison.php">LOF Baseline</a></li>
				<li><a href="singleissuequerycomparison.php">Single Issue %</a></li>
				<li><a href="singleissuecategorycomparison.php">Single Issue Cat</a></li>
				<li><a href="demand1and2querycomparison.php">Service Demand</a></li>
				<li><a style="color: #D34836;" href="csvexportallcomparison.php">Export All Data</a></li>
			  </ul>
			</li>
			<li class="divider"></li>
			<li><a href="enterrofoundation.php">Enter ROs</a></li>
			<li class="divider"></li>
			<li><a href="admin-process.php">Admin</a></li>
			<li class="divider"></li>
            <li><a href="logout.php">Logout</a></li>
            <li class="divider"></li>
          </ul>
        </li>
      </ul>
    </section>
</nav> 
</div>
