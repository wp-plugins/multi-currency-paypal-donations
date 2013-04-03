<link rel="stylesheet" href="<?php echo plugins_url('style/style.css', __FILE__) ?>" media="screen" type='text/css' />
<?php
  wp_enqueue_script('jquery');
	$options 			= get_option('mcpd-accounts');
	$thanks 			= get_option('mcpd-thanks');
	$itemname 			= get_option('mcpd-itemname');
	$monthlydef 		= get_option('mcpd-monthly');
	$onetimedef 		= get_option('mcpd-onetime');
	$onetimecheck 		= get_option('mcpd-onetimecheck');
	$monthlycheck	 	= get_option('mcpd-monthlycheck');
	$offsetcheck	 	= get_option('mcpd-offsetcheck');
	$contactlink		= get_option('mcpd-contactlink');
	$styles				= get_option('mcpd-styles');
	$default			= get_option('mcpd-default');
	//$update				= get_option('mcpd-update');
?>
<style type='text/css'>
hr{ margin-top: 30px; margin-bottom: 30px;}
#palletOuter { width: 18px; height: 18px; border: 1px solid #ddd; padding: 2px; margin-right:10px; float:left;}
#palletOuter div { width: 18px; height: 18px; cursor:pointer; }
button.colorPallet { width: 18px; height: 18px; background-color: #BEEF77; border: 1px solid #ddd; }

.mcpdleftcol{ background-color: <?php echo $styles['backgroundColor'] ?> }
.nationality{ background-color: <?php echo $styles['topBackgroundColor'] ?> }
.mcpdcontent { color:<?php echo $styles['text'] ?>; float:left;}
</style>

<style id='donateButton' type='text/css'>
.mcpddonatenow {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, <?php echo $styles['topGradient'] ?>), color-stop(1, <?php echo $styles['bottomGradient'] ?>) );
	background:-moz-linear-gradient( center top, <?php echo $styles['topGradient'] ?> 5%, <?php echo $styles['bottomGradient'] ?> 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles['topGradient'] ?>', endColorstr='<?php echo $styles['bottomGradient'] ?>');
	background-color:<?php echo $styles['topGradient'] ?>;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid <?php echo $styles['border'] ?>;
	display:inline-block;
	color:<?php echo $styles['buttonText'] ?> !important;
	font-family:arial;
	font-size:21px;
	font-weight:bold;
	padding:19px 24px;
	margin-left: 10px;
	text-decoration:none;
}.mcpddonatenow:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, <?php echo $styles['bottomGradient'] ?>), color-stop(1, <?php echo $styles['topGradient'] ?>) );
	background:-moz-linear-gradient( center top, <?php echo $styles['bottomGradient'] ?> 5%, <?php echo $styles['topGradient'] ?> 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles['bottomGradient'] ?>', endColorstr='<?php echo $styles['topGradient'] ?>');
	background-color:<?php echo $styles['bottomGradient'] ?>;
	cursor: pointer;
}.mcpddonatenow:active {
	position:relative;
	top:1px;
}
</style>
<div class="rm_wrap">
<?php echo "<h2>" . __('Multi Currency PayPal Donations', 'mcpdpro'). "</h2>" ?>
<form method="post" action="options.php">
<?php settings_fields( 'mcpd-options' ); ?>
<hr />

<div class="rm_section">
	<div class="rm_title"><?php echo "<h3><img src=\"" . plugins_url('/images/trans.png', __FILE__) . "\" class=\"inactive\" width=\"16px\" height=\"16px\" alt=\"\">" . __('Usage Instructions', 'mcpdpro'). "</h3>" ?>
	<div class="clearfix"></div></div>  
	<div class="rm_opts">
		<p><?php _e('Type \'<b>[paypalDonationForm]</b>\' (without the quotes) wherever you want the donate form to show up. 
									The form needs a minimum of 370px wide to display properly. We recommend you make a page called 
									\'Donate\' and put \'<b>[paypalDonationForm]</b>\' in it to make a donate page.') ?></p>
	</div>
</div>


