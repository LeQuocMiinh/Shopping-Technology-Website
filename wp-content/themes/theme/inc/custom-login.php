<?php
//Start session Wp
function session_init()
{
    if (!session_id() && !headers_sent()) {
        session_start();
    }
}
session_init();
//create the table to store the user details to the plugin
function epal_database_install_custom_login()
{
    global $wpdb;
    // create user details table
    $epal_userdetails = "{$wpdb->prefix}epal_users_social_profile_details";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $sql = "CREATE TABLE IF NOT EXISTS `$epal_userdetails` (
        id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        provider_name varchar(50) NOT NULL,
        identifier varchar(255) NOT NULL,
        unique_verifier varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        email_verified varchar(255) NOT NULL,
        first_name varchar(150) NOT NULL,
        last_name varchar(150) NOT NULL,
        profile_url varchar(255) NOT NULL,
        website_url varchar(255) NOT NULL,
        photo_url varchar(255) NOT NULL,
        display_name varchar(150) NOT NULL,
        description varchar(255) NOT NULL,
        gender varchar(10) NOT NULL,
        language varchar(20) NOT NULL,
        age varchar(10) NOT NULL,
        birthday int(11) NOT NULL,
        birthmonth int(11) NOT NULL,
        birthyear int(11) NOT NULL,
        phone varchar(75) NOT NULL,
        address varchar(255) NOT NULL,
        country varchar(75) NOT NULL,
        region varchar(50) NOT NULL,
        city varchar(50) NOT NULL,
        zip varchar(25) NOT NULL,
        UNIQUE KEY id (id),
        KEY user_id (user_id),
        KEY provider_name (provider_name)
    )";
    dbDelta($sql);
}
epal_database_install_custom_login();

//Custom gọi hàm khi login
function callOnFaceGoogle()
{
    if (isset($_GET['epal_login_id'])) {
        if ($_GET['epal_login_id'] == 'facebook_login') {
            onFacebookLogin();
        } else if ($_GET['epal_login_id'] == 'google_login' || $_GET['epal_login_id'] == 'google_check') {
            onGoogleLogin();
        } else {
        }
    } else {
        $result = 'FALSE';
    }
}
callOnFaceGoogle();

//Login facebook
function onFacebookLogin()
{
    $response = '';
    $result = facebookLogin();

    if (isset($result['status']) && $result['status'] == 'SUCCESS') {
        global $wpdb;
        $unique_verifier = sha1($result['deutype'] . $result['deuid']);
        $deutype = $result['deutype'];
        $deuid = $result['deuid'];
        $sql = "SELECT *  FROM  `{$wpdb->prefix}epal_users_social_profile_details` WHERE  `provider_name` LIKE  '$deutype' AND  `identifier` LIKE  '$deuid' AND `unique_verifier` LIKE '$unique_verifier' LIMIT 1";
        $row = $wpdb->get_row($sql);
        if (!$row) {
            //check if there is already a user with the email address provided from social login already
            $user_details_by_email = getUserByMail($result['email']);
            if ($user_details_by_email != false) {
                //user already there so log him in
                $id = $user_details_by_email->ID;
                $sql = "SELECT * FROM `{$wpdb->prefix}epal_users_social_profile_details` WHERE `user_id` LIKE  '$id'; ";
                $row = $wpdb->get_row($sql);
                if (!$row) {
                    link_user($id, $result);
                }
                loginUser($id);
                echo $id;
                die();
            }
            $_SESSION['user_details'] = $result;
            // use FB id as username if sanitized username is empty
            $sanitized_user_name = sanitize_user($result['username'], true);
            if (empty($sanitized_user_name)) {
                $sanitized_user_name = $result['deuid'];
            }
            $user_Id = creatUser($sanitized_user_name, $result['email']);
            $user_row = getUserByMail($result['email']);
            $id = $user_row->ID;
            $result = $result;
            $role = 'subscriber';
            UpdateUserMeta($id, $result, $role);
            echo $id;
            loginUser($id);
            exit();
        } else {
            if (($row->provider_name == $result['deutype']) && ($row->identifier == $result['deuid'])) {
                loginUser($row->user_id);
                echo $row->user_id;
                exit();
            } else {
                // user not found in our database
            }
        }
    } else {
        if (isset($_REQUEST['error'])) {
            $_SESSION['epal_login_error_flag'] = 1;
            $redirect_url = isset($_REQUEST['redirect_to']) ? ($_REQUEST['redirect_to']) : site_url();
            wp_redirect($redirect_url);
        }
    }
}

