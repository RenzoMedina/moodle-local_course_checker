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
        $modinfo = get_fast_modinfo((int)$courseid);
        $results = [];
        foreach ($modinfo->cms as $cm) {
            $html = '';
            switch ($cm->modname) {
                case 'page':
                    $record = $DB->get_record('page', ['id' => $cm->instance], 'id, name, content, intro');
                    if ($record) {
                        $html = $record->content . ' ' . $record->intro;
                    }
                    break;
                case 'assign':
                    $record = $DB->get_record('assign', ['id' => $cm->instance], 'id, name, intro');
                    if ($record) {
                        $html = $record->intro;
                    }
                    break;
                case 'forum':
                    $record = $DB->get_record('forum', ['id' => $cm->instance], 'id, name, intro');
                    if ($record) {
                        $html = $record->intro;
                    }
                    break;
                case 'url':
                    $record = $DB->get_record('url', ['id' => $cm->instance], 'id, name, externalurl, intro');
                    if ($record) {
                        $html = $record->externalurl . ' ' . $record->intro;
                    }
                    break;
                case 'quiz':
                    $record = $DB->get_record('quiz', ['id' => $cm->instance], 'id, name, intro');
                    if ($record) {
                        $html = $record->intro;
                    }
                    break;
                case 'resource':
                    $record = $DB->get_record('resource', ['id' => $cm->instance], 'id, name, content, intro');
                    if ($record) {
                        $html = $record->content . ' ' . $record->intro;
                    }
                    break;
            }
            if (empty($html)) {
                continue;
            }
            preg_match_all('/<a[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/is', $html, $matches);
            foreach ($matches[1] as $index => $url) {
                if (strpos($url, 'http') !== 0) {
                    continue; // Skip non-HTTP links.
                }
                $curl = new \curl();
                $curl->setopt([
                    'CURLOPT_TIMEOUT' => 5,
                    'CURLOPT_FOLLOWLOCATION' => true,
                    'CURLOPT_NOBODY' => true,
                ]);
                $curl->head($url);
                $info = $curl->get_info();
                $statuscode = $info['http_code'] ?? 0;
                $linktext = strip_tags($matches[2][$index]);
                $linktext = trim($linktext);
                $linktext = !empty($linktext) ? $linktext : $url;
                $results[] = [
                    'url'          => $url,
                    'linktext'     => $linktext,
                    'activityname' => $cm->name,
                    'modtypelabel' => get_string('modulename', $cm->modname),
                    'statuscode'   => $statuscode,
                    'isbroken'     => $statuscode == 0 || $statuscode >= 400,
                    'isok'         => $statuscode >= 200 && $statuscode < 300,
                ];
            }
        }
        return $results;
    }
}