<div class="rm_section">
	<div class="rm_title"><?php echo "<h3><img src=\"" . plugins_url('/images/trans.png', __FILE__) . "\" class=\"inactive\" width=\"16px\" height=\"16px\" alt=\"\">" . __('General Settings', 'mcpdpro'). "</h3>" ?>
	<span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span>
	<div class="clearfix"></div></div>  
	<div class="rm_opts">
	
		<div class="rm_input rm_text">  
		    <label for="mcpd-thanks"><?php _e('Thank You Page') ?></label>  
		    <input name="mcpd-thanks" id="mcpd-thanks" type="input" value='<?php echo $thanks ?>' />  
		 	<small><?php _e('Enter the URL the page the donor will be redirected to after a successful payment. Make this a complete URL (ie. http://your-domain.com/thank-you)') ?></small>
		 <div class="clearfix"></div>  
		</div>  

		<div class="rm_input rm_text">  
		    <label for="mcpd-itemname"><?php _e('Item Name', 'mcpdpro') ?></label>  
		    <input name="mcpd-itemname" id="mcpd-itemname" type="input" value='<?php echo $itemname ?>' />  
		 	<small><?php _e('This is the name of the item that will show up on the donors Paypal receipt (ex. \'Donation to John\').') ?></small><div class="clearfix"></div>  
		 	<div class="clearfix"></div>  
		</div>  
		 
		<div class="rm_input rm_text">  
		    <label for="mcpd-contactlink"><?php _e('Contact Link', 'mcpdpro') ?></label>  
		    <input name="mcpd-contactlink" id="mcpd-contactlink" type="input" value='<?php echo $contactlink ?>' />  
		 	<small><?php _e('At the bottom of the page is a link if people are having trouble donating through the form. Enter the URL you want it to link to.') ?></small>
		 	<div class="clearfix"></div>  
		</div>	
	</div>
</div>	
<div class="rm_section">
	<div class="rm_title"><?php echo "<h3><img src=\"" . plugins_url('/images/trans.png', __FILE__) . "\" class=\"inactive\" width=\"16px\" height=\"16px\" alt=\"\">" . __('Default Options', 'mcpdpro'). "</h3>" ?>
	<span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span>
	<div class="clearfix"></div></div>  
	<div class="rm_opts">	
		<div class="rm_input rm_checkbox">  
		    <label for="mcpd-monthlycheck"><?php _e('Monthly Checked', 'mcpdpro') ?></label>  
			<input type="checkbox" name="mcpd-monthlycheck" id="mcpd-monthlycheck" value='on' <?php if(get_option('mcpd-monthlycheck') == 'on') echo 'checked'; ?> />  
		 	 <small><?php _e('Is the monthly box checked by default?') ?></small>
		    <div class="clearfix"></div>
		</div>
			
		<div class="rm_input rm_text">  
		    <label for="mcpd-monthly"><?php _e('Monthly Amount', 'mcpdpro') ?></label>  
		    <input name="mcpd-monthly" id="mcpd-monthly" type="input" value='<?php echo $monthlydef ?>' />  
		 	<small><?php _e('Amount to pre-fill the monthly donation with.') ?></small>
		 	<div class="clearfix"></div>  
		</div>
		
		<div class="rm_input rm_checkbox">  
		    <label for="mcpd-onetimecheck"><?php _e('One-Time Checked', 'mcpdpro') ?></label>  
			<input type="checkbox" name="mcpd-onetimecheck" id="mcpd-onetimecheck" value='on' <?php if(get_option('mcpd-onetimecheck') == 'on') echo 'checked'; ?> />  
		 	<small><?php _e('Is the one-time donation box checked by default?') ?></small>
		    <div class="clearfix"></div>  
		</div>
			
		<div class="rm_input rm_text">  
		    <label for="mcpd-onetime"><?php _e('One-Time Amount', 'mcpdpro') ?></label>  
		    <input name="mcpd-onetime" id="mcpd-onetime" type="input" value='<?php echo $onetimedef ?>' />  
		 	<small><?php _e('Amount to pre-fill the one-time donation with.') ?></small>
		 	<div class="clearfix"></div>  
		</div>
  <div class="rm_input rm_checkbox">  
    <label for="mcpd-offsetcheck"><?php _e('Offset Charges Checked', 'mcpdpro') ?></label>  
    <input type="checkbox" name="mcpd-offsetcheck" id="mcpd-offsetcheck" value='on' <?php if(get_option('mcpd-offsetcheck') == 'on') echo 'checked'; ?> />  
    <small><?php _e('Should the offset charges box be checked by default?') ?></small>
    <div class="clearfix"></div>  
  </div>
  </div>
</div>

