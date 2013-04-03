<?php
/*
Plugin Name: Multi Currency PayPal Donations
Plugin URI: http://makesomecode.com/2010/01/07/multi-currency-paypal-donations-wp-plugin/
Description: PayPal charges high fees for cross border transactions. If you are one of the fortunate few that have paypal accounts in multiple currencies then this plugin is for you. It allows you to route different currencies to specific paypal accounts. Even if you don't have multiple paypal accounts this is still a great plugin for accepting donations. It allows you to accept donations as one time or subscription payments. Visit the <a href="options-general.php?page=mcpd">configuration page</a> to get started. To use put <strong>[paypalDonationForm]</strong> in a page or post wherever you want your form to show up.
Version: 2.2.1
Author: Nick Verwymeren
Author URI: http://www.makesomecode.com
Copyright 2013 Multi Currency PayPal Donations - Nick Verwymeren  (email: nickv@makesomecode.com)
*/


add_action('admin_init', 'mcpd_init' );
register_activation_hook(__FILE__,'mcpd_install');
add_action('admin_menu', 'mcpd_menu');
add_shortcode( 'paypalDonationForm', 'mcpd_shortcode' );
add_action('the_content', 'mcpd_displayForm');
add_filter('plugin_action_links', 'our_plugin_action_links', 10, 2);
add_action('plugins_loaded', 'mcpd_update_db_check');
@define('MCPD_PATH', dirname(__FILE__));
@define('MCPD_ABS', ABSPATH);
@define('MCPD_JS', MCPD_PATH . '/js');

global $mcpd_db_version;
$mcpd_db_version = "1.2";

function mcpd_init(){
  wp_enqueue_script("hide_script", plugins_url('/js/hide_script.js', __FILE__), false, "1.0");  
  wp_enqueue_style("functions", plugins_url('/style/functions.css', __FILE__), false, "1.0", "all");
  load_plugin_textdomain('mcpdpro');
  register_setting( 'mcpd-options', 'mcpd-accounts' );
  register_setting( 'mcpd-options', 'mcpd-thanks' );
  register_setting( 'mcpd-options', 'mcpd-itemname' );
  register_setting( 'mcpd-options', 'mcpd-monthly' );
  register_setting( 'mcpd-options', 'mcpd-onetime' );
  register_setting( 'mcpd-options', 'mcpd-onetimecheck' );
  register_setting( 'mcpd-options', 'mcpd-monthlycheck' );
  register_setting( 'mcpd-options', 'mcpd-offsetcheck' );
  register_setting( 'mcpd-options', 'mcpd-contactlink' );
  register_setting( 'mcpd-options', 'mcpd-default' );
  register_setting( 'mcpd-options', 'mcpd-styles' );
  //register_setting( 'mcpd-options', 'mcpd-update' );
}

function mcpd_install() {
  global $wpdb;
  global $mcpd_db_version;

  $table_name = $wpdb->prefix . "mcpd_currency";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

  $sql = "CREATE TABLE " . $table_name . " (
  id bigint(20) unsigned NOT NULL auto_increment,
  currency varchar(255) NOT NULL default '',
  symbol varchar(10) NOT NULL default '',
  symbol_html varchar(10) NOT NULL default '',
  code char(3) NOT NULL default '',
  has_regions char(1) NOT NULL default '0',
  paypal_accepts tinyint(1) default NULL,
  paypal_in_country tinyint(1) default NULL,
  paypal_email varchar(64) default NULL,
  PRIMARY KEY  (id)
    )";

  dbDelta($sql);

  //Set our default values
  if (!get_option('mcpd-styles')){
    update_option( "mcpd-styles", array('topBackgroundColor' => '#BEEF77', 'backgroundColor' => '#E0FFD8', 'text' => '#222222', 'topGradient' => '#89C403', 'bottomGradient' => '#77A809', 'border' => '#74B807', 'buttonText' => '#FFFFFF') );
  }

  if (!get_option('mcpd-onetimecheck')){
    update_option( "mcpd-onetimecheck", "on" );
  }

  if (!get_option('mcpd-onetime')){
    update_option( "mcpd-onetime", "10.00" );
  }

  if (!get_option('mcpd-thanks')){
    update_option( "mcpd-thanks", home_url() );
  }

  if (!get_option('mcpd-itemname')){
    update_option( "mcpd-itemname", "Donation to ".get_bloginfo('name') );
  }

  if (!get_option('mcpd-contactlink')){
    update_option( "mcpd-contactlink", "mailto:".get_bloginfo('admin_email') );
  }
}