function facebookLogin()
{
    $exploder = explode('_', sanitize_text_field($_GET['epal_login_id']));
    $action = $exploder[1];

    $app_id = get_field('app_id_fb', 'option');
    $app_secret = get_field('app_secret_fb', 'option');
    $enable_fb = get_field('enable_fb', 'option');
    $callback = get_field('url_callback_facebook', 'option');

    $return_user_details = array();
    if ($enable_fb == 1) {
        $config = array(
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.4',
            'persistent_data_handler' => 'session'
        );
        include('./wp-content/themes/epal-theme/vendor/autoload.php');
        $fb = new Facebook\Facebook($config);
        if ($action == 'login') {
            // Well looks like we are a fresh dude, login to Facebook!
            $helper = $fb->getRedirectLoginHelper();
            $permissions = array('email', 'public_profile'); // optional
            $loginUrl = $helper->getLoginUrl($callback, $permissions);

            $encoded_url = isset($_GET['redirect_to']) ? esc_url($_GET['redirect_to']) : '';
            if (isset($encoded_url) && $encoded_url != '') {
                setcookie("epal_login_redirect_url", $encoded_url, time() + 3600);
                // $callback = $callBackUrl . 'epal_login_id' . '=facebook_check&redirect_to=' . $encoded_url;
            }
            wp_redirect($loginUrl);
            echo $loginUrl;
            die();
        } else {
            if (isset($_REQUEST['error'])) {
                $response->status = 'ERROR';
                $response->error_code = 2;
                $response->error_message = 'INVALID AUTHORIZATION';
                return $response;
                die();
            }
            if (isset($_REQUEST['code'])) {
                $helper = $fb->getRedirectLoginHelper();
                // Trick below will avoid "Cross-site request forgery validation failed. Required param "state" missing." from Facebook
                $_SESSION['FBRLH_state'] = sanitize_text_field($_REQUEST['state']);
                try {
                    $accessToken = $helper->getAccessToken($callback);
                } catch (Facebook\Exceptions\FacebookResponseException $e) {
                    // When Graph returns an error
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    // When validation fails or other local issues
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                    exit;
                }

                if (isset($accessToken)) {
                    // Logged in!
                    $_SESSION['facebook_access_token'] = (string) $accessToken;
                    $fb->setDefaultAccessToken($accessToken);

                    try {
                        $response = $fb->get('/me?fields=email,name, first_name, last_name, gender, link, about, birthday, education, hometown, is_verified, languages, location, website');
                        $userNode = $response->getGraphUser();
                    } catch (Facebook\Exceptions\FacebookResponseException $e) {
                        // When Graph returns an error
                        echo 'Graph returned an error: ' . $e->getMessage();
                        exit;
                    } catch (Facebook\Exceptions\FacebookSDKException $e) {
                        // When validation fails or other local issues
                        echo 'Facebook SDK returned an error: ' . $e->getMessage();
                        exit;
                    }
                    // get the user profile details

                    $user_profile = accessProtected($userNode, 'items');
                    if ($user_profile != null) {
                        $return_user_details['status'] = 'SUCCESS';
                        $return_user_details['deuid'] = $user_profile['id'];
                        $return_user_details['deutype'] = 'facebook';
                        $return_user_details['first_name'] = $user_profile['first_name'];
                        $return_user_details['last_name'] = $user_profile['last_name'];
                        if (isset($user_profile['email'])) {
                            if ($user_profile['email'] != '') {
                                $user_email = $user_profile['email'];
                            } else {
                                $user_email = $user_profile['id'] . '@facebook.com';
                            }
                        } else {
                            $user_email = $user_profile['id'] . '@facebook.com';
                        }
                        $return_user_details['email'] = $user_email;
                        $return_user_details['username'] = ($user_profile['first_name'] != '') ? strtolower($user_profile['first_name']) : $user_email;
                        $return_user_details['gender'] = isset($user_profile['gender']) ? $user_profile['gender'] : 'N/A';
                        $return_user_details['url'] = isset($user_profile['link']) ? $user_profile['link'] : '';
                        $return_user_details['about'] = ''; //facebook doesn't return user about details.
                        $headers = get_headers('https://graph.facebook.com/' . $user_profile['id'] . '/picture?width=500&height=500', 1);
                        // just a precaution, check whether the header isset...
                        if (isset($headers['Location'])) {
                            $return_user_details['deuimage'] = $headers['Location']; // string
                        } else {
                            $return_user_details['deuimage'] = false;
                            // nothing there? .. weird, but okay!
                        }
                        $return_user_details['error_message'] = '';
                    } else {
                        $return_user_details['status'] = 'ERROR';
                        $return_user_details['error_code'] = 2;
                        $return_user_details['error_message'] = 'INVALID AUTHORIZATION';
                    }
                }
            } else {
                // Well looks like we are a fresh dude, login to Facebook!
                $helper = $fb->getRedirectLoginHelper();
                $permissions = array('email', 'public_profile'); // optional
                $loginUrl = $helper->getLoginUrl($callback, $permissions);
                wp_redirect($loginUrl);
            }
        }
    } else {
        function noti_function()
        {
            echo "<div id='notification' class='d-none'>Website bạn chưa kích hoạt đăng nhập bằng Facebook</div>";
            $js = '<script type="text/javascript">setTimeout(function(){jQuery("#notification").removeClass("d-none");}, 1000);setTimeout(function(){jQuery("#notification").addClass("d-none");}, 5000);</script>';
            echo $js;
            echo "<style type='text/css'>";
            echo " #notification{ background-color:#FE2E2E;color:#ffffff;position: fixed;z-index: 1000;padding: 4px 15px;top: 15px;right: 15px;border-radius: 5px;transition: 1s all;border: none; }#notification.d-none{top:-35px;transition: 1s all;display:block !important;}";
            echo "</style>";
        }
        add_action('wp_footer', 'noti_function');
    }
    return $return_user_details;
}

