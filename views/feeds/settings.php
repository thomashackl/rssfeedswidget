<form class="studip_form" action="<?= PluginEngine::getURL('RSSFeedsWidget/feeds/save_settings') ?>" method="post">
    <label class="caption">
        <?= dgettext('rssfeedswidget', 'Wie viele Einträge sollen pro Feed angezeigt werden?') ?>
        <input type="number" name="max_items" value="<?= $max_items ?>"/>
    </label>
    <table class="default" id="myfeeds">
        <caption>
            <?= dgettext('rssfeedswidget', 'Globale RSS-Feeds') ?>
            <span class="actions">
                <a href="" onclick="return STUDIP.RSSFeedsWidget.addFeed()" id="add-feed" title="<?= dgettext('rssfeedswidget', 'Globalen RSS-Feed hinzufügen') ?>">
                    <?= Assets::img('icons/16/blue/add.png') ?></a>
            </span>
        </caption>
        <thead>
            <tr>
                <th><?= dgettext('rssfeedswidget', 'Name') ?></th>
                <th><?= dgettext('rssfeedswidget', 'URL') ?></th>
                <th><?= dgettext('rssfeedswidget', 'Sichtbar?') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($feeds as $f) { ?>
            <tr class="feed">
                <td>
                    <input type="hidden" name="feeds[<?= $f->id ?>][id]" value="<?= $f->id ?>"/>
                    <input type="hidden" name="feeds[<?= $f->id ?>][user_id]" value="<?= $f->user_id ?>"/>
                    <input type="text" size="30" maxlength="255" name="feeds[<?= $f->id ?>][name]" value="<?= htmlReady($f->name) ?>"/>
                </td>
                <td>
                    <input type="text" size="50" maxlength="1024" name="feeds[<?= $f->id ?>][url]" value="<?= htmlReady($f->url) ?>"<?= ($f->user_id == 'studip') ? ' readonly' : '' ?>/>
                </td>
                <td>
                    <input type="checkbox" name="feeds[<?= $f->id ?>][visible]"<?= $f->hidden ? '' : ' checked' ?>/>
                </td>
                <td>
                    <a href="<?= PluginEngine::getURL('RSSFeedsWidget/feeds/delete/'.$f->id) ?>" id="delete-<?= $f->id ?>" class="delete-feed" onclick="return STUDIP.RSSFeedsWidget.askDelete('<?= $f->id ?>')" data-confirm="<?= dgettext('rssfeedswidget', 'Wollen Sie diesen Feed wirklich löschen?') ?>" title="<?= dgettext('rssfeedswidget', 'Globalen RSS-Feed löschen') ?>">
                        <?= Assets::img('icons/16/blue/trash.png') ?></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?= CSRFProtection::tokenTag() ?>
    <div class="submit_wrapper" data-dialog-buttons>
        <?= Studip\Button::createAccept(dgettext('rssfeedswidget', 'Speichern'), 'submit', array('data-dialog-button' => '')) ?>
        <?= Studip\LinkButton::createCancel(dgettext('rssfeedswidget', 'Abbrechen'), URLHelper::getLink('dispatch.php/start'), array('data-dialog-button' => '', 'data-dialog' => 'close')) ?>
    </div>
</form>