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
 * Course Checker search form.
 *
 * @package   local_course_checker
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_course_checker\form;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/**
 * Course Checker search form.
 * @package   local_course_checker
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class search extends \moodleform {
    /**
     * Form definition.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'search', get_string('courseshortname', 'local_course_checker'));
        $mform->setType('search', PARAM_TEXT);
        $mform->addHelpButton('search', 'courseshortname', 'local_course_checker');
        $mform->addElement('text', 'fullname', get_string('coursefullname', 'local_course_checker'));
        $mform->setType('fullname', PARAM_TEXT);
        $mform->addHelpButton('fullname', 'coursefullname', 'local_course_checker');
        $mform->addElement('submit', 'submitbutton', get_string('search', 'local_course_checker'));
    }
}
