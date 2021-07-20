package br.com.esmaltec.etiqueta.business;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Properties;

import org.apache.log4j.Logger;

/**
 * @author Rafael Lima
 * 
 *         Classe responsável por instanciar a conexão com o Bando de Dados
 * 
 */
public class ConnectionOracle {

	public ConnectionOracle() {
	}

	/*
	 * public static Connection getConnection() {
	 * 
	 * Connection connection = null; Logger logger =
	 * Logger.getLogger(br.com.esmaltec
	 * .etiqueta.business.ConnectionOracle.class);
	 * 
	 * Properties config = PropertyLoader
	 * .loadProperties("config/etiqueta.properties");
	 * 
	 * String driverBD = config.getProperty("connection.driver"); String urlBD =
	 * config.getProperty("connection.url");
	 * 
	 * try { //Class.forName("oracle.jdbc.driver.OracleDriver"); //connection =
	 * DriverManager
	 * .getConnection("jdbc:oracle:thin:logix/logix@192.168.192.4:1521:lgxprd");
	 * connection = ConnectionOracle.getConnection(driverBD, urlBD);
	 * //connection = DriverManager.getConnection(
	 * "jdbc:oracle:thin:logix/logix@teste.esmaltec.com.br:1521:teste");
	 * connection.setAutoCommit(false);
	 * logger.debug("Setou conexao com banco producao getConnection()");
	 * //logger.debug("Setou conexao com banco de teste"); }
	 * //catch(ClassNotFoundException cnfe) //{ //
	 * logger.debug("ClassNotFoundException " + cnfe.getMessage()); //}
	 * catch(SQLException sqle) { logger.debug(sqle.getMessage()); } return
	 * connection;
	 * 
	 * }
	 */

	public static Connection getConnection() {

		Connection connection = null;
		Logger logger = Logger
				.getLogger(br.com.esmaltec.etiqueta.business.ConnectionOracle.class);

		Properties config = PropertyLoader
				.loadProperties("config/etiqueta.properties");

		String driverBD = config.getProperty("connection.driver");
		String urlBD = config.getProperty("connection.url");
 
		try {
			connection = ConnectionOracle.getConnection(driverBD, urlBD);
			connection.setAutoCommit(false);
			logger.debug("Setou conexao com banco producao getConnection()");
		} catch (SQLException sqle) {
			logger.debug(sqle.getMessage());
		}
		return connection;

	}

	public static Connection getConnection(String driver, String urlBD) {
		Connection connection = null;
		Logger logger = Logger
				.getLogger(br.com.esmaltec.etiqueta.business.ConnectionOracle.class);
		try {
			Class.forName(driver);
			connection = DriverManager.getConnection(urlBD);
			connection.setAutoCommit(false);
			logger.debug("Setou conexao com banco producao getConnection(String driver, String urlBD)");
		} catch (ClassNotFoundException cnfe) {
			logger.debug("ClassNotFoundException " + cnfe.getMessage());
		} catch (SQLException sqle) {
			logger.debug(sqle.getMessage());
		}
		return connection;
	}
}