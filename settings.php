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
 * @package     local_learnbookfiletransfer
 * @category    admin
 * @copyright   2019 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use local_learnbookfiletransfer\settings\setting_statictext;

//if ($ADMIN->fulltree) {
if ($hassiteconfig) {
    require_once(__DIR__.'/classes/settings/setting_statictext.php');
    require_once($CFG->dirroot.'/local/learnbookfiletransfer/filetransfer.php');

    global $OUTPUT;
    $settings = new admin_settingpage('local_learnbookfiletransfer', new lang_string('pluginname', 'local_learnbookfiletransfer'));
    $ADMIN->add('localplugins', $settings);
    $settings->add(new admin_setting_heading('local_learnbookfiletransfer', '', get_string('pluginname_description', 'local_learnbookfiletransfer')));
    /*
    $connectiontest = $OUTPUT->notification(get_string('connectionerror', 'local_learnbookfiletransfer'), 'notifyproblem');
    if (connectionStatus()) {
    $connectiontest = $OUTPUT->notification(get_string('connectionsuccess', 'local_learnbookfiletransfer'), 'notifysuccess');
    }*/
    $connectionStatus = connectionStatus();
    $connectionTest = $OUTPUT->notification(get_string('connectionerror', 'local_learnbookfiletransfer'), 'notifyproblem');
    switch ($connectionStatus){
        case 1:
            $connectionTest = $OUTPUT->notification(get_string('connectionerrorpassword', 'local_learnbookfiletransfer'), 'notifyproblem');
            break;
        case 2:
            $connectionTest = $OUTPUT->notification(get_string('connectionerrorrsa', 'local_learnbookfiletransfer'), 'notifyproblem');
            break;
        case 3:
            $connectionTest = $OUTPUT->notification(get_string('connectionerrornofile', 'local_learnbookfiletransfer'), 'notifyproblem');
            break;
        case 4:
            $connectionTest = $OUTPUT->notification(get_string('connectionsuccess', 'local_learnbookfiletransfer'), 'notifysuccess');
            break;
        default:
            $connectionTest = $OUTPUT->notification(get_string('connectionerror', 'local_learnbookfiletransfer'), 'notifyproblem');
            break;
    }

    $name = 'local_learnbookfiletransfer/connectiontest';
    $setting = new setting_statictext($name, $connectionTest);
    $settings->add($setting);

    $name = 'local_learnbookfiletransfer/host';
    $visiblename = get_string('host','local_learnbookfiletransfer');
    $description = get_string('hostdesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));

    $name = 'local_learnbookfiletransfer/port';
    $visiblename = get_string('port','local_learnbookfiletransfer');
    $description = get_string('portdesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configtext($name, $visiblename, $description, '22'));

    $name = 'local_learnbookfiletransfer/username';
    $visiblename = get_string('username','local_learnbookfiletransfer');
    $description = get_string('usernamedesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));

    $name = 'local_learnbookfiletransfer/password';
    $visiblename = get_string('password','local_learnbookfiletransfer');
    $description = get_string('passworddesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configpasswordunmask($name, $visiblename, $description, ''));

    $name = 'local_learnbookfiletransfer/enablekey';
    $visiblename = get_string('enableskey','local_learnbookfiletransfer');
    $description = get_string('enableskeydesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configcheckbox($name, $visiblename, $description, 0));

    $name = 'local_learnbookfiletransfer/rsatoken';
    $visiblename = get_string('rsatoken','local_learnbookfiletransfer');
    $description = get_string('rsatokendesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));

    $name = 'local_learnbookfiletransfer/remotedir';
    $visiblename = get_string('remotedir','local_learnbookfiletransfer');
    $description = get_string('remotedirdesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));

    $name = 'local_learnbookfiletransfer/masterfile';
    $visiblename = get_string('masterfile','local_learnbookfiletransfer');
    $description = get_string('masterfiledesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));



    /*
    $name = 'local_learnbookfiletransfer/emailenable';
    $visiblename = get_string('emailenable','local_learnbookfiletransfer');
    $description = get_string('emailenabledesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configcheckbox($name, $visiblename, $description, 0));

    $name = 'local_learnbookfiletransfer/emaillog';
    $visiblename = get_string('emaillog','local_learnbookfiletransfer');
    $description = get_string('emaillogdesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));
    */

}
