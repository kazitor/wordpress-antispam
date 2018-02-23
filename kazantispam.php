<?php
/*
Plugin Name: Kazitor Anti-spam
Description: Requires a simple test when commenting from certain IPs
Author URI: https://kazitor.com
*/

function tott_ip_in_range( $ip, $range ) {
    // taken from https://gist.github.com/tott/7684443

    if ( strpos( $range, '/' ) === false ) {
        $range .= '/32';
    }
    // $range is in IP/CIDR format eg 127.0.0.1/24
    list( $range, $netmask ) = explode( '/', $range, 2 );
    $range_decimal = ip2long( $range );
    $ip_decimal = ip2long( $ip );
    $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
    $netmask_decimal = ~ $wildcard_decimal;
    return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
}

function kazantispam_isbadip()
{
    $badips = array(
        '46.161.9.0/25',
        '77.120.125.23',
        '178.159.37.0/24'
    );

   foreach ($badips as $badip) {
        if (tott_ip_in_range($_SERVER['REMOTE_ADDR'], $badip)) {
            return true;
        }
    }
    return false;
}

function kazantispam_field($postid)
{
    if (kazantispam_isbadip()) {
        echo '<div class="row-fluid">
                            <div class="span6">
                                <div class="af-outer af-required">
                                    <div class="af-inner">
                                        <label for="ip-check" id="name_label">You&rsquo;re writing from an IP that frequently spams.<br>Please write &ldquo;please&rdquo; in the following textbox:</label>
                                        <input type="text" name="ip-check" id="ip-check" class="text-input span12"/>
                                    </div>
                                </div>
                            </div>
                        </div>';
    }
}
add_action('comment_form_after_fields','kazantispam_field');

function kazantispam_verify($commentdata)
{
    if (kazantispam_isbadip()) {
        if (isset($_POST['ip-check']) && $_POST['ip-check'] != 'please') {
            wp_die('You did not say please. Use your browser&rsquo;s back button and type please in the relevant field.');
        }
    }
    return $commentdata;
}
add_action('preprocess_comment','kazantispam_verify');