package br.com.esmaltec.etiqueta.business;

import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.rmi.server.UID;
import java.sql.Connection;
import java.sql.SQLException;
import java.text.DecimalFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;

import org.apache.log4j.Logger;

import br.com.softsite.sfc.business.SSValueObject;
import br.com.softsite.sfc.exception.SSException;
import br.com.softsite.sfc.persistence.SSPersistence;
import br.com.softsite.sfc.persistence.SSPersistenceFactory;
import br.com.softsite.sfc.util.SSDataFormat;

/**
 * @author rafael@esmaltec.com.br
 */
public class ImprimeEtiquetaOld {

	private SSPersistence pst = null;
	private String pstDir;
	private String pstName;
	private Connection connection;
	private int num_serie_ini;
	int numEtiqCompleta;
	private String sisOper;
	private Logger logger;

	private boolean refresh = true;
	String itemSerie;

	SSValueObject itemVO = null;
	SSValueObject itemSerieVO = null;

	String codEmpresa;
	String codItem;
	String qtdEtiq;
	String iesLote;
	String modelo;
	Integer numVias;
	String compressor;
	String serieInicial;

	boolean reimprime;
	boolean isCE;
	boolean isIN;

	String impressao = "";

	String num_serie;
	String num_etiq_completa;
	String num_etiq_produto;
	String num_etiq_modelo;
	String cabecalho_1, cabecalho_2, cabecalho_3, cabecalho_4;
	String linha_1, linha_2;
	String linha_3, linha_3_1, linha_3_2;
	String linha_4, linha_4_1, linha_4_2;
	String linha_5, linha_5_1, linha_5_2, linha_5_3;
	String linha_6, linha_6_1, linha_6_2, linha_6_3;
	String linha_7, linha_7_1;
	String linha_8, linha_8_1;
	String linha_9, linha_9_1;
	String linha_10, linha_10_1;
	String linha_11, linha_11_1, linha_11_2;
	String linha_12, linha_12_1, linha_12_2;
	String linha_13, linha_13_1, linha_13_2;
	String linha_14, linha_14_1, linha_14_2;
	String linha_15;
	String linha_16, linha_16_1;
	String linha_17;
	String linha_17_1;
	String linha_18;
	String linha_18_1;
	String linha_19;
	String linha_19_1;
	String anoMesDia;

	ConsultaItem consultaItem = new ConsultaItem();

	String pathSistema;

	public ImprimeEtiquetaOld(String codEmpresa, String codItem, String qtdEtiq,
			String iesLote, String modelo, Integer numVias, String compressor,
			Connection con, String serieInicial, boolean reimprime, String so) {
		this.codEmpresa = codEmpresa.trim();
		this.codItem = codItem.trim();
		this.qtdEtiq = qtdEtiq;
		this.iesLote = iesLote;
		this.modelo = modelo;
		this.numVias = numVias;
		this.compressor = compressor;
		this.connection = con;
		this.serieInicial = serieInicial;
		this.reimprime = reimprime;
		this.sisOper = so;

		pathSistema = "/sisetiq";

		pstDir = "xml";
		pstName = "etiqueta";
		logger = Logger.getLogger(ImprimeEtiqueta.class);

		logger.debug("Verificando Cod Empresa " + codEmpresa);

	}

