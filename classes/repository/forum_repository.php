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
 * forum_repository.php
 *
 * @package   local_sitemap
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_sitemap\repository;

use core\exception\moodle_exception;
use dml_exception;
use moodle_url;

/**
 * Loads visible forums from the frontpage or guest-access courses.
 */
class forum_repository {
    /**
     * Returns forum URLs.
     *
     * @return array<int, array<string, string>>
     * @throws moodle_exception
     * @throws dml_exception
     */
    public function get_urls(): array {
        global $DB;

        $sql = "SELECT DISTINCT cm.id AS cmid, f.timemodified
                  FROM {forum} f
                  JOIN {course_modules} cm
                    ON cm.instance = f.id
                  JOIN {modules} m
                    ON m.id = cm.module
                   AND m.name = :forumname
                  JOIN {course} c
                    ON c.id = cm.course
                   AND c.visible = 1
                 WHERE cm.visible = 1
                   AND (
                        c.id = :siteid
                        OR EXISTS (
                            SELECT 1
                              FROM {enrol} e
                             WHERE e.courseid = c.id
                               AND e.enrol = :guestenrol
                               AND e.status = 0
                        )
                   )
              ORDER BY cm.id ASC";

        $params = [
            "forumname" => "forum",
            "siteid" => SITEID,
            "guestenrol" => "guest",
        ];

        $records = $DB->get_records_sql($sql, $params);
        $items = [];

        foreach ($records as $record) {
            $items[] = [
                "loc" => (new moodle_url("/mod/forum/view.php", ["id" => $record->cmid]))->out(false),
                "lastmod" => !empty($record->timemodified) ? date("c", (int)$record->timemodified) : "",
            ];
        }

        return $items;
    }
}
