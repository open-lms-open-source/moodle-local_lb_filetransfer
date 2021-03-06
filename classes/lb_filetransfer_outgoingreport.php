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
 * @copyright   2021 eCreators PTY LTD
 * @author      2021 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_lb_filetransfer;
defined('MOODLE_INTERNAL') || die;
global $CFG;
set_include_path(get_include_path(). PATH_SEPARATOR . $CFG->dirroot.'/local/lb_filetransfer/lib/phpseclib');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Net/SFTP.php');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Crypt/RSA.php');

use coding_exception;
use core_user;
use Crypt_RSA;
use csv_export_writer;
use dml_exception;
use Net_SFTP;
use stdClass;

/**
 * Class lb_filetransfer_outgoingreport represents all the available outgoing report object.
 */
class lb_filetransfer_outgoingreport {

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
     * Creates local directory.
     * @return string
     */
    public function create_local_dir() {
        global $CFG;
        if (file_exists ($CFG->dataroot . '/temp/lb_filetransfer/outgoingreport')) {
            //Temporary folder already exists, using existing one.
            $tempdir = '/temp/lb_filetransfer/outgoingreport/';
            return $tempdir;
        }
        make_temp_directory('lb_filetransfer/outgoingreport/');
        //Created temp folder for the file.
        $tempdir = '/temp/lb_filetransfer/outgoingreport/';
        return $tempdir;
    }

    /**
     * exports the data to csv.
     * @param $report
     * @param $destination_filename
     * @param $tempdir
     * @return string
     */
    public function export_report($report, $destination_filename, $tempdir) {
        global $DB, $CFG;
        require_once($CFG->libdir . '/csvlib.class.php');

        $table = $report->table;
        $matrix = array();
        $filename = 'report';

        if (!empty($table->head)) {
            $countcols = count($table->head);
            $keys = array_keys($table->head);
            $lastkey = end($keys);
            foreach ($table->head as $key => $heading) {
                $matrix[0][$key] = str_replace("\n", ' ', htmlspecialchars_decode(strip_tags(nl2br($heading))));
            }
        }

        if (!empty($table->data)) {
            foreach ($table->data as $rkey => $row) {
                foreach ($row as $key => $item) {
                    $matrix[$rkey + 1][$key] = str_replace("\n", ' ', htmlspecialchars_decode(strip_tags(nl2br($item))));
                }
            }
        }

        $csvexport = new csv_export_writer();
        $csvexport->set_filename($filename);

        foreach ($matrix as $ri => $col) {
            $csvexport->add_data($col);
        }
        $source_file = $csvexport->path;
        $destination = $CFG->dataroot . $tempdir . $destination_filename;
        if(copy($source_file, $destination)) {
            return $destination;
        }
        else {
            return false;
        }
    }

    /**
     * Archives directory.
     * @param $localdir
     * @param $filename
     * @return string
     */
    public function archive_directory($localdir, $filename) {
        global $CFG;
        if (file_exists ($CFG->dataroot . '/temp/lb_filetransfer/outgoingreport/archive')) {
            mtrace("Backup folder already exists, using existing one.");
            $backupdir = 'archive/';
        }
        else {
            make_temp_directory('lb_filetransfer/outgoingreport/archive/');
            $backupdir = 'archive/';
            mtrace("Created backup folder for archiving.");
        }
        $today = time();
        $today = date("Y-m-d-h-m-s",$today);
        $newExtension = '_processed_'.$today;
        $newName = $localdir.$filename.$newExtension;
        rename( $localdir.$filename, $newName);
        mtrace("Renamed the file to archive.");
        copy($newName, $localdir.$backupdir.$filename.$newExtension);
        mtrace("Copied the file to backup directory.");
        unlink($newName);
        mtrace("Removed the file from the temp directory");
        $archiveDir = $localdir.$backupdir;
        mtrace("Archiving completed");
        return $archiveDir;
    }

