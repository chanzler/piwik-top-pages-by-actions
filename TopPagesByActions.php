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
		$jsFiles[] = 'plugins/TopPagesByActions/javascripts/toppagesbyaction.js';
	}
	
	public function getStylesheetFiles(&$stylesheets)
	{
		$stylesheets[] = "plugins/TopPagesByActions/stylesheets/toppagesbyactions.css";
	}
	
	/**
	 * Add Widget to Live! >
	 */
	public function addWidget()
	{
		WidgetsList::add( 'Live!', 'TopPagesByActions_WidgetName', 'TopPagesByActions', 'index');
	}
	
}
