<script type="text/javascript" src="<?= $js ?>"></script>
<form class="studip_form" action="<?= PluginEngine::getURL('RSSFeedsWidget/feeds/save_subscriptions') ?>" method="post">
    <table class="default" id="myfeeds">
        <caption>
            <?= dgettext('rssfeedswidget', 'Meine RSS-Feeds') ?>
            <span class="actions">
                <a href="" id="add-feed" title="<?= dgettext('rssfeedswidget', 'RSS-Feed hinzufügen') ?>">
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
                    <?php if ($f->user_id != 'studip') { ?>
                    <input type="text" size="30" maxlength="255" name="feeds[<?= $f->id ?>][name]" value="<?= htmlReady($f->name) ?>"/>
                    <?php } else { ?>
                    <?= htmlReady($f->name) ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($f->user_id != 'studip') { ?>
                    <input type="text" size="50" maxlength="1024" name="feeds[<?= $f->id ?>][url]" value="<?= htmlReady($f->url) ?>"<?= ($f->user_id == 'studip') ? ' readonly' : '' ?>/>
                    <?php } else { ?>
                    <?= htmlReady($f->url) ?>
                    <?php } ?>
                </td>
                <td>
                    <input type="checkbox" name="feeds[<?= $f->id ?>][visible]"<?= $f->hidden ? '' : ' checked' ?>/>
                </td>
                <td>
                    <?php if ($f->user_id != 'studip') { ?>
                    <a href="<?= PluginEngine::getURL('RSSFeedsWidget/feeds/delete/'.$f->id) ?>" class="delete-feed" data-confirm="<?= dgettext('rssfeedswidget', 'Wollen Sie diesen Feed wirklich löschen?') ?>" title="<?= dgettext('rssfeedswidget', 'Globalen RSS-Feed löschen') ?>">
                        <?= Assets::img('icons/16/blue/trash.png') ?></a>
                    <?php } ?>
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