<?php
class EnviaReuniao{
	
	function __construct(){
		
	}
	
	public static function enviaReuniao($dtstart,$dtend,$loc,$summary,$from,$to,$subject, $project) {
		
		$vcal = "BEGIN:VCALENDAR\r\n";
		$vcal .= "VERSION:2.0\r\n";
		$vcal .= "PRODID:-//Esmaltec\r\n";
		$vcal .= "METHOD:REQUEST\r\n";
		$vcal .= "BEGIN:VEVENT\r\n";
		$vcal .= "ATTENDEE;CN=\"Leandro Pedrosa\";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:leandror@intranet.esmaltec.com.br\r\n";
		$vcal .= "UID:".date('Ymd').'T'.date('His')."-".rand()."-esmaltec.com.br\r\n";
		$vcal .= "DTSTAMP:".date('Ymd').'T'.date('His')."\r\n";
		$vcal .= "DTSTART:$dtstart\r\n";
		$vcal .= "DTEND:$dtend\r\n"; 
		if ($loc != "") $vcal .= "LOCATION:$loc\r\n";
		$vcal .= "SUMMARY:$summary\r\n";
		$vcal .= "ORGANIZER; CN=\"EPROJ - $project\":mailto:portalprojetos@intranet.esmaltec.com.br\n";
		$vcal .= "SEQUENCE:0\r\n";
		$vcal .= "BEGIN:VALARM\r\n";
		$vcal .= "TRIGGER:-PT15M\r\n";
		$vcal .= "ACTION:DISPLAY\r\n";
		$vcal .= "DESCRIPTION:".$subject."\r\n";
		$vcal .= "END:VALARM\r\n";
		$vcal .= "END:VEVENT\r\n";
		$vcal .= "END:VCALENDAR\r\n";
		
		//echo $vcal;
	 //iso-8859-1
		$headers = "From: $from\r\nReply-To: $from, portalprojetos@intranet.esmaltec.com.br\r\n"; 
		//$headers .= 'Bcc: leandror@intranet.esmaltec.com.br' . "\r\n";
		$headers .= "MIME-version: 1.0\r\nContent-Type: text/calendar; method=REQUEST; charset=\"iso-8859-1\"";
		$headers .= "\r\nContent-Transfer-Encoding: 7bit\r\nX-Mailer: Microsoft Office Outlook 12.0"; 
	 
		if(mail($to, $summary, $vcal, $headers)) { 
			return true;
		} else { 
			return false;
		}
	 }	
}
?>