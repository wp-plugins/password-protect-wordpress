<?php
/*
Plugin Name: Password Protect Wordpress
Plugin URI: http://www.spiders-design.co.uk/wordpress-stuff/password-protect-wordpress-blog/
This plugin password protects your wordpress blog with a single password. Great for family blogs.
Version: 3.3
Author: Daniel Chatfield
Author URI: http://www.spiders-design.co.uk
License: GPLv2
*/
?>
<?php
session_start();
if(file_exists(dirname(__FILE__).'/extensions.php'))
{
    include_once(dirname(__FILE__).'/extensions.php');
}
if(!defined('PLUGINVERSION')){
    define('PLUGINVERSION','3.3');
}
if(!defined('THIS_PLUGIN_TEXT')){
    define('THIS_PLUGIN_TEXT','Plugin version '.PLUGINVERSION);
}
$options = get_option('password_protect_options');
add_action('admin_menu', 'add_defaults_fn');
add_action('init', 'loginrequest', 1);
add_action('admin_init', 'sd_showlog');
add_action('wp_head', 'sd_authenticate');
if($options['getheader'] == 'on' OR $options['usecookies'] == 'on')
{
    add_action('get_header', 'sd_authenticate');
}
if($options['init'] == 'on')
{
    add_action('init', 'sd_authenticate');
}
add_action('admin_menu', 'sd_plugin_add_page_fn');
add_action('init', 'sd_jquery');
if (isset($_GET['page']) && $_GET['page'] == 'password_protection') {
add_action('admin_print_scripts', 'my_admin_scripts');
add_action('admin_print_styles', 'my_admin_styles');
}
function my_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
}

function my_admin_styles() {
wp_enqueue_style('thickbox');
}

function disable_rss_feed() {
    wp_die( __('No feeds are available, please visit our <a href="'. get_bloginfo('url') .'">website</a>!') );
}

