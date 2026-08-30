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
 * tag_repository.php
 *
 * @package   local_kopere_sitemap
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_sitemap\repository;

use core_tag_tag;
use ddl_exception;
use dml_exception;

/**
 * Loads Moodle tags used by at least one tag instance.
 */
class tag_repository {
    /**
     * Returns tag URLs.
     *
     * @return array<int, array<string, string>>
     * @throws dml_exception
     */
    public function get_urls(): array {
        global $CFG, $DB;

        if (empty($CFG->usetags)) {
            return [];
        }

        $sql = "SELECT t.id, t.tagcollid, t.rawname, t.timemodified
                  FROM {tag} t
                 WHERE EXISTS (
                           SELECT 1
                             FROM {tag_instance} ti
                            WHERE ti.tagid = t.id
                       )
              ORDER BY t.id ASC";

        $records = $DB->get_records_sql($sql);
        $items = [];

        foreach ($records as $record) {
            $items[] = [
                "loc" => core_tag_tag::make_url($record->tagcollid, $record->rawname)->out(false),
            ];
        }

        return $items;
    }
}