//for google login
function onGoogleLogin()
{
    $result = GoogleLogin();
    if (isset($result['status']) && $result['status'] == 'SUCCESS') {
        global $wpdb;
        $unique_verifier = sha1($result['deutype'] . $result['deuid']);
        $deutype = $result['deutype'];
        $deuid = $result['deuid'];
        $sql = "SELECT *  FROM  `{$wpdb->prefix}epal_users_social_profile_details` WHERE  `provider_name` LIKE  '$deutype' AND  `identifier` LIKE  '$deuid' AND `unique_verifier` LIKE '$unique_verifier'";
        $row = $wpdb->get_row($sql);
        if (!$row) {
            //check if there is already a user with the email address provided from social login already
            $user_details_by_email = getUserByMail($result['email']);
            if ($user_details_by_email != false) {
                //user already there so log him in
                $id = $user_details_by_email->ID;
                $sql = "SELECT *  FROM  `{$wpdb->prefix}epal_users_social_profile_details` WHERE  `user_id` LIKE  '$id'; ";
                $row = $wpdb->get_row($sql);
                if (!$row) {
                    link_user($id, $result);
                }
                loginUser($id);
                die();
            }
            $_SESSION['user_details'] = $result;
            creatUser($result['username'], $result['email']);
            $user_row = getUserByMail($result['email']);
            $id = $user_row->ID;
            $result = $result;
            $role = 'subscriber';
            UpdateUserMeta($id, $result, $role);
            loginUser($id);
            exit();
        } else {
            if (($row->provider_name == $result['deutype']) && ($row->identifier == $result['deuid'])) {
                loginUser($row->user_id);
                exit();
            } else {
                // user not found in our database
            }
        }

        $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : home_url(); // Nếu không có trang trước đó, chuyển hướng về trang chính
        // Chuyển hướng người dùng đến trang trước đó
        wp_redirect($redirect_url);
    } else {
        if (isset($_REQUEST['error'])) {
            $_SESSION['epal_login_error_flag'] = 1;
            $redirect_url = isset($_REQUEST['redirect_to']) ? sanitize_text_field($_REQUEST['redirect_to']) : site_url();
            redirect($redirect_url);
        }
        //die();
    }
}

