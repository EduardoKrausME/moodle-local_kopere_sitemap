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
 * Tests for the sitemap XML builder.
 *
 * @package   local_kopere_sitemap
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_sitemap;

use advanced_testcase;
use DOMDocument;
use local_kopere_sitemap\service\sitemap_builder;

/**
 * Validates the serialized sitemap document.
 */
final class sitemap_builder_test extends advanced_testcase {
    /**
     * A sitemap contains one document with properly escaped URL values.
     *
     * @covers \local_kopere_sitemap\service\sitemap_builder::build_xml
     */
    public function test_build_xml_produces_one_valid_document(): void {
        $builder = new class extends sitemap_builder {
            /**
             * Supplies sample sitemap entries for XML serialization.
             *
             * @return array<int, array<string, string>>
             */
            public function build_items(): array {
                return [
                    [
                        "loc" => "https://example.org/course/view.php?id=3&section=1",
                        "lastmod" => "2026-10-18T21:05:29-03:00",
                    ],
                    [
                        "loc" => "https://example.org/tag/index.php?tag=Forma%C3%A7%C3%A3o",
                    ],
                ];
            }
        };

        $xml = $builder->build_xml();
        $document = new DOMDocument();

        $this->assertTrue($document->loadXML($xml));
        $this->assertSame(0, strpos($xml, "<?xml"));
        $this->assertSame(1, substr_count($xml, "<?xml"));
        $this->assertSame("urlset", $document->documentElement->localName);
        $this->assertSame("http://www.sitemaps.org/schemas/sitemap/0.9",
            $document->documentElement->namespaceURI);
        $this->assertSame(2, $document->getElementsByTagName("url")->length);
        $this->assertSame("https://example.org/course/view.php?id=3&section=1",
            $document->getElementsByTagName("loc")->item(0)->textContent);
        $this->assertSame(1, $document->getElementsByTagName("lastmod")->length);
    }

    /**
     * An empty sitemap still produces a complete document.
     *
     * @covers \local_kopere_sitemap\service\sitemap_builder::build_xml
     */
    public function test_build_xml_with_no_items(): void {
        $builder = new class extends sitemap_builder {
            /**
             * Supplies an empty sitemap to test serialization.
             *
             * @return array<int, array<string, string>>
             */
            public function build_items(): array {
                return [];
            }
        };

        $xml = $builder->build_xml();
        $document = new DOMDocument();
        $this->assertTrue($document->loadXML($xml));
        $this->assertSame(1, substr_count($xml, "<?xml"));
        $this->assertSame(0, $document->getElementsByTagName("url")->length);
    }
}
