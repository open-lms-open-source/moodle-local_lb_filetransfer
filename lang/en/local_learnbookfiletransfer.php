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
 * Plugin strings are defined here.
 *
 * @package     local_learnbookfiletransfer
 * @category    string
 * @copyright   2019 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Learnbook File Transfer';
$string['pluginname_description'] = 'Learnbook File Transfer plugin to automate user upload using CSV file from a remote repository.';

$string['host'] = 'Host name';
$string['hostdesc'] = 'URL of the host.';

$string['port'] = 'Port Number';
$string['portdesc'] = 'Port number of the connection.';

$string['username'] = 'Username';
$string['usernamedesc'] = 'Enter the username of the connection.';

$string['password'] = 'Password';
$string['passworddesc'] = 'Enter the password of the connection.';

$string['enableskey'] = 'Use public key authentication';
$string['enableskeydesc'] = 'Enable or disable public key authentication.';

$string['rsatoken'] = 'Private key';
$string['rsatokendesc'] = 'Enter private key to authenticate via SFTP.';

$string['remotedir'] = 'Path to file';
$string['remotedirdesc'] = 'Location of the remote directory, e.g. /directory/';

$string['masterfile'] = 'File name';
$string['masterfiledesc'] = 'Name of the file to upload.';

$string['emailenable'] = 'Email log report';
$string['emailenabledesc'] = 'Enable or disable the email to get the log report.';

$string['emaillog'] = 'Receiver emails';
$string['emaillogdesc'] = 'Enter the emails to send the log report, e.g. example@email.com,example2@email.com.';

$string['connectionerror'] = 'Connection Status: Error connecting to the server, please check your connection information';
$string['connectionerrorpassword'] = 'Connection Status: Error connecting to the server, please check your username and password.';
$string['connectionerrorrsa'] = 'Connection Status: Error connecting to the server, please check your username and RSA key.';
$string['connectionerrornofile'] = 'Connection Status: Filename is empty, please specify the filename';
$string['connectionsuccess'] = 'Connection Status: Successfully connected to the server.';

$string['filetransfertask'] = 'Learnbook File trasfer integration.';
