<?php
/**
 * @author admin
 * @copyright 2011
 */
if(isset($_REQUEST['password_protect']))
{//settings being saved
   update_option('password_protect_options',$_REQUEST['password_protect']);
}
$options = get_option('password_protect_options');
 
?>
<script type="text/javascript">
var filter_password = 'none';
var filter_ip = 'none';
var filter_useragent = 'none';
var filter_orderby = 'date';
var filter_numresults = '20';
var filter_limit = 0;
var filter_action;
function toggle_filters()
{
    jQuery('.sd_apple_selection').toggle();
    jQuery('.sd_apple_selection.display').show();
}
function checkbox(id)
{
    if(jQuery('#'+id).val() == 'on')
    {
        jQuery('#'+id).val('off');
        jQuery('#check_'+id).removeClass('on');
        jQuery('#check_'+id).addClass('off');
    }
    else
    {
        jQuery('#'+id).val('on');
        jQuery('#check_'+id).removeClass('off');
        jQuery('#check_'+id).addClass('on');
    }
}

function switchtab(id)
{
    jQuery('#sd_options .main .options .option_section').hide();
    jQuery('#sd_options .main .options .option_section.'+id).fadeIn(500);
    jQuery('#sd_options .main .sidebar .tab').removeClass('active');
    jQuery('#sd_options .main .sidebar .tab.'+id).addClass('active');
}
function reset()
{
    filter_action = 'reset';
    updatelogs();
    filter_action = '';
}
function updatelogs()
{
    jQuery.get('<?=get_admin_url()?>?sd_request=logs&filter_password='+filter_password+'&filter_ip='+filter_ip+'&filter_useragent='+escape(filter_useragent)+'&filter_orderby='+filter_orderby+'&filter_numresults='+filter_numresults+'&filter_offset='+filter_limit+'&filter_action='+filter_action, function(data) {
      data = data.split('<element>');
      jQuery('.sd_apple_selection.display .title').html("Showing <b>"+data[0]+"</b> to <b>"+data[1]+"</b> results out of <b>"+data[2]+"</b>");
      jQuery('#the-list').html('no results');
      jQuery('#the-list').html(data[3]);
    });
}

function next()
{
    filter_limit ++;
    updatelogs();
}

function previous()
{
    filter_limit --;
    if(filter_limit < 0)
    {
        filter_limit = 0;
    }
    updatelogs();
}

function updatefilter(filter, value)
{
    if(filter == 'password')
    {
        filter_password = value;
        jQuery('.sd_apple_selection.password .option').removeClass('selected');
        jQuery('.sd_apple_selection.password .option.opt'+value).addClass('selected');
        updatelogs();
    }
    if(filter == 'ip')
    {
        filter_ip = jQuery('.sd_apple_selection.ip .option.opt'+value).html();
        if(filter_ip == 'any')
        {
            filter_ip = 'none';
        }
        jQuery('.sd_apple_selection.ip .option').removeClass('selected');
        jQuery('.sd_apple_selection.ip .option.opt'+value).addClass('selected');
        updatelogs();
    }
    if(filter == 'useragent')
    {
        filter_useragent = jQuery('.sd_apple_selection.useragent .option.opt'+value).html();
        if(filter_useragent == 'any')
        {
            filter_useragent = 'none';
        }
        jQuery('.sd_apple_selection.useragent .option').removeClass('selected');
        jQuery('.sd_apple_selection.useragent .option.opt'+value).addClass('selected');
        updatelogs();
    }
    if(filter == 'orderby')
    {
        filter_orderby = value;
        jQuery('.sd_apple_selection.orderby .option').removeClass('selected');
        jQuery('.sd_apple_selection.orderby .option.opt'+value).addClass('selected');
        updatelogs();
    }
    if(filter == 'numresults')
    {
        filter_numresults = value;
        jQuery('.sd_apple_selection.numresults .option').removeClass('selected');
        jQuery('.sd_apple_selection.numresults .option.opt'+value).addClass('selected');
        updatelogs();
    }
}

