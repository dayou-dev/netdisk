<?php
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
require APP_ROOT_PATH.'system/mail/Exception.php'; 
require APP_ROOT_PATH.'system/mail/PHPMailer.php'; 
require APP_ROOT_PATH.'system/mail/SMTP.php'; 

class mail_sender
{
    /////////////////////////////////////////////////
    // PUBLIC VARIABLES
    /////////////////////////////////////////////////

    /**
     * Email priority (1 = High, 3 = Normal, 5 = low).
     * @var int
     */
    var $Priority          = 3;

    /**
     * Sets the CharSet of the message.
     * @var string
     */
    var $CharSet           = "utf8";

    /**
     * Sets the Content-type of the message.
     * @var string
     */
    var $ContentType        = "text/plain";

    /**
     * Sets the Encoding of the message. Options for this are "8bit",
     * "7bit", "binary", "base64", and "quoted-printable".
     * @var string
     */
    var $Encoding          = "8bit";

    /**
     * Holds the most recent mailer error message.
     * @var string
     */
    var $ErrorInfo         = "";

    /**
     * Sets the From email address for the message.
     * @var string
     */
    var $From               = "root@localhost";

    /**
     * Sets the From name of the message.
     * @var string
     */
    var $FromName           = "Root User";

    /**
     * Sets the Sender email (Return-Path) of the message.  If not empty,
     * will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.
     * @var string
     */
    var $Sender            = "";

    /**
     * Sets the Subject of the message.
     * @var string
     */
    var $Subject           = "";

    /**
     * Sets the Body of the message.  This can be either an HTML or text body.
     * If HTML then run IsHTML(true).
     * @var string
     */
    var $Body               = "";

    /**
     * Sets the text-only body of the message.  This automatically sets the
     * email to multipart/alternative.  This body can be read by mail
     * clients that do not have HTML email capability such as mutt. Clients
     * that can read HTML will view the normal Body.
     * @var string
     */
    var $AltBody           = "";

    /**
     * Sets word wrapping on the body of the message to a given number of 
     * characters.
     * @var int
     */
    var $WordWrap          = 0;

    /**
     * Method to send mail: ("mail", "sendmail", or "smtp").
     * @var string
     */
    var $Mailer            = "mail";

    /**
     * Sets the path of the sendmail program.
     * @var string
     */
    var $Sendmail          = "/usr/sbin/sendmail";
    
    /**
     * Path to PHPMailer plugins.  This is now only useful if the SMTP class 
     * is in a different directory than the PHP include path.  
     * @var string
     */
    var $PluginDir         = "";

    /**
     *  Holds PHPMailer version.
     *  @var string
     */
    var $Version;

    /**
     * Sets the email address that a reading confirmation will be sent.
     * @var string
     */
    var $ConfirmReadingTo  = "";

    /**
     *  Sets the hostname to use in Message-Id and Received headers
     *  and as default HELO string. If empty, the value returned
     *  by SERVER_NAME is used or 'localhost.localdomain'.
     *  @var string
     */
    var $Hostname          = "";

    /////////////////////////////////////////////////
    // SMTP VARIABLES
    /////////////////////////////////////////////////

    /**
     *  Sets the SMTP hosts.  All hosts must be separated by a
     *  semicolon.  You can also specify a different port
     *  for each host by using this format: [hostname:port]
     *  (e.g. "smtp1.example.com:25;smtp2.example.com").
     *  Hosts will be tried in order.
     *  @var string
     */
    var $Host;

    /**
     *  Sets the default SMTP server port.
     *  @var int
     */
    var $Port;

    /**
     *  Sets the SMTP HELO of the message (Default is $Hostname).
     *  @var string
     */
    var $Helo;

    /**
     *  Sets SMTP authentication. Utilizes the Username and Password variables.
     *  @var bool
     */
    var $SMTPAuth     = true;

    /**
     *  Sets SMTP username.
     *  @var string
     */
    var $Username;

    /**
     *  Sets SMTP password.
     *  @var string
     */
    var $Password;

    /**
     *  Sets the SMTP server timeout in seconds. This function will not 
     *  work with the win32 version.
     *  @var int
     */
    var $Timeout      = 10;

    /**
     *  Sets SMTP class debugging on or off.
     *  @var bool
     */
    var $SMTPDebug    = false;

    /**
     * Prevents the SMTP connection from being closed after each mail 
     * sending.  If this is set to true then to close the connection 
     * requires an explicit call to SmtpClose(). 
     * @var bool
     */
    var $SMTPKeepAlive = false;
	
	

    /**#@+
     * @access private
     */
    var $smtp            = NULL;
    var $to              = array();
    var $cc              = array();
    var $bcc             = array();
    var $ReplyTo         = array();
    var $attachment      = array();
    var $CustomHeader    = array();
    var $message_type    = "";
    var $boundary        = array();
    var $language        = array();
    var $error_count     = 0;
    var $LE              = "\n";
    /**#@-*/
    
    var $mail_server_id = 0;
	
	public function __construct()
    { 	
	
	}
	