    /**
     * Gets the files and archives them.
     * @param $archive_enabled
     * @param $archive_period
     * @param $localdir
     * @param $filename
     * @param $tempdir
     * @return void
     */
    public function archive_file ($archive_enabled, $archive_period, $localdir, $filename, $tempdir) {
        global $CFG;
        if ($archive_enabled) {
            mtrace("Archiving uploaded file.");
            $path = self::archive_directory($localdir, $filename);
            $days = $archive_period;
            // Open the directory
            if ($handle = opendir($path))
            {
                // Loop through the directory
                while (false !== ($file = readdir($handle)))
                {
                    // Check the file we're doing is actually a file
                    if (is_file($path.$file))
                    {
                        // Check if the file is older than X days old
                        if (filemtime($path.$file) < ( time() - ( $days * 24 * 60 * 60 ) ) )
                        {
                            // Do the deletion
                            unlink($path.$file);
                        }
                    }
                }
            }
            mtrace("Deleted old files.");
        } else {
            fulldelete($CFG->dataroot.$tempdir.$filename);
            mtrace("Deleted temporary file.");
        }
    }

    /**
     * test encrypted file data.
     * @return string
     */
    public function file_decrypt ($encryptedfile, $privatekey) {
        $rsa = new Crypt_RSA();
        $ciphertext = base64_decode($encryptedfile);
        $rsa->loadKey($privatekey);
        var_dump('decrypted file');
        $output = $rsa->decrypt($ciphertext);
        var_dump($output);
    }

    /**
     * encrypt file data.
     * @return string
     */
    public function file_encrypt ($encryptiontype, $rawfile, $encryptionkey, $privatekey) {
        if ($encryptiontype == 1) {
            $rsa = new Crypt_RSA();
            $rsa->loadKey($encryptionkey);
            $output = $rsa->encrypt($rawfile);
            return base64_encode($output);
        }
    }


