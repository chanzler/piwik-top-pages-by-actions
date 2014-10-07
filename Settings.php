<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\TopPagesByVisits;

use Piwik\Settings\SystemSetting;
use Piwik\Settings\UserSetting;
use Piwik\Piwik;

/**
 * Defines Settings for PerformanceMonitorPlugin.
 *
 */
class Settings extends \Piwik\Plugin\Settings
{
    /** @var SystemSetting */
    public $refreshInterval;

    /** @var SystemSetting */
    public $numberOfEntries;
	
    protected function init()
    {
        $this->setIntroduction(Piwik::translate('TopPagesByVisits_SettingsIntroduction'));

        // System setting --> textbox converted to int defining a validator and filter
        $this->createRefreshIntervalSetting();

        // System setting --> textbox converted to int defining a validator and filter
        $this->createNumberOfEntriesSetting();
        
    }

    private function createRefreshIntervalSetting()
    {
        $this->refreshInterval        = new SystemSetting('refreshInterval', Piwik::translate('TopPagesByVisits_SettingsRefreshInterval'));
        $this->refreshInterval->readableByCurrentUser = true;
        $this->refreshInterval->type  = static::TYPE_INT;
        $this->refreshInterval->uiControlType = static::CONTROL_TEXT;
        $this->refreshInterval->uiControlAttributes = array('size' => 3);
        $this->refreshInterval->description     = Piwik::translate('TopPagesByVisits_SettingsRefreshIntervalDescription');
        $this->refreshInterval->inlineHelp      = Piwik::translate('TopPagesByVisits_SettingsRefreshIntervalHelp');
        $this->refreshInterval->defaultValue    = '30';
        $this->refreshInterval->validate = function ($value, $setting) {
            if ($value < 1) {
                throw new \Exception('Value is invalid');
            }
        };

        $this->addSetting($this->refreshInterval);
    }

    private function createNumberOfEntriesSetting()
    {
        $this->numberOfEntries        = new SystemSetting('numberOfEntries', Piwik::translate('TopPagesByVisits_SettingsNumber'));
        $this->numberOfEntries->readableByCurrentUser = true;
        $this->numberOfEntries->type  = static::TYPE_INT;
        $this->numberOfEntries->uiControlType = static::CONTROL_TEXT;
        $this->numberOfEntries->uiControlAttributes = array('size' => 3);
        $this->numberOfEntries->description     = Piwik::translate('TopPagesByVisits_SettingsNumberDescription');
        $this->numberOfEntries->inlineHelp      = Piwik::translate('TopPagesByVisits_SettingsNumberHelp');
        $this->numberOfEntries->defaultValue    = '30';
        $this->numberOfEntries->validate = function ($value, $setting) {
            if ($value > 30 && $value < 10) {
                throw new \Exception('Value is invalid');
            }
        };

        $this->addSetting($this->numberOfEntries);
    }

}
