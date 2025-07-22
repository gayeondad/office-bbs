<?php
/**
 * bulletin
 */
namespace cls\controller;
// use Exception;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class mailer {
	

	private $mail;
	private $fromAddress;
	private $fromName;
	private $subject;
	private $body;
	private $altBody;
	private $toArr 		= array();
	private $atchArr 	= array();

	private $mailSvrKey;
	private $mailSvr = array(
		'naver' => array(
			'host' => "smtp.naver.com",
			'user' => "creloper@naver.com",
			'pswd' => "1234",
			'port' => 465,
			'secr' => 'smtp'
		),
		'kbid' => array(
			'host' => "112.175.236.8",
			'user' => "creloper@naver.com",
			'pswd' => "1234",
			'port' => 465,
			'secr' => 'tls'
		),
	);

	public function __construct($bool=false)
	{
		$this->mail = new PHPMailer(true);
		$this->mailSvrKey = "naver";
	}

	function __destruct()
	{

	}

	public function setFromAddress($email='')
	{
		$this->fromAddress = $email;
	}

	public function setFromName($name='')
	{
		$this->fromName = $name;
	}

	public function setSubject($str='')
	{
		$this->subject = $str;
	}

	public function setBody($str='')
	{
		$this->body = $str;
	}

	public function setAltBody($str='')
	{
		$this->altBody = $str;
	}

	public function setToArr($arr=array())
	{
		$this->toArr = $arr;
	}

	public function setAtchArr($arr=array())
	{
		$this->atchArr = $arr;
	}

	public function sendMail()
	{
		$bool = false;
		try {
			// 서버 설정
			$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;							// 디버깅 설정 (개발 시에는 SMTP::DEBUG_SERVER, 운영 시에는 SMTP::DEBUG_OFF)
			$this->mail->isSMTP();
			$this->mail->Host = $this->mailSvr[$this->mailSvrKey]['host'];			// "smtp.naver.com";		//112.175.236.8
			$this->mail->SMTPAuth = true;
			$this->mail->Username = $this->mailSvr[$this->mailSvrKey]['user'];		// "creloper@naver.com";
			$this->mail->Password = $this->mailSvr[$this->mailSvrKey]['pwwd'];		// "1234";
			$this->mail->SMTPSecure = $this->mailSvr[$this->mailSvrKey]['secr'];	//PHPMailer::ENCRYPTION_SMTPS, tls ->  PHPMailer::ENCRYPTION_STARTTLS;
			$this->mail->Port = $this->mailSvr[$this->mailSvrKey]['port'];			// SMTP port (ssl:465, tls:587)

			// 보내는 사람, 받는 사람 설정
			$this->mail->setFrom($this->fromAddress, $this->fromName);
			// $this->mail->From = $this->fromAddress;
			// $this->mail->FromName = $this->fromName;

			// $this->mail->addReplyTo('replyto@example.com', 'First Last');
			// $this->mail->addCC($cc);
			// $this->mail->addBCC($bcc);

			$this->mail->addAddress('ddashan@daum.net', '박무성');

			// 첨부파일
			if (!empty($this->atchArr)) {
				foreach ($this->atchArr as $atch) {
					$this->mail->addAttachment($atch['path'], $atch['name']);
				}
			}

			// 메일 내용 설정
			$this->mail->isHTML(true);
			$this->mail->CharSet = "utf-8";
			// $this->mail->Encoding = 'base64';
			$this->mail->Subject = $this->subject;
			$this->mail->Body = '
			<html>
		    <head>
		    	<meta charset="UTF-8">
		        <title>HTML 메일</title>
		    </head>
		    <body>
		        <p>HTML 형식의 메일입니다.</p>
		        <a href="https://www.naver.com">링크</a>
		    </body>
		    </html>
			';
			// $this->msgHTML($this->body);

			if ($this->mail->send()) {
				$bool = true;
			}
			
		} catch (Exception $e) {
			echo "메일 발송 실패: " . $this->mail->ErrorInfo;
		} finally {
			return $bool;
		}
	}


	
}
