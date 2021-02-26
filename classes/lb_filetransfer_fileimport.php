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

set_include_path(get_include_path(). PATH_SEPARATOR . $CFG->dirroot.'/local/lb_filetransfer/lib/phpseclib');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Net/SFTP.php');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Crypt/RSA.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/filelib.php');
require($CFG->dirroot.'/local/lb_filetransfer/classes/lb_filetransfer_fileimport_helper.php');

/**
 * Class lb_filetransfer_fileimport represents all the available fileimport object.
 */
class lb_filetransfer_fileimport {

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
    public function create_local_dir($savetolocation) {
        global $CFG;
        $tempdir = 'lb_filetransfer'. $savetolocation;
        if (!file_exists ($CFG->dataroot . '/temp/lb_filetransfer' . $savetolocation)) {
            //Created temp folder for the file.
            make_temp_directory($tempdir);
        }
        return $tempdir;
    }

    public function get_fileimport_file() {
        global $CFG, $DB;
        $get_fileimport_instances = $DB->get_records_sql('SELECT *
                                                               FROM {local_lb_filetr_fileimport}
                                                               WHERE active = :active',
                                                                array('active' => 1));
        foreach ($get_fileimport_instances as $fileimport) {
            $connection = new lb_filetransfer_fileimport_helper((int)$fileimport->id);
            if($connection->test_connection()) {
                $host = $connection->hostname;
                $port = (int)$connection->portnumber;
                $username = $connection->username;
                $remotedir = $connection->pathtofile;
                $filename = $connection->filename;
                $savetolocation = $connection->savetolocation;
                $tempdir = self::create_local_dir($savetolocation);
                $localdir = $CFG->dataroot . '/temp/'.$tempdir;
                $sftp = new Net_SFTP($host, $port);
                $fileToUpload = $localdir.$filename;

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
                //Checking if the file exist or not.
                if (file_exists($fileToUpload)) {
                    fulldelete($fileToUpload);
                }

                //Transfer the file from remote directory.
                if ((int)$connection->getlatestfile == 0) {
                    $remotefile = $remotedir.$filename;
                } else {
                    $remote_files = $sftp->rawlist();
                    //remove . and ..
                    foreach ($remote_files as $value => $values) {
                        if ($value === '.' || $value === "..") {
                            unset($remote_files[$value]);
                        }
                    }
                    $compare_size = sizeof($remote_files);
                    foreach ($remote_files as $key => $remote_file) {
                        $time = $remote_file["mtime"];
                        $counter = 0;
                        foreach ($remote_files as $keys => $file) {
                            if ($time >= $file["mtime"]) {
                                $counter++;
                            }
                        }
                        if ($counter == $compare_size) {
                            $selected_file = $remote_file;
                        }
                    }
                    $remotefile = $remotedir.$selected_file["filename"];
                }

                $sftp->get($remotefile, $fileToUpload);
                //Changed permission of the file to 0777.
                chmod($fileToUpload,0777);

                $a = new stdClass();
                $a->id = $fileimport->id;
                self::eventTrigger(get_string('filetransfertask_fileimport', 'local_lb_filetransfer', $a));
                mtrace("File import process completed.");
            }
        }
    }

}
