<?php
/*
Template Name: Multi Currency Donations
*/

get_header();
?>
<?php
	global $wpdb;
	$table_name 	= $wpdb->prefix . "mcpd_currency";
	$options 		= get_option('mcpd-accounts');
	$thanks 		= get_option('mcpd-thanks');
	$itemname		= get_option('mcpd-itemname');
	$monthlydefault	= get_option('mcpd-monthly');
	$onetimedefault	= get_option('mcpd-onetime');	
	$onetimecheck 	= get_option('mcpd-onetimecheck');
	$monthlycheck	= get_option('mcpd-monthlycheck');
	$offsetcheck	= get_option('mcpd-offsetcheck');
?>

<script src="/js/boxover.js"></script>
<script language='javascript'>
function changeBus(input){
 	index = document.getElementById('country').options.selectedIndex;
	switch (index){
<?php
$i = 1;
foreach( $options as $currency => $account ){
	if ($account){
		$results = $wpdb->get_results("SELECT code, symbol_html FROM ".$table_name." WHERE currency='".$currency."'", ARRAY_A);
		echo "\t\tcase ".$i.":\n";
		echo "\t\tdocument.getElementById('currencyOne').innerHTML 	= '".$results[0]['code']."';\n";
		echo "\t\tdocument.getElementById('currencyMon').innerHTML 	= '".$results[0]['code']."';\n";
		echo "\t\tdocument.getElementById('currsymbol1').innerHTML	= '".$results[0]['symbol_html']."';\n";
		echo "\t\tdocument.getElementById('currsymbol2').innerHTML	= '".$results[0]['symbol_html']."';\n";
		echo "\t\tdocument.getElementById('currsymbol3').innerHTML	= '".$results[0]['symbol_html']."';\n";
		echo "\t\tdocument.getElementById('currsymbol4').innerHTML	= '".$results[0]['symbol_html']."';\n";
		echo "\t\tdocument.getElementById('currsymbol5').innerHTML	= '".$results[0]['symbol_html']."';\n";
		echo "\t\tbreak;\n\n";
		$i++;
	}
}
?>
	}
	//Make The donate Button
	createButton();
 	document.getElementById('business').value = input.value;
}

function loadCurrency(){
	return document.getElementById('currencyOne').innerHTML;
}

function loadEmail(){
	var email;
	var i = document.getElementById("country").selectedIndex;
	email = document.getElementById("country").options[i].value;
	return email;
}


function createButton(){
	var buynow;
	var monthly;
	var onetime;
	var addonetime;
	var subpart1;
	var subpart2;
	var total;
	var onetimeamt;
	var monthlyamt;
	var ppform;

	updateAmount("both");
	//Add up total charge for today
	onetimeamt = updateAmount('onetime');
	monthlyamt = updateAmount('monthly');
	onetimeamt = parseFloat(onetimeamt);
	monthlyamt = parseFloat(monthlyamt);
	total = onetimeamt + monthlyamt;
	total = total.toFixed(2);
	document.getElementById('total').innerHTML = total;

	if (((document.amounts.selmonthly.checked == true) && (document.amounts.selonetime.checked == true)) || ((document.amounts.selmonthly.checked == true) && (document.amounts.selonetime.checked == false))){
			//Create a subscription button with a trail payment of one time donation value
			//Find out if one time donation has been selected. If so add it to the subscription payment
			subpart1 = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input name="cmd" type="hidden" value="_xclick-subscriptions" /><input id="business" name="business" type="hidden" value="'+loadEmail()+'" /><input name="item_name" type="hidden" value="<?php echo $itemname; ?>" /><input id="currency_code" name="currency_code" type="hidden" value="'+loadCurrency()+'" /><input name="no_shipping" type="hidden" value="1" /><input name="return" type="hidden" value="<?php echo $thanks; ?>" /><input name="cancel_return" type="hidden" value="<?php echo $thanks; ?>?error=cancel" /><input name="no_note" type="hidden" value="1" /><input name="lc" type="hidden" value="CA" /><input name="bn" type="hidden" value="PP-SubscriptionsBF" />';
			subpart2 = '<input name="p3" type="hidden" value="1" /><input name="t3" type="hidden" value="M" /><input name="src" type="hidden" value="1" /><input name="sra" type="hidden" value="1" /><input id="a3" name="a3" size="6" type="hidden" value="'+updateAmount("monthly")+'" style="padding:5px;font-size:20px;font-weight:900;" /><input type="submit" class="donatenow" name="submit" value="Donate Now" alt=""></form>';

		if (document.amounts.selonetime.checked == true){
			addonetime = '<input name="a1" type="hidden" value="'+updateAmount("onetime")+'" /><input name="p1" type="hidden" value="1" /><input name="t1" type="hidden" value="D" />';
			ppform = subpart1+addonetime+subpart2;
		} else {
			ppform = subpart1+subpart2;
		}
			document.getElementById('ppform').innerHTML = '';
			document.getElementById('ppform').innerHTML = ppform;
		
		monthly = updateAmount("monthly");
		return true;
		
	} else if ((document.amounts.selmonthly.checked == false) && (document.amounts.selonetime.checked == true)){
		//Make a buy it now button for a one time donation
		buynow = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input name="cmd" type="hidden" value="_donations" /><input id="business" name="business" type="hidden" value="'+loadEmail()+'" /><input name="item_name" type="hidden" value="<?php echo $itemname; ?>" /><input id="amount" name="amount" size="6" type="hidden" value="'+updateAmount("onetime")+'" style="padding:5px;font-size:20px;font-weight:900;" /><input id="currency_code1" name="currency_code" type="hidden" value="'+loadCurrency()+'" /><input name="return" type="hidden" value="<?php echo $thanks; ?>" /><input type="submit" class="donatenow" name="submit" value="Donate Now" alt=""></form>';
		document.getElementById('ppform').innerHTML = '';
		document.getElementById('ppform').innerHTML = buynow;
		onetime = updateAmount("onetime");
		return true;
	} else {
		//No donation selected. Remove donation button.
		document.getElementById('ppform').innerHTML = '';
		return false;
	}
}

