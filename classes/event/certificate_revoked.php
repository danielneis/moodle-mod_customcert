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
 * The mod_customcert certificate issued event.
 *
 * @package    mod_customcert
 * @copyright  2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_customcert\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The mod_customcert certificate revoked event class.
 *
 * @package    mod_customcert
 * @copyright  2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class certificate_revoked extends \core\event\base {

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'customcert_issues';
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The user with '$this->userid' has revoked issue of certificate with id '$this->objectid'".
                "from user with id '$this->relateduserid'.";
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventtemplatecreated', 'mod_customcert');
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/mod/customcert/edit.php', array('tid' => $this->objectid));
    }

    /**
     * Create instance of event.
     *
     * @param int $certificateid
     * @param \stdClass $issue
     * @return certificate_revoked
     */
    public static function create_from_issue($certificateid, \stdClass $issue) {
        $data = array(
            'context' => \mod_customcert\certificate::get_context($certificateid),
            'objectid' => $issue->id,
            'relateduserid' => $issue->userid
        );
        $event = self::create($data);
        $event->add_record_snapshot('customcert_issues', $issue);
        return $event;
    }
}
