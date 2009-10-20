<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2005 - 2009 Dimitri König <dk@cabag.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
 /*
 * Service 'Automatic login by Session' for the 'yellowpay2commerce' extension.
 *
 * @author	Dimitri König <dk@cabag.ch>
 * @author	Jonas Dübi <jd@cabag.ch>
 */


class tx_yellowpay2commerce_sv1 extends tx_sv_authbase 	{
	private function getUserIDByYellowpayData() {
		$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'*',
						'fe_sessions',
						'MD5(CONCAT(ses_id, \''.mysql_escape_string($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']).'\')) = \''.mysql_escape_string(t3lib_div::_GP('COMPLUS')).'\'',
						'',
						'ses_tstamp DESC'
				);
		
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbres);
		
		if($row) {
			// save session id for overwrithe new created session
			$GLOBALS['tx_yellowpay2commerce_sv1_tmp_ses']['ses_id'] = $row['ses_id'];
			
			// get session data for overwrithe new created session
			$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'fe_session_data', 'hash='.$GLOBALS['TYPO3_DB']->fullQuoteStr($row['ses_id'], 'fe_session_data'));
			
			if ($sesDataRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbres))	{
				$GLOBALS['tx_yellowpay2commerce_sv1_tmp_ses']['ses_content'] = unserialize($sesDataRow['content']);
			}
			
			return $row['ses_userid'];
		} else {
			return 0;
		}
	}
	
	/**
	 * Find a user by yellowpay data
	 *
	 * @return	mixed	user array or false
	 */
	function getUser() {
		
		$feUserID = $this->getUserIDByYellowpayData();
		$row = false;
		
		if($feUserID > 0) {
			$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
								'*',
								$this->db_user['table'],
								'fe_users.uid = '.intval($feUserID)
						);
			
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbres);
			$GLOBALS['TYPO3_DB']->sql_free_result($dbres);
		}
		
		if($row) {
			return $row;
		} else {
			return 0;
		}
	}
	
	
	/**
	 * Authenticate a user
	 *
	 * @param	array 	Data of user.
	 * @return	boolean
	 */	
	function authUser($user)	{
		
		$feUserID = $this->getUserIDByYellowpayData();
		
		if($feUserID > 0) {
			return 200;
		} else {
			return 100;
		}
	}
	
	/**
		debug($GLOBALS['tx_yellowpay2commerce_sv1_tmp_ses']);
		if(!empty($GLOBALS['tx_yellowpay2commerce_sv1_tmp_ses']['ses_id'])) {
			$GLOBALS['TSFE']->fe_user->id = $GLOBALS['tx_yellowpay2commerce_sv1_tmp_ses']['ses_id'];
			$GLOBALS["TSFE"]->fe_user->sesData = $GLOBALS['tx_yellowpay2commerce_sv1_tmp_ses']['ses_content'];
		}
	 **/
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/yellowpay2commerce/sv1/class.tx_yellowpay2commerce_sv1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/yellowpay2commerce/sv1/class.tx_yellowpay2commerce_sv1.php"]);
}

?>