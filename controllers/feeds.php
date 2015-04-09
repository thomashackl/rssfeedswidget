<?php
/**
 * feeds.php
 *
 * Shows RSS news feeds.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Thomas Hackl <thomas.hackl@uni-passau.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 */

class FeedsController extends AuthenticatedController {

    public function __construct($dispatcher) {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
        if (Request::isXhr()) {
            $this->set_layout(null);
            $this->response->add_header('Content-Type', 'text/html;charset=windows-1252');
        }
    }

    public function before_filter(&$action, &$args) {
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
    }

    public function index_action() {
        $globalfeeds = RSSFeed::findByUser_id('studip');
        foreach ($globalfeeds as &$g) {
            $g->hidden = $this->get_global_feed_visibility($g);
        }
        $myfeeds = RSSFeed::findByUser_id($GLOBALS['user']->id);
        $allfeeds = $globalfeeds + $myfeeds;
        $visiblefeeds = array_filter($allfeeds, function ($feed) {
            return $feed->hidden == 0;
        });
        $this->items = $this->get_feed_items($visiblefeeds);
    }

    public function subscriptions_action() {
        if (Request::isXhr()) {
            $this->set_layout(null);
        } else {
            $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        }
        $globalfeeds = RSSFeed::findByUser_id('studip');
        foreach ($globalfeeds as &$g) {
            $g->hidden = $this->get_global_feed_visibility($g);
        }
        $myfeeds = RSSFeed::findByUser_id($GLOBALS['user']->id);
        $this->feeds = array_merge($globalfeeds, $myfeeds);
        usort($this->feeds, function($a, $b) { return strnatcasecmp($a->name, $b->name); });
    }

    public function save_subscriptions_action() {
        CSRFProtection::verifyUnsafeRequest();
        $config = UserConfig::get($GLOBALS['user']->id)->RSSFEEDSWIDGET_SETTINGS;
        if ($config) {
            $config = unserialize($config);
        } else {
            $config = array();
        }
        foreach (Request::getArray('feeds') as $feed) {
            if ($feed['user_id'] == 'studip') {
                $config['feeds'][$feed['id']]['hidden'] = $feed['visible'] ? 0 : 1;
            } else {
                if ($feed['id']) {
                    $f = RSSFeed::find($feed['id']);
                } else {
                    $f = new RSSFeed();
                    $f->priority = 0;
                    $f->fetch_title = 0;
                }
                $f->user_id = $GLOBALS['user']->id;
                $f->name = $feed['name'];
                $f->url = $feed['url'];
                $f->hidden = $feed['visible'] ? 0 : 1;
                $f->store();
            }
        }
        if ($config) {
            UserConfig::get($GLOBALS['user']->id)->store('RSSFEEDSWIDGET_SETTINGS', serialize($config));
        }
        $this->redirect(URLHelper::getLink('dispatch.php/start'));
    }

    public function settings_action() {
        $GLOBALS['perm']->check('root');
        if (Request::isXhr()) {
            $this->set_layout(null);
        } else {
            $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        }
        $this->max_items = Config::get()->RSSFEEDSWIDGET_MAX_FEED_ITEMS;
        $this->feeds = RSSFeed::findByUser_id('studip');
        usort($this->feeds, function($a, $b) { return strnatcasecmp($a->name, $b->name); });
    }

    public function save_settings_action() {
        CSRFProtection::verifyUnsafeRequest();
        $GLOBALS['perm']->check('root');
        Config::get()->store('RSSFEEDSWIDGET_MAX_FEED_ITEMS', Request::int('max_items', 5));
        foreach (Request::getArray('feeds') as $feed) {
            if ($feed['id']) {
                $f = RSSFeed::find($feed['id']);
            } else {
                $f = new RSSFeed();
                $f->priority = 0;
                $f->fetch_title = 0;
            }
            $f->user_id = 'studip';
            $f->name = $feed['name'];
            $f->url = $feed['url'];
            $f->hidden = $feed['visible'] ? 0 : 1;
            $f->store();
        }
        $this->redirect(URLHelper::getLink('dispatch.php/start'));
    }

    public function delete_action($feed_id) {
        $feed = RSSFeed::find($feed_id);
        if ($feed->user_id == 'studip' && !$GLOBALS['perm']->have_perm('root')) {
            continue;
        } else {
            $feed->delete();
        }
        $this->redirect(URLHelper::getLink('dispatch.php/start'));
    }

    private function get_feed_items($feeds) {
        require_once(__DIR__.'/../vendor/simplepie/autoloader.php');
        $items = array();
        // Get all feed urls.
        $urls = array_map(function($f) {
            return $f->url;
        }, $feeds);
        $max_items_per_feed = Config::get()->RSSFEEDSWIDGET_MAX_ITEMS ?: 5;
        // Merge all feed items.
        foreach ($feeds as $feed) {
            $sp = new SimplePie();
            // Enable caching in default location.
            $sp->set_cache_location($GLOBALS['CACHING_FILECACHE_PATH']);
            $sp->set_feed_url($feed->url);
            $sp->init();
            foreach ($sp->get_items(0, $max_items_per_feed) as $item) {
                $items[$item->get_title()] = $item;
            }
            unset($sp);
        }
        // Sort items by timestamp.
        usort($items, function($a, $b) {
            if ($a->get_date() && $b->get_date()) {
                return strtotime($b->get_date()) - strtotime($a->get_date());
            } else if ($a->get_date()) {
                return -1;
            } else {
                return 1;
            }
        });
        return $items;
    }

    private function get_global_feed_visibility($feed) {
        $config = UserConfig::get($GLOBALS['user']->id)->RSSFEEDSWIDGET_SETTINGS;
        if ($config) {
            $config = unserialize($config);
            if (isset($config['feeds'][$feed->id])) {
                return $config['feeds'][$feed->id]['hidden'];
            }
        }
        return $feed->hidden;
    }

}