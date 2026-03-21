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
 * Adds a link to the course checker report in the course navigation menu.
 *
 * @param navigation_node $frontpage Node representing the front page in the navigation tree.
 * @package   local_course_checker
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Extends the course navigation menu to include a link to the course checker report.
 * @param mixed $navigation
 * @param mixed $coursenode
 * @param mixed $context
 * @return void
 */
function local_course_checker_extend_navigation_course($navigation, $coursenode, $context) {
    if (!has_capability('local/course_checker:use', $context)) {
        return;
    }
    $url = new moodle_url('/local/course_checker/index.php', null);
    $node = navigation_node::create(
        get_string('pluginname', 'local_course_checker'),
        $url,
        navigation_node::TYPE_CUSTOM,
        null,
        'local_course_checker',
        new pix_icon('i/report', '')
    );
    $navigation->add_node($node);
}