if($options['disablerss'] == 'on')
{
    add_action('do_feed', 'disable_rss_feed', 1);
    add_action('do_feed_rdf', 'disable_rss_feed', 1);
    add_action('do_feed_rss', 'disable_rss_feed', 1);
    add_action('do_feed_rss2', 'disable_rss_feed', 1);
    add_action('do_feed_atom', 'disable_rss_feed', 1);
}
if(!function_exists('add_defaults_fn'))
{
    function add_defaults_fn() {
    	$options = get_option('password_protect_options');
        $defaults = array(
          "version"=>PLUGINVERSION,
          "enabled"=>"on",//enabled
          "password" => "password", //password
          "licensed" => "nokey",
          "logo_path" => "http://einstein.ringwoodhosting.com/_packages/_50200002/licensing/logo.png", //CBT
          "message" => __("Password:"), //CBT
          "oneforall" => "off",
          "disablerss" => "on", 
          "getheader" => "on",
          "usecookies" => "off",
          "multipass" => "off",
          "uselogs" => "off",
          "logs_id" => "10",
          "init" => "off"
        );
        $logs;
        $tmp_log = array(
            "timestamp" => 201103010000,
            "action" => "login",
            "password" => "0",
            "user_agent" => "Mozilla/5.0",
            "ip" => "192.168.1.1"
        );
        $logs[0] = $tmp_log;
        $tmp_log = array(
            "timestamp" => 201103010100,
            "action" => "login attempt",
            "password" => "",
            "user_agent" => "Mozilla/5.0",
            "ip" => "192.168.1.1"
        );
        array_push($logs, $tmp_log);
        $tmp_log = array(
            "timestamp" => 201103010200,
            "action" => "logout",
            "password" => "",
            "user_agent" => "Mozilla/5.0",
            "ip" => "192.168.1.2"
        );
        array_push($logs, $tmp_log);
        if(!is_array($options)) {//First install
    		update_option('password_protect_options', $defaults);     
    	}
        if($_GET['page'] == 'password_protection' && $_GET['reset'] == 'true') {//manual reset
    		update_option('password_protect_options', $defaults);
            update_option('password_protect_logs', $logs); 
    	}
        $options = get_option('password_protect_options');
        if($options['version'] == '3.0')//upgrade from version 3
        {
            $options['usecookies'] = "off";
            $options['multipass'] = "off";
            $options['uselogs'] = "off";
            $options['logs_id'] = "10";
            $options['init'] = 'off';
            $options['message'] = 'Password:';
            update_option('password_protect_options', $options);
            update_option('password_protect_logs', $logs);
        }
        elseif($options['version'] != PLUGINVERSION)
        {//upgrade required
            $defaults['enabled'] = $options['checkbox_1'];
            $defaults['password'] = $options['pass_1'];
            if($options["disabled_checkbox_1"] == 'on')
            {
                $defaults['licensed'] = 'license';
            }
            $defaults['logo_path'] = $options['text_1'];
            update_option('password_protect_options', $defaults);
            update_option('password_protect_logs', $logs);
        }
    }
}
global $status;
if(!function_exists('sd_authenticate'))
{
    function sd_authenticate()
    {
        global $status;
    	$options = get_option('password_protect_options');
        $siteprefix = $blog_id;
        if($options['oneforall'] == 'on')
        {
            $siteprefix = 'global';
        }
    	//check if password protection is enabled
    	if($options['enabled'] == 'off')
    	{
    		//password protection is disabled
    		$GLOBALS['sd_status'] = 1;//allow
    		return;
    	}
    	//password protection is enabled
    	//define variables
    	$GLOBALS['sd_status'] = 0;//disallow
    	$action = $_REQUEST['action'];
    	if($action == 'logout')
    	{
    		//they are logging out
            sd_addlog('Logging out','n/a');
    		$_SESSION[$siteprefix.'_sd_authenticated'] = 'false';
    		$GLOBALS['sd_status'] = 2;//display logged out message
    		$status = "logged out";
            if($options['usecookies'] == 'on')
            {
                setcookie($siteprefix.'_sd_authenticated', 'false', time()+60*60*24*14);
            }
    	}
    	//Are they logged in
    	if($_SESSION[$siteprefix.'_sd_authenticated'] == 'true')
    	{
    		//logged in
    		$GLOBALS['sd_status'] = 1;//allow
    		return;
    	}
        if($options['multipass'] != 'on')
        {
            if($_COOKIES[$siteprefix.'_sd_authenticated'] == $options['password'])
            {
                $GLOBALS['sd_status'] = 1;//allow
                setcookie($siteprefix.'_sd_authenticated', $options['password'], time()+60*60*24*14);
		        return;
            }
        }
        else
        {
            $counter = 0;
                while($counter <= 10 AND $loggedin != true)
                {
                    $counter ++;
                    if($sd_password == $options['password'.$counter] AND $tmp['password'.$counter] != '')
            		{
            			//correct password
            			$_SESSION[$siteprefix.'_sd_authenticated'] = "true";
                        $GLOBALS['sd_status'] = 1;//allow
                        setcookie($siteprefix.'_sd_authenticated', $options['password'], time()+60*60*24*14);
                        return;
                        $loggedin = true;
            		}
                }
        }
    	
    	//Display login form
    	include(dirname(__FILE__).'/login.php');
    	exit();
    }
}