function updateAmount(type){
	var monthly;
	var onetime;
	
	if ((type == 'onetime') && (document.amounts.selonetime.checked == true)){
		//Calculate one time donation
		onetime = document.amounts.onetimeamt.value;
		onetime = addSurcharge(onetime);
		document.getElementById('onetimetotal').innerHTML = onetime;
		return onetime;
	} else if ((type == 'monthly') && (document.amounts.selmonthly.checked == true)){
		//Calculate monthly total
		monthly = document.amounts.monthlyamt.value;
		monthly = addSurcharge(monthly);
		document.getElementById('monthlytotal').innerHTML = monthly;
		return monthly;
	} else if (type == 'both'){
		if (document.amounts.selmonthly.checked == true){
			monthly = document.amounts.monthlyamt.value;
			monthly = addSurcharge(monthly);
			document.getElementById('monthlytotal').innerHTML = monthly;
		} else {
			document.getElementById('monthlytotal').innerHTML = 0;
		}
		if (document.amounts.selonetime.checked == true){
			onetime = document.amounts.onetimeamt.value;
			onetime = addSurcharge(onetime);
			document.getElementById('onetimetotal').innerHTML = onetime;
		} else {
			document.getElementById('onetimetotal').innerHTML = 0;
		}
			return;
	} else {
		if (type == 'onetime'){
			document.getElementById('onetimetotal').innerHTML = 0;
		} else if (type == 'monthly'){
			document.getElementById('monthlytotal').innerHTML = 0;
		}
		return 0;
	}
	return;
}


function addSurcharge(amt){
	var amt;
	

	if (document.surchargeForm.surcharge.checked == true){
	 	amt = (parseFloat(amt)+0.30)/0.971;

	 	amt = roundNumber(amt, 2);
	}
	return amt;	

}



