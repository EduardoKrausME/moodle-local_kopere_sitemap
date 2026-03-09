<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * index.php
 *
 * @package   local_kopere_sitemap
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_sitemap\config;

require_once(__DIR__ . "/../../config.php");

require_login();
require_capability("local/kopere_sitemap:viewadmin", context_system::instance());

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url("/local/kopere_sitemap/index.php"));
$PAGE->set_pagelayout("admin");
$PAGE->set_title(get_string("adminpage_title", "local_kopere_sitemap"));
$PAGE->set_heading(get_string("adminpage_title", "local_kopere_sitemap"));

$status = config::is_enabled()
    ? get_string("adminpage_status_enabled", "local_kopere_sitemap")
    : get_string("adminpage_status_disabled", "local_kopere_sitemap");

$templatecontext = [
    "title" => get_string("adminpage_title", "local_kopere_sitemap"),
    "description" => get_string("adminpage_description", "local_kopere_sitemap"),
    "statuslabel" => get_string("adminpage_status", "local_kopere_sitemap"),
    "statusvalue" => $status,
    "sitemapurllabel" => get_string("adminpage_sitemapurl", "local_kopere_sitemap"),
    "sitemapurl" => config::get_public_sitemap_url(),
    "openxmllabel" => get_string("adminpage_openxml", "local_kopere_sitemap"),
    "items" => [
        [
            "name" => get_string("adminpage_item_frontpage", "local_kopere_sitemap"),
            "enabled" => config::include_frontpage(),
        ],
        [
            "name" => get_string("adminpage_item_courses", "local_kopere_sitemap"),
            "enabled" => config::include_courses(),
        ],
        [
            "name" => get_string("adminpage_item_categories", "local_kopere_sitemap"),
            "enabled" => config::include_categories(),
        ],
        [
            "name" => get_string("adminpage_item_blog", "local_kopere_sitemap"),
            "enabled" => config::include_blog(),
        ],
        [
            "name" => get_string("adminpage_item_forums", "local_kopere_sitemap"),
            "enabled" => config::include_forums(),
        ],
        [
            "name" => get_string("adminpage_item_frontpagemodules", "local_kopere_sitemap"),
            "enabled" => config::include_frontpage_modules(),
        ],
    ],
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template("local_kopere_sitemap/index", $templatecontext);
echo $OUTPUT->footer();
