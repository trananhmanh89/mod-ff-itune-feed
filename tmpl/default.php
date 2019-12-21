<?php 
defined('_JEXEC') or die('Restricted access');
?>
<div class="mod-<?php echo $module->id ?> ff-feed-items">
    <?php foreach ($items as $item): ?>
    <div class="ff-feed-item">
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
            <div class="ff-feed-item__artis">
                <?php echo $item->artistName ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>