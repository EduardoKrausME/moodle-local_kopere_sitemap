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
 * sitemap_builder.php
 *
 * @package   local_kopere_sitemap
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_sitemap\service;

use cache;
use core\exception\moodle_exception;
use ddl_exception;
use dml_exception;
use local_kopere_sitemap\config;
use local_kopere_sitemap\repository\blog_repository;
use local_kopere_sitemap\repository\category_repository;
use local_kopere_sitemap\repository\course_repository;
use local_kopere_sitemap\repository\forum_repository;
use local_kopere_sitemap\repository\frontpage_repository;
use local_kopere_sitemap\repository\tag_repository;
use XMLWriter;

/**
 * Builds and outputs the XML sitemap.
 */
class sitemap_builder {
    /**
     * Returns all sitemap URL items.
     *
     * @return array<int, array<string, string>>
     * @throws moodle_exception
     * @throws ddl_exception
     * @throws dml_exception
     */
    public function build_items(): array {
        global $CFG;

        $items = [];

        if (!config::is_enabled()) {
            return $items;
        }

        if (config::include_frontpage()) {
            $items[] = [
                "loc" => $CFG->wwwroot . "/",
                "lastmod" => date("c"),
            ];
        }

        if (config::include_categories()) {
            $items = array_merge($items, (new category_repository())->get_urls());
        }

        if (config::include_courses()) {
            $items = array_merge($items, (new course_repository())->get_urls());
        }

        if (config::include_blog()) {
            $items = array_merge($items, (new blog_repository())->get_urls());
        }

        if (config::include_forums()) {
            $items = array_merge($items, (new forum_repository())->get_urls());
        }

        if (config::include_frontpage_modules()) {
            $items = array_merge($items, (new frontpage_repository())->get_urls());
        }

        if (config::include_tags()) {
            $items = array_merge($items, (new tag_repository())->get_urls());
        }

        return $this->remove_duplicates($items);
    }

    /**
     * Sends a valid XML sitemap response.
     *
     * @return void
     * @throws ddl_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function send_xml(): void {
        $xml = $this->get_xml();

        // Moodle bootstrap or other plugins can leave warnings or markup in output buffers.
        // A sitemap response must contain exactly one XML document and no surrounding content.
        while (ob_get_level() > 0) {
            $status = ob_get_status();
            if (($status["flags"] & PHP_OUTPUT_HANDLER_REMOVABLE) === 0) {
                if (($status["flags"] & PHP_OUTPUT_HANDLER_CLEANABLE) !== 0) {
                    ob_clean();
                }
                break;
            }
            ob_end_clean();
        }

        header("Content-Type: application/xml; charset=UTF-8");
        header("X-Robots-Tag: noindex");
        echo $xml;
    }

    /**
     * Returns the cached sitemap, regenerating a corrupted XML declaration when necessary.
     *
     * @return string
     * @throws ddl_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function get_xml(): string {
        $cache = cache::make("local_kopere_sitemap", "sitemapxml");
        $cachekey = "sitemapxml";
        $xml = $cache->get($cachekey);

        // A concatenated or prefixed XML document must never be served from cache.
        if (!is_string($xml) || strpos($xml, "<?xml") !== 0 || substr_count($xml, "<?xml") !== 1) {
            $xml = $this->build_xml();
            $cache->set($cachekey, $xml);
        }

        return $xml;
    }

    /**
     * Generates a single, well-formed XML sitemap document.
     *
     * @return string
     * @throws ddl_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function build_xml(): string {
        $items = $this->build_items();

        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument("1.0", "UTF-8");
        $writer->startElement("urlset");
        $writer->writeAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");

        foreach ($items as $item) {
            $writer->startElement("url");
            $writer->writeElement("loc", $item["loc"]);

            if (!empty($item["lastmod"])) {
                $writer->writeElement("lastmod", $item["lastmod"]);
            }

            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();
        return $writer->outputMemory();
    }

    /**
     * Removes duplicated URLs.
     *
     * @param array<int, array<string, string>> $items Sitemap items.
     * @return array<int, array<string, string>>
     */
    protected function remove_duplicates(array $items): array {
        $indexed = [];

        foreach ($items as $item) {
            if (empty($item["loc"])) {
                continue;
            }

            $indexed[$item["loc"]] = $item;
        }

        return array_values($indexed);
    }
}
