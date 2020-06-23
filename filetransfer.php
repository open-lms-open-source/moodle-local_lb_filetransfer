<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @category    admin
 * @copyright   2019 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;
set_include_path(get_include_path(). PATH_SEPARATOR . $CFG->dirroot.'/local/lb_filetransfer/lib/phpseclib');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Net/SFTP.php');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Crypt/RSA.php');

require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once($CFG->dirroot.'/group/lib.php');
require_once($CFG->dirroot.'/cohort/lib.php');
require_once($CFG->dirroot.'/admin/tool/uploaduser/locallib.php');
require_once($CFG->dirroot.'/admin/tool/uploaduser/user_form.php');

function connectionStatus() {
    $config = get_config('local_lb_filetransfer');
    $host = $config->host;
    $port = $config->port;
    $filename = $config->masterfile;
    $username = $config->username;
    $sftp = new Net_SFTP($host,$port);

    if (empty($host)) {
        return 3;
    }
    if ($config->enablekey == 0) {
        $password = $config->password;
        if (!$sftp->login($username, $password)) {
            return 1;
        }
    }
    else {
        $key = new Crypt_RSA();
        $key->loadKey($config->rsatoken);
        if (!$sftp->login($username, $key)) {
            return 2;
        }
    }
    return 4;
}


function testConnection() {
    $config = get_config('local_lb_filetransfer');
    $host = $config->host;
    if (empty($host)) {
        eventDescription (get_string('connectionerrornohost', 'local_lb_filetransfer'));
        return false;
    }
    $port = $config->port;
    $username = $config->username;
    $sftp = new Net_SFTP($host,$port);
    if (empty($config->remotedir)) {
        $remotedir = '/';
    }
    else {
        //$remotedir = '/' . $config->remotedir . '/';
        $remotedir = $config->remotedir;
    }
    $filename = $config->masterfile;
    if ($config->enablekey == 0) {
        $password = $config->password;
        if (!$sftp->login($username, $password)) {
            eventDescription (get_string('connectionerrorpassword', 'local_lb_filetransfer'));
            return false;
        }
    }
    else {
        $key = new Crypt_RSA();
        $key->loadKey($config->rsatoken);
        if (!$sftp->login($username, $key)) {
            eventDescription (get_string('connectionerrorrsa', 'local_lb_filetransfer'));
            return false;
        }
    }
    if (!empty($filename)) {
        if ($sftp->file_exists($remotedir . $filename)) {
            if (!$sftp->is_readable($remotedir . $filename)) {
                eventDescription (get_string('filedirectoryerror', 'local_lb_filetransfer'));
                return false;
            }
        }
        else {
            eventDescription (get_string('filedirectoryerrornomatch', 'local_lb_filetransfer'));
            return false;
        }
    }
    else {
        eventDescription (get_string('filedirectoryerrornofile', 'local_lb_filetransfer'));
        return false;
    }
    return true;
}

function testFileDirectory() {
    $config = get_config('local_lb_filetransfer');
    $connectionStatus = connectionStatus();
    if ($connectionStatus == 4) {
        $host = $config->host;
        $port = $config->port;
        $username = $config->username;
        if (empty($config->remotedir)) {
            $remotedir = '/';
        } else {
            $remotedir = $config->remotedir;
        }
        $filename = $config->masterfile;
        $sftp = new Net_SFTP($host, $port);

        if ($config->enablekey == 0) {
            $password = $config->password;
            $sftp->login($username, $password);
        } else {
            $key = new Crypt_RSA();
            $key->loadKey($config->rsatoken);
            $sftp->login($username, $key);
        }
        if (empty($config->masterfile)) {
            return 1;
        }
        if (!$sftp->file_exists($remotedir . $filename)) {
            return 2;
        }
        if (!$sftp->is_readable($remotedir . $filename)) {
            return 3;
        }
        return 4;
    }
}

function deleteOldFiles($archiving) {
    $config = get_config('local_lb_filetransfer');
    $archivePeriod = $config->filearchiveperiod;
    switch ($archivePeriod) {
        case 1:
            $days = 7;
            break;
        case 2:
            $days = 14;
            break;
        case 3:
            $days = 21;
            break;
        case 4:
            $days = 28;
            break;
    }
    $path = $archiving;

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
}