function roundNumber(number,decimals) {

	var newString;// The new rounded number

	decimals = Number(decimals);

	if (decimals < 1) {

		newString = (Math.round(number)).toString();

	} else {

		var numString = number.toString();

		if (numString.lastIndexOf(".") == -1) {// If there is no decimal point

			numString += ".";// give it one at the end

		}

		var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number

		var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with

		var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want

		if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated

			if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point

				while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {

					if (d1 != ".") {

						cutoff -= 1;

						d1 = Number(numString.substring(cutoff,cutoff+1));

					} else {

						cutoff -= 1;

					}

				}

			}

			d1 += 1;

		} 

		newString = numString.substring(0,cutoff) + d1.toString();

	}

	if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string

		newString += ".";

	}

	var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;

	for(var i=0;i<decimals-decs;i++) newString += "0";

	//var newNumber = Number(newString);// make it a number if you like

	return newString;

}
</script>

	<h1>Donate to the 100 people project</h1>
	<div class='content100'>
		<h3><b>The Vision</b></h3>
		<p><img title='Nick and Sara' class='people100Photo' alt='Nick and Sara' src='/wordpress/wp-content/themes/nickandsara-v3/images/us.jpg' align='right' />We, Nick and Sara, have been volunteering with Youth With a Mission Australia since 2004. We're currently using our gifts to bring awareness and change in areas of injustice that we've seen in the nations around us. We believe in helping the poor and needy and speaking for those who are not able to help themselves.</p>
		
		<h3><b>The Project</b></h3>
		<p>The Project
	In order to keep this vision alive, we need your help in our project! We are believing to see $10,000 raised by November 1. This covers our tour costs and annual expenses. We also need to see our monthly support increased by $2,500/month. We are contacting 100 of our friends to see if you would be willing to give a one time donation for $100 and support us $25 a month!</p>
	</div>
	<h2>Donate</h2>
		
		<p class='nationality'>Select your Currency:
		<select id='country' onclick="changeBus(this)" onchange="changeBus(this)">
			<option value=''>---Select---</option>
<?php
foreach( $options as $currency => $account ){
		if ($account){
?>

			<option value='<?php echo $account ?>'><?php echo $currency ?></option>
<?php
		}
	}
?>
		</select>
	</p>
	<div class='step2'>
		<div class='leftcol100'>
			<div id='monthly'>
				<h3 class='top'>Monthly Donation</h3>
				<form name='amounts'>
					<input name='selmonthly' type="checkbox" <?php if($monthlycheck == 'on') echo 'checked'; ?> onclick='createButton()' />
					<span id='currsymbol1'>$</span><input type='input' size="6" name='monthlyamt' value='<?php echo $monthlydefault ?>' onkeyup='createButton()' style='padding:5px;font-size:20px;font-weight:900;' />
					<span id='currencyMon'></span><span class='permonth'>/month</span>
					
				<h3>One Time Donation</h3>
					<input name='selonetime' type="checkbox" <?php if($onetimecheck == 'on') echo 'checked'; ?> onclick='createButton()' />
					<span id='currsymbol2'>$</span><input type='input' size="6" name='onetimeamt' value='<?php echo $onetimedefault ?>' onkeyup='createButton()' style='padding:5px;font-size:20px;font-weight:900;' />
					<span id='currencyOne'></span>
				</form>
				<br />
				<form name='surchargeForm'>
					<input name='surcharge' type="checkbox" <?php if($offsetcheck == 'on') echo 'checked'; ?> onclick='createButton();'><label for='surcharge'>Offset credit card surcharge<span style="cursor: help; color: #2e2d2d;" title="header=[] cssbody[padding:12px;] body=[Credit cards charge us a percentage for their use. Clicking this box will ensure your donation above will be received in full]" width:200px;"> <u>?</u></span></label>
				</form>
				<hr />
	
			</div>
			<div class='subtotal'>
				<p><b>Subtotal</b></p>
				<p>Monthly Donation......................<b><span id='currsymbol3'>$</span><span id='monthlytotal'></span></b></p>
				<p>One Time Donation....................<b><span id='currsymbol4'>$</span><span id='onetimetotal'></span></b></p>
			</div>
			<div class='totalbox'>
				<p style='font-size:25px;'>Total Charge Today <b><br /><span id='currsymbol5'>$</span><span style='font-size:45px;' id='total'></span></b></p>
			</div>
			<br />
			<div id='ppform'></div>
			<br />
			<small>Having trouble donating on this site?<br /><a title='Nick and Sara - Contact' href='/contact/'>Let Nick and Sara know and we'll help you out</a>.</small>
		</div>
		<div class='rightcol100'>
		
			<?php query_posts('pagename=100 People') ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<h2 class='top'>Project Update</h2>
			<small>Last Updated <?php the_modified_time('F jS, Y') ?></small>
			<div class='divider'></div>

			<?php the_content(); ?>

			

			<?php endwhile; else: ?>

			<?php _e('Sorry, no posts matched your criteria.'); ?>

			<?php endif; ?>
			
		</div>
		<div style='clear:both'></div>
	</div>

</div>


<?php
get_footer();

?>