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

global $CFG;

require_once($CFG->dirroot . '/local/reminders/reminder.class.php');

/**
 * Class to specify the reminder message object for group events.
 *
 * @package    local
 * @subpackage reminders
 * @copyright  2012 Isuru Madushanka Weerarathna
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class group_reminder extends reminder {
    
    private $group;
    
    public function __construct($event, $group, $aheaddays = 1) {
        parent::__construct($event, $aheaddays);
        $this->group = $group;
    }
    
    protected function get_content_rows() {
        $rows = parent::get_content_rows();
        
        $row = new reminder_content_row();
        $row->add_column(new reminder_content_column(get_string('contenttypegroup', 'local_reminders')));
        $row->add_column(new reminder_content_column($this->group->name));
        $rows[] = $row;
        
        return $rows;
    }
    
    public function get_message_plaintext() {
        $text  = $this->get_message_title().' ['.$this->aheaddays.' day(s) to go]\n';
        $text .= get_string('contentwhen', 'local_reminders').': '.$this->format_event_time_duration().'\n';
        $text .= get_string('contenttypegroup', 'local_reminders').': '.$this->group->name.'\n';
        $text .= get_string('contentdescription', 'local_reminders').': '.$this->event->description.'\n';
        
        return $text;
    }

    protected function get_message_provider() {
        return 'reminders_group';
    }

    public function get_message_title() {
        $course = $DB->get_record('course', array('id' => $group->courseid));
        return $course->shortname.' - '.$this->group->name.' - '.$this->event->name;
    }
    
    public function get_custom_headers() {
        $headers = parent::get_custom_headers();
        
        $headers[] = 'X-Group-Id: '.$this->group->id;
        return $headers;
    }
}