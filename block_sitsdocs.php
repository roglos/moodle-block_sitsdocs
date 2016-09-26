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
 * SITSDocs - Block to display documents stored in SITS.
 *
 * @package    block_sitsdocs
 * @copyright  ROelmann
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_sitsdocs extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_sitsdocs');
    }

    function applicable_formats() {
        return array('all' => true, 'mod' => false, 'tag' => false, 'my' => false);
    }

    function get_content() {
        global $CFG, $DB, $PAGE, $OUTPUT;

        $table = 'usr_sitsdocs';
        // Get document types for this module page.
        $sql = 'SELECT DISTINCT `doctype` FROM '.$table.' WHERE `moduleid` = "'.$this->page->course->idnumber.'"'; //Sort order to put them alphabetically?
        $params = null;
        $types = $DB->get_records_sql($sql, $params);
        // Get all documents for this module page.
        $sql = 'SELECT * FROM '.$table.' WHERE `moduleid` = "'.$this->page->course->idnumber.'"'; //Would this be faster if sortorder and grouping applied?
        $params = null;
        $docs = $DB->get_records_sql($sql, $params);

        $this->content = new stdClass();
        $this->content->text = '<div class = "sitsdocs_content">';
        $this->content->text .= '<ul>';
        foreach ($types as $type) {
            $doctype = $type->doctype;
            $this->content->text .= '<li><h4>'.$doctype.'</h4>';  // Icons for doc types? - wouldn't matter too much if one was missing - make the type a class too so it can be targetted, would need doc type to be one word -> probably not as types will not be one word, so possibly no way to target?
            $this->content->text .= '<ul>';
            foreach ($docs as $doc) {
                if ($doc->doctype == $doctype) {
                    // Icons for doc format? - wouldn't matter too much if one was missing - make the format a class too so it can be targetted - extract the format as the file extension?
                    $this->content->text .= '<li><a href = "'.$doc->doclink.'" title = "'.$doc->docname.'">'.$doc->docname.'</a></li>';
                }
            }
            $this->content->text .= '</ul>';
            $this->content->text .= '</li>';
        }
        $this->content->text .= '</ul>';
        $this->content->text .= '</div>';

        $this->content->footer = 'Footer - some link?';

        return $this->content;
    }

}


