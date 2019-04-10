<?php

class Initialize extends Migration {

    public function up() {
        try {
            Config::get()->create('RSSFEEDSWIDGET_MAX_FEED_ITEMS', [
                'value' => 5,
                'type' => 'integer',
                'range' => 'global',
                'section' => 'rssfeedswidget',
                'description' => 'Legt fest, wie viele Newseinträge maximal pro RSS-Feed angezeigt werden.'
            ]);
        } catch (InvalidArgumentException $e) {}
    }

    public function down() {
        $entries = ConfigEntry::findByField('RSSFEEDSWIDGET_MAX_FEED_ITEMS');
        foreach ($entries as $e) {
            $e->delete();
        }
    }

}
