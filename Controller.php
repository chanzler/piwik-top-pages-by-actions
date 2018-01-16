<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\TopPagesByActions;

use Piwik\View;
use Piwik\Piwik;
use Piwik\Common;
use Piwik\Settings\SystemSetting;
use Piwik\Settings\UserSetting;
use Piwik\Settings\Manager as SettingsManager;

/**
 *
 */
class Controller extends \Piwik\Plugin\Controller
{
    public function index()
    {
		$settings = new SystemSettings();

        $view = new View('@TopPagesByActions/index.twig');
        $this->setBasicVariablesView($view);
        $view->refreshInterval = (int)$settings->refreshInterval->getValue();
        $view->numberOfEntries = (int)$settings->numberOfEntries->getValue();
        $view->idSite = $this->idSite;

        return $view->render();
    }
}