function mcpd_update_db_check() {
  global $mcpd_db_version;
  global $wpdb;
  $table_name = $wpdb->prefix . "mcpd_currency";

  switch (get_option('mcpd_db_version')) {
    case 1.1:
    mcpd_install();
    //Update our two currencies
    $wpdb->update( $table_name, array( 'paypal_accepts' => 1 ), array( 'code' => 'MYR' ), array( '%d' ), array( '%s' ) );
    $wpdb->update( $table_name, array( 'paypal_accepts' => 1 ), array( 'code' => 'BRL' ), array( '%d' ), array( '%s' ) );
    update_option( "mcpd_db_version", $mcpd_db_version );
    break;

    case 1.2:
    break;

    default:
    mcpd_install();
    mcpd_install_data();
    break;
  }

}

function mcpd_install_data(){
  global $wpdb;
  global $mcpd_db_version;
  $table_name = $wpdb->prefix . "mcpd_currency";

  $insert = "INSERT INTO " .$table_name. " (`id`,`currency`,`symbol`,`symbol_html`,`code`,`has_regions`,`paypal_accepts`,`paypal_in_country`,`paypal_email`)
    VALUES (1,'Mauritanian Ouguiya','','','MRO',0,NULL,NULL,NULL),
  (2,'Maltese Lira','','','MTL',0,NULL,NULL,NULL),
  (3,'Maldive Rufiyaa','','','MVR',0,NULL,NULL,NULL),
  (4,'Malaysian Ringgit','','','MYR',0,1,1,NULL),
  (5,'Malawi Kwacha','','','MWK',0,NULL,NULL,NULL),
  (6,'Malagasy Franc','','','MGF',0,NULL,NULL,NULL),
  (7,'Macau Pataca','','','MOP',0,NULL,NULL,NULL),
  (8,'Denar','','','MKD',0,NULL,NULL,NULL),
  (9,'Lithuanian Litas','','','LTL',0,NULL,NULL,NULL),
  (11,'Libyan Dinar','','','LYD',0,NULL,NULL,NULL),
  (12,'Liberian Dollar','$','&#036;','LRD',0,NULL,NULL,NULL),
  (13,'Lesotho Loti','','','LSL',0,NULL,NULL,NULL),
  (14,'Lebanese Pound','','','LBP',0,NULL,NULL,NULL),
  (15,'Latvian Lats','','','LVL',0,NULL,NULL,NULL),
  (16,'Lao Kip','','','LAK',0,NULL,NULL,NULL),
  (17,'Som','','','KGS',0,NULL,NULL,NULL),
  (18,'Kuwaiti Dinar','','','KWD',0,NULL,NULL,NULL),
  (19,'Korean Won','','&#8361;','KRW',0,NULL,NULL,NULL),
  (20,'North Korean Won','','&#8361;','KPW',0,NULL,NULL,NULL),
  (21,'Kenyan Shilling','','','KES',0,NULL,NULL,NULL),
  (22,'Kazakhstan Tenge','','','KZT',0,NULL,NULL,NULL),
  (23,'Jordanian Dinar','','','JOD',0,NULL,NULL,NULL),
  (24,'Japanese Yen','Œ«','&#165;','JPY',0,1,NULL,NULL),
  (25,'Jamaican Dollar','$','&#036;','JMD',0,NULL,NULL,NULL),
  (26,'Israeli New Shekel','','&#8362;','ILS',0,1,NULL,NULL),
  (27,'Euro','ä‰å','&#8364;','EUR',0,1,NULL,NULL),
  (28,'Iraqi Dinar','','','IQD',0,NULL,NULL,NULL),
  (29,'Indonesian Rupiah','','','IDR',0,NULL,NULL,NULL),
  (30,'Iranian Rial','','','IRR',0,NULL,NULL,NULL),
  (31,'Indian Rupee','','&#8360;','INR',0,NULL,NULL,NULL),
  (32,'Iceland Krona','','','ISK',0,NULL,NULL,NULL),
  (33,'Hungarian Forint','','','HUF',0,1,NULL,NULL),
  (34,'Hong Kong Dollar','$','&#036;','HKD',0,1,NULL,NULL),
  (35,'Honduran Lempira','','','HNL',0,NULL,NULL,NULL),
  (36,'Haitian Gourde','','','HTG',0,NULL,NULL,NULL),
  (37,'Guyana Dollar','$','&#036;','GYD',0,NULL,NULL,NULL),
  (38,'Guinea-Bissau Peso','','','GWP',0,NULL,NULL,NULL),
  (39,'Guinea Franc','','','GNF',0,NULL,NULL,NULL),
  (40,'Guatemalan Quetzal','','','QTQ',0,NULL,NULL,NULL),
  (41,'East Carribean Dollar','$','&#036;','XCD',0,NULL,NULL,NULL),
  (42,'Gibraltar Pound','','','GIP',0,NULL,NULL,NULL),
  (43,'Ghanaian Cedi','','','GHC',0,NULL,NULL,NULL),
  (44,'Georgian Lari','','','GEL',0,NULL,NULL,NULL),
  (45,'Gambian Dalasi','','','GMD',0,NULL,NULL,NULL),
  (46,'Fiji Dollar','$','&#036;','FJD',0,NULL,NULL,NULL),
  (47,'Falkland Islands Pound','','','FKP',0,NULL,NULL,NULL),
  (48,'Ethiopian Birr','','','ETB',0,NULL,NULL,NULL),
  (49,'Estonian Kroon','','','EEK',0,NULL,NULL,NULL),
  (50,'Eritrean Nakfa','','','ERN',0,NULL,NULL,NULL),
  (51,'CFA Franc BEAC','','','XAF',0,NULL,NULL,NULL),
  (52,'El Salvador Colon','','','SVC',0,NULL,NULL,NULL),
  (53,'Egyptian Pound','','','EGP',0,NULL,NULL,NULL),
  (54,'Ecuador Sucre','','','ECS',0,NULL,NULL,NULL),
  (55,'Timor Escudo','','','TPE',0,NULL,NULL,NULL),
  (56,'Dominican Peso','','','DOP',0,NULL,NULL,NULL),
  (57,'Djibouti Franc','','','DJF',0,NULL,NULL,NULL),
  (58,'Danish Krone','','','DKK',0,1,NULL,NULL),
  (59,'Francs','','','CDF',0,NULL,NULL,NULL),
  (60,'Czech Koruna','','','CZK',0,1,NULL,NULL),
  (61,'Cyprus Pound','','','CYP',0,NULL,NULL,NULL),
  (62,'Cuban Peso','','','CUP',0,NULL,NULL,NULL),
  (63,'Croatian Kuna','','','HRK',0,NULL,NULL,NULL),
  (64,'Costa Rican Colon','','','CRC',0,NULL,NULL,NULL),
  (65,'Comoros Franc','','','KMF',0,NULL,NULL,NULL),
  (66,'Colombian Peso','','','COP',0,NULL,NULL,NULL),
  (67,'Chilean Peso','','','CLP',0,NULL,NULL,NULL),
  (68,'Yuan Renminbi','','','CNY',0,NULL,NULL,NULL),
  (69,'Cayman Islands Dollar','$','&#036;','KYD',0,NULL,NULL,NULL),
  (70,'Cape Verde Escudo','','','CVE',0,NULL,NULL,NULL),
  (71,'Canadian Dollar','$','&#036;','CAD',1,1,NULL,NULL),
  (72,'Burundi Franc','','','BIF',0,NULL,NULL,NULL),
  (73,'Bulgarian Lev','','','BGL',0,NULL,NULL,NULL),
  (74,'Brunei Dollar','$','&#036;','BND',0,NULL,NULL,NULL),
  (75,'Brazilian Real','','','BRL',0,1,1,NULL),
  (76,'Botswana Pula','','','BWP',0,NULL,NULL,NULL),
  (77,'Marka','','','BAM',0,NULL,NULL,NULL),
  (78,'Boliviano','','','BOB',0,NULL,NULL,NULL),
  (79,'Bhutan Ngultrum','','','BTN',0,NULL,NULL,NULL),
  (80,'Bermudian Dollar','$','&#036;','BMD',0,NULL,NULL,NULL),
  (81,'CFA Franc BCEAO','','','XOF',0,NULL,NULL,NULL),
  (82,'Belize Dollar','$','&#036;','BZD',0,NULL,NULL,NULL),
  (83,'Belarussian Ruble','','','BYB',0,NULL,NULL,NULL),
  (84,'Barbados Dollar','$','&#036;','BBD',0,NULL,NULL,NULL),
  (85,'Bangladeshi Taka','','','BDT',0,NULL,NULL,NULL),
  (86,'Bahraini Dinar','','','BHD',0,NULL,NULL,NULL),
  (87,'Bahamian Dollar','$','&#036;','BSD',0,NULL,NULL,NULL),
  (88,'Azerbaijanian Manat','','','AZM',0,NULL,NULL,NULL),
  (89,'Aruban Guilder','','','AWG',0,NULL,NULL,NULL),
  (90,'Armenian Dram','','','AMD',0,NULL,NULL,NULL),
  (91,'Argentine Peso','','','ARS',0,NULL,NULL,NULL),
  (92,'Dollar','$','&#036;','ATA',0,NULL,NULL,NULL),
  (93,'Angolan New Kwanza','','','AON',0,NULL,NULL,NULL),
  (94,'Algerian Dinar','','','DZD',0,NULL,NULL,NULL),
  (95,'Albanian Lek','','','ALL',0,NULL,NULL,NULL),
  (96,'Afghanistan Afghani','','','AFA',0,NULL,NULL,NULL),
  (97,'US Dollar','$','&#036;','USD',1,1,NULL,NULL),
  (98,'Australian Dollar','$','&#036;','AUD',0,1,NULL,NULL),
  (99,'Mauritius Rupee','','&#8360;','MUR',0,NULL,NULL,NULL),
  (100,'Mexican Nuevo Peso','','&#036;','MXN',0,1,NULL,NULL),
  (101,'Moldovan Leu','','','MDL',0,NULL,NULL,NULL),
  (102,'Mongolian Tugrik','','','MNT',0,NULL,NULL,NULL),
  (103,'Mozambique Metical','','','MZM',0,NULL,NULL,NULL),
  (104,'Myanmar Kyat','','','MMK',0,NULL,NULL,NULL),
  (105,'Namibian Dollar','$','&#036;','NAD',0,NULL,NULL,NULL),
  (106,'Nepalese Rupee','','&#8360;','NPR',0,NULL,NULL,NULL),
  (107,'Netherlands Antillean Guilder','','','ANG',0,NULL,NULL,NULL),
  (108,'CFP Franc','','','XPF',0,NULL,NULL,NULL),
  (109,'New Zealand Dollar','$','&#036;','NZD',0,1,NULL,NULL),
  (110,'Nicaraguan Cordoba Oro','','','NIC',0,NULL,NULL,NULL),
  (111,'Nigerian Naira','','&#8358;','NGN',0,NULL,NULL,NULL),
  (112,'Norwegian Krone','','','NOK',0,1,NULL,NULL),
  (113,'Omani Rial','','','OMR',0,NULL,NULL,NULL),
  (114,'Pakistan Rupee','','&#8360;','PKR',0,NULL,NULL,NULL),
  (115,'Panamanian Balboa','','','PAB',0,NULL,NULL,NULL),
  (116,'Papua New Guinea Kina','','','PGK',0,NULL,NULL,NULL),
  (117,'Paraguay Guarani','','','PYG',0,NULL,NULL,NULL),
  (118,'Peruvian Nuevo Sol','','','PEN',0,NULL,NULL,NULL),
  (119,'Philippine Peso','','','PHP',0,1,NULL,NULL),
  (120,'Polish Zloty','','','PLZ',0,1,NULL,NULL),
  (121,'Qatari Rial','','','QAR',0,NULL,NULL,NULL),
  (122,'Romanian Leu','','','ROL',0,NULL,NULL,NULL),
  (123,'Russian Ruble','','','RUR',0,NULL,NULL,NULL),
  (124,'Rwanda Franc','','','RWF',0,NULL,NULL,NULL),
  (125,'St. Helena Pound','','','SHP',0,NULL,NULL,NULL),
  (126,'Samoan Tala','','','WST',0,NULL,NULL,NULL),
  (127,'Italian Lira','','','ITL',0,NULL,NULL,NULL),
  (128,'Dobra','','','STD',0,NULL,NULL,NULL),
  (129,'Saudi Riyal','','','SAR',0,NULL,NULL,NULL),
  (130,'Seychelles Rupee','','&#8360;','SCR',0,NULL,NULL,NULL),
  (131,'Sierra Leone Leone','','','SLL',0,NULL,NULL,NULL),
  (132,'Singapore Dollar','$','&#036;','SGD',0,1,NULL,NULL),
  (133,'Slovak Koruna','','','SKK',0,NULL,NULL,NULL),
  (134,'Slovenian Tolar','','','SIT',0,NULL,NULL,NULL),
  (135,'Solomon Islands Dollar','$','&#036;','SBD',0,NULL,NULL,NULL),
  (136,'Somali Shilling','','','SOD',0,NULL,NULL,NULL),
  (137,'South African Rand','','','ZAR',0,NULL,NULL,NULL),
  (138,'Sri Lanka Rupee','','&#8360;','LKR',0,NULL,NULL,NULL),
  (139,'Sudanese Dinar','','','SDD',0,NULL,NULL,NULL),
  (140,'Surinam Guilder','','','SRG',0,NULL,NULL,NULL),
  (141,'Swaziland Lilangeni','','','SZL',0,NULL,NULL,NULL),
  (142,'Swedish Krona','','','SEK',0,1,NULL,NULL),
  (143,'Swiss Franc','','','CHF',0,1,NULL,NULL),
  (144,'Syrian Pound','','','SYP',0,NULL,NULL,NULL),
  (145,'Taiwan Dollar','$','&#036;','TWD',0,1,NULL,NULL),
  (146,'Tajik Ruble','','','TJR',0,NULL,NULL,NULL),
  (147,'Tanzanian Shilling','','','TZS',0,NULL,NULL,NULL),
  (148,'Thai Baht','','&#3647;','THB',0,1,NULL,NULL),
  (149,'Tongan Pa\'anga','','','TOP',0,NULL,NULL,NULL),
  (150,'Trinidad and Tobago Dollar','$','&#036;','TTD',0,NULL,NULL,NULL),
  (151,'Tunisian Dollar','$','&#036;','TND',0,NULL,NULL,NULL),
  (152,'Turkish Lira','','','TRL',0,NULL,NULL,NULL),
  (153,'Manat','','','TMM',0,NULL,NULL,NULL),
  (154,'Pound Sterling','Œ£','&#163;','GBP',0,1,NULL,NULL),
  (155,'Uganda Shilling','','','UGS',0,NULL,NULL,NULL),
  (156,'Ukraine Hryvnia','','','UAG',0,NULL,NULL,NULL),
  (157,'Arab Emirates Dirham','','','AED',0,NULL,NULL,NULL),
  (158,'Uruguayan Peso','','','UYP',0,NULL,NULL,NULL),
  (159,'Uzbekistan Sum','','','UZS',0,NULL,NULL,NULL),
  (160,'Vanuatu Vatu','','','VUV',0,NULL,NULL,NULL),
  (161,'Venezuelan Bolivar','','','VUB',0,NULL,NULL,NULL),
  (162,'Vietnamese Dong','','&#8363;','VND',0,NULL,NULL,NULL),
  (163,'Moroccan Dirham','','','MAD',0,NULL,NULL,NULL),
  (164,'Yemeni Rial','','','YER',0,NULL,NULL,NULL),
  (165,'Yugoslav New Dinar','','','YUN',0,NULL,NULL,NULL),
  (166,'Zambian Kwacha','','','ZMK',0,NULL,NULL,NULL),
  (167,'Zimbabwe Dollar','$','&#036;','ZWD',0,NULL,NULL,NULL),
  (168,'Kampuchean Riel','','','KHR',0,NULL,NULL,NULL);";
  $results = $wpdb->query( $insert );
  update_option( "mcpd_db_version", $mcpd_db_version );
}