function GoogleLogin()
{
    $response = array();
    $a = explode('_', sanitize_text_field($_GET['epal_login_id']));
    $action = $a[1];

    $client_id = get_field('client_id_gg', 'option');
    $client_secret = get_field('client_secret_gg', 'option');
    $enable_gg = get_field('enable_gg', 'option');

    $site_url = site_url() . '/wp-admin';
    $encoded_url = isset($_GET['redirect_to']) ? sanitize_text_field($_GET['redirect_to']) : $site_url;
    // $callback ='https://mocflowers.epalshop.com/wp-login.php?epal_login_id=google_check';

    $callback = get_field('url_callback_google', 'option');


    if ($enable_gg == 1) {
        include('./wp-content/themes/Epal-theme/vendor/autoload.php');
        $redirect_uri = $callback;
        $client = new Google_Client;

        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);

        $client->setScopes([
            "profile",
            // can give: all we need, but no email
            "email",
            // can give: all we need, but no language (do we need that?)
        ]);

        if (isset($encoded_url) && $encoded_url != '') {
            $client->setState(base64_encode("redirect_to=$encoded_url"));
        }
        $service = new Google_Service_Oauth2($client);
        if ($action == 'login') { // Get identity from user and redirect browser to OpenID Server
            unset($_SESSION['access_token']);
            if (!(isset($_SESSION['access_token']) && $_SESSION['access_token'])) {
                $authUrl = $client->createAuthUrl();
                echo $authUrl;
                redirect($authUrl);
                die();
            } else {
                redirect($redirect_uri . "&redirect_to=$encoded_url");
                die();
            }
        } elseif (isset($_GET['code'])) { // Perform HTTP Request to OpenID server to validate key
            $client->authenticate(sanitize_text_field($_GET['code']));
            $_SESSION['access_token'] = $client->getAccessToken();
            echo $_SESSION['access_token'];
            redirect($redirect_uri . "&redirect_to=$encoded_url");
            die();
        } elseif (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
            try {
                $user = $service->userinfo->get();
            } catch (Exception $fault) {
                unset($_SESSION['access_token']);
                $ref_object = accessProtected($fault, 'errors');
                echo $ref_object[0]['message'] . " Please notify about this error to the Site Admin.";
                die();
            }
            print_r($user);
            //die();
            if (!empty($user)) {
                if (!empty($user->email)) {
                    $response['email'] = $user->email;
                    $response['username'] = ($user->givenName != '') ? strtolower($user->givenName . ' ' . $user->familyName) : $user->email;
                    $response['first_name'] = $user->givenName;
                    $response['last_name'] = $user->familyName;
                    $response['deuid'] = $user->id;
                    $imageUrl = substr($user->picture, 0, strpos($user->picture . "?sz=", "?sz=")) . '?sz=450';
                    $response['deuimage'] = $imageUrl;
                    $response['gender'] = isset($user->gender) ? $user->gender : 'N/A';
                    $response['id'] = $user->id;
                    $response['about'] = $user->aboutMe;
                    $response['url'] = $user->url;
                    $response['deutype'] = 'google';
                    $response['status'] = 'SUCCESS';
                    $response['error_message'] = '';
                } else {
                    $response['status'] = 'ERROR';
                    $response['error_code'] = 2;
                    $response['error_message'] = "INVALID AUTHORIZATION";
                }
            } else { // Signature Verification Failed
                $response['status'] = 'ERROR';
                $response['error_code'] = 2;
                $response['error_message'] = "INVALID AUTHORIZATION";
                print_r($response);
            }
        } else { // User failed to login
            $response['status'] = 'ERROR';
            $response['error_code'] = 3;
            $response['error_message'] = "USER LOGIN FAIL";
            print_r($response);
        }
    } else {
        function noti_function()
        {
            echo "<div id='notification' class='d-none'>Website bạn chưa kích hoạt đăng nhập bằng Google</div>";
            $js = '<script type="text/javascript">setTimeout(function(){jQuery("#notification").removeClass("d-none");}, 1000);setTimeout(function(){jQuery("#notification").addClass("d-none");}, 5000);</script>';
            echo $js;
            echo "<style type='text/css'>";
            echo " #notification{ background-color:#FE2E2E;color:#ffffff;position: fixed;z-index: 1000;padding: 4px 15px;top: 15px;right: 15px;border-radius: 5px;transition: 1s all;border: none; }#notification.d-none{top:-35px;transition: 1s all;display:block !important;}";
            echo "</style>";
        }
        add_action('wp_footer', 'noti_function');
    }
    return $response;
}



function siteUrl()
{
    return site_url();
}

function callBackUrl()
{
    $url = wp_login_url();
    if (strpos($url, '?') === false) {
        $url .= '?';
    } else {
        $url .= '&';
    }
    return $url;
}
//function to return json values from social media urls
function get_json_values($url)
{
    $response = wp_remote_get($url);
    $json_response = wp_remote_retrieve_body($response);
    return $json_response;
}

function redirect($redirect)
{
    if (headers_sent()) { // Use JavaScript to redirect if content has been previously sent (not recommended, but safe)
        echo '<script language="JavaScript" type="text/javascript">window.location=\'';
        echo $redirect;
        echo '\';</script>';
    } else { // Default Header Redirect
        header('Location: ' . $redirect);
    }
    exit;
}

function getUserByMail($email)
{
    global $wpdb;
    $row = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE user_email = '$email'");
    if ($row) {
        return $row;
    }
    return false;
}

