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
            $connectionTest = $OUTPUT->notification(get_string('connectionerrornohost', 'local_learnbookfiletransfer'), 'notifyproblem');
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

    $name = 'local_learnbookfiletransfer/connectionsetting';
    $visiblename = get_string('connectionsetting','local_learnbookfiletransfer');
    $settings->add(new admin_setting_heading($name, $visiblename, ''));

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

    $fileDirectoryStatus = testFileDirectory();
    $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerror', 'local_learnbookfiletransfer'), 'notifyproblem');
    switch ($fileDirectoryStatus) {
        case 1:
            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerrornofile', 'local_learnbookfiletransfer'), 'notifyproblem');
            break;
        case 2:
            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerrornomatch', 'local_learnbookfiletransfer'), 'notifyproblem');
            break;
        case 3:
            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerrornotreadable', 'local_learnbookfiletransfer'), 'notifyproblem');
            break;
        case 4:
            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectorysuccesse', 'local_learnbookfiletransfer'), 'notifysuccess');
            break;
        default:
            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerror', 'local_learnbookfiletransfer'), 'notifyproblem');
            break;
    }

    $name = 'local_learnbookfiletransfer/filedirectorytest';
    $setting = new setting_statictext($name, $fileDirectoryTest);
    $settings->add($setting);

    $name = 'local_learnbookfiletransfer/filesetting';
    $visiblename = get_string('filesetting','local_learnbookfiletransfer');
    $settings->add(new admin_setting_heading($name, $visiblename, ''));

    $name = 'local_learnbookfiletransfer/remotedir';
    $visiblename = get_string('remotedir','local_learnbookfiletransfer');
    $description = get_string('remotedirdesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));

    $name = 'local_learnbookfiletransfer/masterfile';
    $visiblename = get_string('masterfile','local_learnbookfiletransfer');
    $description = get_string('masterfiledesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));

    $name = 'local_learnbookfiletransfer/filearchive';
    $visiblename = get_string('filearchiveenable','local_learnbookfiletransfer');
    $description = get_string('filearchiveenabledesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configcheckbox($name, $visiblename, $description, 0));

    $oneWeek = get_string('oneweek','local_learnbookfiletransfer');
    $twoWeeks = get_string('twoweeks','local_learnbookfiletransfer');
    $threeWeeks = get_string('threeweeks','local_learnbookfiletransfer');
    $fourWeeks = get_string('fourweeks','local_learnbookfiletransfer');
    $dropdown = array($oneWeek,$twoWeeks,$threeWeeks,$fourWeeks);
    $name = 'local_learnbookfiletransfer/filearchiveperiod';
    $visiblename = get_string('filearchiveperiod','local_learnbookfiletransfer');
    $description = get_string('filearchiveperioddesc','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, $description, 0,$dropdown));

    $name = 'local_learnbookfiletransfer/uploadsetting';
    $visiblename = get_string('uploadsetting','local_learnbookfiletransfer');
    $settings->add(new admin_setting_heading($name, $visiblename, ''));

    $UU_USER_ADDNEW = get_string('uuoptype_addnew', 'local_learnbookfiletransfer');
    $UU_USER_ADDINC = get_string('uuoptype_addinc', 'local_learnbookfiletransfer');
    $UU_USER_ADD_UPDATE = get_string('uuoptype_addupdate', 'local_learnbookfiletransfer');
    $UU_USER_UPDATE = get_string('uuoptype_update', 'local_learnbookfiletransfer');
    $dropdown = array($UU_USER_ADDNEW,$UU_USER_ADDINC,$UU_USER_ADD_UPDATE,$UU_USER_UPDATE);

    $name = 'local_learnbookfiletransfer/uutype';
    $visiblename = get_string('uutype','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, '', 2,$dropdown));

    $infilefield = get_string('infilefield', 'local_learnbookfiletransfer');
    $createpasswordifneeded = get_string('createpasswordifneeded', 'local_learnbookfiletransfer');
    $dropdown = array($infilefield,$createpasswordifneeded);

    $name = 'local_learnbookfiletransfer/uupasswordnew';
    $visiblename = get_string('uupasswordnew','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

    $UU_UPDATE_NOCHANGES = get_string('nochanges', 'local_learnbookfiletransfer');
    $UU_UPDATE_FILEOVERRIDE = get_string('uuupdatefromfile', 'local_learnbookfiletransfer');
    $UU_UPDATE_ALLOVERRIDE  = get_string('uuupdateall', 'local_learnbookfiletransfer');
    $UU_UPDATE_MISSING = get_string('uuupdatemissing', 'local_learnbookfiletransfer');
    $dropdown = array($UU_UPDATE_NOCHANGES,$UU_UPDATE_FILEOVERRIDE,$UU_UPDATE_ALLOVERRIDE,$UU_UPDATE_MISSING);

    $name = 'local_learnbookfiletransfer/uuupdatetype';
    $visiblename = get_string('uuupdatetype','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, '', 2,$dropdown));

    $nochanges  = get_string('nochanges', 'local_learnbookfiletransfer');
    $update = get_string('update', 'local_learnbookfiletransfer');
    $dropdown = array($nochanges,$update);

    $name = 'local_learnbookfiletransfer/uupasswordold';
    $visiblename = get_string('uupasswordold','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, '', 0,$dropdown));

    $yes = get_string('yes', 'local_learnbookfiletransfer');
    $no = get_string('no', 'local_learnbookfiletransfer');
    $dropdown = array($no,$yes);

    $name = 'local_learnbookfiletransfer/allowrename';
    $visiblename = get_string('allowrename','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

    $yes = get_string('yes', 'local_learnbookfiletransfer');
    $no = get_string('no', 'local_learnbookfiletransfer');
    $dropdown = array($no,$yes);

    $name = 'local_learnbookfiletransfer/allowdeletes';
    $visiblename = get_string('allowdeletes','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

    $yes = get_string('yes', 'local_learnbookfiletransfer');
    $no = get_string('no', 'local_learnbookfiletransfer');
    $dropdown = array($no,$yes);

    $name = 'local_learnbookfiletransfer/allowsuspend';
    $visiblename = get_string('allowsuspend','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

    $yes = get_string('yes', 'local_learnbookfiletransfer');
    $no = get_string('no', 'local_learnbookfiletransfer');
    $dropdown = array($no,$yes);

    $name = 'local_learnbookfiletransfer/noemailduplicate';
    $visiblename = get_string('noemailduplicate','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

    $yes = get_string('yes', 'local_learnbookfiletransfer');
    $no = get_string('no', 'local_learnbookfiletransfer');
    $dropdown = array($no,$yes);

    $name = 'local_learnbookfiletransfer/standardusername';
    $visiblename = get_string('standardusername','local_learnbookfiletransfer');
    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

}