	public void processar() throws SSException, SQLException, IOException,
			ParseException {
		SSValueObject paramVO = new SSValueObject();
		itemSerieVO = new SSValueObject();

		logger.debug("\n" + getDataHora() + "\n");
		int num_serie_atual;

		int numeroVias = numVias.intValue();
		int qtdEtiquetas = (new Integer(qtdEtiq)).intValue();

		logger.debug("Verificando Cod Empresa " + codEmpresa);

		paramVO.set(0, "COD_EMPRESA", codEmpresa);
		paramVO.set(0, "COD_ITEM", codItem);
		paramVO.set(0, "QTD_ETIQ", new Integer(qtdEtiquetas));

		logger.debug("Verificando Cod Empresa " + codEmpresa);

		pst = SSPersistenceFactory.getInstance(pstDir, pstName, connection);

		itemVO = pst.executeQuery("ObterInformacaoItem", paramVO);

		logger.debug("Verificando Contigencia " + itemVO.toString());

		if (reimprime) {
			num_serie_ini = (new Integer(serieInicial.substring(8))).intValue();
			num_serie_ini--;
			itemVO.set(0, "ULT_NUM_SERIE", new Integer(num_serie_ini));
			logger.debug("Reimpressao " + num_serie_ini);
		} else {
			num_serie_ini = ((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
					.intValue();
			paramVO.set(0, "DEN_ETIQUETA", itemVO.get(0, "DEN_ETIQUETA"));
			logger.debug("Atualizar Numero Serie !");
			pst.executeUpdate("AtualizarNumSerie", paramVO);
			logger.debug("Atualizou Padroes Esmaltec !");
			connection.commit();
		}

		numEtiqCompleta = ((Integer) itemVO.get(0, "NUM_ETIQ_COMPLETA"))
				.intValue();

		// Seleciona Garga de Gas !
		if (compressor == null || "".equals(compressor)) {
			logger.debug("Carga de GAS");
			linha_12_2 = (String) itemVO.get(0, "VALOR_CARGA_GAS");
		} else {
			linha_12_2 = consultaItem.getCargaGas(codItem, compressor);
			if (linha_12_2 == null || "".equals(linha_12_2))
				linha_12_2 = (String) itemVO.get(0, "VALOR_CARGA_GAS");
		}

		String textoCEIN = (String) itemVO.get(0, "VALOR_ESTRELAS");

		if (textoCEIN != null) {
			textoCEIN = textoCEIN.toLowerCase();
			if ("ce".equals(textoCEIN)) {
				isCE = true;
			} else
				isCE = false;
			if ("in".equals(textoCEIN)) {
				isIN = true;
			} else
				isIN = false;
		} else {
			isCE = false;
			isIN = false;
		}

		for (int i = 0; i < qtdEtiquetas; i++) {
			logger.debug("Quantidade de Etiquetas: " + i);
			num_etiq_completa = (String) itemVO.get(0, "NUM_ESTIQ_COMPLETA");
			cabecalho_1 = (String) itemVO.get(0, "CABECALHO_1");
			cabecalho_2 = (String) itemVO.get(0, "CABECALHO_2");
			cabecalho_3 = (String) itemVO.get(0, "CABECALHO_3");
			cabecalho_4 = (String) itemVO.get(0, "CABECALHO_4");

			// TO DO. Se for nulo...
			num_serie_atual = ((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
					.intValue();
			num_serie_atual++;

			// Aqui gravar o VO onde será inserido todos os numeros de series
			// !!!
			// #############################

			/*
			 * COD_EMPRESA, COD_ITEM, NUM_SERIE_INI , QTD_ETIQ, DAT_IMPRESSAO,
			 * IES_CONTADO, QTD_REPROCES, DAT_PRODUCAO
			 */

			logger.debug("Monta Cabecalho");
			itemSerieVO.set(i, "COD_EMPRESA", codEmpresa);
			itemSerieVO.set(i, "COD_ITEM", codItem);

			itemSerieVO.set(i, "NUM_SERIE",
					getAnoMesDia() + codItem.substring(2, 4)
							+ formataNumero(num_serie_atual));

			// itemSerieVO.set(i, "NUM_SERIE", getAnoMesDia() + 99
			// + formataNumero(num_serie_atual));

			// itemSerieVO.set(i,"NUM_SERIE",getAnoMesDia() +
			// codItem.substring(2, 4) + formataNumero(num_serie_atual));
			// itemSerieVO.set(i,"NUM_SERIE",getAnoMesDia() + codEmpresa +
			// formataNumero(num_serie_atual));
			itemSerieVO.set(i, "DAT_IMPRESSAO", SSDataFormat.getDateToday());
			itemSerieVO.set(i, "IES_CONTADO", "N");

			// #############################

			logger.debug("Pega NUMSERIE");
			linha_1 = (String) itemVO.get(0, "TEXTO_NUM_SERIE");
			paramVO.set(0, "ULT_NUM_SERIE", new Integer(num_serie_atual));

			itemVO.set(0, "ULT_NUM_SERIE", new Integer(num_serie_atual));

			linha_2 = getAnoMesDia() + codItem.substring(2, 4)
					+ formataNumero(num_serie_atual);

			// linha_2 = getAnoMesDia() + 99 + formataNumero(num_serie_atual);
			// linha_2 = getAnoMesDia() + codItem.substring(2, 4) +
			// formataNumero(num_serie_atual);
			// linha_2 = getAnoMesDia() + codEmpresa +
			// formataNumero(num_serie_atual);

			if (refresh) {
				itemSerie = linha_2;
				refresh = false;
			}

			if (numeroVias > 0) {
				if ("N".equals(iesLote)) {
					for (int j = 0; j < numeroVias; j++) {
						if ("1".equals(modelo))
							montaEtiquetaFogao();
						if ("2".equals(modelo))
							montaEtiquetaRefrigeracao();
						if ("3".equals(modelo))
							montaEtiquetaMicro();
					}

				} else {
					if ("1".equals(modelo))
						montaEtiquetaFogao();
					if ("2".equals(modelo))
						montaEtiquetaRefrigeracao();
					if ("3".equals(modelo))
						montaEtiquetaMicro();
				}
			}

		}
		if ("S".equals(iesLote)) {
			int serie;
			itemVO.set(0, "ULT_NUM_SERIE", new Integer(num_serie_ini));
			for (int i = 0; i < qtdEtiquetas; i++) {
				num_etiq_completa = (String) itemVO
						.get(0, "NUM_ESTIQ_COMPLETA");
				cabecalho_1 = (String) itemVO.get(0, "CABECALHO_1");
				cabecalho_2 = (String) itemVO.get(0, "CABECALHO_2");
				cabecalho_3 = (String) itemVO.get(0, "CABECALHO_3");
				cabecalho_4 = (String) itemVO.get(0, "CABECALHO_4");

				linha_1 = (String) itemVO.get(0, "TEXTO_NUM_SERIE");

				serie = ((Integer) itemVO.get(0, "ULT_NUM_SERIE")).intValue();
				serie++;

				itemVO.set(0, "ULT_NUM_SERIE", new Integer(serie));

				linha_2 = getAnoMesDia() + codItem.substring(2, 4)
						+ formataNumero(serie);

				// linha_2 = getAnoMesDia() + 99 + formataNumero(serie);
				// linha_2 = getAnoMesDia() + codItem.substring(2, 4) +
				// formataNumero(serie);
				// linha_2 = getAnoMesDia() + codEmpresa + formataNumero(serie);

				if (refresh) {
					itemSerie = linha_2;
					refresh = false;
				}

				if (numeroVias > 0) {
					if ("1".equals(modelo))
						montaEtiquetaFogao();
					if ("2".equals(modelo))
						montaEtiquetaRefrigeracao();
					if ("3".equals(modelo))
						montaEtiquetaMicro();
				}
			}
		}

		logger.debug("Imprimindo ...");

		if ("WIN".equals(sisOper)) {
			logger.debug("Imprimindo no Windows"); // Windows
			this.imprimeArquivoWindows();
		} else {
			logger.debug("Imprimindo no Linux");
			this.imprimeArquivo(); // Linux
		}

		// Aqui insere os series dos produtos ...
		if (!reimprime) {
			consultaItem.insereItem(itemSerieVO);
		}
		logger.debug("Imprimiu Etiquetas !");

	}

	private void montaEtiquetaFogao() {
		linha_3 = (String) itemVO.get(0, "TEXTO_MODELO");
		linha_3_1 = (String) itemVO.get(0, "TEXTO_COR");
		linha_4 = (String) itemVO.get(0, "DEN_ETIQUETA");
		linha_4_1 = (String) itemVO.get(0, "VALOR_COR");
		linha_5 = (String) itemVO.get(0, "TEXTO_CLASSE");
		linha_5_1 = (String) itemVO.get(0, "TEXTO_CATEGORIA");
		linha_5_2 = (String) itemVO.get(0, "TEXTO_GAS");
		linha_6 = (String) itemVO.get(0, "VALOR_CLASSE");
		linha_6_1 = (String) itemVO.get(0, "VALOR_CATEGORIA");
		linha_6_2 = (String) itemVO.get(0, "VALOR_GAS");
		linha_7 = (String) itemVO.get(0, "TEXTO_PRESSAO");
		linha_7_1 = (String) itemVO.get(0, "TEXTO_POTENCIA");
		linha_8 = (String) itemVO.get(0, "VALOR_PRESSAO");
		linha_8_1 = (String) itemVO.get(0, "VALOR_POTENCIA");
		linha_9 = (String) itemVO.get(0, "TEXTO_POTEN_ELET");
		linha_10 = (String) itemVO.get(0, "VALOR_POTEN_ELET");
		linha_11 = (String) itemVO.get(0, "TEXTO_FREQUENCIA");
		linha_11_1 = (String) itemVO.get(0, "TEXTO_FAIXA_TENSAO");
		linha_12 = (String) itemVO.get(0, "VALOR_FREQUENCIA");
		linha_12_1 = (String) itemVO.get(0, "VALOR_FAIXA_TENSAO");
		linha_13 = (String) itemVO.get(0, "TEXTO_ISOLAMENTO");
		linha_14 = (String) itemVO.get(0, "VALOR_ISOLAMENTO");
		linha_13_1 = (String) itemVO.get(0, "TEXTO_MODELO");
		linha_14_1 = (String) itemVO.get(0, "VALOR_MODELO");
		linha_15 = (String) itemVO.get(0, "CODIGO_EAN13");
		linha_15 = linha_15.trim();

		linha_16 = codItem.substring(0, 10)
				+ getAnoMesDia()
				+ codItem.substring(2, 4)
				+ formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
						.intValue());
		linha_16_1 = codItem.substring(0, 10)
				+ getAnoMesDia()
				+ codItem.substring(2, 4)
				+ formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
						.intValue());

		/*
		 * linha_16 = codItem.substring(0, 10) + getAnoMesDia() + 99 +
		 * formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
		 * .intValue()); linha_16_1 = codItem.substring(0, 10) + getAnoMesDia()
		 * + 99 + formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
		 * .intValue());
		 */
		// linha_16 = codItem.substring(0,10) + getAnoMesDia() +
		// codItem.substring(2, 4) +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		// linha_16_1 = codItem.substring(0,10) + getAnoMesDia() +
		// codItem.substring(2, 4) +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		// linha_16 = codItem.substring(0,12) + getAnoMesDia() + codEmpresa +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		// linha_16_1 = codItem.substring(0,12) + getAnoMesDia() + codEmpresa +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		this.imprimir();

	}

	private void montaEtiquetaRefrigeracao() {
		logger.debug("Inicia Etiqueta");

		linha_3 = (String) itemVO.get(0, "TEXTO_MODELO");
		linha_3_1 = (String) itemVO.get(0, "TEXTO_ESTRELAS");
		linha_3_2 = (String) itemVO.get(0, "TEXTO_COR");
		linha_4 = (String) itemVO.get(0, "DEN_ETIQUETA");
		linha_4_1 = (String) itemVO.get(0, "VALOR_ESTRELAS");
		linha_4_2 = (String) itemVO.get(0, "VALOR_COR");
		linha_5 = (String) itemVO.get(0, "TEXTO_VOLUME");
		linha_5_1 = (String) itemVO.get(0, "TEXTO_VOLREF");
		linha_5_2 = (String) itemVO.get(0, "TEXTO_VOLCON");
		linha_5_3 = (String) itemVO.get(0, "TEXTO_CLASSE");
		linha_6 = (String) itemVO.get(0, "VALOR_VOLUME");
		linha_6_1 = (String) itemVO.get(0, "VALOR_VOLREF");
		linha_6_2 = (String) itemVO.get(0, "VALOR_VOLCON");
		linha_6_3 = (String) itemVO.get(0, "VALOR_CLASSE");
		linha_7 = (String) itemVO.get(0, "TEXTO_ALTA_PRES");
		linha_7_1 = (String) itemVO.get(0, "TEXTO_PSIG_BAIXA");
		linha_8 = (String) itemVO.get(0, "VALOR_ALTA_PRES");
		linha_8_1 = (String) itemVO.get(0, "VALOR_PSIG_BAIXA");
		linha_9 = (String) itemVO.get(0, "TEXTO_FAIXA_TENSAO");

		linha_9_1 = (String) itemVO.get(0, "TEXTO_TENSAO");
		linha_10 = (String) itemVO.get(0, "VALOR_FAIXA_TENSAO");
		linha_10_1 = (String) itemVO.get(0, "VALOR_TENSAO");
		linha_11 = (String) itemVO.get(0, "TEXTO_GAS");
		linha_11_1 = (String) itemVO.get(0, "TEXTO_ISOLAMENTO");
		linha_11_2 = (String) itemVO.get(0, "TEXTO_CARGA_GAS");
		linha_12 = (String) itemVO.get(0, "VALOR_GAS");
		linha_12_1 = (String) itemVO.get(0, "VALOR_ISOLAMENTO");

		linha_13 = (String) itemVO.get(0, "TEXTO_FREQUENCIA");
		linha_13_1 = (String) itemVO.get(0, "TEXTO_POTENCIA");
		linha_13_2 = (String) itemVO.get(0, "TEXTO_CORRENTE");

		linha_14 = (String) itemVO.get(0, "VALOR_FREQUENCIA");
		linha_14_1 = (String) itemVO.get(0, "VALOR_POTENCIA");
		linha_14_2 = (String) itemVO.get(0, "VALOR_CORRENTE");
		linha_15 = (String) itemVO.get(0, "CODIGO_EAN13");

		linha_15 = linha_15.trim();

		logger.debug("Imprimir Etiqueta Ultima Serie: "
				+ codItem.substring(0, 10)
				+ " ==> "
				+ formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
						.intValue()));

		linha_16 = codItem.substring(0, 10)
				+ getAnoMesDia()
				+ codItem.substring(2, 4)
				+ formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
						.intValue());
		linha_16_1 = codItem.substring(0, 10)
				+ getAnoMesDia()
				+ codItem.substring(2, 4)
				+ formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
						.intValue());

