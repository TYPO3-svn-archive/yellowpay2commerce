<?php
/**
 * Converts html2pdf of a certain url
 *
 * @author	Jonas Duebi <jd@cabag.ch>
 * @package	TYPO3
 * @subpackage	tx_cabaghtml2pdf
 */

//Exit, if script is called directly (must be included via eID in index_ts.php)
if (!defined('PATH_typo3conf')) {
	die ('Could not access this script directly!');
}

// Initialize FE user object:
$feUserObj = tslib_eidtools::initFeUser();
	
// Connect to database:
tslib_eidtools::connectDB();

mail('jd@cabag.ch', 'test', print_r($_REQUEST, true));

?>
