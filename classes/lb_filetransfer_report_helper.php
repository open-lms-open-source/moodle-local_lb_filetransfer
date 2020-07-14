<?php
/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

set_include_path(get_include_path(). PATH_SEPARATOR . $CFG->dirroot.'/local/lb_filetransfer/lib/phpseclib');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Net/SFTP.php');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Crypt/RSA.php');

/**
 * Class lb_filetransfer_report_helper represents the helper functions available outgoing report.
 */
class lb_filetransfer_report_helper {

    public $connectionid = 0;
    public $connectiontype = 0;
    public $hostname = null;
    public $portnumber = 0;
    public $username = null;
    public $password = null;
    public $usepublickey = 0;
    public $privatekey = null;
    public $outgoingreportpreference = 0;
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
    public $encryptprivatekey = null;


    /**
     * lb_filetransfer_helper constructor.
     * Builds object if $id provided.
     * @param null $object
     * @param null $objectid
     * @throws dml_exception
     */
    public function __construct($objectid = null) {
        if (!empty($objectid)) {
            $this->load_directory( $objectid);
        }
    }

    /**
     * Constructs the actual connection object given either a $DB object or Moodle form data.
     * @param $connections
     */
    public function construct_connection($connections) {
        if (!empty($connections)) {
            $this->connectionid = $connections->id;
            $this->connectiontype = $connections->connectiontype;
            $this->hostname = $connections->hostname;
            $this->portnumber = $connections->portnumber;
            $this->username = $connections->username;
            $this->password = $connections->password;
            $this->usepublickey = $connections->usepublickey;
            $this->privatekey = $connections->privatekey;
        }
    }

    /**
     * Constructs the actual directory object given either a $DB object or Moodle form data.
     * @param $directory
     */
    public function construct_directory($directory) {
        if (!empty($directory)) {
            $this->outgoingreportpreference = $directory->outgoingreportpreference;
            $this->configurablereportid = $directory->configurablereportid;
            $this->pathtofile = $directory->pathtofile;
            $this->filename = $directory->filename;
            $this->archivefile = $directory->archivefile;
            $this->archiveperiod = $directory->archiveperiod;
            $this->emailpreference = $directory->emailpreference;
            $this->email = $directory->email;
            $this->encryptfile = $directory->encryptfile;
            $this->encryptiontype = $directory->encryptiontype;
            $this->encryptionkey = $directory->encryptionkey;
            $this->encryptprivatekey = $directory->privatekey;
        }
    }

    /**
     * Gets the specified connection and loads it into the object.
     * @param $id
     * @throws dml_exception
     */
    private function load_connections($id) {
        global $DB;
        $connections = $DB->get_record('local_lb_filetr_connections', array('id' => $id));
        $this->construct_connection($connections);
    }

    /**
     * Gets the specified directory and loads it into the object.
     * @param $objectid
     * @throws dml_exception
     */
    private function load_directory($objectid) {
        global $DB;
        $directory = $DB->get_record('local_lb_filetr_reports', array('id' => $objectid));
        $this->construct_directory($directory);
        $this->load_connections((int)$directory->connectionid);
    }

    /**
     * Triggers event.
     * @param $description
     * @return string
     * @throws coding_exception
     */
    public function eventTrigger($description) {
        $event = \local_lb_filetransfer\event\filetransfer_event::create(array(
            'other' => $description
        ));
        $event->trigger();
    }

    /**
     * validates email.
     * @param $email
     * @return bool
     */
    public function validate_emails($email)
    {
        if (!empty($email)) {
            $domain = ltrim(stristr($email, '@'), '@') . '.';
            $user = stristr($email, '@', TRUE);
            if (!empty($user) && !empty($domain) && checkdnsrr($domain)) {
                return true;
            }
        }
        return false;
    }

    /**
     * constructs emails.
     * @return array
     */
    public function construct_email() {
        $emails = explode (",", $this->email);
        foreach ($emails as $key => $email) {
            $emails[$key] = trim($email);
            //email validation
            if (!self::validate_emails($email)) {
                unset($emails[$key]);
            }
        }
        return $emails;
    }

    /**
     * Tests the connection and directory.
     * @return bool
     * @throws coding_exception
     */
    public function test_connection () {
        $host = $this->hostname;
        if (empty($host)) {
            return false;
        }
        $port = $this->portnumber;
        $username = $this->username;
        $sftp = new Net_SFTP($host, $port);
        $filename = $this->filename;

        if ($this->usepublickey == 0) {
            $password = $this->password;
            if (!$sftp->login($username, $password)) {
                $a = new stdClass();
                $a->id = $this->connectionid;
                self::eventTrigger(get_string('filetransfertask_auth_error', 'local_lb_filetransfer', $a));
                return false;
            }
        } else {
            $key = new Crypt_RSA();
            $key->loadKey($this->privatekey);
            if (!$sftp->login($username, $key)) {
                $a = new stdClass();
                $a->id = $this->connectionid;
                self::eventTrigger(get_string('filetransfertask_key_error', 'local_lb_filetransfer', $a));
                return false;
            }
        }
        if (empty($filename)) {
            return false;
        }
        return true;
    }

}
