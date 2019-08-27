<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_learnbookfiletransfer
 * @category    admin
 * @copyright   2019 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG;
set_include_path(get_include_path(). PATH_SEPARATOR . $CFG->dirroot.'/local/learnbookfiletransfer/lib/phpseclib');
require_once($CFG->dirroot .'/local/learnbookfiletransfer/lib/phpseclib/Net/SFTP.php');
require_once($CFG->dirroot .'/local/learnbookfiletransfer/lib/phpseclib/Crypt/RSA.php');

//set_include_path(get_include_path(). PATH_SEPARATOR .'/Applications/MAMP/htdocs/moodle/local/learnbookfiletransfer/lib/phpseclib');
//require_once('/Applications/MAMP/htdocs/moodle/local/learnbookfiletransfer/lib/phpseclib/Net/SFTP.php');
//require_once('/Applications/MAMP/htdocs/moodle/local/learnbookfiletransfer/lib/phpseclib/Crypt/RSA.php');

function connectionStatus() {
    global $CFG;
    $config = get_config('local_learnbookfiletransfer');
    $host = $config->host;
    $port = $config->port;
    $username = $config->username;
    $sftp = new Net_SFTP($host,$port);
    //var_dump($sftp);die;

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
    if (empty($config->masterfile)) {
        return 3;
    }
    return 4;
}

function testConnection() {
    global $CFG;
    $config = get_config('local_learnbookfiletransfer');
    $host = $config->host;
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
            mtrace("Connection error: Username or password not correct");
            return false;
        }
    }
    else {
        $key = new Crypt_RSA();
        $key->loadKey($config->rsatoken);
        if (!$sftp->login($username, $key)) {
            mtrace("Connection error: Username or RSA not correct");
            return false;
        }
    }
    if (!empty($filename)) {
        if ($sftp->file_exists($remotedir . $filename)) {
            if (!$sftp->is_readable($remotedir . $filename)) {
                mtrace("file not readable");
                return false;
            }
        }
        else {
            mtrace("No matching file found in the remote directory.");
            return false;
            //return a value instead//
        }
    }
    else {
        mtrace("Filename is empty");
        return false;
    }
    return true;
}

function createTempDir () {
    global $CFG;
    if (file_exists ($CFG->dataroot . '/temp/learnbookfiletransfer')) {
        mtrace("Temporary file already exists, using existing one.");
        $tempdir = 'learnbookfiletransfer/';
        return $tempdir;
    }
    $tempdir = make_temp_directory('learnbookfiletransfer/');
    mtrace("Created temp folder for the file.");
    return $tempdir;
}

function deleteTempDir ($tempdir, $filename) {
    fulldelete($tempdir.$filename);
    mtrace("Deleted temp folder created for the file.");
}

function getFile() {

    global $CFG;
    $config = get_config('local_learnbookfiletransfer');

    if (testConnection()) {
        $host = $config->host;
        $port = $config->port;
        $username = $config->username;
        if (empty($config->remotedir)) {
            $remotedir = '/';
        }
        else {
            //$remotedir = '/' . $config->remotedir . '/';
            $remotedir = $config->remotedir;
        }
        $filename = $config->masterfile;
        $tempdir = createTempDir ();
        $localdir = $CFG->dataroot . '/temp/'.$tempdir;
        $sftp = new Net_SFTP($host, $port);


        if ($config->enablekey == 0) {
            $password = $config->password;
            $sftp->login($username, $password);
        }
        else {
            $key = new Crypt_RSA();
            $key->loadKey($config->rsatoken);
            $sftp->login($username, $key);
        }
        //var_dump($remotedir);
        $sftp->chdir($remotedir);
        //var_dump($sftp->rawlist());
        $sftp->get($remotedir.$filename, $localdir.$filename);

        /*
        if ($sftp->file_exists($remotedir.$filename)) {
            if ($sftp->is_readable($remotedir.$filename)) {
                $sftp->get($remotedir.$filename, $localdir.$filename);
                mtrace("Transferred the file from remote directory.");
            }
            else {
                mtrace("File is not readable.");
                return false;
            }
        }
        else {
            mtrace("No matching file found in the remote directory.");
            return false;
            //return a value instead//
        }
        */
        //deleteTempDir ($tempdir, $filename);
        return true;
    }
    else {
        mtrace("The connection is not successful, can not get file from remote directory");
        return false;
    }
}
