<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\TopPagesByActions;

use Piwik\WidgetsList;

/**
 */
class TopPagesByActions extends \Piwik\Plugin
{
    public function getListHooksRegistered()
    {
        return array(
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
        );
    }
	
	public function getJsFiles(&$jsFiles)
	{
		$jsFiles[] = 'plugins/TopPagesByActions/javascripts/toppagesbyaction.js';
	}
	
	public function getStylesheetFiles(&$stylesheets)
	{
		$stylesheets[] = "plugins/TopPagesByActions/stylesheets/toppagesbyactions.less";
	}
}
