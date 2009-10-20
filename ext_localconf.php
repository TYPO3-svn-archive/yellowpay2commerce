<?php

// add payment lib class
$TYPO3_CONF_VARS['EXTCONF']['commerce']['SYSPRODUCTS']['PAYMENT']['types']['yellowpay'] = array (
	'path' => t3lib_extmgm::extPath('yellowpay2commerce') .'class.tx_yellowpay2commerce.php',
	'class' => 'tx_yellowpay2commerce',
	'type'=> 2,
);


// hook for setting the yellowpay id as orderID
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/pi3/class.tx_commerce_pi3.php']['finishIt'][] = 'EXT:yellowpay2commerce/class.tx_yellowpay2commerce_hooks.php:tx_yellowpay2commerce_hooks';

$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['initFEuser'][] = 'EXT:yellowpay2commerce/class.tx_yellowpay2commerce_hooks.php:tx_yellowpay2commerce_hooks->restoreSession';

$TYPO3_CONF_VARS['SVCONF']['auth']['setup']['FE_alwaysFetchUser'] = 1;

t3lib_extMgm::addService($_EXTKEY,  'auth' /* sv type */,  'tx_yellowpay2commerce_sv1' /* sv key */,
		array(

			'title' => 'Automatic FE login by Yellowpay data',
			'description' => 'Login a frontend user automatically if the yellowpay data is found.',

			'subtype' => 'getUserFE,authUserFE',

			'available' => TRUE,
			'priority' => 55,
			'quality' => 50,

			'os' => '',
			'exec' => '',

			'classFile' => t3lib_extMgm::extPath($_EXTKEY).'sv1/class.tx_yellowpay2commerce_sv1.php',
			'className' => 'tx_yellowpay2commerce_sv1',
		)
	);


?>