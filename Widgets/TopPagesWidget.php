<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\TopPagesByActions\Widgets;

use Piwik\Plugins\TopPagesByActions\Controller;
use Piwik\Widget\Widget;
use Piwik\Widget\WidgetConfig;

/**
 * This class allows you to add your own widget to the Piwik platform. In case you want to remove widgets from another
 * plugin please have a look at the "configureWidgetsList()" method.
 * To configure a widget simply call the corresponding methods as described in the API-Reference:
 * http://developer.piwik.org/api-reference/Piwik/Plugin\Widget
 */
class TopPagesWidget extends Widget
{
    public static function configure(WidgetConfig $config) {
        $config->setCategoryId('Live!');
        $config->setName('TopPagesByActions_WidgetName');
        $config->setOrder(99);
    }

    public function render() {
        $a = new Controller();
        return $a->index();
    }

}