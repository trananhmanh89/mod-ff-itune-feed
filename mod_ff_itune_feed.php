<?php
/**
 * @package     Itune Feed Display
 * @subpackage  mod_ff_itune_feed
 *
 * @copyright   https://github.com/trananhmanh89/mod-ff-itune-feed
 * @license     GNU General Public License version 2 or later;
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/helper.php';

$feed = ModFFItuneFeedHelper::getFeed($module, $params);

if (!empty($feed->feed->results)) {
    $doc = Factory::getDocument();
    $doc->addStyleSheet(Uri::root() . 'modules/mod_ff_itune_feed/assets/mod_ff_itune_feed.css');

    $items = $feed->feed->results;
    
    require ModuleHelper::getLayoutPath('mod_ff_itune_feed', $params->get('layout', 'default'));
} else {
    Factory::getApplication()->enQueueMessage('Empty content. Module ' . $module->id, 'error');
}
