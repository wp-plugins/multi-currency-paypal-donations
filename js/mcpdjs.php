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

<script language='javascript'>
function startmcpd() {
  changeBus();
  createButton();
}

window.onload = startmcpd;

function changeBus(){
  var currencyCode;
  index = document.getElementById('country').options.selectedIndex;
  switch (index){
    <?php
    $i = 0;
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
        echo "\t\tcurrencyCode 						= '".$results[0]['code']."';\n";
        echo "\t\tbreak;\n\n";
        $i++;
      }
    }
    ?>
  }
  //Make The donate Button
  createButton();
}

function loadCurrency(){
  var currency = document.getElementById("currencyOne").innerHTML;
  return currency;
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
    subpart1 = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> \
                <input name="notify_url" type="hidden" value="<?php echo get_bloginfo('wpurl').'/mcpd/ipn'; ?>" /> \
                <input name="cmd" type="hidden" value="_xclick-subscriptions" /> \
                <input id="business" name="business" type="hidden" value="'+loadEmail()+'" /> \
                <input name="item_name" type="hidden" value="<?php echo $itemname; ?>" /> \
                <input name="item_number" type="hidden" value="aio-donation" /> \
                <input id="currency_code" name="currency_code" type="hidden" value="'+loadCurrency()+'" /> \
                <input name="no_shipping" type="hidden" value="1" /> \
                <input name="return" type="hidden" value="<?php echo $thanks; ?>" /> \
                <input name="cancel_return" type="hidden" value="<?php echo $thanks; ?>?error=cancel" /> \
                <input name="no_note" type="hidden" value="1" /><input name="lc" type="hidden" value="CA" /> \
                <input name="bn" type="hidden" value="PP-SubscriptionsBF" />';

    subpart2 = '<input name="p3" type="hidden" value="1" /> \
                <input name="t3" type="hidden" value="M" /> \
                <input name="src" type="hidden" value="1" /> \
                <input name="sra" type="hidden" value="1" /> \
                <input id="a3" name="a3" size="6" type="hidden" value="'+updateAmount("monthly")+'" \
                 style="padding:5px;font-size:20px;font-weight:900;" /> \
                <input type="submit" class="mcpddonatenow" name="submit" value="Donate Now" alt=""> \
                </form>';

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
    buynow = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> \
              <input name="notify_url" type="hidden" value="<?php echo get_bloginfo('wpurl').'/mcpd/ipn'; ?>" /> \
              <input name="cmd" type="hidden" value="_donations" /> \
              <input id="business" name="business" type="hidden" value="'+loadEmail()+'" /> \
              <input name="item_name" type="hidden" value="<?php echo $itemname; ?>" /> \
              <input name="item_number" type="hidden" value="aio-donation" /> \
              <input id="amount" name="amount" size="6" type="hidden" value="'+updateAmount("onetime")+'" style="padding:5px;font-size:20px;font-weight:900;" /> \
              <input id="currency_code1" name="currency_code" type="hidden" value="'+loadCurrency()+'" /> \
              <input name="return" type="hidden" value="<?php echo $thanks; ?>" /> \
              <input type="submit" class="mcpddonatenow" name="submit" value="Donate Now" alt=""></form>';
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

  if ((type == 'onetime') && (document.amounts.selonetime.checked == true) && (document.amounts.onetimeamt.value)){
    //Calculate one time donation
    onetime = document.amounts.onetimeamt.value;
    onetime = addSurcharge(onetime);
    document.getElementById('onetimetotal').innerHTML = onetime;
    return onetime;
  } else if ((type == 'monthly') && (document.amounts.selmonthly.checked == true) && (document.amounts.monthlyamt.value)){
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