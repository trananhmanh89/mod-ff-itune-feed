<?php
/**
 * @package     Itune Feed Display
 * @subpackage  mod_ff_itune_feed
 *
 * @copyright   https://github.com/trananhmanh89/mod-ff-itune-feed
 * @license     GNU General Public License version 2 or later;
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

defined('_JEXEC') or die('Restricted access');

class ModFFItuneFeedHelper
{
    public static function getFeed($module, $params)
    {
        $mediaPath = JPATH_ROOT . '/media/mod_ff_itune_feed';
        $url = $params->get('feed_url', 'https://rss.itunes.apple.com/api/v1/us/apple-music/coming-soon/all/10/explicit.json');

        $updateTime = +$params->get('update_time', 0);
        $updateTimeFile = $mediaPath . '/lastUpdate-' . md5($url) . '-' . $module->id . '.txt';
        $lastUpdate = file_exists($updateTimeFile) ? +file_get_contents($updateTimeFile) : 0;
        $now = time();

        $cacheFile = $mediaPath . '/cache-' . md5($url) . '-' . $module->id . '.txt';

        if ($lastUpdate + $updateTime < $now && file_exists($cacheFile)) {
            HTMLHelper::_('behavior.core');
            HTMLHelper::_('jquery.framework');

            $doc = Factory::getDocument();
            $doc->addScript(Uri::root() . '/modules/mod_ff_itune_feed/assets/update_ff_itune_cache.js');
            $doc->addScriptDeclaration(';updateFFItuneCache(' . $module->id . ');');
        }


        if (file_exists($cacheFile)) {
            return @json_decode(@file_get_contents($cacheFile));
        }

        $cache = self::setCache($cacheFile, $updateTimeFile, $url);

        return $cache;
    }

    protected static function setCache($cacheFile, $updateTimeFile, $url)
    {
        File::write($updateTimeFile, time());

        $app = Factory::getApplication();

        $options = new Registry();
        $options->set('userAgent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');

        try
        {
            $res = HttpFactory::getHttp($options)->get($url);
        } catch (RuntimeException $e)
        {
            return $app->enQueueMessage("Apple Feed Module Error: Couldn't get feed.");
        }

        if ($res->code != 200)
        {
            return $app->enQueueMessage("Apple Feed Module Error: Couldn't get feed.");
        }

        File::write($cacheFile, $res->body);
        File::write($updateTimeFile, time());

        return @json_decode($res->body);
    }

    public static function updateCacheAjax()
    {
        $input = Factory::getApplication()->input;
        $id = $input->getInt('id', 0);

        $module = Table::getInstance('module');
        $module->load($id);

        if (!$module->id || $module->module !== 'mod_ff_itune_feed' || !$module->published) {
            die('Error! Unknow module.');
        }

        $params = new Registry($module->params);
        $mediaPath = JPATH_ROOT . '/media/mod_ff_itune_feed';
        $url = $params->get('feed_url', 'https://rss.itunes.apple.com/api/v1/us/apple-music/coming-soon/all/10/explicit.json');

        $updateTime = +$params->get('update_time', 0);
        $updateTimeFile = $mediaPath . '/lastUpdate-' . md5($url) . '-' . $module->id . '.txt';
        $lastUpdate = file_exists($updateTimeFile) ? +file_get_contents($updateTimeFile) : 0;
        $now = time();

        $cacheFile = $mediaPath . '/cache-' . md5($url) . '-' . $module->id . '.txt';

        if ($lastUpdate + $updateTime < $now) {
            self::setCache($cacheFile, $updateTimeFile, $url);
        }
    }
}