		/*
		 * linha_16 = codItem.substring(0, 10) + getAnoMesDia() + 99 +
		 * formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
		 * .intValue()); linha_16_1 = codItem.substring(0, 10) + getAnoMesDia()
		 * + 99 + formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
		 * .intValue());
		 */
		// linha_16 = codItem.substring(0,10) + getAnoMesDia() +
		// codItem.substring(2, 4) +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		// linha_16_1 = codItem.substring(0,10) + getAnoMesDia() +
		// codItem.substring(2, 4) +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;

		/*
		 * linha_16 = codItem.substring(0,12) + getAnoMesDia() + codEmpresa +
		 * formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		 * linha_16_1 = codItem.substring(0,12) + getAnoMesDia() + codEmpresa +
		 * formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		 */

		linha_17 = (String) itemVO.get(0, "TEXTO_PRESSAO");
		linha_17_1 = (String) itemVO.get(0, "VALOR_PRESSAO");

		linha_18 = (String) itemVO.get(0, "TEXTO_POTEN_ELET");
		linha_18_1 = (String) itemVO.get(0, "VALOR_POTEN_ELET");

		linha_19 = (String) itemVO.get(0, "VALOR_CARGA_GAS");

		logger.debug("Imprimir Etiqueta");
		this.imprimir();

	}

	private void montaEtiquetaMicro() {
		linha_3 = (String) itemVO.get(0, "TEXTO_MODELO");
		linha_3_1 = (String) itemVO.get(0, "TEXTO_COR");
		linha_4 = (String) itemVO.get(0, "DEN_ETIQUETA");
		linha_4_1 = (String) itemVO.get(0, "VALOR_COR");
		linha_5 = (String) itemVO.get(0, "TEXTO_CLASSE");
		linha_5_1 = (String) itemVO.get(0, "TEXTO_CATEGORIA");
		linha_5_2 = (String) itemVO.get(0, "TEXTO_GAS");
		linha_6 = (String) itemVO.get(0, "VALOR_CLASSE");
		linha_6_1 = (String) itemVO.get(0, "VALOR_CATEGORIA");
		linha_6_2 = (String) itemVO.get(0, "VALOR_GAS");
		linha_7 = (String) itemVO.get(0, "TEXTO_PRESSAO");
		linha_7_1 = (String) itemVO.get(0, "TEXTO_POTENCIA");
		linha_8 = (String) itemVO.get(0, "VALOR_PRESSAO");
		linha_8_1 = (String) itemVO.get(0, "VALOR_POTENCIA");
		linha_9 = (String) itemVO.get(0, "TEXTO_POTEN_ELET");
		linha_10 = (String) itemVO.get(0, "VALOR_POTEN_ELET");
		linha_11 = (String) itemVO.get(0, "TEXTO_FREQUENCIA");
		linha_11_1 = (String) itemVO.get(0, "TEXTO_FAIXA_TENSAO");
		linha_12 = (String) itemVO.get(0, "VALOR_FREQUENCIA");
		linha_12_1 = (String) itemVO.get(0, "VALOR_FAIXA_TENSAO");
		linha_13 = (String) itemVO.get(0, "TEXTO_ISOLAMENTO");
		linha_14 = (String) itemVO.get(0, "VALOR_ISOLAMENTO");
		linha_13_1 = (String) itemVO.get(0, "TEXTO_MODELO");
		linha_14_1 = (String) itemVO.get(0, "VALOR_MODELO");
		linha_15 = (String) itemVO.get(0, "CODIGO_EAN13");
		linha_15 = linha_15.trim();

		linha_16 = codItem.substring(0, 10)
				+ getAnoMesDia()
				+ codItem.substring(2, 4)
				+ formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
						.intValue());
		linha_16_1 = codItem.substring(0, 10)
				+ getAnoMesDia()
				+ codItem.substring(2, 4)
				+ formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
						.intValue());

		/*
		 * linha_16 = codItem.substring(0, 10) + getAnoMesDia() + 99 +
		 * formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
		 * .intValue()); linha_16_1 = codItem.substring(0, 10) + getAnoMesDia()
		 * + 99 + formataNumero(((Integer) itemVO.get(0, "ULT_NUM_SERIE"))
		 * .intValue());
		 */

		// linha_16 = codItem.substring(0,10) + getAnoMesDia() +
		// codItem.substring(2, 4) +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		// linha_16_1 = codItem.substring(0,10) + getAnoMesDia() +
		// codItem.substring(2, 4) +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		// linha_16 = codItem.substring(0,12) + getAnoMesDia() + codEmpresa +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		// linha_16_1 = codItem.substring(0,12) + getAnoMesDia() + codEmpresa +
		// formataNumero(((Integer)itemVO.get(0,"ULT_NUM_SERIE")).intValue()) ;
		this.imprimir(); 

	} 

	private String getAnoMesDia() {

		if (codItem.equals("0104000016")
				|| codItem.equals("0104000530")) {

			SimpleDateFormat formatadorData = new SimpleDateFormat("yyMMdd");
			String dateInString = "140622";
			Date data;

			try {

				data = formatadorData.parse(dateInString);
				if (reimprime)
					return serieInicial.substring(0, 6);
				else
					return formatadorData.format(data);

			} catch (ParseException e) { // TODO Auto-generated catch block
				e.printStackTrace();

				return serieInicial.substring(0, 6);

			}

		} else {

			Date data = new Date();
			SimpleDateFormat formatadorData = new SimpleDateFormat("yyMMdd");
			if (reimprime)
				return serieInicial.substring(0, 6);
			else
				return formatadorData.format(data);

		}
	}

	/*
	 * private String getAnoMesDia() { Date data = new Date(); SimpleDateFormat
	 * formatadorData = new SimpleDateFormat("yyMMdd"); if (reimprime) return
	 * serieInicial.substring(0, 6); else return formatadorData.format(data); }
	 */
	private String formataNumero(int numero) {
		DecimalFormat formata = new DecimalFormat("000000");
		return formata.format(numero);
	}

	private void imprimir() {

		/* 
		 * 
		 * ^FO45,50^A0,50,50^FDIMPORTADOR^FS ^FO45,110^A0,20,20^FDGOLDMUND S.A.
		 * AV. A.MOREAU DE JUSTO,1720^FS ^FO45,145^A0,20,20^FDCIUDAD AUTONOMA DE
		 * BUENOS AIRES^FS ^FO45,180^A0,20,20^FDMARCA: PEABODY ORIGEM: BRASIL^FS
		 */ 

		if ("1".equals(modelo)) {
			StringBuffer buffer = new StringBuffer();
			buffer.append("^XA^CFD"); // inicio
			buffer.append("^PQ1");
			buffer.append("^LH0,0"); // Posicionamento Inicial da Etiqueta
			buffer.append("^FO35,35^GB430,800,7,B^FS"); // GRADE INICIO (Fogão e
														// Refrig.)
			buffer.append("^FO35,210^GB430,0,4,B^FS");

			buffer.append("^FO35,280^GB430,0,4,B^FS");
			buffer.append("^FO35,350^GB430,0,4,B^FS");

			buffer.append("^FO35,430^GB430,0,4,B^FS");

			buffer.append("^FO240,350^GB0,75,4,B^FS");

			buffer.append("^FO35,515^GB430,0,4,B^FS");
			buffer.append("^FO150,430^GB0,85,4,B^FS");
			buffer.append("^FO330,430^GB0,85,4,B^FS"); // GRADE FIM
			buffer.append("^FO45,50^A0,60,60^FD");
			buffer.append(cabecalho_1);
			buffer.append("^FS"); // INICIO CABEÇALHO
			buffer.append("^FO45,110^A0,28,28^FD");
			buffer.append(cabecalho_2);
			buffer.append("^FS");
			buffer.append("^FO45,145^A0,28,28^FD");
			buffer.append(cabecalho_3);
			buffer.append("^FS");
			buffer.append("^FO45,180^A0,28,28^FD");
			buffer.append(cabecalho_4);
			buffer.append("^FS"); // FIM CABEÇALHO
			buffer.append("^FO35,600^GB430,0,4,B^FS"); // GRADE INICIO
														// (específica do Fogão)
			buffer.append("^FO240,515^GB0,85,4,B^FS");
			buffer.append("^FO35,680^GB430,0,4,B^FS");
			buffer.append("^FO35,760^GB430,0,4,B^FS");
			buffer.append("^FO200,680^GB0,85,4,B^FS");
			buffer.append("^FO200,760^GB260,70,65^FS"); // GRADE FIM

			buffer.append("^FO45,230^A0,28,28^FD");
			buffer.append(linha_1);
			// buffer.append("Serie");
			buffer.append("^FS"); // INICIO DADOS

			buffer.append("^FO140,230^A0,45,45^SN");
			buffer.append(linha_2);
			buffer.append(",1Y^FS");

			buffer.append("^FO45,300^A0,28,28^FDProduto^FS");
			buffer.append("^FO150,300^A0,45,45^FD");
			buffer.append(codItem);
			buffer.append("^FS");

			buffer.append("^FO45,365^A0,28,28^FD");
			buffer.append(linha_3);
			buffer.append("^FS");
			buffer.append("^FO315,365^A0,28,28^FD");
			buffer.append(linha_3_1);
			buffer.append("^FS");
			buffer.append("^FO45,400^A0,35,35^FD");
			buffer.append(linha_4);
			buffer.append("^FS");
			buffer.append("^FO280,400^A0,35,35^FD");
			buffer.append(linha_4_1);
			buffer.append("^FS");
			buffer.append("^FO45,440^A0,28,28^FD");
			buffer.append(linha_5);
			buffer.append("^FS");
			buffer.append("^FO180,440^A0,28,28^FD");
			buffer.append(linha_5_1);
			buffer.append("^FS");
			buffer.append("^FO380,440^A0,28,28^FD");
			buffer.append(linha_5_2);
			buffer.append("^FS");
			buffer.append("^FO80,470^A0,50,50^FD");
			buffer.append(linha_6);
			buffer.append("^FS");
			buffer.append("^FO210,470^A0,50,50^FD");
			buffer.append(linha_6_1);
			buffer.append("^FS");
			buffer.append("^FO360,470^A0,50,50^FD");
			buffer.append(linha_6_2);
			buffer.append("^FS");

			buffer.append("^FO90,525^A0,28,28^FD");
			buffer.append(linha_7);
			buffer.append("^FS");
			buffer.append("^FO290,525^A0,28,28^FD");
			buffer.append(linha_7_1);
			buffer.append("^FS");
			buffer.append("^FO60,555^A0,50,50^FD");
			buffer.append(linha_8);
			buffer.append("^FS");
			buffer.append("^FO260,555^A0,50,50^FD");
			buffer.append(linha_8_1);
			buffer.append("^FS");
			buffer.append("^FO160,610^A0,28,28^FD");
			buffer.append(linha_9);
			buffer.append("^FS");
			buffer.append("^FO55,640^A0,38,38^FD"); // Alterado de 41 para 38
													// por conta do lote de
													// engenharia
			buffer.append(linha_10);
			buffer.append("^FS");
			buffer.append("^FO55,690^A0,28,28^FD");
			buffer.append(linha_11);
			buffer.append("^FS");
			buffer.append("^FO240,690^A0,28,28^FD");
			buffer.append(linha_11_1);
			buffer.append("^FS");
			buffer.append("^FO70,720^A0,50,50^FD");
			buffer.append(linha_12);
			buffer.append("^FS");
			buffer.append("^FO210,720^A0,50,50^FD");
			buffer.append(linha_12_1);
			buffer.append("^FS");
			buffer.append("^FO60,770^A0,28,28^FD");
			buffer.append(linha_13);
			buffer.append("^FS");
			buffer.append("^FO90,800^A0,35,35^FD");
			buffer.append(linha_14);
			buffer.append("^FS");
			// DEN MODELO
			// buffer.append("^FO300,750^A0,28,28^FR^FD");
			// buffer.append(linha_13_1);
			// buffer.append("^FS");
			buffer.append("^FO210,780^A0,50,50^FR^FD");
			buffer.append(linha_14_1);
			buffer.append("^FS");
			buffer.append("^BY3");
			buffer.append("^FO120,840^BE,90,Y,N,^FD");
			buffer.append(linha_15);
			buffer.append("^FS");
			/*
			 * SOLICITANTE ALTERAÇÃO LEANDRO RODRIGUES
			 * <servicedesk@esmaltec.com.br>
			 * 
			 * A linha abaixo altera a largura e altura do codigo de barra da
			 * etiqueta
			 * 
			 * Anterior estava: "^BY5^FO470,60^BCR,180,N,N,^SN>;" Atual:
			 * "^BY5^FO470,60^BCR,180,N,N,^SN>;" Alterado para:
			 * "^BY4^FO470,110^BCR,180,N,N,^SN>;"
			 */
			buffer.append("^BY4^FO470,110^BCR,180,N,N,^SN>;");
			buffer.append(linha_16);
			buffer.append("^FS");
			buffer.append("^FO650,350^A0B,22,22^SN");
			buffer.append(linha_16_1);
			buffer.append("^FS"); // FIM DADOS
			buffer.append("^XZ"); // fim
			impressao = impressao + buffer.toString();
		}
		if ("2".equals(modelo)) {
			StringBuffer buffer = new StringBuffer();
			buffer.append("^XA");
			buffer.append("^CFD");
			buffer.append("^PQ1^LH0,0");
			buffer.append("^FO35,35^GB430,800,7,B^FS");
			buffer.append("^FO35,210^GB430,0,4,B^FS");
			buffer.append("^FO35,280^GB430,0,4,B^FS");
			buffer.append("^FO35,350^GB430,0,4,B^FS");
			buffer.append("^FO35,430^GB430,0,4,B^FS");
			buffer.append("^FO35,510^GB430,0,4,B^FS");
			buffer.append("^FO35,580^GB430,0,4,B^FS");
			buffer.append("^FO35,670^GB430,0,4,B^FS");
			buffer.append("^FO35,750^GB430,0,4,B^FS");
			buffer.append("^FO35,580^GB150,90,50^FS");
			buffer.append("^FO250,210^GB0,70,4,B^FS");
			buffer.append("^FO350,280^GB0,70,4,B^FS");
			buffer.append("^FO380,350^GB0,80,4,B^FS ");
			buffer.append("^FO150,430^GB0,80,4,B^FS");
			buffer.append("^FO300,430^GB0,80,4,B^FS");
			buffer.append("^FO190,510^GB0,70,4,B^FS");
			buffer.append("^FO350,510^GB0,70,4,B^FS ");
			buffer.append("^FO350,580^GB0,90,4,B^FS");
			buffer.append("^FO170,670^GB0,80,4,B^FS");
			buffer.append("^FO330,670^GB0,80,4,B^FS");
			buffer.append("^FO180,750^GB0,80,4,B^FS");
			buffer.append("^FO320,750^GB0,80,4,B^FS");
			buffer.append("^FO45,50^A0,60,60^FD");
			buffer.append(cabecalho_1);
			buffer.append("^FS");
			buffer.append("^FO45,110^A0,28,28^FD");
			buffer.append(cabecalho_2);
			buffer.append("^FS");
			buffer.append("^FO45,145^A0,28,28^FD");
			buffer.append(cabecalho_3);
			buffer.append("^FS");
			buffer.append("^FO45,180^A0,28,28^FD");
			buffer.append(cabecalho_4);
			buffer.append("^FS");
			buffer.append("^FO45,220^A0,20,20^FD");
			buffer.append(linha_3);
			buffer.append("^FS");
			buffer.append("^FO260,220^A0,20,20^FD");
			buffer.append(linha_3_2);
			buffer.append("^FS");
			buffer.append("^FO45,290^A0,20,20^FD");
			buffer.append("Produto");
			buffer.append("^FS");
			buffer.append("^FO360,290^A0,20,20^FD");
			buffer.append(linha_5_3);
			buffer.append("^FS");
			buffer.append("^FO45,360^A0,20,20^FD");
			buffer.append(linha_1);
			buffer.append("^FS");
			buffer.append("^FO390,360^A0,20,20^FD");
			buffer.append(linha_3_1);
			buffer.append("^FS");
			buffer.append("^FO45,440^A0,20,20^FD");
			buffer.append(linha_5_2);
			buffer.append("^FS");
			buffer.append("^FO160,440^A0,20,20^FD");
			buffer.append(linha_5_1);
			buffer.append("^FS");
			buffer.append("^FO310,440^A0,20,20^FD");
			buffer.append(linha_5);
			buffer.append("^FS");
			buffer.append("^FO45,520^A0,20,20^FD");
			buffer.append(linha_7);
			buffer.append("^FS");
			buffer.append("^FO195,520^A0,20,20^FD");
			buffer.append(linha_7_1);
			buffer.append("^FS");
			buffer.append("^FO360,520^A0,20,20^FD");
			buffer.append(linha_13);
			buffer.append("^FS");
			buffer.append("^FO45,590^A0,20,20^FR^FD");
			buffer.append(linha_9_1);
			buffer.append("^FS");
			buffer.append("^FO200,590^A0,20,20^FD");
			buffer.append(linha_9);
			buffer.append("^FS");
			buffer.append("^FO370,590^A0,20,20^FD");
			buffer.append(linha_13_2);
			buffer.append("^FS");
			buffer.append("^FO45,680^A0,20,20^FD");
			buffer.append(linha_11);
			buffer.append("^FS");
			buffer.append("^FO180,680^A0,20,20^FD");
			buffer.append(linha_11_1);
			buffer.append("^FS");
			buffer.append("^FO340,680^A0,20,20^FD");
			buffer.append(linha_11_2);
			buffer.append("^FS");
			buffer.append("^FO45,760^A0,20,20^FD");
			buffer.append(linha_13_1);
			buffer.append("^FS");
			buffer.append("^FO190,760^A0,20,20^FD");
			buffer.append(linha_17);
			buffer.append("^FS");
			buffer.append("^FO330,760^A0,20,20^FD");
			buffer.append(linha_18);
			buffer.append("^FS");
			
			// Inicio do conteudo
			// Alteração Efetuada em 2015-01-19
			// Motivo: Descrição de Modelos maior que 8 caracteres
			
			/*  Informação Anterior:
			 *  buffer.append("^FO65,240^A0,45,45^FD");
				buffer.append(linha_4);
				buffer.append("^FS");
			 */
			
			// Nova Implementação
			if (linha_4.length() >= 9 ) {
				buffer.append("^FO65,240^A0,30,30^FD");
				buffer.append(linha_4);
				buffer.append("^FS");
			} else {
				buffer.append("^FO65,240^A0,45,45^FD");
				buffer.append(linha_4);
				buffer.append("^FS");
			} 
			  
			buffer.append("^FO280,240^A0,45,45^FD");
			buffer.append(linha_4_2);
			buffer.append("^FS");
			buffer.append("^FO65,310^A0,45,45^FD");
			buffer.append(codItem);
			buffer.append("^FS");
			buffer.append("^FO370,310^A0,45,45^FD");
			buffer.append(linha_6_3);
			buffer.append("^FS");
			buffer.append("^FO65,390^A0,45,45^FD");
			buffer.append(linha_2);
			buffer.append("^FS");
			buffer.append("^FO390,390^A0,45,45^FD");
			buffer.append(linha_4_1);
			buffer.append("^FS");
			buffer.append("^FO65,470^A0,45,45^FD");
			buffer.append(linha_6_2);
			buffer.append("^FS");
			/*
			 * Alterado em: 2012-04-02 16:00 Por: Mario Hercules Motivo:
			 * Solicitação da Engenharia Solicitante: Alan
			 * 
			 * DE: buffer.append("^FO180,470^A0,45,45^FD"); PARA:
			 * buffer.append("^FO160,470^A0,25,25^FD");
			 */
			buffer.append("^FO160,470^A0,25,25^FD");
			buffer.append(linha_6_1);
			buffer.append("^FS");
			buffer.append("^FO330,470^A0,45,45^FD");
			buffer.append(linha_6);
			buffer.append("^FS");
			buffer.append("^FO45,540^A0,45,45^FD");
			buffer.append(linha_8);
			buffer.append("^FS");
			buffer.append("^FO210,540^A0,45,45^FD");
			buffer.append(linha_8_1);
			buffer.append("^FS");
			buffer.append("^FO360,540^A0,45,45^FD");
			buffer.append(linha_14);
			buffer.append("^FS");
			buffer.append("^FO50,610^A0,60,60^FR^FD");
			buffer.append(linha_10_1);
			buffer.append("^FS");
			/*
			 * 
			 * Alterado por: Mario Hercules Motivo: Atender portaria
			 * Solicitante: Alan (Engenharia) Data: 2012-04-03
			 * 
			 * Antigo: buffer.append("^FO200,620^A0,45,35^FD"); Novo :
			 * buffer.append("^FO200,620^A0,45,35^FD");
			 */
			buffer.append("^FO200,620^A0,35,25^FD");
			buffer.append(linha_10);
			buffer.append("^FS");
			buffer.append("^FO370,620^A0,45,35^FD");
			buffer.append(linha_14_2);
			buffer.append("^FS");
			buffer.append("^FO45,705^A0,45,45^FD");
			buffer.append(linha_12);
			buffer.append("^FS");
			buffer.append("^FO210,705^A0,45,45^FD");
			buffer.append(linha_12_1);
			buffer.append("^FS");
			buffer.append("^FO360,705^A0,45,45^FD");
			buffer.append(linha_19);
			buffer.append("^FS");
			buffer.append("^FO65,790^A0,45,45^FD");
			buffer.append(linha_14_1);
			buffer.append("^FS");
			buffer.append("^FO210,790^A0,45,45^FD");
			buffer.append(linha_17_1);
			buffer.append("^FS");
			buffer.append("^FO340,790^A0,45,45^FD");
			buffer.append(linha_18_1);
			buffer.append("^FS");
			buffer.append("^BY3^FO120,840^BE,90,Y,N,^FD");
			buffer.append(linha_15);
			buffer.append("^FS");
			/*
			 * SOLICITANTE ALTERAÇÃO LEANDRO RODRIGUES
			 * <servicedesk@esmaltec.com.br>
			 * 
			 * A linha abaixo altera a largura e altura do codigo de barra da
			 * etiqueta
			 * 
			 * Anterior estava: "^BY5^FO470,60^BCR,180,N,N,^SN>;"
			 */
			buffer.append("^BY4^FO470,110^BCR,180,N,N,^SN>;");
			buffer.append(linha_16);
			buffer.append("^FS");
			buffer.append("^FO650,350^A0B,22,22^SN");
			buffer.append(linha_16_1);
			buffer.append("^FS^XZ");
			impressao = impressao + buffer.toString();

		}
		if ("3".equals(modelo)) {
			StringBuffer buffer = new StringBuffer();
			buffer.append("^XA^CFD"); // inicio
			buffer.append("^PQ1");
			buffer.append("^LH0,0"); // Posicionamento Inicial da Etiqueta
			buffer.append("^FO35,35^GB430,800,7,B^FS"); // GRADE INICIO (Fogão ,
														// Refrig , Micro)
			buffer.append("^FO35,210^GB430,0,4,B^FS");

			buffer.append("^FO35,280^GB430,0,4,B^FS");
			buffer.append("^FO35,350^GB430,0,4,B^FS");

			buffer.append("^FO35,430^GB430,0,4,B^FS");

			buffer.append("^FO240,350^GB0,75,4,B^FS");

			buffer.append("^FO35,515^GB430,0,4,B^FS");
			buffer.append("^FO180,430^GB0,85,4,B^FS");
			buffer.append("^FO330,430^GB0,85,4,B^FS"); // GRADE FIM
			buffer.append("^FO45,50^A0,28,28^FD");
			buffer.append(cabecalho_1);
			buffer.append("^FS"); // INICIO CABEÇALHO
			buffer.append("^FO45,110^A0,28,28^FD");
			buffer.append(cabecalho_2);
			buffer.append("^FS");
			buffer.append("^FO45,145^A0,28,28^FD");
			buffer.append(cabecalho_3);
			buffer.append("^FS");
			buffer.append("^FO45,180^A0,28,28^FD");
			buffer.append(cabecalho_4);
			buffer.append("^FS"); // FIM CABEÇALHO
			buffer.append("^FO35,600^GB430,0,4,B^FS"); // GRADE INICIO
														// (específica do Micro)
			buffer.append("^FO240,515^GB0,85,4,B^FS");
			buffer.append("^FO240,600^GB0,85,4,B^FS");
			buffer.append("^FO35,680^GB430,0,4,B^FS");
			buffer.append("^FO35,760^GB430,0,4,B^FS");
			buffer.append("^FO200,680^GB0,85,4,B^FS"); // GRADE FIM

			buffer.append("^FO45,230^A0,28,28^FD");
			buffer.append(linha_1);
			// buffer.append("Serie");
			buffer.append("^FS"); // INICIO DADOS

			buffer.append("^FO140,230^A0,45,45^SN");
			buffer.append(linha_2);
			buffer.append(",1Y^FS");

			buffer.append("^FO45,300^A0,28,28^FDProduto^FS");
			buffer.append("^FO150,300^A0,45,45^FD");
			buffer.append(codItem);
			buffer.append("^FS");

			buffer.append("^FO45,365^A0,28,28^FD");
			buffer.append(linha_3);
			buffer.append("^FS");
			buffer.append("^FO315,365^A0,28,28^FD");
			buffer.append(linha_3_1);
			buffer.append("^FS");
			buffer.append("^FO45,400^A0,35,35^FD");
			buffer.append(linha_4);
			buffer.append("^FS");
			buffer.append("^FO280,400^A0,35,35^FD");
			buffer.append(linha_4_1);
			buffer.append("^FS");
			buffer.append("^FO45,440^A0,28,28^FD");
			buffer.append(linha_5);
			buffer.append("^FS");
			buffer.append("^FO180,440^A0,28,28^FD");
			buffer.append(linha_5_1);
			buffer.append("^FS");
			buffer.append("^FO335,440^A0,28,28^FD");
			buffer.append(linha_5_2);
			buffer.append("^FS");
			buffer.append("^FO80,470^A0,50,50^FD");
			buffer.append(linha_6);
			buffer.append("^FS");
			buffer.append("^FO210,470^A0,50,50^FD");
			buffer.append(linha_6_1);
			buffer.append("^FS");
			buffer.append("^FO360,470^A0,50,50^FD");
			buffer.append(linha_6_2);
			buffer.append("^FS");

			buffer.append("^FO45,525^A0,28,28^FD");
			buffer.append(linha_7);
			buffer.append("^FS");
			buffer.append("^FO250,525^A0,28,28^FD");
			buffer.append(linha_7_1);
			buffer.append("^FS");
			buffer.append("^FO60,555^A0,50,50^FD");
			buffer.append(linha_8);
			buffer.append("^FS");
			buffer.append("^FO260,555^A0,50,50^FD");
			buffer.append(linha_8_1);
			buffer.append("^FS");
			buffer.append("^FO45,610^A0,28,28^FD");
			buffer.append(linha_9);
			buffer.append("^FS");

			buffer.append("^FO250,610^A0,28,28^FD");
			buffer.append(linha_13);
			buffer.append("^FS");

			buffer.append("^FO55,640^A0,41,41^FD");
			buffer.append(linha_10);
			buffer.append("^FS");

			buffer.append("^FO260,640^A0,41,41^FD");
			buffer.append(linha_14);
			buffer.append("^FS");

			buffer.append("^FO55,690^A0,28,28^FD");
			buffer.append(linha_11);
			buffer.append("^FS");
			buffer.append("^FO220,690^A0,28,28^FD");
			buffer.append(linha_11_1);
			buffer.append("^FS");
			buffer.append("^FO70,720^A0,50,50^FD");
			buffer.append(linha_12);
			buffer.append("^FS");
			buffer.append("^FO210,720^A0,50,50^FD");
			buffer.append(linha_12_1);
			buffer.append("^FS");

			buffer.append("^FO60,770^A0,28,28^FD");
			buffer.append("ENERGIA DE MICROONDAS");
			buffer.append("^FS");

			buffer.append("^FO60,800^A0,28,28^FH_^FD");
			buffer.append("N_c6O REMOVA O GABINETE");
			buffer.append("^FS");

			/*
			 * buffer.append("^FO90,800^A0,35,35^FD"); buffer.append(linha_14);
			 * buffer.append("^FS"); buffer.append("^FO210,780^A0,50,50^FR^FD");
			 * buffer.append(linha_14_1); buffer.append("^FS");
			 */

			buffer.append("^BY3");
			buffer.append("^FO120,840^BE,90,Y,N,^FD");
			buffer.append(linha_15);
			buffer.append("^FS");
			/*
			 * SOLICITANTE ALTERAÇÃO LEANDRO RODRIGUES
			 * <servicedesk@esmaltec.com.br>
			 * 
			 * A linha abaixo altera a largura e altura do codigo de barra da
			 * etiqueta
			 * 
			 * Anterior estava: "^BY5^FO470,60^BCR,180,N,N,^SN>;"
			 */
			buffer.append("^BY4^FO470,110^BCR,180,N,N,^SN>;");
			buffer.append(linha_16);
			buffer.append("^FS");
			buffer.append("^FO650,350^A0B,22,22^SN");
			buffer.append(linha_16_1);
			buffer.append("^FS"); // FIM DADOS
			buffer.append("^XZ"); // fim
			impressao = impressao + buffer.toString();
		}
	}

	public void imprimeArquivo() throws FileNotFoundException, IOException {
		byte b[] = impressao.getBytes();

		FileOutputStream out = new FileOutputStream("/sisetiq/impressao.txt"); // Linux

		out.write(b);
		impressao = "";
		out.close();

		if (isCE || isIN) {
			if (isCE) {
				Runtime.getRuntime().exec("/sisetiq/bin/imprime.sh");
				logger.debug("Impressao produto CE");
			} else {
				Runtime.getRuntime().exec("/sisetiq/bin/imprimeIN.sh");
				logger.debug("Impressao produto INMETRO");
			}
		} else {
			Runtime.getRuntime().exec("lpr -P zebra /sisetiq/impressao.txt"); // Linux
			logger.debug("Impressao normal");
		}

	}

	public void imprimeArquivoGenerico() throws FileNotFoundException,
			IOException {
		byte b[] = impressao.getBytes();

		String arquivo = generateFileName("txt");
		FileOutputStream out = new FileOutputStream(pathSistema + "/" + arquivo); // Linux

		out.write(b);
		impressao = "";
		out.close();

		String comando;

		if (isCE || isIN) {
			if (isCE) {
				comando = pathSistema + "/bin/imprimeCE.sh " + pathSistema
						+ "/" + arquivo;
				Runtime.getRuntime().exec(comando);
				logger.debug("Impressao produto CE");
			} else {
				comando = pathSistema + "/bin/imprimeIN.sh " + pathSistema
						+ "/" + arquivo;
				Runtime.getRuntime().exec(comando);
				logger.debug("Impressao produto INMETRO");
			}
		} else {
			comando = pathSistema + "/bin/imprime.sh " + pathSistema + "/"
					+ arquivo;
			Runtime.getRuntime().exec(comando); // Linux
			logger.debug("Impressao normal -> " + comando);
		}

	}

	public void imprimeArquivoWindows() throws FileNotFoundException,
			IOException {

		byte b[] = impressao.getBytes();

		FileOutputStream out = new FileOutputStream("C:/sisetiq/impressao.txt"); // Windows

		out.write(b);
		impressao = "";
		out.close();

		if (isCE || isIN) {
			if (isCE) {
				Runtime.getRuntime().exec("c:/sisetiq/bin/imprimeCE.bat");
				logger.debug("Impressao produto CE");
			} else {
				Runtime.getRuntime().exec("c:/sisetiq/bin/imprimeIN.bat");
				logger.debug("Impressao produto INMETRO");
			}
		} else {
			Runtime.getRuntime().exec("c:/sisetiq/bin/imprime.bat"); // Windows
			logger.debug("Impressao normal");
		}

	}

	protected String generateFileName(String formato) {
		return (new UID()).toString().replace('-', 'A').replace('/', 'B')
				.replace(':', 'P')
				+ "." + formato;
	}

	public String getDataHora() {
		Date data = new Date();
		SimpleDateFormat formatadorData = new SimpleDateFormat("dd/MM/yyyy");
		SimpleDateFormat formatadorHora = new SimpleDateFormat("HH:mm:ss");
		String dataStr = formatadorData.format(data);
		String horaStr = formatadorHora.format(data);
		return (dataStr + " - " + horaStr);
	}

	public static void main(String args[]) {

		Connection connection = null;

		try {
			SSPersistenceFactory
					.setPath("F:/informatica/Projetos/app/0026_Etiquetas30");

			connection = ConnectionOracle.getConnection();

			ImprimeEtiqueta etiq = new ImprimeEtiqueta("106", "103150320703",
					"1", "N", "2", new Integer(1), null, connection, "", false,
					"WIN");

			etiq.processar();
		} catch (SQLException sqle) {
			sqle.printStackTrace();

		} catch (SSException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally {
			try {
				connection.close();
			} catch (SQLException e) {
			}
		}
	}

}
