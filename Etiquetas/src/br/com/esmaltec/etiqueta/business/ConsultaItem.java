package br.com.esmaltec.etiqueta.business;
import java.sql.Connection;
import java.sql.SQLException;

import org.apache.log4j.Logger;

import br.com.softsite.sfc.business.SSValueObject;
import br.com.softsite.sfc.exception.SSError;
import br.com.softsite.sfc.exception.SSException;
import br.com.softsite.sfc.persistence.SSPersistence;
import br.com.softsite.sfc.persistence.SSPersistenceFactory;

/**
 * @author rafael@esmaltec.com.br
 *
 * 
 */

public class ConsultaItem {
	
	SSPersistence pst = null;
	String pstDir;
	String pstName;
	Logger logger ;
	
	Connection conexao = null;
	
	
	public ConsultaItem() {
		pstDir = "xml";
		pstName = "etiqueta";
		logger = Logger.getLogger(ConsultaItem.class);
	}


	// Este outro construtor será utilizado para metodos 
	// que não irao instanciar uma conexão , para termos uma
	// conexao unica

	public ConsultaItem(Connection con) {
		pstDir = "xml";
		pstName = "etiqueta";
		logger = Logger.getLogger(ConsultaItem.class);
		conexao = con;		
	}




	public String getDenItem(String codItem) {
		
		SSValueObject paramVO = new SSValueObject();
		SSValueObject itemVO = null;
		String denItem = null;
		Connection connection = ConnectionOracle.getConnection();
		logger.debug("Setou conexao local");
		paramVO.set(0,"COD_ITEM",codItem);
		try {		
			pst = SSPersistenceFactory.getInstance( pstDir,pstName,connection);
			itemVO = pst.executeQuery("ObterItem",paramVO);
			denItem = (String)itemVO.get(0,"DEN_ITEM");
			
		}
		catch(SSException sse) {
			logger.debug(sse.getErrors());
		}
		finally {
			try {
				if(connection != null)
				connection.close();
				logger.debug("Fechou conexao local");
			}
			catch(SQLException sqle) {
				//
			}
		}
		return denItem;
	}
	
	public String getNumEtiqCompleta(String codItem) {
		SSValueObject paramVO = new SSValueObject();
		SSValueObject itemVO = null;
		String numEtiqCompleta = null;
		Connection connection = ConnectionOracle.getConnection();
		logger.debug("Setou conexao" + connection);
		paramVO.set(0,"COD_EMPRESA","106");
		paramVO.set(0,"COD_ITEM",codItem);
		try {		
			pst = SSPersistenceFactory.getInstance( pstDir,pstName,connection);
			itemVO = pst.executeQuery("ObterInformacaoItem",paramVO);
			if(itemVO.size() > 0 ) {
				numEtiqCompleta = ((Integer)itemVO.get(0,"NUM_ETIQ_COMPLETA")).toString();
			}
			else {
				numEtiqCompleta = "2";
			}
			logger.debug("Obteve num Etiqueta Completa: " + numEtiqCompleta);	
		}
		catch(SSException sse) {
			logger.debug("SSException: " + sse.getErrors());
		}
		catch(Exception e) {
			logger.debug("Exception: " + e.getMessage() + e.getCause());
		}
		finally {
			try {
				if(connection != null)
				connection.close();
				logger.debug("Fechou conexao");
			}
			catch(SQLException sqle) {
				//
			}
		}
		return numEtiqCompleta;
	}
	public String getCargaGas(String codItem, String compressor) {
		SSValueObject paramVO = new SSValueObject();
		SSValueObject itemVO = null;
		String valorCarga = null;
		Connection connection = ConnectionOracle.getConnection();
		logger.debug("Setou conexao");
		paramVO.set(0,"COMPRESSOR",compressor);
		paramVO.set(0,"COD_ITEM",codItem);
		try {		
			pst = SSPersistenceFactory.getInstance( pstDir,pstName,connection);
			itemVO = pst.executeQuery("ObterCargaGas",paramVO);
			valorCarga = (String)itemVO.get(0,"VALOR_GAS");
			
		}
		catch(SSException sse) {
			logger.debug(sse.getErrors());
		}
		finally {
			try {
				if(connection != null)
				connection.close();
				logger.debug("Fechou conexao");
			}
			catch(SQLException sqle) {
				//
			}
		}
		return valorCarga;
	}
	
	
	public void insereItem(String codEmpresa , String codItem , String numSerie,String datProducao) {
		SSValueObject paramVO = new SSValueObject();
		Connection connection = ConnectionOracle.getConnection();
		logger.debug("Setou conexao");
		paramVO.set(0,"COD_EMPRESA",codEmpresa);
		paramVO.set(0,"COD_ITEM",codItem);
		paramVO.set(0,"NUM_SERIE",numSerie);
		paramVO.set(0,"DATA_PRODUCAO",datProducao);
		try {		
			pst = SSPersistenceFactory.getInstance( pstDir,pstName,connection);
			pst.executeUpdate("InserirItemSerie",paramVO);
		}
		catch(SSException sse) {
			logger.debug(sse.getErrors());
		}
		finally {
			try {
				if(connection != null)
				connection.close();
				logger.debug("Fechou conexao");
			}
			catch(SQLException sqle) {
				//
			}
		}
	}
	
