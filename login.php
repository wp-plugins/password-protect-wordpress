<?php
$options = get_option('password_protect_options');
if($options['getheader'] == 'on')
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Login</title>
<?php
}
?>
<!--www.spiders-design.co.uk password protection ver  <?=PLUGINVERSION?> -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" language="javascript">
<?php
if($options['usecookies'] != 'on')
{?>
function dologin()	{
	var password = $('#password').val();
	if(password == "")
	{
		$('#status').html('No password entered');
	}
	else
	{
	var url = location.href
	url = url.split("?", 1);
	//alert(url);
	url = url + "?sd_password=";
	url = url + password;
	showdiv('loading');
	$.getScript(url, function() {
		hidediv('loading');
	}
	);
	}
}
<?php
}
else
{?>
function dologin()
{
    showdiv('loading');
    document.forms["login"].submit();
}
<?php }?>
function hidediv(id) {
	//safe function to hide an element with a specified id
	if (document.getElementById) { // DOM3 = IE5, NS6
		document.getElementById(id).style.display = 'none';
	}
	else {
		if (document.layers) { // Netscape 4
			document.id.display = 'none';
		}
		else { // IE 4
			document.all.id.style.display = 'none';
		}
	}
}

function showdiv(id) {
	//safe function to show an element with a specified id
		  
	if (document.getElementById) { // DOM3 = IE5, NS6
		document.getElementById(id).style.display = 'block';
	}
	else {
		if (document.layers) { // Netscape 4
			document.id.display = 'block';
		}
		else { // IE 4
			document.all.id.style.display = 'block';
		}
	}
}
</script>
<style type="text/css">
body, html
{
	background-color:#F9F9F9 !important;
}
#logo_container
{
	position:relative;
	width:auto;
	min-width:375px;
	padding:10px;
	height:250px;
	margin-left:auto;
	margin-right:auto;
	background-image:url("<?php echo($options['logo_path']); ?>");
	background-repeat:no-repeat;
	background-position:bottom;
	margin-bottom:30px;
}
.logo_container
{
	position:relative;
	width:auto;
	min-width:375px;
	padding:10px;
	height:250px;
	margin-left:auto;
	margin-right:auto;
	background-image:url("<?php echo($options['logo_path']); ?>");
	background-repeat:no-repeat;
	background-position:bottom;
	margin-bottom:30px;
}
#login_form
{
	background-color:#FFFFFF;
	-webkit-box-shadow: 0px 4px 18px #c8c8c8;
	-moz-box-shadow: 0px 4px 18px #c8c8c8;
	box-shadow: 0px 4px 18px #c8c8c8;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	width:375px;
	min-height:20px;
	margin-bottom:10px;
	padding:20px;
	margin-left:auto;
	margin-right:auto;
	border: 1px solid #E5E5E5;
}
.formtext
{
	margin-top:30px;
	font-family:Verdana, Geneva, sans-serif;
	color:#777;
	font-size:16px;
	cursor: default;
	margin-bottom:10px;
}
#password
{
	position:relative;
	top:10px;
	width:370px;
	height:40px;
	border: 1px solid #E5E5E5;
	background-color:#F9F9F9;
	font-size: 24px;
	padding:3px;
	color: #555;
	margin-bottom:20px;
}
#submit
{
	border-color: #13455B;
	background-image: url("<?php echo(WP_PLUGIN_URL);?>/password-protect-wordpress/images/button-grad.png");
	background-repeat-x: repeat;
	background-repeat-y: no-repeat;
    background-color: #21759B;
    margin-left:auto;
	margin-right:auto;
    width:100px;
    color: #EAF2FA;
    font-weight: bold;
	-webkit-border-radius: 11px;
	-moz-border-radius: 11px;
	border-radius: 11px;
	border-style: solid;
	border-width: 1px;
    text-align:center;
    cursor: pointer;
	font-family:Verdana, Geneva, sans-serif;
	font-size: 12px;
    padding: 3px;
    /*
	
	
	
    overflow: hidden;
	margin:10px;
	
	padding-top:3px;
	
    */
}
#submit:active
{
	background-image:url("<?php echo(WP_PLUGIN_URL);?>/password-protect-wordpress/images/button-grad-active.png");
}
#status
{
	color:#F00;
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
}
#loading
{
	background-image:url("<?php echo(WP_PLUGIN_URL);?>/password-protect-wordpress/images/ajax-loader.gif");
	height:16px;
	width:16px;
	float:right;
	margin-right:10px;
}
#caption
{
	font-family:Verdana, Geneva, sans-serif;
	float:right;
	font-size:12px;
	color:#666;
	font-style:italic;
}
#caption:visited
{
	color:#666;
}
<?php
if(function_exists('sd_pp_login_style'))
{
    sd_pp_login_style();
}
?>
</style>
<!--[if IE 8]>
  <style type="text/css">
  #submit
  {
    padding:0px;
  }
  </style>
<![endif]-->
</head>

<body>
	<div id="logo_container" class="logo_container" align="center">
	</div>
    
    <div id="login_form">
    	<label class="formtext">
       <?php echo($options['message']); ?>
        </label>
        <br />
        <form name="login" action="<?=site_url();?>/" method="post" onsubmit="<?php if($options['usecookies'] != 'on'){echo('return false');}?>">
        <input id="password" type="password" name="sd_password" onkeypress="if (event.keyCode == 13){dologin()}" tabindex="1" />
        
      <div id="submit" onClick="dologin()"><?=__('Login');?><div id="loading" style="display:none"></div>
        </form>
      </div>
      <div id="status">
      <?php echo($status); ?>
      </div>
      <p id="caption">
       
      </p>
      <script type="text/javascript" language="JavaScript">
    document.getElementById("password").focus();
	</script>
    </div>
</body>
</html>
