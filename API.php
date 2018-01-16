<?php
/**
 * Piwik -  free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\TopPagesByActions;

use \DateTimeZone;
use Piwik\Common;
use Piwik\Db;
use Piwik\Piwik;
use Piwik\Site;


/**
 * API for plugin TopPagesByActions
 *
 * @method static API getInstance()
 */
class API extends \Piwik\Plugin\API {

	private static function get_timezone_offset($remote_tz, $origin_tz = null) {
    		if($origin_tz === null) {
        		if(!is_string($origin_tz = date_default_timezone_get())) {
            			return false; // A UTC timestamp was returned -- bail out!
        		}
    		}
			if (preg_match("/^UTC[-+]*/", $origin_tz)){
				return(substr($origin_tz, 3));
    		}
			
    		$origin_dtz = new \DateTimeZone($origin_tz);
    		$remote_dtz = new \DateTimeZone($remote_tz);
    		$origin_dt = new \DateTime("now", $origin_dtz);
    		$remote_dt = new \DateTime("now", $remote_dtz);
    		$offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    		return $offset;
	}

    /**
     * Retrieves action count from last 20 minutes
     *
     * @param int $idSite
     * @param int $lastMinutes
     * @return array
     */
    public static function getTopPagesByActions($idSite, $lastMinutes = 20)
    {
    	
        Piwik::checkUserHasViewAccess($idSite);
		$historical = false;
		$settings = new SystemSettings();
        $numberOfEntries = (int)$settings->numberOfEntries->getValue();
		$timeZoneDiff = API::get_timezone_offset('UTC', Site::getTimezoneFor($idSite));

        $sql = "SELECT    COUNT(*) AS number, idaction_url, AVG(TIME_TO_SEC(time_spent_ref_action)/60) as time, idaction_name 
				FROM      " . Common::prefixTable("log_link_visit_action") . "
				WHERE     DATE_SUB(NOW(), INTERVAL ? MINUTE) < server_time 
				AND       idsite = ?
				GROUP BY idaction_url ORDER BY number desc, server_time desc LIMIT ". $numberOfEntries;
		
		$pages = Db::fetchAll($sql, array(
            $lastMinutes+($timeZoneDiff/60), $idSite 
        ));
		foreach ($pages as &$value) {
			if($value['idaction_name'] != null && $value['idaction_name'] != 0) {
				$nameSql = "SELECT    name 
						FROM      " . Common::prefixTable("log_action") . "
						WHERE     idaction = ". $value['idaction_name'];
				$name = Db::fetchAll($nameSql);
				$value['name'] = $name[0]['name'];
	        } else {
	        	$value['name'] = "null";
	        }
			if($value['idaction_url'] != null && $value['idaction_url'] != 0) {
		        $urlSql = "SELECT    name AS url
						FROM      " . Common::prefixTable("log_action") . "
						WHERE     idaction = ". $value['idaction_url'];
		        $url = Db::fetchAll($urlSql);
				$value['url'] = $url[0]['url'];
	        } else {
	        	$value['url'] = "null";
	        }
		}
        return $pages;
    }
}
