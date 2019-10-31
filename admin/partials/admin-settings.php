<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://madebybluejay.com
 * @since      1.0.0
 *
 * @package    Blue_mail_smtp
 * @subpackage Blue_mail_smtp/admin/partials
 */
?>
<div class="wrap"> 

    <div class="blue-mail-smtp-wrap"> 

		<h3 class="blue-mail-smtp-title"><span> Welcome to Blue Mail SMTP  </span></h3>
		
		<p class="intro-text">
			Blue Mail SMTP plugin helps you to set up custom email SMTP configurations and also test if the settings are working properly. <br>
		</p> 

        <div class= "blue-mail-smtp-form">
		
			<?php  Blue_mail_smtp_Admin::bluemail_tabs();  ?> 

		</div> <!-- #blue-mail-smtp-form ---> 
	
	</div><!-- #blue-mail-smtp-wrap ---> 

</div> <!-- #wrap end ---> 