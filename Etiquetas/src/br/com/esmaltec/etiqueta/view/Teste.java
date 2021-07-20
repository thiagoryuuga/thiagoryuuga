package br.com.esmaltec.etiqueta.view;
import java.text.SimpleDateFormat;
import java.util.Date;


public class Teste {

	
	public static void main(String args[]) {
		
		System.out.println( getAnoMesDia() );
		
	}
	
	private static String getAnoMesDia() {
		Date data = new Date();
		SimpleDateFormat formatadorData = new
		SimpleDateFormat("yyMMdd");		
		return formatadorData.format(data);
	}
}
