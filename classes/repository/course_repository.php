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
 * course_repository.php
 *
 * @package   local_kopere_sitemap
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_sitemap\repository;

use core\exception\moodle_exception;
use dml_exception;
use moodle_url;

/**
 * Loads public courses with active self enrolment.
 */
class course_repository {
    /**
     * Returns course URLs.
     *
     * @return array<int, array<string, string>>
     * @throws moodle_exception
     * @throws dml_exception
     */
    public function get_urls(): array {
        global $DB;

        $sql = "SELECT DISTINCT c.id, c.timemodified
                  FROM {course} c
                  JOIN {course_categories} cc
                    ON cc.id = c.category
                   AND cc.visible = 1
                  JOIN {enrol} e
                    ON e.courseid = c.id
                   AND e.enrol = :enrolself
                   AND e.status = 0
                 WHERE c.id <> :siteid
                   AND c.visible = 1
              ORDER BY c.id ASC";

        $params = [
            "enrolself" => "self",
            "siteid" => SITEID,
        ];

        $records = $DB->get_records_sql($sql, $params);
        $items = [];

        foreach ($records as $record) {
            $items[] = [
                "loc" => (new moodle_url("/course/view.php", ["id" => $record->id]))->out(false),
                "lastmod" => !empty($record->timemodified) ? date("c", (int)$record->timemodified) : "",
            ];
        }

        return $items;
    }
}