	public void insereItem(SSValueObject paramVO) {
		Connection connection = ConnectionOracle.getConnection();
		logger.debug("Setou conexao");
		try {		
			pst = SSPersistenceFactory.getInstance(pstDir,pstName,connection);
			pst.insert(paramVO);
		}
		catch(SSException sse) {
			logger.debug(sse.getErrors());
		}
		finally {
			try {
				if(connection != null)
				connection.close();
				logger.debug("Fechou conexao");
			}
			catch(SQLException sqle) {
				//
			}
		}
	}
	
	public boolean validaSerie(String codEmpresa , String codItem, String serie ) {
		SSValueObject paramVO = new SSValueObject();
		SSValueObject itemVO = null;
		Connection connection = ConnectionOracle.getConnection();
		logger.debug("Setou conexao");
		paramVO.set(0,"COD_EMPRESA",codEmpresa);
		paramVO.set(0,"COD_ITEM",codItem);
		paramVO.set(0,"NUM_SERIE",serie);
		try {		
			pst = SSPersistenceFactory.getInstance( pstDir,pstName,connection);
			itemVO = pst.executeQuery("ValidaSerie",paramVO);
			int totalSerie = ((Integer)itemVO.get(0,"TOTAL")).intValue();
			if(totalSerie == 0) return false;
			return true;
		}
		catch(SSException sse) {
			logger.debug(sse.getErrors());
			return false;
		}
		finally {
			try {
				if(connection != null)
				connection.close();
				logger.debug("Fechou conexao");
			}
			catch(SQLException sqle) {
				//
			}
		}
	}
	
	public boolean validaSerie(String codEmpresa , String codItem, String serie,Connection connection ) throws SSException {
		SSValueObject paramVO = new SSValueObject();
		SSValueObject itemVO = null;

		logger.debug("Setou parametros");
		paramVO.set(0,"COD_EMPRESA",codEmpresa);
		paramVO.set(0,"COD_ITEM",codItem);
		paramVO.set(0,"NUM_SERIE",serie);

		pst = SSPersistenceFactory.getInstance( pstDir,pstName,connection);
		itemVO = pst.executeQuery("ValidaSerie",paramVO);
		int totalSerie = ((Integer)itemVO.get(0,"TOTAL")).intValue();
		if(totalSerie == 0) return false;
		return true;
	}	
	
	public String obterCodItem(String codEmpresa,String modeloCor,String voltagem , Connection connection) throws SSException {
		SSValueObject paramVO = new SSValueObject();
		pst = SSPersistenceFactory.getInstance( pstDir,pstName,connection);
		SSValueObject modeloVO = null;
		
		logger.debug("Setou parametros");
		
		paramVO.set(0,"COD_EMPRESA",codEmpresa);
		paramVO.set(0,"COD_MODELO",modeloCor);
		paramVO.set(0,"COD_VOLTAGEM",voltagem);		
		
		modeloVO = pst.executeQuery("ObterCodItem",paramVO);
		if(modeloVO.size()==0) {
			throw new SSException(new SSError("Item nao encontrado"));	
		}
		String codItem = (String) modeloVO.get(0,"ITEM");
				
		return codItem;
	}
	
	public String obterModeloCor(String modeloCor , Connection connection ) throws SSException {
		SSValueObject paramVO = new SSValueObject();
		pst = SSPersistenceFactory.getInstance( pstDir,pstName,connection);
		SSValueObject modeloVO = null;
		
		logger.debug("Setou parametros");
		paramVO.set(0,"COD_MODELO",modeloCor);
		
		modeloVO = pst.executeQuery("ObterModelo",paramVO);
		if(modeloVO.size()==0) {
			throw new SSException(new SSError("Modelo nao encontrado"));	
		}
		String denModeloCor = (String) modeloVO.get(0,"DEN_MODELO_COR");
				
		return denModeloCor;
	}
}
