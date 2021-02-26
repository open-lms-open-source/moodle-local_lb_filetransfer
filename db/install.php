<?php

/**
 * Code to be executed after the plugin's database scheme has been installed is defined here.
 *
 * @package     local_lb_filetransfer
 * @category    upgrade
 * @copyright   2020 eCreators PTY LTD
 * @author      2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Custom code to be run on installing the plugin.
 */
function xmldb_local_lb_filetransfer_install() {

    return true;
}