function our_plugin_action_links($links, $file) {
  static $this_plugin;

  if (!$this_plugin) {
    $this_plugin = plugin_basename(__FILE__);
  }

  // check to make sure we are on the correct plugin
  if ($file == $this_plugin) {
    // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
    $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=mcpd">Settings</a>';
    $support_link = '<a href="http://makesomecode.com/forums/">Support</a>';
    // add the link to the list
    array_unshift($links, $support_link);
    array_unshift($links, $settings_link);
  }

  return $links;
}

function mcpd_menu() {
  add_options_page('Multi Currency Donations', 'Multi Currency Donations', 'administrator', 'mcpd', 'mcpd_admin');
  wp_register_script( 'colorPicker', plugins_url('/js/jscolor/jscolor.js', __FILE__) );
  wp_register_script( 'adminFunctions', plugins_url('/js/admin.js', __FILE__) );
  wp_register_script( 'jquery', plugins_url('/js/jquery.js', __FILE__) );
  wp_enqueue_script( 'colorPicker' );
  wp_enqueue_script( 'adminFunctions' );
  wp_enqueue_script( 'jquery' );
}

function mcpd_admin() {
  global $wpdb;
  $table_name = $wpdb->prefix . "mcpd_currency";
  include(MCPD_PATH . '/options.php');
}

