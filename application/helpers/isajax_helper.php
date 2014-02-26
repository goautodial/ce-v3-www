<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* is_ajax_call
*
* Determines if the current page request is done through an AJAX call
*
* @access    public
* @param    void
* @return    boolean
*/
if ( ! function_exists('isAjax')) {
    function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }
}
?>