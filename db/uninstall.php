<?php

/**
 * Code that is executed before the tables and data are dropped during the plugin uninstallation.
 *
 * @package     local_learnbookfiletransfer
 * @category    upgrade
 * @copyright   2019 eCreators <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Custom uninstallation procedure.
 */
function xmldb_local_learnbookfiletransfer_uninstall() {

    return true;
}
