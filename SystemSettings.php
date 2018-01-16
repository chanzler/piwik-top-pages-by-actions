<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\TopPagesByActions;

use Piwik\Piwik;
use Piwik\Settings\FieldConfig;
use Piwik\Settings\Setting;

/**
 * Defines Settings for TopPagesByActionsPlugin.
 *
 */
class SystemSettings extends \Piwik\Settings\Plugin\SystemSettings
{
    /** @var Setting */
    public $refreshInterval;

    /** @var Setting */
    public $numberOfEntries;
	
    protected function init()
    {
//        $this->setIntroduction(Piwik::translate('TopPagesByActions_SettingsIntroduction'));

        // System setting --> textbox converted to int defining a validator and filter
        $this->refreshInterval = $this->createRefreshIntervalSetting();

        // System setting --> textbox converted to int defining a validator and filter
        $this->numberOfEntries = $this->createNumberOfEntriesSetting();
        
    }

    private function createRefreshIntervalSetting()
    {
        $defaultValue = '30';
        $phpType = FieldConfig::TYPE_INT;
        return $this->makeSetting('refreshInterval', $defaultValue, $phpType, function (FieldConfig $field) {
            $field->title = Piwik::translate('TopPagesByActions_SettingsRefreshInterval');
            $field->description = Piwik::translate('TopPagesByActions_SettingsRefreshIntervalDescription');
            $field->inlineHelp = Piwik::translate('TopPagesByActions_SettingsRefreshIntervalHelp');
            $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
            $field->uiControlAttributes = array('size' => 3);
            $field->validate = function ($value, $setting) {
                if ($value < 1) {
                    throw new \Exception('The value has to be larger than 0.');
                }
            };
        });
    }

    private function createNumberOfEntriesSetting()
    {
        $defaultValue = '15';
        $phpType = FieldConfig::TYPE_INT;
        return $this->makeSetting('numberOfEntries', $defaultValue, $phpType, function (FieldConfig $field) {
            $field->title = Piwik::translate('TopPagesByActions_SettingsNumber');
            $field->description = Piwik::translate('TopPagesByActions_SettingsNumberDescription');
            $field->inlineHelp = Piwik::translate('TopPagesByActions_SettingsNumberHelp');
            $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
            $field->uiControlAttributes = array('size' => 3);
            $field->validate = function ($value, $setting) {
                if ($value > 30 && $value < 10) {
                    throw new \Exception('Value is invalid');
                }
            };
        });
    }

}
