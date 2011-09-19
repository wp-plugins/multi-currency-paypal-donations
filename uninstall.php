<?php
global $wpdb;

function deleteOptions()
{
	$args = func_get_args();
	$num = count($args);

	if ($num == 1) {
		return (delete_option($args[0]) ? TRUE : FALSE);
	}
	elseif (count($args) > 1)
	{
		foreach ($args as $option) {
			delete_option($option);
		}
		return TRUE;
	}
	return FALSE;
}

//Delet all our options
if (deleteOptions('mcpd-accounts', 'mcpd-contactlink', 'mcpd-styles', 'mcpd-default', 'mcpd-itemname', 'mcpd-thanks', 'mcpd-monthly', 'mcpd-onetime', 'mcpd-onetimecheck', 'mcpd-monthlycheck', 'mcpd-offsetcheck', 'mcpd_db_version')){
   echo 'Options have been deleted!';
} else {
   echo 'An error has occurred while trying to delete the options from database!';
}

//Drop our tables from the database
$table_name = $wpdb->prefix . "mcpd_currency";
$ipn_table_name = $wpdb->prefix . "mcpd_ipn";
$wpdb->query("DROP TABLE ".$table_name);
$wpdb->query("DROP TABLE ".$ipn_table_name);

?>