//----------------------------------------------------------------
if(!function_exists('loginrequest'))
{
    function loginrequest()
    {
        global $status;
        $options = get_option('password_protect_options');
    	$sd_password = $_REQUEST['sd_password'];
    	if($sd_password != NULL AND $options['usecookies'] != 'on')
    	{
    		//WHOOAA - someone is attempting to login
            $tmp = get_option('password_protect_options');
            if($tmp['multipass'] != 'on')
            {
        		if($sd_password == $tmp['password'])
        		{
        			//correct password
        			$_SESSION[$siteprefix.'_sd_authenticated'] = "true";
                    sd_addlog('login',0);
        			echo("var url = location.href; url = url.split(\"?\", 1); location.href = url + '?login=true';");
        			echo("$('#status').html('<span style=\"color:green;\">Correct password - Please Wait...</span>');");
        			exit;
        		}
        		else
        		{
        			//incorrect password
                    sd_addlog('incorrect login','n/a');
        			echo("$('#status').html('Incorrect password')");
        			exit;
        		}
            }
            else
            {
                $counter = 0;
                while($counter <= 10 AND $loggedin != true)
                {
                    $counter ++;
                    if($sd_password == $tmp['password'.$counter] AND $tmp['password'.$counter] != '')
            		{
            			//correct password
            			$_SESSION[$siteprefix.'_sd_authenticated'] = "true";
                        sd_addlog('login',$counter);
            			echo("var url = location.href; url = url.split(\"?\", 1); location.href = url + '?login=true';");
            			echo("$('#status').html('<span style=\"color:green;\">Correct password - Please Wait...</span>');");
            			exit;
                        $loggedin = true;
            		}
                }
                if($loggedin != true)
                    {
                        sd_addlog('incorrect login','n/a');
                        echo("$('#status').html('Incorrect password')");
        			    exit;
                    }
            }
    	}
        elseif($sd_password != NULL AND $options['usecookies'] == 'on')
        {
            
            $tmp = get_option('password_protect_options');
            if($tmp['multipass'] != 'on')
            {
                
        		if($sd_password == $tmp['password'])
        		{
        			//correct password
        			$_SESSION[$siteprefix.'_sd_authenticated'] = "true";
                    setcookie($siteprefix.'_sd_authenticated', $sd_password, time()+60*60*24*14);
                    sd_addlog('login',0);
                    return;
        		}
        		else
        		{
        			//incorrect password
                    sd_addlog('incorrect login','n/a');
                    $status = 'Incorrect Password';
                    return;
        		}
            }
            else
            {
                $counter = 0;
                while($counter <= 10 AND $loggedin != true)
                {
                    $counter ++;
                    if($sd_password == $tmp['password'.$counter] AND $tmp['password'.$counter] != '')
            		{
            			//correct password
            			$_SESSION[$siteprefix.'_sd_authenticated'] = "true";
                        setcookie($siteprefix.'_sd_authenticated', $sd_password, time()+60*60*24*14);
                        sd_addlog('login',$counter);
                        $loggedin = true;
                        return;
            		}
                    
                }
                if($loggedin != true)
                    {
                        sd_addlog('incorrect login','n/a');
                        $status = 'Incorrect Password';
                        return;
                    }
            }
        }
    }
}
?>
<?php
if(!function_exists('sd_plugin_add_page_fn'))
{
    function sd_plugin_add_page_fn() {
        add_menu_page('Password Protection', 'Password Protect', 'manage_options', 'password_protection', 'sd_optionpage');
    }
}

if(!function_exists('sd_optionpage'))
{
    function sd_optionpage()
    {
        if(file_exists(dirname(__FILE__).'/options.php'))
        {
            include_once(dirname(__FILE__).'/options.php');
        }
    }
}
if(!function_exists('sd_jquery'))
{
    function sd_jquery()
    {
        wp_enqueue_script( 'jquery' );
    }
}
if(!function_exists('sd_addlog'))
{
    function sd_addlog($action = 'unknown', $password = '')
    {
        $options = get_option('password_protect_options');
        if($options['uselogs'] == 'on')
        {
            $logs = get_option('password_protect_logs');
            $timestamp = time();
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $tmp_array = array(
                "timestamp" => $timestamp,
                "action" => $action,
                "password" => $password,
                "user_agent" => $user_agent,
                "ip" => $ip
            );
            if(is_array($logs))
            {
                array_push($logs,$tmp_array);
            }
            else
            {
                $logs[0] = $tmp_array;
            }
            update_option('password_protect_logs',$logs);
        }
    }
}
function aasort (&$array, $key) {
            $sorter=array();
            $ret=array();
            reset($array);
            foreach ($array as $ii => $va) {
                $sorter[$ii]=$va[$key];
            }
            asort($sorter);
            foreach ($sorter as $ii => $va) {
                $ret[$ii]=$array[$ii];
            }
            $array=$ret;
        }
