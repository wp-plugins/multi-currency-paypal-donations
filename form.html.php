<?php 
wp_enqueue_style( 'opentipcss', plugins_url('multi-currency-paypal-donations/style/opentip.css') );
wp_enqueue_script( 'opentipjs', plugins_url('multi-currency-paypal-donations/js/opentip-native.min.js') );
$options 		= get_option('mcpd-accounts');
$contactlink	= get_option('mcpd-contactlink');
$styles			= get_option('mcpd-styles');
$default		= get_option('mcpd-default');
require_once(MCPD_JS.'/mcpdjs.php');
?>
<link rel="stylesheet" href="<?php echo plugins_url('multi-currency-paypal-donations/style/style.css') ?>" media="screen" type='text/css' />
<style type='text/css'>
.mcpdleftcol{ background-color: <?php echo $styles['backgroundColor'] ?> }
.nationality{ background-color: <?php echo $styles['topBackgroundColor'] ?> }
.mcpdcontent { color:<?php echo $styles['text'] ?> }

input.mcpddonatenow {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, <?php echo $styles['topGradient'] ?>), color-stop(1, <?php echo $styles['bottomGradient'] ?>) );
	background:-moz-linear-gradient( center top, <?php echo $styles['topGradient'] ?> 5%, <?php echo $styles['bottomGradient'] ?> 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles['topGradient'] ?>', endColorstr='<?php echo $styles['bottomGradient'] ?>');
	background-color:<?php echo $styles['topGradient'] ?>;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid <?php echo $styles['border'] ?>;
	display:inline-block;
	color:<?php echo $styles['buttonText'] ?>;
	font-family:arial;
	font-size:25px;
	font-weight:bold;
	padding:12px 24px;
	margin-left: 10px;
	text-decoration:none;
}
input.mcpddonatenow:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, <?php echo $styles['bottomGradient'] ?>), color-stop(1, <?php echo $styles['topGradient'] ?>) );
	background:-moz-linear-gradient( center top, <?php echo $styles['bottomGradient'] ?> 5%, <?php echo $styles['topGradient'] ?> 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles['bottomGradient'] ?>', endColorstr='<?php echo $styles['topGradient'] ?>');
	background-color:<?php echo $styles['bottomGradient'] ?>;
}
input.mcpddonatenow:active {
	position:relative;
	top:1px;
}

</style>

<div class='mcpdcontent'>

	<?php
		//Check to make sure at least one paypal account has been filled in
		$showform = false;
		$error_msg = "";
		if ($options){
			foreach( $options as $currency => $account ){
				if ($account){
					$showform = true;
				} else {
					$error_msg = "Please Fill in at least one paypal account on the";
				}
			}
		} else {
			$error_msg = "Please Fill in at least one paypal account on the";		
		}

	?>
	<?php if($showform){ ?>
			
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
					<input name='selmonthly' type="checkbox" <?php if(get_option('mcpd-monthlycheck') == 'on') echo 'checked'; ?> onclick='createButton()' />
					<span id='currsymbol1'>$</span><input type='input' size="6" name='monthlyamt' value='<?php echo $monthlydefault ?>' onkeyup='createButton()' style='padding:5px;font-size:20px;font-weight:900;' />
					<span id='currencyMon'></span><span class='permonth'>/month</span>
					
				<h3>One Time Donation</h3>
					<input name='selonetime' type="checkbox" <?php if(get_option('mcpd-onetimecheck') == 'on') echo 'checked'; ?> onclick='createButton()' />
					<span id='currsymbol2'>$</span><input type='input' size="6" name='onetimeamt' value='<?php echo $onetimedefault ?>' onkeyup='createButton()' style='padding:5px;font-size:20px;font-weight:900;' />
					<span id='currencyOne'></span>
				</form>
				<form name='surchargeForm'>
					<input name='surcharge' type="checkbox" <?php if(get_option('mcpd-offsetcheck') == 'on') echo 'checked'; ?> onclick='createButton();'><label for='surcharge'>Offset credit card surcharge<span style="cursor: help" data-ot='Credit cards charge us a percentage for their use. Clicking this box will ensure your donation above will be received in full' > <u>?</u></span></label>
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
			<div id='ppform'></div>
			<br />
			<?php if($contactlink){ ?>
			<div class='mcpdHelp'><small>Having trouble donating on this site?<br /><a title='<?php bloginfo('name'); ?> - Contact' href='<?php echo $contactlink; ?>'>Let us know and we'll help you out.</a></small></div>
			<?php } ?>
		</div>
		
		<div style='clear:both'></div>
	</div>
	<?php }else{ 
		echo "<div class='mcpdAlert'><h1>All in One Paypal Donation Form ALERT</h1>".$error_msg." <a href='".admin_url( 'options-general.php?page=mcpd' )."'>Settings Page</a>.</div>"; } ?>
</div>