<div class="rm_section">
	<div class="rm_title"><?php echo "<h3><img src=\"" . plugins_url('/images/trans.png', __FILE__) . "\" class=\"inactive\" width=\"16px\" height=\"16px\" alt=\"\">" . __('Styles', 'mcpdpro'). "</h3>" ?>
	<span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span>
	<div class="clearfix"></div></div>  
	<div class="rm_opts">
		<div style="float:left; margin-right: 100px;padding:10px;">
			<h4>Color Schemes</h4>
			<small>Click on of the colors to select a pre-made color scheme</small>
			<div style="clear:left;"></div>
			<div id="palletOuter"><div style="background-color:#bbdaf7;" onclick="setPallet('#79bbff', '#BAD8FF', '#222222', '#79bbff', '#378de5', '#84bbf3', '#ffffff')"></div></div>
			<div id="palletOuter"><div style="background-color:#BEEF77;" onclick="setPallet('#BEEF77', '#E0FFD8', '#222222', '#89C403', '#77A809', '#74B807', '#FFFFFF')"></div></div>
			<div id="palletOuter"><div style="background-color:#de5555;" onclick="setPallet('#de5555', '#ef5445', '#222222', '#de5555', '#de5555', '#ff3344', '#000000')"></div></div>
			<div id="palletOuter"><div style="background-color:#ffc477;" onclick="setPallet('#ffc477', '#FFE9C2', '#222222', '#ffc477', '#fb9e25', '#eeb44f', '#ffffff')"></div></div>
			<div id="palletOuter"><div style="background-color:#141715;" onclick="setPallet('#141715', '#2E2E2E', '#C2C2C2', '#525252', '#000000', '#E3EE4F', '#C4C4C4')"></div></div>
			<div style="clear:left;"></div>
			<?php echo "<h4>" . __('Background Colors', 'mcpdpro'). "</h4>" ?>
			<?php
				echo "Top Background Color:<br /><input id='topBackgroundColor' class='color{hash:true}' name='mcpd-styles[topBackgroundColor]' onchange=\"document.getElementById('nationality').style.backgroundColor = '#'+this.color\" value='".$styles['topBackgroundColor']."' /><br /><br /> \n";
				echo "Background Color:<br /><input id='backgroundColor' class='color{hash:true}' name='mcpd-styles[backgroundColor]' onchange=\"document.getElementById('mcpdleftcol').style.backgroundColor = '#'+this.color\" value='".$styles['backgroundColor']."' /><br /><br /> \n";
				echo "Text Color:<br /><input id='text' class='color{hash:true}' name='mcpd-styles[text]' onchange=\"document.getElementById('mcpdcontent').style.color = '#'+this.color\" value='".$styles['text']."' /><br /><br /> \n";
		
			echo "<h4>" . __('Donate Button Colors', 'mcpdpro'). "</h4>" ?>
			<?php
				echo "Top Gradient:<br /><input id='topGradient' class='color{hash:true}' name='mcpd-styles[topGradient]' onchange='updatePallet()' value='".$styles['topGradient']."' /><br /><br /> \n";
				echo "Bottom Gradient:<br /><input id='bottomGradient' class='color{hash:true}' name='mcpd-styles[bottomGradient]' onchange='updatePallet()' value='".$styles['bottomGradient']."' /><br /><br /> \n";
				echo "Border:<br /><input id='border' class='color{hash:true}' name='mcpd-styles[border]' onchange='updatePallet()' value='".$styles['border']."' /><br /><br /> \n";
				echo "Text Color:<br /><input id='buttonText' class='color{hash:true}' name='mcpd-styles[buttonText]' onchange='updatePallet()' value='".$styles['buttonText']."' /><br /><br /> \n";
			?>
		</div>
		
		<div id='mcpdcontent' class='mcpdcontent'>
		<?php echo "<h3>" . __('Preview', 'mcpdpro'). "</h3>" ?>
			<p>Actual output may look slightly different due to your chosen template css (ie. font family, size, etc)</p>
			<div class='mcpdstep2'>
					<div id='nationality' class='nationality'><span style='color:red'>*</span>Select your Currency:
					<select style="width: 150px" id='country' disabled="disabled">
				
				<option value='' >Canadian Dollar</option>
						</select>
				</div>
				<div id='mcpdleftcol' class='mcpdleftcol'>
					<div id='mcpdmonthly'>
							<h3 class='top'>Monthly Donation</h3>
							<input name='selmonthly' type="checkbox" disabled="disabled" />
							<span id='currsymbol1'>$</span><input type='input' size="6" name='monthlyamt' value='0.00' style='padding:5px;font-size:20px;font-weight:900;' disabled="disabled" />
							<span id='currencyMon'>CAD</span><span class='permonth'>/month</span>
							
						<h3>One Time Donation</h3>
							<input name='selonetime' type="checkbox" checked  disabled="disabled" />
							<span id='currsymbol2'>$</span><input type='input' size="6" name='onetimeamt' value='10.00' style='padding:5px;font-size:20px;font-weight:900;' disabled="disabled" />
							<span id='currencyOne'>CAD</span><br />
							<input name='surcharge' type="checkbox" disabled="disabled" /><label for='surcharge'>Offset credit card surcharge</label>
			
					</div>
					<div class='mcpdsubtotal'>
						<p><b>Subtotal</b></p>
						<p>Monthly Donation......................<b><span id='currsymbol3'>$</span><span id='monthlytotal'>0.00</span></b></p>
						<p>One Time Donation....................<b><span id='currsymbol4'>$</span><span id='onetimetotal'>10.00</span></b></p>
					</div>
					<div class='mcpdtotalbox'>
						<p style='font-size:25px;'>Total Charge Today <b><br /><span id='currsymbol5'>$</span><span style='font-size:45px;' id='total'>10.00</span></b></p>
					</div>
					<br />
					<input type="button" class="mcpddonatenow" name="submit" value="Donate Now" alt="" disabled="disabled">
					<br />
					<small>Having trouble donating on this site?<br /><a title='wp test site - Contact' href='#'>Let us know and we'll help you out.</a>.</small>
				</div>
				
				<div style='clear:both'></div>
			</div>
			<input type='button' onclick="setPallet(<?php echo "'". $styles['topBackgroundColor'] ."', '". $styles['backgroundColor'] ."', '". $styles['text'] ."', '". $styles['topGradient'] ."', '". $styles['bottomGradient'] ."', '". $styles['border'] ."', '". $styles['buttonText'] ."'" ?>)" value='Cancel Color Change'><br />
		</div>
		<div style="clear:both"></div>
	</div>
