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
 * hook_callbacks.php
 *
 * @package   local_sitemap
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_sitemap;

use core\hook\output\before_standard_head_html_generation;
use dml_exception;
use html_writer;

/**
 * Hook callbacks for the local_sitemap plugin.
 */
class hook_callbacks {
    /**
     * Adds the sitemap <link> tag into the page head.
     *
     * @param before_standard_head_html_generation $hook Hook instance.
     * @return void
     * @throws dml_exception
     */
    public static function before_standard_head_html_generation(
        before_standard_head_html_generation $hook
    ): void {
        if (!config::is_enabled()) {
            return;
        }

        $sitemapurl = config::get_public_sitemap_url();
        if ($sitemapurl === "") {
            return;
        }

        $attributes = [
            "rel" => "sitemap",
            "type" => "application/xml",
            "title" => "Sitemap",
            "href" => $sitemapurl,
        ];

        $hook->add_html(html_writer::empty_tag("link", $attributes) . "\n");
    }
}