    /**
     * send outgoing report to sftp.
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function send_outgoingreport() {
        global $CFG, $DB, $USER;
        $get_outgoingreport_instances = $DB->get_records_sql('SELECT *
                                                              FROM {local_lb_filetr_reports}
                                                              WHERE active = :active',
                                                              array('active' => 1));
        //added here otherwise creates issues if the plugin is not present
        require_once($CFG->dirroot."/blocks/configurable_reports/locallib.php");
        foreach($get_outgoingreport_instances as $key => $outgoingreport) {
            $log_data = array();
            $connection = new lb_filetransfer_report_helper((int)$outgoingreport->id);
            //directories for archive
            $tempdir = self::create_local_dir();
            $localdir = $CFG->dataroot .$tempdir;
            //configurable report
            $id = (int)$connection->configurablereportid;
            if (!$report = $DB->get_record('block_configurable_reports', ['id' => $id])) {
                $a = new stdClass();
                $a->id = $id;
                self::eventTrigger(get_string('filetransfertask_configreport_error', 'local_lb_filetransfer', $a));
                continue;
            }
            $courseid = $report->courseid;
            if (!$course = $DB->get_record('course', ['id' => $courseid])) {
                continue;
            }
            require_once($CFG->dirroot.'/blocks/configurable_reports/report.class.php');
            require_once($CFG->dirroot.'/blocks/configurable_reports/reports/'.$report->type.'/report.class.php');

            $reportclassname = 'report_'.$report->type;
            $reportclass = new $reportclassname($report);
            $reportclass->create_report();
            $export_file_path = self::export_report($reportclass->finalreport, $connection->filename, $tempdir);
            $export_file = file_get_contents($export_file_path);
            //if sftp connection is available
            if ((int)$connection->outgoingreportpreference == 0 || (int)$connection->outgoingreportpreference == 2) {
                if ($connection->test_connection()) {
                    $host = $connection->hostname;
                    $port = (int)$connection->portnumber;
                    $username = $connection->username;
                    $remotedir = $connection->pathtofile;
                    //encryption
                    if ((int)$connection->encryptfile) {
                        $encrypted_data = self::file_encrypt((int)$connection->encryptiontype,  $export_file, $connection->encryptionkey, $connection->encryptprivatekey);
                        //put encrypted string in the file
                        $raw_file = $export_file_path;
                        $today = time();  // Make the date stamp
                        $today = date("Y-m-d-h-m-s",$today);
                        $encrypted_filename = "report_". $today. "_" . $outgoingreport->id . ".txt";
                        $export_file_path = $localdir . $encrypted_filename;
                        file_put_contents($export_file_path, $encrypted_data);
                        $export_file = file_get_contents($export_file_path);
                        $connection->filename = $encrypted_filename;
                    }
                    $sftp = new Net_SFTP($host, $port);
                    if ((int)$connection->usepublickey == 0) {
                        $password = $connection->password;
                        $sftp->login($username, $password);
                        //Password login successful.
                    } else {
                        $key = new Crypt_RSA();
                        $key->loadKey($connection->privatekey);
                        $sftp->login($username, $key);
                        //RSA login successful.
                    }
                    //Going to remote directory.
                    $sftp->chdir($remotedir);
                    $sftp->put($remotedir.$connection->filename, $export_file);
                    mtrace('File send to sftp');
                    //log data
                    $a = new stdClass();
                    $a->id = $outgoingreport->id;
                    $log_data[] = get_string('filetransfertask_reportsend', 'local_lb_filetransfer', $a);
                    self::eventTrigger(get_string('filetransfertask_reportsend', 'local_lb_filetransfer', $a));
                }
            }

            if ((int)$connection->outgoingreportpreference == 1 || (int)$connection->outgoingreportpreference == 2) {
                //email log and file
                $emails = $connection->construct_email();
                if (!empty($emails)) {
                    //email file
                    foreach ($emails as $keys => $email) {
                        //create user object for the email
                        $user = new stdClass;
                        $user->id = -1;
                        $user->name = $email;
                        $user->email = $email;
                        $user->suspended = 0;
                        $user->deleted = 0;
                        if ((int)$connection->emailpreference == 0 || (int)$connection->emailpreference == 2) {
                            email_to_user($user, core_user::get_noreply_user(), get_string('outgoingreport_email_subject', 'local_lb_filetransfer'),
                                get_string('outgoingreport_email_body', 'local_lb_filetransfer'), '', $export_file_path, $connection->filename,
                                true, '', '', 79);
                            $a = new stdClass();
                            $a->id = $outgoingreport->id;
                            $a->email = $email;
                            $log_data[] = get_string('filetransfertask_reporemailed', 'local_lb_filetransfer', $a);
                            self::eventTrigger(get_string('filetransfertask_reporemailed', 'local_lb_filetransfer', $a));
                        }
                        mtrace("Notification send");
                    }
                }
            }

            //archive and clean file
            self::archive_file($connection->archivefile, $connection->archiveperiod, $localdir, $connection->filename, $tempdir);
            $a->id = $outgoingreport->id;
            $log_data[] = get_string('filetransfertask_filearchive', 'local_lb_filetransfer', $a);
            self::eventTrigger(get_string('filetransfertask_filearchive', 'local_lb_filetransfer', $a));
            fulldelete($raw_file);

            //send log
            if ((int)$connection->emailpreference == 1 || (int)$connection->emailpreference == 2) {
                $send_log_data = implode("\n",$log_data);
                if (!empty($emails)) {
                    foreach ($emails as $keys => $email) {
                        //create user object for the email
                        $user = new stdClass;
                        $user->id = -1;
                        $user->name = $email;
                        $user->email = $email;
                        $user->suspended = 0;
                        $user->deleted = 0;
                        email_to_user($user, core_user::get_noreply_user(), get_string('outgoingreport_logemail_subject', 'local_lb_filetransfer'),
                            $send_log_data, '', '', $connection->filename,
                            true, '', '', 79);
                        mtrace("Log send");
                    }
                }
            }
        }
    }

}
