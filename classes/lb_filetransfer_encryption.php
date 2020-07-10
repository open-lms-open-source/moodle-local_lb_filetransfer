<?php
/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Class lb_filetransfer_encryption is used to encrypt and decrypt a file.
 */
class lb_filetransfer_encryption
{
    public function file_decrypt ($decryptiontype, $decryptionkey, $encryptedfile) {
        if ($decryptiontype == 1) {
            $cipher = 'AES-256-CBC';
        }
        $raw = base64_decode($encryptedfile);
        $decrypted = openssl_decrypt($raw, $cipher, $decryptionkey, 0);
        $msg = openssl_error_string();
        var_dump($decrypted);
        var_dump($msg);die;
        return $decrypted;
    }

    public function file_encrypt ($encryptiontype, $rawfile, $encryptionkey, $iv = null) {
        if ($encryptiontype == 1) {
            $cipher = 'AES-128-CBC';
        }
        $key = base64_decode($encryptionkey);
        $iv_size = openssl_cipher_iv_length($cipher);
        if (!$iv) {
            $iv = openssl_random_pseudo_bytes($iv_size);
        }
        $encryptedMessage = openssl_encrypt($rawfile, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encryptedMessage);
    }
}
