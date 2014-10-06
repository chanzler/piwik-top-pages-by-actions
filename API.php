<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\TopPagesByVisits;

use \DateTimeZone;
use Piwik\Settings\SystemSetting;
use Piwik\Settings\UserSetting;
use Piwik\Settings\Manager as SettingsManager;
use Piwik\Site;


/**
 * API for plugin PerformanceMonitor
 *
 * @method static \Piwik\Plugins\PerformanceMonitor\API getInstance()
 */
class API extends \Piwik\Plugin\API {

	public static function get_timezone_offset($remote_tz, $origin_tz = null) {
    		if($origin_tz === null) {
        		if(!is_string($origin_tz = date_default_timezone_get())) {
            			return false; // A UTC timestamp was returned -- bail out!
        		}
    		}
    		$origin_dtz = new \DateTimeZone($origin_tz);
    		$remote_dtz = new \DateTimeZone($remote_tz);
    		$origin_dt = new \DateTime("now", $origin_dtz);
    		$remote_dt = new \DateTime("now", $remote_dtz);
    		$offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    		return $offset;
	}
    /**
     * Retrieves visit count from lastMinutes and peak visit count from lastDays
     * in lastMinutes interval for site with idSite.
     *
     * @param int $idSite
     * @param int $lastMinutes
     * @param int $lastDays
     * @return int
     */
    public static function getMostVisitedPages($idSite, $lastMinutes = 20)
    {
        \Piwik\Piwik::checkUserHasViewAccess($idSite);
		$timeZoneDiff = API::get_timezone_offset('UTC', Site::getTimezoneFor($idSite));

        $sql = "SELECT    COUNT(*) as number, idaction_url 
				FROM      " . \Piwik\Common::prefixTable("log_link_visit_action") . "
				WHERE     DATE_SUB(NOW(), INTERVAL ? MINUTE) < server_time
				AND       idsite = ?
				GROUP BY idaction_url ORDER BY number desc limit 10";

        $pages = \Piwik\Db::fetchAll($sql, array(
            $lastMinutes+($timeZoneDiff/60)+1000, $idSite
        ));
        return $pages;
    }


}
