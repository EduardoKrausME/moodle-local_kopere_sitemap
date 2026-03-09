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
 * config.php
 *
 * @package   local_kopere_sitemap
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_sitemap;

use dml_exception;

/**
 * Reads plugin configuration values.
 */
class config {
    /**
     * Returns true when plugin is enabled.
     *
     * @return bool
     * @throws dml_exception
     */
    public static function is_enabled(): bool {
        return (bool)get_config("local_kopere_sitemap", "enabled");
    }

    /**
     * Returns true when frontpage should be included.
     *
     * @return bool
     * @throws dml_exception
     */
    public static function include_frontpage(): bool {
        return (bool)get_config("local_kopere_sitemap", "includefrontpage");
    }

    /**
     * Returns true when courses should be included.
     *
     * @return bool
     * @throws dml_exception
     */
    public static function include_courses(): bool {
        return (bool)get_config("local_kopere_sitemap", "includecourses");
    }

    /**
     * Returns true when categories should be included.
     *
     * @return bool
     * @throws dml_exception
     */
    public static function include_categories(): bool {
        return (bool)get_config("local_kopere_sitemap", "includecategories");
    }

    /**
     * Returns true when blog should be included.
     *
     * @return bool
     * @throws dml_exception
     */
    public static function include_blog(): bool {
        return (bool)get_config("local_kopere_sitemap", "includeblog");
    }

    /**
     * Returns true when forums should be included.
     *
     * @return bool
     * @throws dml_exception
     */
    public static function include_forums(): bool {
        return (bool)get_config("local_kopere_sitemap", "includeforums");
    }

    /**
     * Returns true when frontpage modules should be included.
     *
     * @return bool
     * @throws dml_exception
     */
    public static function include_frontpage_modules(): bool {
        return (bool)get_config("local_kopere_sitemap", "includefrontpagemodules");
    }

    /**
     * Returns the configured sitemap URL as absolute URL.
     *
     * @return string
     * @throws dml_exception
     */
    public static function get_public_sitemap_url(): string {
        global $CFG;

        $configured = trim((string)get_config("local_kopere_sitemap", "sitemapurl"));
        if ($configured === "") {
            $configured = "/local/kopere_sitemap/sitemap.php";
        }

        if (preg_match("/^https?:\\/\\//i", $configured)) {
            return $configured;
        }

        return rtrim($CFG->wwwroot, "/") . "/" . ltrim($configured, "/");
    }
}