    public function mail_sender()
    {
    }
	
	
	
    public function Send()
     {
    	//构造    	
		
		$sql = "select * from ".DB_PREFIX."mail_server where is_effect = 1 and ((use_limit > total_use and use_limit > 0) or (use_limit = 0)) order by id asc";
		$smtp_servers = $GLOBALS['db']->getAll($sql);	 //取出所有可用的服务器
	
		if(!$smtp_servers)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."mail_server set total_use = 0 where is_reset = 1 and is_effect = 1 and use_limit <= total_use and use_limit > 0");  //将所有可清零的服务器清零
			$smtp_servers = $GLOBALS['db']->getAll($sql);
		}			
		foreach($smtp_servers as $k=>$v)
		{
			if($v['use_limit'] > $v['total_use']||$v['use_limit']==0)
			{
				$smtp_item = $v;
				break;
			}				
		}
		$mail = new PHPMailer(true); 
		try { 
			//服务器配置
			$mail->CharSet ="UTF-8";                     //设定邮件编码 
			$mail->SMTPDebug = 0;                        // 调试模式输出 
			$mail->isSMTP();                             // 使用SMTP 
			$mail->Host = $smtp_item['smtp_server'];                // SMTP服务器 
			$mail->SMTPAuth = true;                      // 允许 SMTP 认证 
			$mail->Username = $smtp_item['smtp_name'];                // SMTP 用户名  即邮箱的用户名 
			$mail->Password = $smtp_item['smtp_pwd'];             // SMTP 密码  部分邮箱是授权码(例如163邮箱) 
			if($smtp_item['smtp_port']=='465'){
				$mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
			}
			$mail->Port = intval($smtp_item['smtp_port']);                            // 服务器端口 25 或者465 具体要看邮箱服务器支持 
			$mail->setFrom($smtp_item['smtp_name'], 'dayou');  //发件人 
			//$mail->addAddress($this->to[0][0], $this->to[0][1]);  // 收件人 
			foreach($this->to as $key=>$rows){
				$mail->addAddress($this->to[$key][0], $this->to[$key][1]);  // 收件人 
				//echo $this->to[$key][0];
			}
			//exit();
			//$mail->addAddress('ellen@example.com');  // 可添加多个收件人 
			$mail->addReplyTo($smtp_item['smtp_name'], 'dayou'); //回复的时候回复给哪个邮箱 建议和发件人一致 
			//$mail->addCC('cc@example.com');                    //抄送 
			//$mail->addBCC('bcc@example.com');                    //密送 
		
			//发送附件 
			// $mail->addAttachment('../xy.zip');         // 添加附件 
			// $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名 
			
			
		
			//Content 
			$mail->isHTML($this->ContentType?true:false);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容 
			$mail->Subject = $this->Subject; 
			$mail->Body    = $this->Body; 
			$mail->AltBody = htmlspecialchars($this->Body); 
			$mail->send();
			
			
			return 1;
		}catch(Exception $e){
			 $this->ErrorInfo='邮件发送失败: '. $mail->ErrorInfo;
		}
    }
	
	
    /**
     * Sets message type to HTML.  
     * @param bool $bool
     * @return void
     */
    function IsHTML($bool) {
        if($bool == true)
            $this->ContentType = 1;
        else
            $this->ContentType = 0;
    }

    /**
     * Sets Mailer to send message using SMTP.
     * @return void
     */
    function IsSMTP() {
        $this->Mailer = "smtp";
    }

    /**
     * Sets Mailer to send message using PHP mail() function.
     * @return void
     */
    function IsMail() {
        $this->Mailer = "mail";
    }

    /**
     * Sets Mailer to send message using the $Sendmail program.
     * @return void
     */
    function IsSendmail() {
        $this->Mailer = "sendmail";
    }

    /**
     * Sets Mailer to send message using the qmail MTA. 
     * @return void
     */
    function IsQmail() {
        $this->Sendmail = "/var/qmail/bin/sendmail";
        $this->Mailer = "sendmail";
    }


    /////////////////////////////////////////////////
    // RECIPIENT METHODS
    /////////////////////////////////////////////////

    /**
     * Adds a "To" address.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddAddress($address, $name = "") {
        $cur = count($this->to);
        $this->to[$cur][0] = trim($address);
        $this->to[$cur][1] = $name;
    }

    /**
     * Adds a "Cc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.  
     * @param string $address
     * @param string $name
     * @return void
    */
    function AddCC($address, $name = "") {
        $cur = count($this->cc);
        $this->cc[$cur][0] = trim($address);
        $this->cc[$cur][1] = $name;
    }

    /**
     * Adds a "Bcc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddBCC($address, $name = "") {
        $cur = count($this->bcc);
        $this->bcc[$cur][0] = trim($address);
        $this->bcc[$cur][1] = $name;
    }

    /**
     * Adds a "Reply-to" address.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddReplyTo($address, $name = "") {
        $cur = count($this->ReplyTo);
        $this->ReplyTo[$cur][0] = trim($address);
        $this->ReplyTo[$cur][1] = $name;
    }

}


?>