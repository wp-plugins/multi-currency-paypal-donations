<div class="wrap">
<script type="text/javascript" src="../wp-includes/js/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
<!--
tinyMCE.init({
theme : "advanced",
mode : "exact",
elements : "editorContent",
width : "100%",
height : "200"
});
-->
</script>
<style type='text/css'>
hr{ margin-top: 30px; margin-bottom: 30px;}
</style>
<h2>Multi Currency Paypal Donations</h2>
<small>Malaysian Ringgit and Brazilian Real are currently not supported by this plugin at this time.</small>
<a href='http://makesomecode.com/multi-currency-paypal-donations-pro/'><img alt='upgrade to PRO today' src='http://makesomecode.com/resources/upgrade-today.png' /></a>
<form method="post" action="options.php">
<hr />
<h3>Usage Intrsuctions</h3>
<small>Type '{mcpdform}' (without the quotes) wherever you want the donate form to show up. The form needs a minimum of 370px wide to display properly. We recommend you make a page called 'Donate' and put '{mcpdform}' in it to make a donate page.</small>
<br />
<hr />
<h3>Thank-you Page</h3>
<small>Enter the URL the page the donor will be redirected to after a successful payment.</small>
<br />
<?php
	settings_fields( 'mcpd-options' );
	$options 			= get_option('mcpd-accounts');
	$thanks 			= get_option('mcpd-thanks');
	$itemname 			= get_option('mcpd-itemname');
	$monthlydef 		= get_option('mcpd-monthly');
	$onetimedef 		= get_option('mcpd-onetime');
	$onetimecheck 		= get_option('mcpd-onetimecheck');
	$monthlycheck	 	= get_option('mcpd-monthlycheck');
	$offsetcheck	 	= get_option('mcpd-offsetcheck');
	$contactlink		= get_option('mcpd-contactlink');
	$default			= get_option('mcpd-default');
	//$update				= get_option('mcpd-update');

	echo "\n<input type='text' name='mcpd-thanks' size='25' value='".$thanks."' /> \n";
	echo "<hr />";
?>
<h3>Item Name</h3>
<small>This is the name of the item that will show up on the donors Paypal receipt (ex. 'Donation to John').</small>
<br />

<?php
	echo "\n<input type='text' name='mcpd-itemname' size='25' value='".$itemname."' /> \n";
	echo "<hr />";
?>


<h3>Contact me link</h3>
<small>At the bottom of the page is a link if people are having trouble donating through the form. Enter the URL you want it to link to.</small>
<br />

<?php
	echo "\n<input type='text' name='mcpd-contactlink' size='35' value='".$contactlink."' /> \n";
	echo "<hr />";
?>

<h3>Default Donation Values</h3>
<small>Default value set in the donation boxes and whether or not they are checked when the page loads.</small>
<br />

<?php
	($monthlycheck == 'on') ? $monthlycheck = 'checked' : $monthlycheck = '';
	($onetimecheck == 'on') ? $onetimecheck = 'checked' : $onetimecheck = '';
	($offsetcheck == 'on') ? $offsetcheck = 'checked' : $offsetcheck = '';
	echo "\nMonthly: <input type='text' name='mcpd-monthly' size='10' value='".$monthlydef."' /> \n";
	echo "\nOne Time: <input type='text' name='mcpd-onetime' size='10' value='".$onetimedef."' /> \n";
	echo "<br />";
	echo "\nMonthly Time Checked: <input type='checkbox' name='mcpd-monthlycheck' ".$monthlycheck." /> \n";
	echo "<br />";
	echo "\nOne Time Checked: <input type='checkbox' name='mcpd-onetimecheck' ".$onetimecheck." /> \n";
	echo "<br />";
	echo "\nOffset Checked: <input type='checkbox' name='mcpd-offsetcheck' ".$offsetcheck." /> \n";
	echo "<hr />";
?>

<h3>Paypal Email Account</h3>
<small>Enter the PayPal account (email address) that you wish to associate with that currency. Empty fields will not show up as an available currency on your donation page. Make sure you select a default currency.</small>
<br />
<?php
	$results = $wpdb->get_results( "SELECT currency, id FROM " . $table_name . " WHERE paypal_accepts = 1");
	sort($results);

	//Create currency form
	echo "<table cellspacing='10'>";
		echo "<tr>";
		
		echo "<td>";
		echo "<b>Select<br />Default</b>";
		echo "</td>";
			
		echo "<td>";
		echo "<b>Paypal Email Account</b>";
		echo "</td>";
			
		echo "<td>";
		echo "<b>Currency</b>";
		echo "</td>";	
		
		echo "</tr>";

	foreach($results as $result){
		echo "<tr>";
		echo "<td>";
		
		($default == $result->currency) ? $ischecked = 'checked' : $ischecked = '';
		
		echo "<input align='right' type='radio' name='mcpd-default' value='".$result->currency."' ".$ischecked.">\n";
		echo "</td>";
		echo "<td>";
		echo "<input type='text' name='mcpd-accounts[".$result->currency."]' size='25' value='".$options[$result->currency]."' /> \n";
		echo "</td>";
		echo "<td>";
		echo "<label for='mcpd-accounts[".$result->currency."]'>".$result->currency."</label>\n";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";


?>
	<hr />
	<input type='submit' class='button-primary' value='<?php _e('Save Changes')?>' />
	</form>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-7629209408171115";
/* MCPD Admin Ad */
google_ad_slot = "4457946558";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>