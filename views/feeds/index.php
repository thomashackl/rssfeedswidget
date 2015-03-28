<section class="contentbox" id="rssfeedswidget" data-feed-url="<?= PluginEngine::getURL('rssfeedswidget/feeds/get') ?>">
    <header>
        <?= htmlReady($controller->plugin->getPluginName()) ?>
    </header>
    <?php if ($items) { ?>
        <?php for ($i = 0; $i < sizeof($items) ; $i++) { ?>
    <article class="rssitem_<?= $i ?>">
        <header>
            <h1>
                <a href="<?= ContentBoxHelper::href('rssitem_'.$i, array('contentbox_type' => 'news')) ?>">
                    <?= studip_utf8decode($items[$i]->get_title()) ?>
                </a>
            </h1>
            <nav>
                <span>
                    <?= date('d.m.Y, H:i', strtotime($items[$i]->get_date())) ?>
                </span>
            </nav>
        </header>
        <section id="rssitem_<?= $i ?>">
            <?= studip_utf8decode($items[$i]->get_content()) ?>
        </section>
        <footer>
            <a href="<?= $items[$i]->get_link() ?>" target="_blank" title="<?= dgettext('rssfeedswidget', 'Zur Originalmeldung') ?>">
                <?= dgettext('rssfeedswidget', 'Zur Originalmeldung') ?></a>
        </footer>
    </article>
        <?php } ?>
    <?php } else { ?>
    <section>
        <?= dgettext('rssfeedswidget', 'Keine RSS-Feeds vorhanden. Um Feeds hinzuzufügen, klicken Sie rechts auf das RSS-Symbol.') ?>
    </section>
    <?php } ?>
</section>