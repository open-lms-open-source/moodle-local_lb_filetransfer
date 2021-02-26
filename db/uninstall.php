<?php

/**
 * Code that is executed before the tables and data are dropped during the plugin uninstallation.
 *
 * @package     local_lb_filetransfer
 * @category    upgrade
 * @copyright   2020 eCreators PTY LTD
 * @author      2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Custom uninstallation procedure.
 */
function xmldb_local_lb_filetransfer_uninstall() {

    return true;
}
