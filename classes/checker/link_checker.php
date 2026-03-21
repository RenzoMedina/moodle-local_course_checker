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
 * Course Checker plugin link checker class.
 *
 * @package   local_course_checker
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_course_checker\checker;

defined('MOODLE_INTERNAL') || die();

/**
 * Link checker class for Course Checker plugin.
 * @package   local_course_checker
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class link_checker {

    /**
     * Checks for links in course pages.
     * @param mixed $courseid
     * @return array []
     */
    public function check($courseid) {
        global $DB;
        $pages = $DB->get_records('page', ['course' => $courseid], '', 'id, name, content, intro' );
        $results = [];
        foreach ($pages as $page) {
            $html = $page->content . ' ' . $page->intro;
            preg_match_all('/https?:\/\/[^\s"\'<>]+/i', $html, $matches);
            foreach ($matches[0] as $url) {
                $results[] = [
                    'url'          => $url,
                    'activityname' => $page->name,
                ];
            }
        }
        return $results;
    }
}
