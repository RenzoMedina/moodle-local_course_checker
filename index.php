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
 * Course Checker plugin main file.
 *
 * @package   local_course_checker
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
global $DB;
require_login();
require_capability('moodle/site:config', context_system::instance());

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/course_checker/index.php'));
$PAGE->set_title(get_string('pluginname', 'local_course_checker'));
$PAGE->set_heading(get_string('pluginname', 'local_course_checker'));

use local_course_checker\form\search;
$courseshortname = optional_param('search', '', PARAM_TEXT);
$coursefullname = optional_param('fullname', '', PARAM_TEXT);
$mform = new search();
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/admin/search.php#linkreports'));
} else if ($data = $mform->get_data()) {
    $courseshortname = $data->search;
    $coursefullname = $data->fullname;
}
$sql = "SELECT id, shortname, fullname FROM {course} WHERE shortname LIKE :shortname AND fullname LIKE :fullname";
if ($courseshortname || $coursefullname) {
    $params = [
        'shortname' => '%' . $courseshortname . '%',
        'fullname' => '%' . $coursefullname . '%',
    ];
    $courses = $DB->get_records_sql($sql, $params);
    var_dump($courses);
}

$template = [
    'search_form' => $mform->render(),
];
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_course_checker/main', $template);
echo $OUTPUT->footer();