function link_user($id, $result)
{
    global $wpdb;
    $unique_verifier = sha1($result['deutype'] . $result['deuid']);
    $epal_userdetails = "{$wpdb->prefix}epal_users_social_profile_details";

    $first_name = sanitize_text_field($result['first_name']);
    $last_name = sanitize_text_field($result['last_name']);
    $profile_url = sanitize_text_field($result['url']);
    $photo_url = sanitize_text_field($result['deuimage']);
    $display_name = sanitize_text_field($result['first_name'] . ' ' . $result['last_name']);
    $description = sanitize_text_field($result['about']);

    $table_name = $epal_userdetails;
    $submit_array = array(
        "user_id" => $id,
        "provider_name" => $result['deutype'],
        "identifier" => $result['deuid'],
        "unique_verifier" => $unique_verifier,
        "email" => $result['email'],
        "first_name" => $first_name,
        "last_name" => $last_name,
        "profile_url" => $profile_url,
        "photo_url" => $photo_url,
        "display_name" => $display_name,
        "description" => $description,
        "gender" => $result['gender']
    );
    $user_profile_details = $result;
    $wpdb->insert($table_name, $submit_array);
    if (!$result) {
        echo __("Data insertion failed");
    }
}

function loginUser($user_id)
{
    if (!set_cookies($user_id)) {
        return false;
    }
    wp_safe_redirect(get_home_url());
    exit();
}

function set_cookies($user_id = 0, $remember = true)
{
    if (!function_exists('wp_set_auth_cookie')) {
        return false;
    }
    if (!$user_id) {
        return false;
    }
    wp_clear_auth_cookie();
    wp_set_auth_cookie($user_id, $remember);
    wp_set_current_user($user_id);
    return true;
}

function UpdateUserMeta($id, $result, $role)
{
    update_user_meta($id, 'email', $result['email']);
    update_user_meta($id, 'first_name', $result['first_name']);
    update_user_meta($id, 'last_name', $result['last_name']);
    update_user_meta($id, 'billing_first_name', $result['first_name']);
    update_user_meta($id, 'billing_last_name', $result['last_name']);
    update_user_meta($id, 'deuid', $result['deuid']);
    update_user_meta($id, 'deutype', $result['deutype']);
    update_user_meta($id, 'deuimage', $result['deuimage']);
    update_user_meta($id, 'description', $result['about']);
    update_user_meta($id, 'sex', $result['gender']);
    wp_update_user(
        array(
            'ID' => $id,
            'display_name' => $result['first_name'] . ' ' . $result['last_name'],
            'user_url' => $result['url']
        )
    );

    global $wpdb;
    $unique_verifier = sha1($result['deutype'] . $result['deuid']);
    $epal_userdetails = "{$wpdb->prefix} epal_users_social_profile_details";

    $first_name = sanitize_text_field($result['first_name']);
    $last_name = sanitize_text_field($result['last_name']);
    $profile_url = sanitize_text_field($result['url']);
    $photo_url = sanitize_text_field($result['deuimage']);
    $display_name = sanitize_text_field($result['first_name'] . ' ' . $result['last_name']);
    $description = sanitize_text_field($result['about']);

    $table_name = $epal_userdetails;
    $submit_array = array(
        "user_id" => $id,
        "provider_name" => $result['deutype'],
        "identifier" => $result['deuid'],
        "unique_verifier" => $unique_verifier,
        "email" => $result['email'],
        "first_name" => $first_name,
        "last_name" => $last_name,
        "profile_url" => $profile_url,
        "photo_url" => $photo_url,
        "display_name" => $display_name,
        "description" => $description,
        "gender" => $result->gender
    );
    $user_profile_details = $result;
    $wpdb->insert($table_name, $submit_array);

    if (!$result) {
        echo "Data insertion failed";
    }
}

function creatUser($user_name, $user_email)
{
    $username = get_username($user_name);
    $random_password = wp_generate_password(12, false);
    $user_id = wp_create_user($username, $random_password, $user_email);
    do_action('EPAL_createUser', $user_id); //hookable function to perform additional work after creation of user.
    $options = 'yes';
    if ($options == 'yes') {
        if (version_compare(get_bloginfo('version'), '4.3.1', '>=')) {
            wp_new_user_notification($user_id, $deprecated = null, $notify = 'both');
        } else {
            wp_new_user_notification($user_id, $random_password);
        }
    }
    return $user_id;
}

function accessProtected($obj, $prop)
{
    $reflection = new ReflectionClass($obj);
    $property = $reflection->getProperty($prop);
    $property->setAccessible(true);
    return $property->getValue($obj);
}

function get_username($user_name)
{
    $username = $user_name;
    $i = 1;
    while (username_exists($username)) {
        $username = $user_name . '_' . $i;
        $i++;
    }
    return $username;
}
