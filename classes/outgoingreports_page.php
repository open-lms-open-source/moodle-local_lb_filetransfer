<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Class outgoingreports_page represents a usedruploads object.
 */
class outgoingreports_page {

    public $name = null;
    public $outgoingreportpreference = 0;
    public $connectionid = 0;
    public $configurablereportid = 0;
    public $pathtofile = null;
    public $filename = null;
    public $archivefile = 0;
    public $archiveperiod = 0;
    public $emailpreference = 0;
    public $email = null;
    public $encryptfile = 0;
    public $encryptiontype = 0;
    public $encryptionkey = null;
    public $privatekey = null;
    public $active = 0;
    public $usermodified = 0;

    /**
     * outgoingreports_page constructor.
     * Builds object if $id provided.
     * @param null $id
     * @throws dml_exception
     */
    public function __construct($id = null) {
        if (!empty($id)) {
            $this->load_outgoingreports_page($id);
        }
    }

    /**
     * Constructs the actual useruploads object given either a $DB object or Moodle form data.
     * @param $outgoingreports
     */
    public function construct_useruploads_page($outgoingreports) {
        if (!empty($outgoingreports)) {
            global $USER;
            $this->id = $outgoingreports->id;
            $this->name = $outgoingreports->name;
            $this->outgoingreportpreference = $outgoingreports->outgoingreportpreference;
            $this->connectionid = $outgoingreports->connectionid;
            $this->configurablereportid = $outgoingreports->configurablereportid;
            $this->pathtofile = $outgoingreports->pathtofile;
            $this->filename = $outgoingreports->filename;
            $this->archivefile = $outgoingreports->archivefile;
            $this->archiveperiod = $outgoingreports->archiveperiod;
            $this->emailpreference = $outgoingreports->emailpreference;
            $this->email = $outgoingreports->email;
            $this->encryptfile = $outgoingreports->encryptfile;
            $this->encryptiontype = $outgoingreports->encryptiontype;
            $this->encryptionkey = $outgoingreports->encryptionkey;
            $this->privatekey = $outgoingreports->privatekey;
            $this->active = $outgoingreports->active;
            $this->usermodified = (int)$USER->id;
        }
    }

    /**
     * Delete the outgoingreport.
     * @return bool
     * @throws dml_exception
     */
    public function delete() {
        global $DB;
        if (!empty($this->id)) {
            return $DB->delete_records('local_lb_filetr_reports', array('id' => $this->id));
        }
        return false;
    }

    /**
     * Gets the active outgoingreport and loads it into the object.
     * @throws dml_exception
     */
    public function load_active_connections() {
        global $DB;
        $useruploads = $DB->get_record('local_lb_filetr_reports', array('active' => 1));
        $this->construct_useruploads_page($useruploads);
    }

    /**
     * Gets the specified outgoingreport and loads it into the object.
     * @param $id
     * @throws dml_exception
     */
    private function load_outgoingreports_page($id) {
        global $DB;
        $useruploads_page = $DB->get_record('local_lb_filetr_reports', array('id' => $id));
        $this->construct_useruploads_page($useruploads_page);
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
     * Gets all the active configurable reports.
     * @return array
     * @throws dml_exception
     */
    public function get_configurable_reports() {
        global $DB;
        $reports = array();
        $configurable_reports = $DB->get_records_sql('SELECT cr.id, cr.name
                                                          FROM {block_configurable_reports} cr
                                                          WHERE cr.visible = :visible',
                                                          array('visible' => 1));
        if (empty($configurable_reports)) {
            $reports[0] = 'No report found';
            return $reports;
        }
        foreach ($configurable_reports as $configurable_report) {
            $reports[$configurable_report->id] = $configurable_report->name;
        }
        return $reports;
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
            $savesuccess = $DB->update_record('local_lb_filetr_reports', $this);
        } else {
            $this->timecreated = time();
            $this->timemodified = time();
            $this->id = $DB->insert_record('local_lb_filetr_reports', $this);
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