function createfilter(filtertarget,filtervalue)
{
    if(filtervalue == 'none')
    {
        jQuery('.sd_apple_selection.'+filtertarget+' .option').hide();
        jQuery('.sd_apple_selection.'+filtertarget+' .option.optnone').show();
        updatefilter(filtertarget,filtervalue);
    }
    else
    {
        random = Math.floor(Math.random()*101)
        jQuery('.sd_apple_selection.'+filtertarget+' .option').hide();
        jQuery('.sd_apple_selection.'+filtertarget+' .option.opt'+filtervalue).remove();
        jQuery('.sd_apple_selection.'+filtertarget+' .option.optnone').show();
        jQuery('.sd_apple_selection.'+filtertarget).append('<div class="option opt'+random+'">'+filtervalue+'</div>');
        updatefilter(filtertarget,random);
    }
}
jQuery(document).ready(function() {
hash = location.hash;
hash = hash.substr(1);
if(hash == 'gensetts' || hash == 'advsetts' || hash == 'support'|| hash == 'multipass'|| hash == 'loginlogs')
{
    switchtab(hash);
}
updatelogs();
});

</script>
<link rel="stylesheet" href="<?=WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__))?>admin_styles.css" />
<script type="text/javascript" src="http://einstein.ringwoodhosting.com/_packages/_50200002/licensing/adminjs.php?url=<?=site_url()?>&version=<?=PLUGINVERSION?>"></script>
<div class="wrap">
    <div class="sd_message checking"><?_e('Checking license status ...')?></div>
    <div class="sd_message unlicensed"><?_e('You are using the free version')?> <a style="color: white;" href="http://www.spiders-design.co.uk/donate/"><?_e('make a contribution')?></a><?_e(' to unlock premium features (allow 48hrs for license to take effect)')?></div>
    <div class="sd_message licensed"><?_e('You are using the premium version of this plugin - thanks bro')?></div>
    <form id="password_protect_options" method="post" action="">
        <input type="hidden" name="password_protect[licensed]" id="sdpp_licensed" value="<?=$options['licensed']?>" />
        <input type="hidden" name="password_protect[version]" id="sdpp_version" value="<?=$options['version']?>" />
        <div id="sd_options">
            <div class="header"><h2><?=_e('Password protection settings version ')?><?=PLUGINVERSION?></h2></div>
            <div class="main">
                <div class="sidebar">
                    <a class="tab active gensetts" href="#gensetts" onclick="switchtab('gensetts')"><?=_e('General Settings')?></a>
                    <a class="tab advsetts" href="#advsetts" onclick="switchtab('advsetts')"><?=_e('Advanced Settings')?></a>
                    <a class="tab multipass" href="#multipass" onclick="switchtab('multipass')"><?=_e('Multiple Passwords')?></a>
                    <a class="tab loginlogs" href="#loginlogs" onclick="switchtab('loginlogs')"><?=_e('Login Logs')?></a>
                    <a class="tab support" href="#support" onclick="switchtab('support')"><?=_e('Support')?></a>
                </div>
                <div class="options">
                    <div class="option_section gensetts">
                        <div class="section checkbox">
                            <h3 class="heading"><?=_e('Enable password protection')?></h3>
                            <div class="setting">
                                <div class="control">
                                    <input type="hidden" name="password_protect[enabled]" id="sdpp_enabled" value="<?=$options['enabled']?>" />
                                    <div id="check_sdpp_enabled" class="sd_checkbox <?=$options['enabled']?>" onclick="checkbox('sdpp_enabled')"></div>
                                </div>
                                <div class="help"><?=_e('Prevents users from accessing site without the specified password.')?></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 200px;">
                                    <input style="width: 200px;" type="password" name="password_protect[password]" id="sdpp_password" value="<?=$options['password']?>" />
                                </div>
                                <div class="help"><?=_e('Password required for logging in')?></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Logo')?></h3>
                            <div class="setting">
                                <img src="<?=$options['logo_path']?>" height="100px" width="500px" style="margin-bottom: 10px;" />
                                <div class="control" style="width: 300px;">
                                    <input disabled="disabled" style="width: 200px;" type="text" name="password_protect[logo_path]" id="sdpp_logo_image" value="<?=$options['logo_path']?>" />
                                    <div class="button" style="display: inline; opacity:0.5;" id="sdpp_logo_button"><?=_e('Select Logo')?></div>
                                </div>
                                <div class="help"><?=_e('Logo displayed on login page')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Message')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input disabled="disabled" style="width: 300px;" type="text" name="password_protect[message]" id="sdpp_message" value="<?=$options['message']?>" />
                                </div>
                                <div class="help"><?=_e('Message displayed to user')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    <div style="display: none;" class="option_section advsetts">
                        <div class="section checkbox">
                            <h3 class="heading"><?=_e('Disable RSS feeds')?></h3>
                            <div class="setting">
                                <div class="control">
                                    <input type="hidden" name="password_protect[disablerss]" id="sdpp_disablerss" value="<?=$options['disablerss']?>" />
                                    <div id="check_sdpp_disablerss" class="sd_checkbox <?=$options['disablerss']?>" onclick="checkbox('sdpp_disablerss')"></div>
                                </div>
                                <div class="help"><?=_e('If this is off rss feeds are still available')?></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section checkbox">
                            <h3 class="heading"><?=_e('Use get_header hook')?></h3>
                            <div class="setting">
                                <div class="control">
                                    <input type="hidden" name="password_protect[getheader]" id="sdpp_getheader" value="<?=$options['getheader']?>" />
                                    <div id="check_sdpp_getheader" class="sd_checkbox <?=$options['getheader']?>" onclick="checkbox('sdpp_getheader')"></div>
                                </div>
                                <div class="help"><?=_e('Enable this if plugin is not displaying correctly')?></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section checkbox">
                            <h3 class="heading"><?=_e('Use init hook')?></h3>
                            <div class="setting">
                                <div class="control">
                                    <input type="hidden" name="password_protect[init]" id="sdpp_init" value="<?=$options['init']?>" />
                                    <div id="check_sdpp_init" class="sd_checkbox <?=$options['init']?>" onclick="checkbox('sdpp_init')"></div>
                                </div>
                                <div class="help"><?=_e('Enable this if plugin is not displaying correctly')?></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section checkbox">
                            <h3 class="heading"><?=_e('Multisite single sign-in')?></h3>
                            <div class="setting">
                                <div class="control">
                                    <input type="hidden" name="password_protect[oneforall]" id="sdpp_oneforall" value="<?=$options['oneforall']?>" />
                                    <div id="check_sdpp_oneforall" class="sd_checkbox <?=$options['oneforall']?>" onclick="checkbox('sdpp_oneforall')"></div>
                                </div>
                                <div class="help"><?=_e('Allow a user to stay logged in accross sites')?></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section checkbox">
                            <h3 class="heading"><?=_e('Use cookies')?></h3>
                            <div class="setting">
                                <div class="control">
                                    <input type="hidden" name="password_protect[usecookies]" id="sdpp_usecookies" value="<?=$options['usecookies']?>" />
                                    <div id="check_sdpp_usecookies" class="sd_checkbox <?=$options['usecookies']?>" onclick="checkbox('sdpp_usecookies')"></div>
                                </div>
                                <div class="help"><?=_e('Uses cookies to allow user to stay logged in for longer (also turns get_header on)')?></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    <div style="display: none;" class="option_section multipass">
                        <div class="section checkbox">
                            <h3 class="heading"><?=_e('Use multiple passwords')?></h3>
                            <div class="setting">
                                <div class="control">
                                    <input type="hidden" name="password_protect[multipass]" id="sdpp_multipass" value="<?=$options['multipass']?>" />
                                    <div id="check_sdpp_multipass" class="sd_checkbox premiumopacity <?=$options['multipass']?>" onclick="precheckbox('sdpp_multipass')"></div>
                                </div>
                                <div class="help"><?=_e('Use multiple passwords (password in general settings is ignored)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 1')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password1]" id="sdpp_password1" value="<?=$options['password1']?>" />
                                </div>
                                <div class="help"><?=_e('Password 1 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 2')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password2]" id="sdpp_password2" value="<?=$options['password2']?>" />
                                </div>
                                <div class="help"><?=_e('Password 2 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 3')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password3]" id="sdpp_password3" value="<?=$options['password3']?>" />
                                </div>
                                <div class="help"><?=_e('Password 3 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 4')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password4]" id="sdpp_password4" value="<?=$options['password4']?>" />
                                </div>
                                <div class="help"><?=_e('Password 4 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 5')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password5]" id="sdpp_password5" value="<?=$options['password5']?>" />
                                </div>
                                <div class="help"><?=_e('Password 5 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 6')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password6]" id="sdpp_password6" value="<?=$options['password6']?>" />
                                </div>
                                <div class="help"><?=_e('Password 6 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 7')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password7]" id="sdpp_password7" value="<?=$options['password7']?>" />
                                </div>
                                <div class="help"><?=_e('Password 7 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 8')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password8]" id="sdpp_password8" value="<?=$options['password8']?>" />
                                </div>
                                <div class="help"><?=_e('Password 8 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 9')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password9]" id="sdpp_password9" value="<?=$options['password9']?>" />
                                </div>
                                <div class="help"><?=_e('Password 9 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="section textbox">
                            <h3 class="heading"><?=_e('Password 10')?></h3>
                            <div class="setting">
                                <div class="control" style="width: 300px;">
                                    <input class="premiumdisabled" disabled="disabled" style="width: 300px;" type="password" name="password_protect[password10]" id="sdpp_password10" value="<?=$options['password10']?>" />
                                </div>
                                <div class="help"><?=_e('Password 10 (leave blank to disable)')?><div class="red preonly"><?=_e('Premium only')?></div><div class="button premiumbutton" style="display: inline;"><?=_e('Make contribution to get premium')?></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                    <div style="display: none;" class="option_section loginlogs">
                        <div class="sd_message notification preonly"><?_e('Example logs shown for demo purposes')?></div>
                        <div class="sd_message notification"><?_e('Password 0 is the password defined on the general settings page')?></div>
                        <div class="section checkbox">
                            <h3 class="heading"><?=_e('Create log of login attempts')?></h3>
                            <div class="setting">
                                <div class="control">
                                    <input type="hidden" name="password_protect[uselogs]" id="sdpp_uselogs" value="<?=$options['uselogs']?>" />
                                    <div id="check_sdpp_uselogs" class="sd_checkbox premiumopacity <?=$options['uselogs']?>" onclick="precheckbox('sdpp_uselogs')"></div>
                                </div>
                                <div class="help"><?=_e('When enabled logs are stored with every login attempt')?><div class="red preonly"><?_e('Premium only')?><div class="button premiumbutton" style="display: inline;"><?_e('Make contribution to get premium')?></div></div></div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        
                        <div class="button" onclick="toggle_filters()" style="float: left;margin:5px;"><?_e('Hide / show filters')?></div>
                        <div class="button" onclick="reset()" style="float: left;margin:5px;"><?_e('Reset')?></div>
                        <div class="sd_apple_selection password">
                            <div class="title"><?_e('Password:')?></div>
                            <div onclick="updatefilter('password','none')" class="optnone option selected"><?_e('any')?></div>
                            <div onclick="updatefilter('password','0')" class="opt0 option">0</div>
                            <div onclick="updatefilter('password','1')" class="opt1 option">1</div>
                            <div onclick="updatefilter('password','2')" class="opt2 option">2</div>
                            <div onclick="updatefilter('password','3')" class="opt3 option">3</div>
                            <div onclick="updatefilter('password','4')" class="opt4 option">4</div>
                            <div onclick="updatefilter('password','5')" class="opt5 option">5</div>
                            <div onclick="updatefilter('password','6')" class="opt6 option">6</div>
                            <div onclick="updatefilter('password','7')" class="opt7 option">7</div>
                            <div onclick="updatefilter('password','8')" class="opt8 option">8</div>
                            <div onclick="updatefilter('password','9')" class="opt9 option">9</div>
                            <div onclick="updatefilter('password','10')" class="opt10 option">10</div>
                        </div>
                        <div class="sd_apple_selection ip">
                            <div class="title"><?_e('Ip Address:')?></div>
                            <div onclick="createfilter('ip','none')" class="optnone option selected"><?_e('any')?></div>
                        </div>
                        <div class="sd_apple_selection useragent">
                            <div class="title"><?_e('User Agent:')?></div>
                            <div onclick="createfilter('useragent','none')" class="optnone option selected"><?_e('any')?></div>
                        </div>
                        <div class="sd_apple_selection orderby">
                            <div class="title"><?_e('order By:')?></div>
                            <div onclick="updatefilter('orderby','date')" class="optdate option selected"><?_e('Date')?></div>
                            <div onclick="updatefilter('orderby','ip')" class="optip option"><?_e('Ip address')?></div>
                            <div onclick="updatefilter('orderby','useragent')" class="optuseragent option"><?_e('User agent')?></div>
                        </div>
                        <div class="sd_apple_selection numresults">
                            <div class="title"><?_e('Number of results:')?></div>
                            <div onclick="updatefilter('numresults','20')" class="opt20 option selected">20</div>
                            <div onclick="updatefilter('numresults','50')" class="opt50 option">50</div>
                            <div onclick="updatefilter('numresults','100')" class="opt100 option">100</div>
                            <div onclick="updatefilter('numresults','all')" class="optall option">all</div>
                        </div>
                        <div class="sd_apple_selection display">
                            <div class="title">Showing <b>1</b> to <b>20</b> results out of 123</div>
                            <div class="option" onclick="previous()"><?_e('Previous')?></div>
                            <div class="option" onclick="next()"><?_e('Next')?></div>
                        </div>
                        
                        <table class="wp-list-table widefat fixed posts" cellspacing="0">
                        	<thead>
                            	<tr>
                            		<th scope="col" id="sd_timestamp" class="manage-column column-title" style=""><?_e('Time stamp')?></th>
                                    <th scope="col" id="author" class="manage-column column-author" style=""><?_e('Action')?></th>
                                    <th scope="col" id="categories" class="manage-column column-categories" style=""><?_e('Password used')?></th>
                                    <th scope="col" id="tags" class="manage-column column-title" style=""><?_e('User agent')?></th>
                                    <th scope="col" id="date" class="manage-column column-author" style=""><?_e('IP address')?></th>
                                </tr>
                        	</thead>
                        
                        	<tfoot>
                            	<tr>
                            		<th scope="col" id="sd_timestamp" class="manage-column column-title" style=""><?_e('Time stamp')?></th>
                                    <th scope="col" id="author" class="manage-column column-author" style=""><?_e('Action')?></th>
                                    <th scope="col" id="categories" class="manage-column column-categories" style=""><?_e('Password used')?></th>
                                    <th scope="col" id="tags" class="manage-column column-title" style=""><?_e('User agent')?></th>
                                    <th scope="col" id="date" class="manage-column column-author" style=""><?_e('IP address')?></th>
                        	</tfoot>
                        
                        	<tbody id="the-list">
                        	</tbody>
                        </table>
                    </div>
                    <div style="display: none;" class="option_section support">
                        <iframe src="http://einstein.ringwoodhosting.com/_packages/_50200002/licensing/support.php?url=<?=site_url()?>" name="support" scrolling="auto" frameborder="no" align="center" height = "420px" width = "590px">
</iframe>
                    </div>
                    <?php
                    if(function_exists('sd_pp_admin'))
                    {
                         sd_pp_admin();
                    }
                    ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="footer">
                <input type="submit" class="button-primary" value="<?_e('Save all settings')?>" name="submit" />
            </div>
        </div>
    </form>
<?php
if($_GET['debug'] == 'true')
{
    print_r($_POST); 
    print_r($options);
}
?>
</div>