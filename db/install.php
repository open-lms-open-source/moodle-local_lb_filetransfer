<?php

/**
 * Code to be executed after the plugin's database scheme has been installed is defined here.
 *
 * @package     local_learnbookfiletransfer
 * @category    upgrade
 * @copyright   2019 eCreators <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Custom code to be run on installing the plugin.
 */
function xmldb_local_learnbookfiletransfer_install() {

    return true;
}