function archiveDir ($localdir,$filename) {
    //fielsystem api to be used in the next phase
    global $CFG;
    if (file_exists ($CFG->dataroot . '/temp/lb_filetransfer/lb_filetransfer_backup')) {
        mtrace("Backup folder already exists, using existing one.");
        $backupdir = 'lb_filetransfer_backup/';
    }
    else {
        make_temp_directory('lb_filetransfer/lb_filetransfer_backup/');
        $backupdir = 'lb_filetransfer_backup/';
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

function createTempDir () {
    global $CFG;
    if (file_exists ($CFG->dataroot . '/temp/lb_filetransfer')) {
        mtrace("Temporary folder already exists, using existing one.");
        $tempdir = 'lb_filetransfer/';
        return $tempdir;
    }
    make_temp_directory('lb_filetransfer/');
    mtrace("Created temp folder for the file.");
    $tempdir = 'lb_filetransfer/';
    return $tempdir;
}

function deleteTempDir ($tempdir, $filename) {
    fulldelete($tempdir.$filename);
    mtrace("Deleted temp folder created for the file.");
}

function eventDescription ($description) {
    mtrace($description);
    eventTrigger ($description);
}

function eventTrigger ($description) {
    $event = \local_lb_filetransfer\event\filetransfer_event::create(array(
            'other' => $description
    ));
    $event->trigger();
}

//cohort select plugin doesn't accept csv upload. When that plugin is changed, this feature can be used.
function cohortSelect() {
    global $DB;
    $sql = "SELECT shortname
            FROM {user_info_field}
            WHERE datatype = 'cohortselect'";
    $dbOutput = $DB->get_record_sql($sql)->shortname;
    //var_dump($dbOutput);die;
    if (!empty($dbOutput)) {
        $dbOutput = 'profile_field_'.$dbOutput;
    }
    return $dbOutput;
}

function userUpload($fileToUpload) {
    global $CFG, $DB, $USER;
    $config = get_config('local_lb_filetransfer');
    $admin = get_admin();
    $USER = $admin;

    mtrace('Setting encoding and delimiter.');
    $encoding = 'UTF-8';
    $delimiter_name = 'comma';

    mtrace('Processing the file.');
    $filepath = $fileToUpload;
    $iid = csv_import_reader::get_new_iid('uploaduser');
    $cir = new csv_import_reader($iid, 'uploaduser');
    $content = file_get_contents($filepath);
    if(!$content) {
        mtrace("No file was found at ".$filepath);
        return true;
    }
    $readcount = $cir->load_csv_content($content, $encoding, $delimiter_name);
    $csvloaderror = $cir->get_error();

    mtrace('Posting form data to mform.');
    $formdata = new stdClass();
    $_POST['iid'] = $iid;
    $_POST['previewrows'] = '10';
    //$_POST['uutype'] = UU_USER_ADD_UPDATE;
    $_POST['uutype'] = $config->uutype;
    //$_POST['uupasswordnew'] = '1';
    $_POST['uupasswordnew'] = $config->uupasswordnew;
    //$_POST['uuupdatetype'] = UU_UPDATE_ALLOVERRIDE;
    $_POST['uuupdatetype'] = $config->uuupdatetype;
    //$_POST['uupasswordold'] = '0';
    $_POST['uupasswordold'] = $config->uupasswordold;
    //$_POST['uuforcepasswordchange'] = UU_PWRESET_NONE;
    $_POST['uuforcepasswordchange'] = $config->uupasswordold;
    //$_POST['allowrenames'] = '0';
    $_POST['allowrenames'] = $config->allowrename;
    //$_POST['uuallowdeletes'] = '0';
    $_POST['uuallowdeletes'] = $config->allowdeletes;
    //$_POST['uuallowsuspends'] = '1';
    $_POST['uuallowsuspends'] = $config->allowsuspend;
    //$_POST['uunoemailduplicates'] = '1';
    $_POST['uunoemailduplicates'] = $config->noemailduplicate;
    //$_POST['uustandardusernames'] = '1';
    $_POST['uustandardusernames'] = $config->standardusername;
    $_POST['uubulk'] = UU_BULK_ALL;
    //$_POST['uuallowrenames'] = '0';
    $_POST['uuallowrenames'] = $config->allowrename;

    /* //cohort select plugin doesn't accept csv upload. When that plugin is changed, this feature can be used.
    $cohortSelect = cohortSelect();
    if (!empty($cohortSelect)) {
        $_POST[$cohortSelect] = '_qf__force_multiselect_submission';
    }*/

    $_POST['_qf__admin_uploaduser_form2'] = '1';
    $_POST['submitbutton'] = 'Upload users';
    $_POST['sesskey'] = sesskey();
    $templateuser = $USER;

    mtrace('Setting up the default profile fields.');
    $_POST['auth'] = 'manual';
    $_POST['maildisplay'] = 2;
    $_POST['mailformat'] = 1;
    $_POST['maildigest'] = 0;
    $_POST['autosubscribe'] = 1;
    $_POST['city'] = '';
    $_POST['country'] = $templateuser->country;
    $_POST['timezone'] = $templateuser->timezone;
    $_POST['lang'] = $templateuser->lang;
    $_POST['description'] = '';
    $_POST['url'] = '';
    $_POST['idnumber'] = '';
    $_POST['institution'] = '';
    $_POST['department'] = '';
    $_POST['phone1'] = '';
    $_POST['phone2'] = '';
    $_POST['address'] = '';
    $_POST['uutype'] = $config->uutype;
    $_POST['submitbutton'] = 'submit';

    mtrace('Getting the moodle upload user codes.');
    ob_start();
    chdir($CFG->dirroot . '/admin/tool/uploaduser');
    $indexfile = file_get_contents($CFG->dirroot . '/admin/tool/uploaduser/index.php');
    $indexfile = str_replace("<?php","",$indexfile);
    $indexfile = str_replace("require('../../../config.php');",'', $indexfile);
    $indexfile = str_replace("echo $OUTPUT", "// echo $OUTPUT", $indexfile);
    $indexfile = str_replace("die;", "return;", $indexfile);

    mtrace('Executing the codes.');
    try {
        eval($indexfile);
        mtrace('Code executed.');
    }
    catch (Exception $e) {
        $output = ob_get_clean();
        return false;
    }

    mtrace('Codes executed, preventing any output from echo statements.');
    $output = ob_get_clean();

    mtrace('Users upload successfull.');
    return true;
}

function getFile() {

    global $CFG;
    $config = get_config('local_lb_filetransfer');

    if (testConnection()) {
        eventDescription (get_string('filetransferstarted', 'local_lb_filetransfer'));
        $host = $config->host;
        $port = $config->port;
        $username = $config->username;
        if (empty($config->remotedir)) {
            $remotedir = '/';
        }
        else {
            //$remotedir = '/' . $config->remotedir . '/';
            $remotedir = $config->remotedir;
            mtrace("The directory to copy from:" .$remotedir);
        }
        $filename = $config->masterfile;
        $tempdir = createTempDir ();
        $localdir = $CFG->dataroot . '/temp/'.$tempdir;
        mtrace("The directory to copy to:" .$localdir);
        $sftp = new Net_SFTP($host, $port);
        $fileToUpload = $localdir.$filename;


        if ($config->enablekey == 0) {
            $password = $config->password;
            $sftp->login($username, $password);
            mtrace("Password login successful.");
        }
        else {
            $key = new Crypt_RSA();
            $key->loadKey($config->rsatoken);
            $sftp->login($username, $key);
            mtrace("RSA login successful.");
        }
        //var_dump($remotedir);
        mtrace("Going to remote directory.");
        $sftp->chdir($remotedir);

        //var_dump($sftp->rawlist());
        mtrace("Checking if the file exist or not.");
        if (file_exists($fileToUpload)) {
            $today = time();  // Make the date stamp
            $today = date("Y-m-d-h-m-s",$today);
            $newName = $fileToUpload.'_renamed_'.$today;
            rename( $fileToUpload, $newName);
            mtrace("Existing file found, renamed the file to:".$newName);
        }
        mtrace("Starting file transfer.");
        $sftp->get($remotedir.$filename, $fileToUpload);
        mtrace("Transferred the file from remote directory.");
        chmod($fileToUpload,0777);
        mtrace("Changed permission of the file to 0777.");

        mtrace("Starting user upload.");
        $fileTransfer = userUpload($fileToUpload);
        if ($fileTransfer) {
            mtrace("File transfer process completed.");
        }
        else {
            eventDescription (get_string('filetransfererrorcsv', 'local_lb_filetransfer'));
            return false;
        }

        if ($config->filearchive) {
            mtrace("Archiving uploaded file.");
            $archiving = archiveDir($localdir,$filename);
            deleteOldFiles($archiving);
            mtrace("Deleted old files.");
        } else {
            deleteTempDir($tempdir, $filename);
            mtrace("Deleted temporary file.");
        }
        eventDescription (get_string('filetransfersuccess', 'local_lb_filetransfer'));
        return true;
    }
    else {
        eventDescription (get_string('filetransfererror', 'local_lb_filetransfer'));
        return false;
    }
}
