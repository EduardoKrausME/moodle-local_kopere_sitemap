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
 * settings.php
 *
 * @package   local_sitemap
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = new admin_settingpage("local_sitemap", get_string("pluginname", "local_sitemap"));

    $settings->add(new admin_setting_configcheckbox(
        "local_sitemap/enabled",
        get_string("settings_enabled", "local_sitemap"),
        get_string("settings_enabled_desc", "local_sitemap"),
        1
    ));

    $settings->add(new admin_setting_configtext(
        "local_sitemap/sitemapurl",
        get_string("settings_sitemapurl", "local_sitemap"),
        get_string("settings_sitemapurl_desc", "local_sitemap"),
        "/local/sitemap/sitemap.php",
        PARAM_URL
    ));

    $settings->add(new admin_setting_configcheckbox(
        "local_sitemap/includefrontpage",
        get_string("settings_includefrontpage", "local_sitemap"),
        get_string("settings_includefrontpage_desc", "local_sitemap"),
        1
    ));

    $settings->add(new admin_setting_configcheckbox(
        "local_sitemap/includecourses",
        get_string("settings_includecourses", "local_sitemap"),
        get_string("settings_includecourses_desc", "local_sitemap"),
        1
    ));

    $settings->add(new admin_setting_configcheckbox(
        "local_sitemap/includecategories",
        get_string("settings_includecategories", "local_sitemap"),
        get_string("settings_includecategories_desc", "local_sitemap"),
        1
    ));

    $settings->add(new admin_setting_configcheckbox(
        "local_sitemap/includeblog",
        get_string("settings_includeblog", "local_sitemap"),
        get_string("settings_includeblog_desc", "local_sitemap"),
        1
    ));

    $settings->add(new admin_setting_configcheckbox(
        "local_sitemap/includeforums",
        get_string("settings_includeforums", "local_sitemap"),
        get_string("settings_includeforums_desc", "local_sitemap"),
        1
    ));

    $settings->add(new admin_setting_configcheckbox(
        "local_sitemap/includefrontpagemodules",
        get_string("settings_includefrontpagemodules", "local_sitemap"),
        get_string("settings_includefrontpagemodules_desc", "local_sitemap"),
        1
    ));

    $ADMIN->add("localplugins", $settings);
}
