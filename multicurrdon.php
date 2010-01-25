<?php
/*
Plugin Name: Multi Currency PayPal Donation
Plugin URI: http://www.makesomecode.com/2010/01/07/multi-currency-paypal-donations-wp-plugin
Description: PayPal charges high fees for cross border transactions. If you are one of the fortunate few that have paypal accounts in multiple currencie then this plugin is for you. It allows you to route different currencies to specific paypal accounts. Even if you don't have multiple paypal accounts this is still a great plugin for accepting donations. Visit the <a href="options-general.php?page=mcpd">configuration page</a> to get started. To use put {mcpdform} in a page or post wherever you want your form to show up.
Version: 1.0
Author: Nick Verwymeren
Author URI: http://www.makesomecode.com
Copyright 2009  Nick Verwymeren  (email: nickv@makesomecode.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


add_action('admin_init', 'mcpd_init' );
register_activation_hook(__FILE__,'mcpd_install');
add_action('admin_menu', 'mcpd_menu');
add_action('the_content', 'mcpd_displayForm');
@define('MCPD_PATH', dirname(__FILE__));
@define('MCPD_ABS', ABSPATH);
@define('MCPD_JS', MCPD_PATH . '/js');

function mcpd_init(){
	
	register_setting( 'mcpd-options', 'mcpd-accounts' );
	register_setting( 'mcpd-options', 'mcpd-thanks' );
	register_setting( 'mcpd-options', 'mcpd-itemname' );
	register_setting( 'mcpd-options', 'mcpd-monthly' );
	register_setting( 'mcpd-options', 'mcpd-onetime' );
	register_setting( 'mcpd-options', 'mcpd-onetimecheck' );
	register_setting( 'mcpd-options', 'mcpd-monthlycheck' );
	register_setting( 'mcpd-options', 'mcpd-offsetcheck' );
	register_setting( 'mcpd-options', 'mcpd-contactlink' );
	//register_setting( 'mcpd-options', 'mcpd-update' );

}

function mcpd_install () {
	global $wpdb;
	$mcpd_db_version = "1.1";
	
	$table_name = $wpdb->prefix . "mcpd_currency";
	$installed_ver = get_option( "mcpd_db_version" );

	if( $installed_verÊ!= $mcpd_db_version ) {
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

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	
		$insert = "INSERT INTO " .$table_name. " (`id`,`currency`,`symbol`,`symbol_html`,`code`,`has_regions`,`paypal_accepts`,`paypal_in_country`,`paypal_email`)
VALUES (1,'Mauritanian Ouguiya','','','MRO',0,NULL,NULL,NULL),
	(2,'Maltese Lira','','','MTL',0,NULL,NULL,NULL),
	(3,'Maldive Rufiyaa','','','MVR',0,NULL,NULL,NULL),
	(4,'Malaysian Ringgit','','','MYR',0,NULL,1,NULL),
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
	(75,'Brazilian Real','','','BRL',0,NULL,1,NULL),
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
		add_option("mcpd_db_version", $mcpd_db_version);
		update_option( "mcpd_db_version", $mcpd_db_version );
		
		//Create page
/*		$post = array(
		  'ID' => '',
		  'menu_order' => '0',
		  'comment_status' => 'closed',
		  'ping_status' => 'closed',
		  'pinged' => '',
		  'post_author' => '1',
		  'post_category' => '',
		  'post_content' => $pgcontent,
		  'post_date' => '',
		  'post_date_gmt' => '',
		  'post_excerpt' => '',
		  'post_name' => 'Donations556',
		  'post_parent' => '0',
		  'post_password' => '',
		  'post_status' => 'publish',
		  'post_title' => 'Donations556',
		  'post_type' => 'page',
		  'tags_input' => '',
		  'to_ping' => '');
		wp_insert_post( $post );
*/		
   }
}

function mcpd_menu() {
	add_options_page('Multi Currency Donation Options', 'Multi Currency Donations', 'administrator', 'mcpd', 'mcpd_admin');
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
?>