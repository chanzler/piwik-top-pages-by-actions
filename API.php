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
		$settings = new Settings('TopPagesByVisits');
        $limit = (int)$settings->numberOfEntries->getValue();
		$timeZoneDiff = API::get_timezone_offset('UTC', Site::getTimezoneFor($idSite));

        $sql = "SELECT    COUNT(*) AS number, llva.idaction_url, la.name AS name, la2.name AS url, AVG(TIME_TO_SEC(llva.time_spent_ref_action)/60) as time 
				FROM      " . \Piwik\Common::prefixTable("log_link_visit_action") . " AS llva
				LEFT JOIN " . \Piwik\Common::prefixTable("log_action") . " AS la ON llva.idaction_name = la.idaction
				LEFT JOIN " . \Piwik\Common::prefixTable("log_action") . " AS la2 ON llva.idaction_url = la2.idaction
				WHERE     DATE_SUB(NOW(), INTERVAL ? MINUTE) < llva.server_time
				AND       llva.idsite = ?
				GROUP BY llva.idaction_url ORDER BY number desc, llva.server_time desc LIMIT ?";
        
        $pages = \Piwik\Db::fetchAll($sql, array(
            $lastMinutes+($timeZoneDiff/60), $idSite, $limit
        ));
        return $pages;
    }


}
