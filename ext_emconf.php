<?php

########################################################################
# Extension Manager/Repository config file for ext: "yellowpay2commerce"
#
# Auto generated 07-10-2009 12:07
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'yellowpay2commerce',
	'description' => 'Provides the yellowpay payment provider for use with tx_commerce',
	'category' => 'misc',
	'author' => 'Cedric Spindler',
	'author_email' => 'cs@cabag.ch',
	'author_company' => 'cab services ag',
	'shy' => '',
	'dependencies' => 'commerce',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.1.8',
	'constraints' => array(
		'depends' => array(
			'commerce' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:14:{s:12:"#Untitled-3#";s:4:"3fce";s:9:"ChangeLog";s:4:"68fe";s:10:"README.txt";s:4:"03fc";s:31:"class.tx_yellowpay2commerce.php";s:4:"6758";s:37:"class.tx_yellowpay2commerce_hooks.php";s:4:"ca34";s:21:"ext_conf_template.txt";s:4:"08dd";s:12:"ext_icon.gif";s:4:"0e5d";s:17:"ext_localconf.php";s:4:"03da";s:12:"fe_index.php";s:4:"497a";s:14:"doc/manual.sxw";s:4:"227e";s:19:"doc/postfinance.jpg";s:4:"838e";s:19:"doc/wizard_form.dat";s:4:"902c";s:20:"doc/wizard_form.html";s:4:"13e2";s:39:"sv1/class.tx_yellowpay2commerce_sv1.php";s:4:"6735";}',
	'suggests' => array(
	),
);

?>