<?php
/**
 * SendEmail
 * send email provide public method.
 *
 * @author hesen2006cn@126.com
 * create_time : 2011-03-04
 *
 * //email sender config
 * $config['EMAIL_DELIVERY'] = 'mail'; //mail sendmail qmail smtp
 * $config['EMAIL_FROM'] = '';
 * $config['EMAIL_FROM_NAME'] = '';
 * //smpt config
 * $config['EMAIL_SMTP_HOST'] = '';
 * $config['EMAIL_SMTP_PORT'] = '';
 * $config['EMAIL_SMTP_MODE'] = '';
 * $config['EMAIL_SMTP_USERNAME'] = '';
 * $config['EMAIL_SMTP_PASSWORD'] = '';
 */

Doo::loadClass('mailer/phpmailer');
 
class SendEmail
{
	private $_mail = null;
	
	public function __construct($seting=null){
		$this->_mail = new PHPMailer(true);
		if(isset(Doo::conf()->EMAIL_SMTP_HOST))
			$this->_mail->Host = Doo::conf()->EMAIL_SMTP_HOST;
		if(!empty(Doo::conf()->EMAIL_FROM))
			$this->_mail->setFrom(Doo::conf()->EMAIL_FROM,Doo::conf()->EMAIL_FROM_NAME);
		if(Doo::conf()->EMAIL_DELIVERY) 
			$this->_delivery = Doo::conf()->EMAIL_DELIVERY;
			
		$this->setDelivery($this->_delivery);
		$this->_mail->IsHTML(true);
		$this->_mail->WordWrap = 80;
		$this->_mail->CharSet = 'utf-8';
		$this->_mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
	}
	
	/**
   * send email from templte
   * @return bool
   */
	public function sendFromTemplte($path,$subject,$content){
		$template = file_get_contents($path);
		if(empty($message)) return false;
		
		$message = str_replace("{content}",$content,$template);
		
		return $this->sendEmail($subject,$message);
	}
	
	public function setFrom($address,$name ='',$auto=1){
		$this->_mail->SetFrom($address,$name ='',$auto=1);
	}
	
	public function sendto($address, $name=''){
		$this->_mail->AddAddress($address, $name);
	}
	
	/**
   * sendcc a "Cc" address.
   * Note: this function works with the SMTP mailer on win32, not with the "mail" mailer.
   * @param string $address
   * @param string $name
   * @return boolean true on success, false if address already used
   */
	public function sendcc($address,$name=''){
		 return $this->_mail->AddAnAddress('cc', $address, $name);
	}
	
	/**
   * Creates message and assigns Mailer. If the message is
   * not sent successfully then it returns false.  Use the ErrorInfo
   * variable to view description of the error.
   * @return bool
   */
	public function sendEmail($subject,$message){
		$this->_mail->Subject = $subject;
		
		$textMsg = trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/s','',$message)));
    if (!empty($textMsg)) {
      $this->_mail->AltBody = html_entity_decode($textMsg);
    }
    
		$this->_mail->Body =  str_replace("\n","\r\n","\n".$message);
		$this->_mail->Body =  str_replace("\r\r\n","\r\n",$this->_mail->Body);
		return $this->_mail->send();
	}
	
	public function setDelivery($type = 'mail'){
		$this->_delivery = $type;
		switch($type) {
			case 'mail':
				$this->_mail->IsMail();
				break;
			case 'sendmail':
				$this->_mail->IsSendmail();
				break;
			case 'qmail':
				$this->_mail->IsQmail();
				break;
			case 'smtp':
				$this->_mail->IsSMTP();
				$this->_mail->SMTPKeepAlive = true;
				if(!empty(Doo::conf()->EMAIL_SMTP_USERNAME)) {
					$this->_mail->SMTPAuth = true;
					$this->_mail->Username = Doo::conf()->EMAIL_SMTP_USERNAME;
					$this->_mail->Password = Doo::conf()->EMAIL_SMTP_PASSWORD;
				}
	
				if(!empty(Doo::conf()->EMAIL_SMTP_MODE)) {
					$this->_mail->SMTPSecure = Doo::conf()->EMAIL_SMTP_MODE;
				}
	
				$this->_mail->Port = Doo::conf()->EMAIL_SMTP_PORT;
	
				break;
		}
	}
	 /**
   * Adds an attachment from a path on the filesystem.
   * Returns false if the file could not be found
   * or accessed.
   * @param string $path Path to the attachment.
   * @param string $name Overrides the attachment name.
   * @return bool
   */
	public function AddAttachment($path,$name=''){
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
    $mimeType  = $this->_mail->_mime_types($ext);
		return $this->_mail->AddAttachment($path,$name,'base64',$mimeType);
	}
	
	public function AddCustomHeader(){
		/*
		if( isset( $t_email_data->metadata['headers'] ) && is_array( $t_email_data->metadata['headers'] ) ) {
			foreach( $t_email_data->metadata['headers'] as $t_key => $t_value ) {
				switch( $t_key ) {
					case 'Message-ID':
						
							if ( !strchr( $t_value, '@' ) && !is_blank( $mail->Hostname ) ) {
								$t_value = $t_value . '@' . $mail->Hostname;
							}
						$mail->set( 'MessageID', "<$t_value>" );
						break;
					case 'In-Reply-To':
						$mail->AddCustomHeader( "$t_key: <{$t_value}@{$mail->Hostname}>" );
						break;
					default:
						$mail->AddCustomHeader( "$t_key: $t_value" );
						break;
				}
			}
		}
		*/
	}
}