</div>


<div class="rm_section">
	<div class="rm_title"><?php echo "<h3><img src=\"" . plugins_url('/images/trans.png', __FILE__) . "\" class=\"inactive\" width=\"16px\" height=\"16px\" alt=\"\">" . __('Paypal Email Account', 'mcpdpro'). "</h3>" ?>
	<span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span>
	<div class="clearfix"></div></div>  
	<div class="rm_opts">
<p>Enter the PayPal account (email address) that you wish to associate with that currency. Empty fields will not show up as an available currency on your donation page. Make sure you select a default currency.</p>
<br />
<?php
	$results = $wpdb->get_results( "SELECT currency, id FROM " . $table_name . " WHERE paypal_accepts = 1");
	sort($results);
	echo '<div class="rm_table">';	
	echo '<table cellspacing="0">';
	echo '<thead>';
	echo '    <tr> 
		        <th>Default</th> 
		        <th>Currency</th> 
		        <th>Paypal Account Email Address</th> 
		      </tr> 
		</thead>		
		<tfoot> 
		    <tr> 
		        <th>Default</th> 
		        <th>Currency</th> 
		        <th>Paypal Account Email Address</th> 
		    </tr> 
		</tfoot>';

	//Create currency form
		echo "<tbody>";

	foreach($results as $result){
		echo "<tr>";
		echo "<td>";
		
		($default == $result->currency) ? $ischecked = 'checked' : $ischecked = '';
		
		echo "<input align='right' type='radio' name='mcpd-default' value='".$result->currency."' ".$ischecked.">\n";
		echo "</td>";
		echo "<td>";
		echo "<label for='mcpd-accounts[".$result->currency."]'>".$result->currency."</label>\n";
		if ( ($result->currency == "Brazilian Real") || ($result->currency == "Malaysian Ringgit")){
			echo "<small>" . __('NOTE: This currency is supported as a payment currency and a currency balance for in-country PayPal accounts only.') ."</small>";
		}
		echo "</td>";
		echo "<td>";
		echo "<input type='text' name='mcpd-accounts[".$result->currency."]' size='25' value='".$options[$result->currency]."' /> \n";
		echo "</td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
	echo "</div>";	


?>
	</div>
</div>
	</form>
<div class='spacer'></div>
<div id="sffields" class="postbox"> 
	<h3><?php _e('MCPD News', 'mcpdpro') ?></h3>  
	        <div class="inside"> 
				<div class="mcpd-col1">
	            	<?php getFeed("http://makesomecode.com/category/wordpress/plugins/multi-currency-paypal-donations/feed"); ?>
				</div>
				<div class="mcpd-col2">
					<h4><?php _e('Donate Now', 'mcpdpro') ?></h4>
					<p>Me, my wife, and my baby daughter (can you feel the guilt trip, hehehe) would love if you'd chuck a few dollars for the time it took to make this plugin.</p>
					<p><a href="http://nickandsarajane.com/donate/"><img alt="Donate Now" src="<?php echo plugins_url('images/donate-now.gif', __FILE__) ?>"></a></p>	
				</div>
				<div class="clearfix"></div>
	        </div><!--end content 1-->  
	</div>
</div>