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
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 eCreators PTY LTD
 * @author      2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Class fileimport_page represents a fileimport object.
 */
class fileimport_page {

    public $name = null;
    public $connectionid = 0;
    public $pathtofile = null;
    public $filename = null;
    public $getlatestfile = 0;
    public $savetolocation = null;
    public $active = 0;
    public $usermodified = 0;

    /**
     * fileimport_page constructor.
     * Builds object if $id provided.
     * @param null $id
     * @throws dml_exception
     */
    public function __construct($id = null) {
        if (!empty($id)) {
            $this->load_fileimport_page($id);
        }
    }

    /**
     * Constructs the actual fileimport object given either a $DB object or Moodle form data.
     * @param $fileimport
     */
    public function construct_fileimport_page($fileimport) {
        if (!empty($fileimport)) {
            global $USER;
            $this->id = $fileimport->id;
            $this->name = $fileimport->name;
            $this->connectionid = $fileimport->connectionid;
            $this->pathtofile = $fileimport->pathtofile;
            $this->filename = $fileimport->filename;
            $this->getlatestfile = $fileimport->getlatestfile;
            $this->savetolocation = $fileimport->savetolocation;
            $this->active = $fileimport->active;
            $this->usermodified = (int)$USER->id;
        }
    }

    /**
     * Delete the fileimport.
     * @return bool
     * @throws dml_exception
     */
    public function delete() {
        global $DB;
        if (!empty($this->id)) {
            return $DB->delete_records('local_lb_filetr_fileimport', array('id' => $this->id));
        }
        return false;
    }

    /**
     * Gets the active fileimport and loads it into the object.
     * @throws dml_exception
     */
    public function load_active_connections() {
        global $DB;
        $fileimport = $DB->get_record('local_lb_filetr_fileimport', array('active' => 1));
        $this->construct_fileimport_page($fileimport);
    }

    /**
     * Gets the specified fileimport and loads it into the object.
     * @param $id
     * @throws dml_exception
     */
    private function load_fileimport_page($id) {
        global $DB;
        $fileimport = $DB->get_record('local_lb_filetr_fileimport', array('id' => $id));
        $this->construct_fileimport_page($fileimport);
    }

    /**
     * Gets all the active connections.
     * @return array
     * @throws dml_exception
     */
    public function get_connections () {
        global $DB;
        $connectiontype = array();
        $connections = $DB->get_records_sql('SELECT lfc.id, lfc.name
                                                  FROM {local_lb_filetr_connections} lfc
                                                  WHERE lfc.active = :active',
            array('active' => 1));
        foreach ($connections as $connection) {
            $connectiontype[$connection->id] = $connection->name;
        }
        return $connectiontype;
    }

    /**
     * Upsert function.
     * @return bool
     * @throws dml_exception
     */
    public function save() {
        global $DB;

        $savesuccess = false;
        if (!empty($this->id)) {
            $this->timemodified = time();
            $savesuccess = $DB->update_record('local_lb_filetr_fileimport', $this);
        } else {
            $this->timecreated = time();
            $this->timemodified = time();
            $this->id = $DB->insert_record('local_lb_filetr_fileimport', $this);
            if (!empty($this->id)) {
                $savesuccess = true;
            }
        }
        if ($savesuccess) {
            return true;
        }
        return false;
    }
}
