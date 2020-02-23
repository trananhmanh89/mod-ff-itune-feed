<?php
/**
 * @package     Itune Feed Display
 * @subpackage  mod_ff_itune_feed
 *
 * @copyright   https://github.com/trananhmanh89/mod-ff-music-chart
 * @license     GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die('Restricted access');
?>
<div class="mod-<?php echo $module->id ?> ff-feed-items">
    <?php foreach ($items as $key => $item): ?>
    <?php 
        if ($key >= $params->get('num_item', 25) ) {
            break;
        }

        $isLong = $key + 1 > 99 ? true : false;
    ?>
    <div class="ff-feed-item">
        <div class="ff-feed-item__rank <?php echo $isLong ? 'ff-feed-item__rank--long' : '' ?>">
            <?php echo $key + 1 ?>
        </div>
        <div class="ff-feed-item__image">
            <a href="<?php echo $item->url ?>">
                <img src="<?php echo $item->artworkUrl100 ?>" alt="<?php echo $item->name ?>">
            </a>
        </div>
        <div class="ff-feed-item__detail">
            <div class="ff-feed-item__title">
                <a href="<?php echo $item->url ?>">
                    <?php echo $item->name ?>
                </a>
            </div>
            <div class="ff-feed-item__artist">
                <?php echo $item->artistName ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>