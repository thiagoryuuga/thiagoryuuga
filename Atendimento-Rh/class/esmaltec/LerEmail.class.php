<?php
class Email_reader {

			// imap server connection
			public $conn;

			// inbox storage and inbox message count
			public $inbox;
			public $msg_cnt;

			// email login credentials
			private $server = 'intranet.esmaltec.com.br';
			private $user   = 'portalprojetos'; //leandror
			private $pass   = 'portalprojetos'; //lpr03kga07
			private $port   = 110; // adjust according to server settings

			// connect to the server and get the inbox emails
			function __construct() {
					$this->connect();
					$this->inbox();
			}

			// close the server connection
			function close() {
					$this->inbox = array();
					$this->msg_cnt = 0;

					imap_close($this->conn);
			}

			// open the server connection
			// the imap_open function parameters will need to be changed for the particular server
			// these are laid out to connect to a Dreamhost IMAP server
			function connect() {
					$this->conn = imap_open('{'.$this->server.'/pop3}', $this->user, $this->pass);
			}

			// move the message to a new folder
			function move($msg_index, $folder='INBOX.Processed') {
					// move on server
					imap_mail_move($this->conn, $msg_index, $folder);
					imap_expunge($this->conn);

					// re-read the inbox
					$this->inbox();
			}

			// get a specific message (1 = first email, 2 = second email, etc.)
			function get($msg_index=NULL) {
					if (count($this->inbox) <= 0) {
							return array();
					}
					elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
							//var_dump($this->inbox);
							return $this->inbox[$msg_index];
					}

					return $this->inbox[0];
			}
			
			function createFolder($name){
				return imap_createmailbox ( $this->conn, $name);
			}
			
			
			/**
			 * returnMailboxListArr - returns lists of mailboxes
			 * @see http://www.php.net/manual/en/function.imap-list.php
			 * @param void
			 * @return array
			 */	
			public function returnMailboxListArr(){
				return imap_list($this->conn,'{'.$this->server.'/pop3}','*');
			}
			
			/**
			 * returnGetBoxMailBoxes - returns lists of mailboxes
			 * @see http://www.php.net/manual/en/function.imap-list.php
			 * @param void
			 * @return array
			 */	
			public function returnGetBoxMailBoxes(){
				return imap_getmailboxes ($this->conn,'{'.$this->server.'/pop3}INBOX','*');
			}
			
			
			/**
			 * deleteEmail - Deleta um email
			 * @see http://www.php.net/manual/pt_BR/function.imap-delete.php
			 * @param int $msg_number Número da mensagem
			 * @return bool
			 */	
			public function deleteEmail($msg_number = NULL){
				if(is_numeric($msg_number)){
					if(imap_delete($this->conn, $msg_number)){
						if(imap_expunge($this->conn)){
							return true;	
						}else{
							return false;
						}
					}else{
						return false;	
					}
				}else{
					return false;
				}
			}
			
			public function convertHeaderDecode($texto){
				return imap_mime_header_decode($texto);	
			}

			// read the inbox
			function inbox() {
					$this->msg_cnt = imap_num_msg($this->conn);

					$in = array();
					for($i = 1; $i <= $this->msg_cnt; $i++) {
							$in[] = array(
									'index'     => $i,
									'header'    => imap_headerinfo($this->conn, $i),
									'body'      => imap_body($this->conn, $i),
									'structure' => imap_fetchstructure($this->conn, $i)
							);
					}

					$this->inbox = $in;
			}

	}
?>