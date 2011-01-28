<?php 
	$table_name 	= $wpdb->prefix . "mcpd_currency";
	$options 		= get_option('mcpd-accounts');
	$contactlink	= get_option('mcpd-contactlink');
	$update			= get_option('mcpd-update');
	$default		= get_option('mcpd-default');
	require_once(MCPD_JS.'/mcpdjs.php'); 
?>
<link rel="stylesheet" href="<?php bloginfo('wpurl') ?>/wp-content/plugins/multi-currency-paypal-donations/style/style.css" media="screen" type='text/css' />

<div class='mcpdcontent'>
			
	<div class='mcpdstep2'>
			<div class='nationality'><span style='color:red'>*</span>Select your Currency:
			<select id='country' onclick="changeBus(this)" onchange="changeBus(this)">
	<?php
	foreach( $options as $currency => $account ){
			if ($account){
			($default == $currency) ? $ischecked = 'selected' : $ischecked = '';
	?>
	
		<option value='<?php echo $account ?>' <?php echo $ischecked ?>><?php echo $currency ?></option>
	<?php
			}
		}
	?>
			</select>
		</div>
		<div class='mcpdleftcol'>
			<div id='mcpdmonthly'>
				<form name='amounts'>
					<h3 class='top'>Monthly Donation</h3>
					<input name='selmonthly' type="checkbox" <?php if($monthlycheck == 'on') echo 'checked'; ?> onclick='createButton()' />
					<span id='currsymbol1'>$</span><input type='input' size="6" name='monthlyamt' value='<?php echo $monthlydefault ?>' onkeyup='createButton()' style='padding:5px;font-size:20px;font-weight:900;' />
					<span id='currencyMon'></span><span class='permonth'>/month</span>
					
				<h3>One Time Donation</h3>
					<input name='selonetime' type="checkbox" <?php if($onetimecheck == 'on') echo 'checked'; ?> onclick='createButton()' />
					<span id='currsymbol2'>$</span><input type='input' size="6" name='onetimeamt' value='<?php echo $onetimedefault ?>' onkeyup='createButton()' style='padding:5px;font-size:20px;font-weight:900;' />
					<span id='currencyOne'></span>
				</form>
				<form name='surchargeForm'>
					<input name='surcharge' type="checkbox" <?php if($offsetcheck == 'on') echo 'checked'; ?> onclick='createButton();'><label for='surcharge'>Offset credit card surcharge<span style="cursor: help; color: #2e2d2d;" title="header=[] cssbody[padding:12px;] body=[Credit cards charge us a percentage for their use. Clicking this box will ensure your donation above will be received in full]" width:200px;"> <u>?</u></span></label>
				</form>
	
			</div>
			<div class='mcpdsubtotal'>
				<p><b>Subtotal</b></p>
				<p>Monthly Donation......................<b><span id='currsymbol3'>$</span><span id='monthlytotal'></span></b></p>
				<p>One Time Donation....................<b><span id='currsymbol4'>$</span><span id='onetimetotal'></span></b></p>
			</div>
			<div class='mcpdtotalbox'>
				<p style='font-size:25px;'>Total Charge Today <b><br /><span id='currsymbol5'>$</span><span style='font-size:45px;' id='total'></span></b></p>
			</div>
			<br />
			<div id='ppform'></div>
			<br />
			<small>Having trouble donating on this site?<br /><a title='<?php bloginfo('name'); ?> - Contact' href='<?php echo $contactlink ?>'>Let us know and we'll help you out.</a>.</small>
		</div>
		
		<div style='clear:both'></div>
	</div>
</div>