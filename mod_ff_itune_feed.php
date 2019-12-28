<?php
/**
 * @package     Itune Feed Module
 * @subpackage  mod_ff_itune_feed
 *
 * @copyright   https://funfis.com
 * @license     GNU General Public License version 2 or later;
 */


defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/helper.php';

$feed = ModFFItuneFeedHelper::getFeed($module, $params);

if (!empty($feed->feed->results)) {
    $doc = JFactory::getDocument();
    $doc->addStyleSheet(JUri::root() . 'modules/mod_ff_itune_feed/assets/mod_ff_itune_feed.css');

    $items = $feed->feed->results;
    
    require JModuleHelper::getLayoutPath('mod_ff_itune_feed', $params->get('layout', 'default'));
} else {
    JFactory::getApplication()->enQueueMessage('Empty content. Module ' . $module->id, 'error');
}