if(!function_exists('sd_showlog'))
{
    function sd_showlog()
    {
        if($_REQUEST['sd_request'] != 'logs')
        {
            return;
        }
        $logs = get_option('password_protect_logs');
        if($_REQUEST['filter_action'] == 'reset')
        {
            $logs = '';
            update_option('password_protect_logs', $logs);
        }
        if(is_array($logs))
        {
            if($_REQUEST['filter_password'] != ('none' OR ''))
            {
                $newlog = array();
                foreach($logs as $log)
                {
                    if($log['password'] == $_REQUEST['filter_password'])
                    {
                        array_push($newlog, $log);
                    }
                }
                $logs = $newlog;
            }
            if($_REQUEST['filter_ip'] != 'none')
            {
                $newlog = array();
                foreach($logs as $log)
                {
                    if($log['ip'] == $_REQUEST['filter_ip'])
                    {
                        array_push($newlog, $log);
                    }
                }
                $logs = $newlog;
            }
            if(urldecode($_REQUEST['filter_useragent']) != 'none')
            {
                $newlog = array();
                foreach($logs as $log)
                {
                    if($log['user_agent'] == urldecode($_REQUEST['filter_useragent']))
                    {
                        array_push($newlog, $log);
                    }
                }
                $logs = $newlog;
            }
            
            if(is_array($logs))
            {
                aasort($logs, 'timestamp');
                if($_REQUEST['filter_orderby'] == 'ip')
                {
                    aasort($logs, 'ip');
                }
                if($_REQUEST['filter_orderby'] == 'useragent')
                {
                    aasort($logs, 'user_agent');
                }
            }
        }
        $numresults = $_REQUEST['filter_numresults'];
        if($numresults == 'all'){$numresults = 10000;}
        $offset = $_REQUEST['filter_offset'];
        $arraymax = count($logs);
        if($numresults * $offset > $arraymax)
        {//no results
            $start = 'n/a';
            $end = 'n/a';
            $max = $arraymax;
            $response = 'No results';
        }
        else
        {
            $start = ($numresults * $offset) + 1;
            $end = $numresults * $offset + $numresults;
            if($end > $arraymax){$end = $arraymax;}
            $max = $arraymax;
            $counter = 0;
            if(is_array($logs))
            {
                foreach($logs as $log)
                {
                    $counter ++;
                    if($counter >= $start AND $counter <= $end)
                    {
                        $date = date('g:i A D d M Y', $log['timestamp']);
                        $response = $response."<tr id=\"post-$counter\" class=\"alternate author-self status-publish format-default iedit\" valign=\"top\">
                            						<td class=\"post-title page-title column-title\">$date</td>
                                                    <td class=\"author column-author\">$log[action]</td>
                            						<td class=\"categories column-categories\"><a style=\"cursor:pointer\" onclick=\"updatefilter('password','$log[password]')\" >$log[password]</a></td>
                            						<td class=\"tags column-tags\"><a style=\"cursor:pointer\" onclick=\"createfilter('useragent','$log[user_agent]')\" >$log[user_agent]</a></td>
                                                    <td class=\"tags column-tags\"><a style=\"cursor:pointer\" onclick=\"createfilter('ip','$log[ip]')\" >$log[ip]</a></td>
                            				</tr>\n";
                    }
                }
            }
        }
        echo("$start<element>$end<element>$max<element>$response");
        exit;
    }
}

?>