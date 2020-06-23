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
 * Plugin upgrade steps are defined here.
 *
 * @package     local_lb_filetransfer
 * @category    upgrade
 * @copyright   2019 eCreators <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/upgradelib.php');

/**
 * Execute local_lb_filetransfer upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_lb_filetransfer_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();
    if ($oldversion < 20200062301) {
        $table = new xmldb_table('local_lb_filetr_connections');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('connectiontype', XMLDB_TYPE_INTEGER, '10', null, null, null);
        $table->add_field('hostname', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('portnumber', XMLDB_TYPE_INTEGER, '10', null, null, null);
        $table->add_field('username', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('password', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('usepublickey', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('privatekey', XMLDB_TYPE_CHAR, '1024', null, null, null);
        $table->add_field('active', XMLDB_TYPE_INTEGER, '1', null, null, null, 1);
        $table ->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, null, null);
        $table ->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
        $table ->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);

        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, array('usermodified'), 'user', 'id');

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_lb_filetr_uploads');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('connectionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
        $table->add_field('pathtofile', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('filename', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('archivefile', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0);
        $table->add_field('archiveperiod', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
        $table->add_field('uutype', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('uupasswordnew', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('uuupdatetype', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('uupasswordold', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('allowrename', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('allowdeletes', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('allowsuspend', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('noemailduplicate', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('standardusername', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        $table->add_field('active', XMLDB_TYPE_INTEGER, '1', null, null, null, 1);
        $table ->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, null, null);
        $table ->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
        $table ->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('connection', XMLDB_KEY_FOREIGN, array('connectionid'), 'local_lb_filetr_connections', 'id');
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, array('usermodified'), 'user', 'id');
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_lb_filetr_reports');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('connectionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
        $table->add_field('configurablereportid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
        $table->add_field('pathtofile', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('filename', XMLDB_TYPE_CHAR, '255', null, null, null);
        $table->add_field('archivefile', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0);
        $table->add_field('archiveperiod', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
        $table->add_field('email', XMLDB_TYPE_CHAR, '1024', null, null, null);
        $table->add_field('active', XMLDB_TYPE_INTEGER, '1', null, null, null, 1);
        $table ->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, null, null);
        $table ->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);
        $table ->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('connection', XMLDB_KEY_FOREIGN, array('connectionid'), 'local_lb_filetr_connections', 'id');
        $table->add_key('report', XMLDB_KEY_FOREIGN, array('configurablereportid'), 'block_configurable_reports', 'id');
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, array('usermodified'), 'user', 'id');
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    return true;
}
