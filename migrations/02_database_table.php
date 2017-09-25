<?php

class DatabaseTable extends Migration {

    public function up() {
        DBManager::get()->exec("CREATE TABLE IF NOT EXISTS `rss_feeds` (
            `feed_id` varchar(32) NOT NULL DEFAULT '' COLLATE latin1_bin,
            `user_id` varchar(32) NOT NULL DEFAULT '' COLLATE latin1_bin,
            `name` varchar(255) NOT NULL DEFAULT '' COLLATE utf8mb4_unicode_ci,
            `url` text NOT NULL COLLATE utf8mb4_unicode_ci,
            `mkdate` int(20) NOT NULL DEFAULT '0',
            `chdate` int(20) NOT NULL DEFAULT '0',
            `priority` int(11) NOT NULL DEFAULT '0',
            `hidden` tinyint(4) NOT NULL DEFAULT '0',
            `fetch_title` tinyint(3) unsigned NOT NULL DEFAULT '0')
            ENGINE=InnoDB ROW_FORMAT=DYNAMIC");
    }

    public function down() {
        $schema = DBManager::get()->fetchOne("SELECT `version` FROM `schema_version` WHERE `domain`='studip'");
        $version = $schema['version'];
        /*
         * Migration 155 dropped the table in core, so in earlier Stud.IP
         * versions this table must be kept.
         */
        if ($version >= 155) {
            DBManager::get()->exec("DROP TABLE IF EXISTS `rss_feeds`");
        }
    }

}
