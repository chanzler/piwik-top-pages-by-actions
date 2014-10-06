<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\TopPagesByVisits;

use Piwik\WidgetsList;

/**
 */
class TopPagesByVisits extends \Piwik\Plugin
{
    /**
     * @see Piwik\Plugin::getListHooksRegistered
     */
    public function getListHooksRegistered()
    {
        return array(
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'WidgetsList.addWidgets' => 'addWidget',
        );
    }
	
	public function getJsFiles(&$jsFiles)
	{
		//$jsFiles[] = 'plugins/TopPagesByVisits/javascripts/toppagesbyvisits.js';
		$jsFiles[] = 'plugins/TopPagesByVisits/javascripts/tablesorter.js';
	}
	
	public function getStylesheetFiles(&$stylesheets)
	{
		//$stylesheets[] = "plugins/TopPagesByVisits/stylesheets/performancemonitor.css";
	}
	
	/**
	 * Add Widget to Live! >
	 */
	public function addWidget()
	{
		WidgetsList::add( 'Live!', 'TopPagesByVisits_WidgetName', 'TopPagesByVisits', 'index');
	}
	
}