function mcpd_validate($results){
  foreach ($results as $result){
    $result =  is_email($result);
  }
  return $results;
}

function mcpd_shortcode(){
  ob_start();
  include(MCPD_PATH . '/form.html.php');
  $file = ob_get_clean();
  return $file;
}

// This is the old way of doing it and is provided for backwards compatibility
function mcpd_displayForm($content){	
  $tag = '{mcpdform}';
  $pos = strpos($content, $tag);
  if ($pos !== false){

    ob_start();
    include(MCPD_PATH . '/form.html.php');
    $file = ob_get_clean();
    $content = str_ireplace($tag, $file, $content);
  }
  return $content;
}

function mcpd_manualDisplay(){
  include(MCPD_PATH . '/form.html.php');	
}

function paypal_form_display(){
  include(MCPD_PATH . '/form.html.php');	
}

function mcpd_query_vars($vars) {
  // add my_plugin to the valid list of variables
  $new_vars = array('mcpd');
  $vars = $new_vars + $vars;
  return $vars;
}
add_filter('query_vars', 'mcpd_query_vars');

function getFeed($feed_url) {  

  $rss = fetch_feed($feed_url);
  if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
    // Figure out how many total items there are, but limit it to 5. 
    $maxitems = $rss->get_item_quantity(5); 

    // Build an array of all the items, starting with element 0 (first element).
    $rss_items = $rss->get_items(0, $maxitems); 
  endif;

  echo "<ul>";  

  $i = 0;
  // Loop through each feed item and display each item as a hyperlink.
  foreach ( $rss_items as $item ){
    echo '<li>';
    echo '<a href="'.esc_url( $item->get_permalink() ).'"  title="Posted '.$item->get_date('F j Y | g:i a').'"> '.esc_html( $item->get_title() ).'</a><br />';
    echo '<small>'.$item->get_date('F j Y | g:i a').'</small>';
    echo '<p>'.$item->get_description().'</p>';
    echo '</li>';
    if (++$i == 3){ break; }
  }

  echo "</ul>";  
}

function check_for_update() {
  $currentVersion = file_get_contents('http://makesomecode.com/mcpd-pro/ver.html');

  if(plugin_get_version() >= $currentVersion){
    echo "<p class='current'>Your plugin is up to date.</p>";
  } else {
    echo "<p class='needsUpdate'>A new version (".$currentVersion.") of MCPD PRO is available. Please <a href='http://makesomecode.com/multi-currency-paypal-donations-pro/'>update now!</a></p>";
  }
  return;
}

/**
* Returns current plugin version.
*
* @return string Plugin version
*/
function plugin_get_version() {
  $plugin_data = get_plugin_data( __FILE__ );
  $plugin_version = $plugin_data['Version'];
  return $plugin_version;
}
?>