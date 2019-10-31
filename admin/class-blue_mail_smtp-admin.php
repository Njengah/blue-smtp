<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://madebybluejay.com
 * @since      1.0.0
 *
 * @package    Blue_mail_smtp
 * @subpackage Blue_mail_smtp/admin
 */


class Blue_mail_smtp_Admin {

	private $plugin_name;
	private $version;
	private $phpmailer_error;


	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'phpmailer_init', array( $this, 'configure_bluemail_smtp_settings' ) );
		add_action( 'admin_init', array( $this, 'send_testing_email' ) );
		
	}
	

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blue_mail_smtp-admin.css', array(), $this->version, 'all' );

	}

	
	public function enqueue_scripts() {	

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blue_mail_smtp-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	
    public function blue_mail_smtp_add_admin_page() {
		
	 add_options_page ( __('Blue Mail SMTP', 'blue-mail-smtp'), __('Blue Mail SMTP', 'blue-mail-smtp'),'manage_options', 'blue_mail_smtp', array($this,'blue_mail_smtp_admin_page' ) );
		
    }
	
	public function blue_mail_smtp_admin_page(){
		
		include plugin_dir_path(__FILE__) . 'partials/admin-settings.php';
	}
	
	
   /**
     * Saving admin setting options
     */
	 
    public function save_bluemail_smtp_settings() {
       $options     = get_option('blue_mail_smtp_options_settings');
        update_option('blue_mail_smtp_options_settings', $options);
		
		$options    = get_option('blue_mail_email_testing_settings');
       update_option('blue_mail_email_testing_settings', $options );
    }

		
	public function register_blue_mail_SMTP_fields(){
			 
	  /**
		*  Register the section for general SMTP fields  
		*/ 	 
		 
		 register_setting('blue_mail_smtp_options', 'blue_mail_smtp_options_settings'); // add sanitization 
		 
		 
		//General Setting Section 
		
		 add_settings_section(
                'blue_mail_smtp_general settings_section', __('General Settings ', 'blue-mail-smtp'), array($this, 'blue_mail_smtp_general_settings'), 'blue_mail_smtp_options'
        );
		
		//From email field 
		
		add_settings_field (
				'from_email_address',    __( 'From Email Address', 'blue-mail-smtp' ), array ( $this, 'from_email_address_render' ), 'blue_mail_smtp_options','blue_mail_smtp_general settings_section'
		);
		
		//From name - email sender's name 
		
		
		add_settings_field (
				'from_name',    __( 'From Name', 'blue-mail-smtp' ), array ( $this, 'from_name_render' ), 'blue_mail_smtp_options','blue_mail_smtp_general settings_section'
		);
		
		
		//SMTP Host 
		
		add_settings_field (
				'smtp_host',    __( 'SMTP Host', 'blue-mail-smtp' ), array ( $this, 'smtp_host_render' ), 'blue_mail_smtp_options','blue_mail_smtp_general settings_section'
		);
		
		// Type of Encryption   
		add_settings_field(
                'encryption_type', __('Encryption Type', 'blue-mail-smtp'), array($this, 'encryption_type_render'), 'blue_mail_smtp_options', 'blue_mail_smtp_general settings_section'
        );
		 
		//SMTP Port 
		
		add_settings_field (
				'smtp_port',    __( 'SMTP Port', 'blue-mail-smtp' ), array ( $this, 'smtp_port_render' ), 'blue_mail_smtp_options','blue_mail_smtp_general settings_section'
		);
		
		
		// SMTP Authentication  
		add_settings_field(
                'smtp_authentication', __('SMTP Authentication', 'blue-mail-smtp'), array($this, 'smtp_authentication_render'), 'blue_mail_smtp_options', 'blue_mail_smtp_general settings_section'
        );
		
		
		//SMTP Username  
		
		add_settings_field (
				'smtp_username',    __( 'SMTP Username ', 'blue-mail-smtp' ), array ( $this, 'smtp_username_render' ), 'blue_mail_smtp_options','blue_mail_smtp_general settings_section'
		);
		
		//SMTP Password 
		
		add_settings_field (
				'smtp_password',    __( 'SMTP Password', 'blue-mail-smtp' ), array ( $this, 'smtp_password_render' ), 'blue_mail_smtp_options','blue_mail_smtp_general settings_section'
		);
		
		
		
		 
	
	$options = get_option('blue_mail_smtp_options_settings');
	
	
	}
	
	public function register_email_testing_fields(){
		
		
	register_setting('blue_mail_smtp_testing', 'blue_mail_email_testing_settings'); // add sanitization 

	
	/**
	 *  Register the section for testing SMTP email settings 
	 */ 
		
		add_settings_section(
                'blue_mail_smtp_email_section', __('Testing Email ', 'blue-mail-smtp'), array($this, 'blue_mail_smtp_email_fields'), 'blue_mail_smtp_testing'
        );
		 
		 //TO* Email Name 
		 add_settings_field (
				'to_name_field', __( ' To Name', 'blue-mail-smtp' ), array ( $this, 'to_name_field_render' ), 'blue_mail_smtp_testing','blue_mail_smtp_email_section'
		);
		
		 //TO* Email Address
		 
		add_settings_field (
				'to_email_field', __( 'To Email', 'blue-mail-smtp' ), array ( $this, 'to_email_field_render' ), 'blue_mail_smtp_testing','blue_mail_smtp_email_section'
		);
		
		//TO* Email Title 
		add_settings_field (
				'email_subject_field', __( 'Email Subject', 'blue-mail-smtp' ), array ( $this, 'email_subject_field_render' ), 'blue_mail_smtp_testing','blue_mail_smtp_email_section'
		);
		
		//From* Email Message (body)
		add_settings_field(
		        'blue_mail_message',  __( 'Email Message', 'blue-mail-smtp' ), array($this,'blue_mail_message_render'),'blue_mail_smtp_testing' ,'blue_mail_smtp_email_section'
	    );
			
	$options = get_option('blue_mail_email_testing_settings');
		
	}
	
	
	
	 
	/**
	 *  Settings API Sections 
	 *  
	 *  Sections callback methods 
	 *  
	 */
	 
	
	   // Tab One - General SMTP Settings Section 
	   
		public function blue_mail_smtp_general_settings(){
			
			echo  '<p>' . __('Set the basic SMTP fields here', 'blue-mail-smtp') . '</p>' . '</br>';
		}
		

		// Tab Two  - Email Testing Section 
		
		public function blue_mail_smtp_email_fields(){
			
			echo '<p>' . __('Send a test email to see if your settings are working properly', 'blue-mail-smtp') . '</p>' . '</br>';
		}
		
		
   /**
	 *  SMTP General Settings Tab 
	 *  
	 *  Callback methods 
	 *  
	 */
	
	
		public function from_email_address_render(){
				
			$options = get_option('blue_mail_smtp_options_settings'); ?> 
			
			<input type="text" class="bluemail-smtp-textfield" name="blue_mail_smtp_options_settings[from_email_address]" value="<?php echo esc_attr( $options['from_email_address'] ); ?>">
			<p class="description"><?php _e( 'Add the email address to be used in the *From* email field', 'blue-mail-smtp' ); ?></p>	
				
		<?php 	
		}

	   public function from_name_render(){
		   
		   $options = get_option('blue_mail_smtp_options_settings'); ?> 
		   
		   <input type="text" class="bluemail-smtp-textfield" name="blue_mail_smtp_options_settings[from_name]" value="<?php echo esc_attr( $options['from_name'] ); ?>">
			<p class="description"><?php _e( 'Add the name to be used in the *From* name field', 'blue-mail-smtp' ); ?></p>			   
		   
		<?php    
		   }

	   public function smtp_host_render(){
		   
		   $options = get_option('blue_mail_smtp_options_settings'); ?>

		   <input type="text" class="bluemail-smtp-textfield" name="blue_mail_smtp_options_settings[smtp_host]" value="<?php echo esc_attr( $options['smtp_host'] ); ?>">
			<p class="description"><?php _e( 'Add your email server', 'blue-mail-smtp' ); ?></p>	
		   
	  <?php
	  }
	   
	   public function encryption_type_render(){
		   $options = get_option('blue_mail_smtp_options_settings'); ?>
		   
			<input type="radio" name="blue_mail_smtp_options_settings[encryption_type]"  <?php echo ( isset($options['encryption_type'])  && $options['encryption_type'] == 1 ? "checked" : "" ); ?> value="1" >None
			<input type="radio" name="blue_mail_smtp_options_settings[encryption_type]"  <?php echo ( isset($options ['encryption_type']) && $options['encryption_type'] == 2 ? "checked" : "" ); ?> value="2" >TLS
			<input type="radio" name="blue_mail_smtp_options_settings[encryption_type]"  <?php echo ( isset($options ['encryption_type']) && $options['encryption_type'] == 3 ? "checked" : "" ); ?> value="3" >SSL
			
			<p class="description"><?php _e('Choose the type of encryption (SSL recommended)   ', 'settings-api-starter-kit'); ?></p>
		   
	<?php   
	   }


	 public function smtp_port_render(){
		 $options = get_option('blue_mail_smtp_options_settings'); ?>

		<input type="text" class="bluemail-smtp-textfield" name="blue_mail_smtp_options_settings[smtp_port]" value="<?php echo esc_attr( $options['smtp_port'] ); ?>">
		<p class="description"><?php _e( 'Add the port to your email server', 'blue-mail-smtp' ); ?></p>	
	
	
	<?php    
	 }
	   
			   
		public function smtp_authentication_render(){
			   
			   
			  $options = get_option('blue_mail_smtp_options_settings'); ?>
			   
				<input type="radio" name="blue_mail_smtp_options_settings[smtp_authentication]"  <?php echo ( isset($options['smtp_authentication'])  && $options['smtp_authentication'] == 1 ? "checked" : "" ); ?> value="1" >Yes
				<input type="radio" name="blue_mail_smtp_options_settings[smtp_authentication]"  <?php echo ( isset($options ['smtp_authentication']) && $options['smtp_authentication'] == 2 ? "checked" : "" ); ?> value="2" >No
				
				
				<p class="description"><?php _e('   ', 'settings-api-starter-kit'); ?></p>
		 
		<?php 		   
			   }
			   
		   public function smtp_username_render(){
			   
			   $options = get_option('blue_mail_smtp_options_settings'); ?>

				<input type="text" class="bluemail-smtp-textfield" name="blue_mail_smtp_options_settings[smtp_username]" value="<?php echo esc_attr( $options['smtp_username'] ); ?>">
				<p class="description"><?php _e( 'Username to login into your email server', 'blue-mail-smtp' ); ?></p>	
		
		<?php	   
		   }
			
		   public function smtp_password_render(){
			   
			   $options = get_option('blue_mail_smtp_options_settings'); ?>

				<input type="password" id="passinput" class="bluemail-smtp-textfield" name="blue_mail_smtp_options_settings[smtp_password]" value="<?php echo esc_attr( $options['smtp_password'] ); ?>">
				 <span  id="password-icon" class="dashicons dashicons-hidden"></span>
				<p class="description"><?php _e( 'Password to login into your email server', 'blue-mail-smtp' ); ?></p>	
			
			
			<?php    
		   }
	   
	   
	   
	/**
	 *  SMTP Email Testing Tab 
	 *  
	 *  Callback methods 
	 *  
	 */
		
		public function to_name_field_render(){
			
			$options = get_option('blue_mail_email_testing_settings');
			
			?> 
			
				<input type="text" class="bluemail-smtp-textfield" name="blue_mail_email_testing_settings[to_name_field]" value="<?php echo esc_attr( $options['to_name_field'] ); ?>">
				<p class="description"><?php _e( 'Name of the recipient of the test email.', 'blue-mail-smtp' ); ?></p>	
			
		<?php
		}
	
		public function to_email_field_render(){
			
			$options = get_option('blue_mail_email_testing_settings');
			
			?> 
			
				<input type="text" class="bluemail-smtp-textfield" name="blue_mail_email_testing_settings[to_email_field]" value="<?php echo esc_attr( $options['to_email_field'] ); ?>">
				<p class="description"><?php _e( 'Email address of the recipient of the test email.', 'blue-mail-smtp' ); ?></p>	
			
		<?php
		}
		
		public function email_subject_field_render(){
			
			$options = get_option('blue_mail_email_testing_settings');
			
			?> 
			
				<input type="text" class="bluemail-smtp-textfield" name="blue_mail_email_testing_settings[email_subject_field]" value="<?php echo esc_attr( $options['email_subject_field'] ); ?>">
				<p class="description"><?php _e( 'Add the subject of the email', 'blue-mail-smtp' ); ?></p>	
			
		<?php		
		}
		
		public function blue_mail_message_render(){
			
			$options = get_option('blue_mail_email_testing_settings');
			$value = $options['blue_mail_message'] ;      
			

				$editor_id = 'bluemail_reset_lost_message';
				$settings = array(
					'media_buttons' => false,
					'tinymce'=> array(
						'toolbar1' => 'bold,italic,link,undo,redo',
						'toolbar2'=> false
					),
					'quicktags' => array( 'buttons' => 'strong,em,link,close' ),
					'editor_class' => 'required',
					'teeny' => true,
					'editor_height' => 150,
					'textarea_name' => 'blue_mail_email_testing_settings[blue_mail_message]',
				);
				$content = stripslashes( $value );

				wp_editor( $content, $editor_id, $settings );?> 

			<p class="description"><?php _e( 'Body message of the test email.', 'blue-mail-smtp' ); ?></p>
		
		<?php
		}
	
	/**
	 *  Admin Settings Tabs  
	 *  
	 */

		public function bluemail_tabs(){
		 
			// Creating Settings Tabs 
			$current = isset($_GET['tab']) ? $_GET['tab'] : 'options';
			$tabs    = array(
				'options' => __('SMTP Settings', 'blue-mail-smtp'),
				'testing' => __('Email Testing', 'blue-mail-smtp')
			);
			 
			 ?>
			 
			 
			 
			 <h3 class="nav-tab-wrapper">
					<?php
					foreach ($tabs as $tab => $name)
					{
						$class = ( $tab == $current ) ? ' nav-tab-active' : '';
						echo "<a class='nav-tab$class' href='?page=blue_mail_smtp&tab=$tab'>$name</a>";			
					}
					?>
				</h3>	
				<?php 
			if($current=="options"){ ?>		
				<form action='options.php' method='post'>
				   <?php
						settings_fields('blue_mail_smtp_' . strtolower($current)); 
						do_settings_sections('blue_mail_smtp_'. strtolower($current));		
						submit_button();										
					?>
			     </form>
			<?php 		
					
				}else {
					?>
					
			<form action='' id="emailtesting" method='post'>	
				<table class ="blue-send-table">
					<tr><td> <p> <strong> To Email</strong></p>  </td>			
						<td class="to-email-recipient">  <input type="text" class="bluemail-emailtest-textfield" name="emailrecipient" value="">
							<p class="description"><?php _e( 'Change an email address a test email will be sent to.', 'blue-mail-smtp' ); ?></p>	
								
								<?php submit_button('Send Email', 'primary', 'send');?>
							
						</td>
					</tr>
				</table>  
			</form>	
               <h2> </h2> 		<!-- Do not remove this empty tag since it purposely placed here to mark the begining of error log admin_notice---> 	
				<?php 	
				}
		}	
	 
	 public function configure_bluemail_smtp_settings($phpmailer) {
		 
		 global $phpmailer;
		 
		 if ( ! is_object( $phpmailer ) || ! is_a( $phpmailer, 'PHPMailer' ) ) {
			require_once ABSPATH . WPINC . '/class-phpmailer.php';
			$phpmailer = new PHPMailer( true );
		}
		 
		 $bluesmtp_creds = get_option('blue_mail_smtp_options_settings');
 
		 $smtphost = $bluesmtp_creds['smtp_host']; 
		 $authent  = $bluesmtp_creds['smtp_authentication'];
		 $smtpport = $bluesmtp_creds['smtp_port'];
		 $username = $bluesmtp_creds['smtp_username'];
		 $password = $bluesmtp_creds['smtp_password'];
		 $fromname = $bluesmtp_creds['from_name']; 
		 $fromemail= $bluesmtp_creds['from_email_address'];
		 $encryption= $bluesmtp_creds['encryption_type'];
		 
		// Set the Default mail sending option (SMTP)
		$phpmailer->isSMTP();
		
		//Debug
		$phpmailer-> SMTPDebug = 2;

		// The HOST address of the SMTP mail server i.e. mail.mydomain.com or server's IP 
		$phpmailer->Host = $smtphost;

		// Use authentication by SMTP (true|false)
		if($authent== 1){	
           $phpmailer->SMTPAuth = true;    				 
		 }else{
			$phpmailer->SMTPAuth = false;  
		 }
		
		// SMTP Port - Example 25, 465 o 587
		$phpmailer->Port = $smtpport;

		// Username of sender 
		$phpmailer->Username = $username;

		// Password of sender 
		$phpmailer->Password = $password;
		
		//Debug
		$phpmailer-> SMTPDebug = 4;

		// The type of encryption that we use when connecting
		
			if($encryption== 2){
				
				$phpmailer->SMTPSecure = "tls";
			
			}elseif($encryption== 3){		
				$phpmailer->SMTPOptions = array(
					'ssl' => array(
						'verify_peer'       => false,
						'verify_peer_name'  => false,
						//'allow_self_signed' => true
					)
				);
			}else{
				$phpmailer->SMTPAutoTLS = false;
				$phpmailer->SMTPSecure = false;			
			}
		 
			

			$phpmailer->From     =  $fromemail;
			$phpmailer->FromName =  $fromname;
			
		
			
		
	}

			
	public function send_testing_email(){
		
		 global $phpmailer;
		 
		 if ( ! is_object( $phpmailer ) || ! is_a( $phpmailer, 'PHPMailer' ) ) {
			require_once ABSPATH . WPINC . '/class-phpmailer.php';
			$phpmailer = new PHPMailer( true );
		}
			
			$bluesmtp_creds = get_option('blue_mail_smtp_options_settings');
			
			//Sending the Test Mail
			
		if(isset($_POST['send'])) {
			
			     $to_email =  $_POST['emailrecipient'];
				 $sendfrom =  esc_attr( $bluesmtp_creds['from_email_address'] );
				 $subject  =  "Test Email from Blue SMTP Plugin  ";
				 $message  =  "Hello, <br> This is a test email from the Blue SMTP Plugin.";	
				 $status = false;
				 $class = 'error';
				 
			
			// Start output buffering to grab smtp debugging output.
			ob_start();

			// Send the test mail.
			
			 if (!empty( $to_email ) ) {
				 
				 try {
				 
						$result = wp_mail( $to_email, $subject, $message );
				    
					}catch (exception $e) {
							
						$status = $e->getMessage();
					}
				} else{		
				
					  $status = __( 'Email field is empty, please add the recipient email and try again', 'blue-mail-smtp' );
				}
				
			//Displaying the error report 
				
				if ( !$status ) {
					
					if ( $result === true ) {
					$status = __( 'Email Sent Successfully!', 'blue-mail-smtp' );
					$class = 'success';
					
				} elseif( !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {   
					
					 $status = __( 'Please enter a valid email and try again', 'blue-mail-smtp' );
				 
				 }else{
				   
				   $status = __( 'Email Sending Failed : Check Your SMTP settings and the debug log then try again!', 'blue-mail-smtp' );
				
			   }
			   
			}	
			
			
			// Grab the smtp debugging output.
			$smtp_debug = ob_get_clean();
			
			
			ob_start(); 
				echo '<div id="message" class="notice notice-' . $class . ' is-dismissible"><p><strong>' . $status . '</strong></p></div>';
			echo ob_get_clean();
			
			if(!empty( $to_email ) && filter_var($to_email, FILTER_VALIDATE_EMAIL)){
			
			
			// Output the response.
				
			?>
			<div id="message" class="error notice is-dismissible"><p><strong><?php esc_html_e( 'Test Email Debug Log', 'blue-mail-smtp' ); ?></strong></p>
				
				<p><strong><?php esc_html_e( 'Response:', 'blue-mail-smtp' ); ?></strong></p>
					
					<pre><?php var_dump( $result ); ?></pre>

				<p><strong><?php esc_html_e( 'Basic SMTP debugging output ', 'blue-mail-smtp' ); ?></strong></p>
				
					<pre><?php echo $smtp_debug; ?></pre>
				
				<p><strong><?php esc_html_e( 'Full debugging output', 'blue-mail-smtp' ); ?></strong></p>
				
			<pre><?php 
					$debug_array = array(
						'action_function' => $phpmailer->action_function,
						'Version' => $phpmailer->Version,
						'Priority' => $phpmailer->Priority,
						'CharSet' => $phpmailer->CharSet,
						'ContentType' => $phpmailer->ContentType,
						'Encoding' => $phpmailer->Encoding,
						'ErrorInfo' => $phpmailer->ErrorInfo,
						'From' => $phpmailer->From,
						'FromName' => $phpmailer->FromName,
						'Sender' => $phpmailer->Sender,
						'ReturnPath' => $phpmailer->ReturnPath,
						'Subject' => $phpmailer->Subject,
						'Body' => $phpmailer-> Body,
						'AltBody' => $phpmailer->AltBody,
						'Ical' => $phpmailer->Ical,
						'WordWrap' => $phpmailer->WordWrap,
						'Mailer' => $phpmailer->Mailer, 
						'Sendmail' => $phpmailer->Sendmail,
						'UseSendmailOptions' => $phpmailer->UseSendmailOptions, 
						'PluginDir' => $phpmailer->PluginDir,
						'ConfirmReadingTo' => $phpmailer->ConfirmReadingTo,
						'Hostname' => $phpmailer->Hostname, 
						'MessageID' => $phpmailer->MessageID,
						'MessageDate' => $phpmailer->MessageDate,
						'Host' =>$phpmailer->Host,
						'Port' => $phpmailer->Port,
						'SMTPSecure' => $phpmailer->SMTPSecure,
						'SMTPAutoTLS' => $phpmailer->SMTPAutoTLS,
						'SMTPAuth' => $phpmailer->SMTPAuth,  
						'SMTPOptions' => $phpmailer->SMTPOptions,
						'Username' => $phpmailer->Username,
						'Password' => $phpmailer->Password,
						'AuthType' => $phpmailer->AuthType,
						'Realm' => $phpmailer->Realm,
						'Workstation' => $phpmailer->Workstation,
						'Timeout' => $phpmailer->Timeout,
						'SMTPDebug' => $phpmailer->SMTPDebug,
						'Debugoutput' => $phpmailer->Debugoutput,
						'SMTPKeepAlive' => $phpmailer->SMTPKeepAlive,
						'SingleTo' =>$phpmailer-> SingleTo,
						'SingleToArray' =>$phpmailer->SingleToArray, 
						'do_verp' =>$phpmailer-> do_verp,
						'AllowEmpty' =>$phpmailer-> AllowEmpty,
						'LE' => $phpmailer-> LE,
						'DKIM_selector' => $phpmailer->DKIM_selector, 
						'DKIM_identity' => $phpmailer->DKIM_identity,
						'DKIM_passphrase' => $phpmailer->DKIM_passphrase,
						'DKIM_domain' => $phpmailer-> DKIM_domain,
						'DKIM_private' => $phpmailer-> DKIM_private, 
						'DKIM_private_string' => $phpmailer->DKIM_private_string,
						'XMailer' => $phpmailer->XMailer, 
					); 
			print_r($debug_array); 
					
					?></pre>
		
		</div>	
			<?php
				
				// Destroy $phpmailer so it doesn't cause issues later.
				unset( $phpmailer );
			
			}
		
		}
			
	}
	
}
