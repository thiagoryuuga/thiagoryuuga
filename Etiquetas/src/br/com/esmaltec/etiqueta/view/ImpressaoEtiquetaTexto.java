package br.com.esmaltec.etiqueta.view;

import java.sql.Connection;
import java.sql.SQLException;
import java.util.Properties;

import charva.awt.*;
import charva.awt.event.*;
import charvax.swing.*;

import org.apache.log4j.Logger;

import br.com.esmaltec.etiqueta.business.ConnectionOracle;
import br.com.esmaltec.etiqueta.business.ConsultaItem;
import br.com.esmaltec.etiqueta.business.ImprimeEtiqueta;
import br.com.esmaltec.etiqueta.business.PropertyLoader;
import br.com.softsite.sfc.exception.SSException;
import br.com.softsite.sfc.persistence.SSPersistenceFactory;

public class ImpressaoEtiquetaTexto extends JFrame implements ActionListener {

	String codEmpresa = null;
	String sisOper = null;
	private JLabel l_codItem = null;
	private JLabel l_qtdEtiq = null;
	private JLabel l_iesLote = null;
	private JLabel l_modelo = null;
	private JLabel l_numVias = null;
	private JLabel l_compressor = null;

	private static int qtdMaxEtiq;

	private Logger logger;
	private Connection connection;

	String numEtiqCompleta = null;

	String infoItem = "";

	private JTextField codItem = new JTextField(12);
	private JTextField denItem = new JTextField(40);
	private JTextField qtdEtiq = new JTextField(6);
	private JTextField iesLote = new JTextField("N", 1);
	private JTextField modelo = new JTextField(1);
	private JTextField numVias = new JTextField(2);
	private JTextField compressor = new JTextField(1);

	private JButton _b1 = null;
	private JButton _b2 = null;
	private JButton fechar = null;

	private JButton reImpressao = null;

	JPanel contentPane;
	JPanel centerPanel = new JPanel();

	String conteudo;

	public static void main(String[] args) {

		String codEmpresa = null;
		String sisOper = null;
		Connection connection = null;
		String pathXml = null;
		String driverBD = null;
		String urlBD = null;

		Logger log = Logger.getLogger(ImpressaoEtiquetaTexto.class);

		Properties config = PropertyLoader
				.loadProperties("config/etiqueta.properties");

		codEmpresa = config.getProperty("etiqueta.empresa");
		sisOper = config.getProperty("etiqueta.sisoper");
		qtdMaxEtiq = new Integer(config.getProperty("etiqueta.qtdMaxEtiq"))
				.intValue();
		pathXml = config.getProperty("path.xml");

		driverBD = config.getProperty("connection.driver");
		urlBD = config.getProperty("connection.url");

		log.debug("Qtd Max de Etiquetas " + qtdMaxEtiq);
		log.debug("Cod Empresa " + codEmpresa);
		log.debug("Sistema Operacional " + sisOper);

		try {

			// Mudar para /sisetiq quando não estiver em ambiente de
			// desenvolvimento

			SSPersistenceFactory.setPath(pathXml);

			connection = ConnectionOracle.getConnection(driverBD, urlBD);

			ImpressaoEtiquetaTexto sisEtiq = new ImpressaoEtiquetaTexto(
					connection);

			sisEtiq.setCodEmpresa(codEmpresa);
			sisEtiq.setSisOper(sisOper);

			sisEtiq.pack();
			sisEtiq.setSize(80, 25);
			sisEtiq.setVisible(true);

		} catch (Exception e) {
			log.debug(e.getMessage());
		}
	}

	public ImpressaoEtiquetaTexto(Connection connection) {

		this.connection = connection;
		logger = Logger.getLogger(ImpressaoEtiquetaTexto.class);

		this.setTitle("Sistema de Impressao de Etiqueta");

		setForeground(Color.white);
		setBackground(Color.blue);

		Color cor = Color.black;

		codItem.setBackground(cor);
		qtdEtiq.setBackground(cor);
		iesLote.setBackground(cor);
		modelo.setBackground(cor);
		numVias.setBackground(cor);
		compressor.setBackground(cor);

		l_codItem = new JLabel("Cod Item   ");
		l_qtdEtiq = new JLabel("Qtd Etiq   ");
		l_iesLote = new JLabel("Imp. Lote   ");
		l_modelo = new JLabel("Modelo   ");
		l_numVias = new JLabel("Num Vias   ");
		l_compressor = new JLabel("Compressor   ");

		JLabel obs_modelo = new JLabel(
				"1 - Fogao | 2 - Refrigeracao");

/*
 * 
 * 		JLabel obs_modelo = new JLabel(
				"1 - Fogao | 2 - Refrigeracao | 3 - Micro");

 * 		
 */
		
		JLabel obs_lote = new JLabel("S - Em Lotes | N - Alternadas");
		JLabel obs_compressor = new JLabel("Vazio | 1 Embraco | 2 Tecumseh");

		codItem.addActionListener(this);
		qtdEtiq.addActionListener(this);
		iesLote.addActionListener(this);
		modelo.addActionListener(this);
		numVias.addActionListener(this);
		compressor.addActionListener(this);
		
		numVias.addFocusListener(new FocusListener() {
		      public void focusGained(FocusEvent e) {
		        //displayMessage("Focus gained", e);
		    	  //showMessagemAviso("Focus gained");
		    	  //logger.debug("Focus : gained");
		      }

		      public void focusLost(FocusEvent e) {
		        //displayMessage("Focus lost", e);
		    	  //showMessagemAviso("Focus lost");
		    	  //logger.debug("Focus : lost");
		    	  		    	  
		  			String conteudo = numVias.getText();
		  			
		  			if (conteudo.length() > 0) {
		  				try {
		  					int numeroVias = (new Integer(numVias.getText()))
		  							.intValue();
		  					if (numeroVias == 0 || numeroVias > 2)
		  						throw new NumberFormatException();
		  					if ("1".equals(modelo.getText()))
		  						_b1.requestFocus();
		  					if ("2".equals(modelo.getText()))
		  						compressor.requestFocus();
		  					if ("3".equals(modelo.getText()))
		  						_b1.requestFocus();
		  				} catch (NumberFormatException nfe) {
		  					showMessagemAviso("Numero de vias invalido !");
		  					numVias.setText("");
		  					numVias.requestFocus();
		  				}
		  			}			  				    	 
		    	  
		      }

		      });


		_b1 = new JButton("OK");

		_b1.addActionListener(this);

		_b2 = new JButton("Cancel");

		_b2.addActionListener(this);

		reImpressao = new JButton("Reimpressao");

		reImpressao.addActionListener(this);

		fechar = new JButton("Fechar");

		fechar.addActionListener(this);

		centerPanel.setLayout(new GridBagLayout());

		GridBagConstraints gbc = new GridBagConstraints();

		gbc.gridx = gbc.gridy = 0;
		gbc.anchor = GridBagConstraints.EAST;

		centerPanel.add(l_codItem, gbc);

		gbc.gridy = 1;
		centerPanel.add(l_qtdEtiq, gbc);

		gbc.gridy = 2;
		centerPanel.add(l_iesLote, gbc);

		gbc.gridy = 3;
		centerPanel.add(l_modelo, gbc);

		gbc.gridy = 4;
		centerPanel.add(l_numVias, gbc);

		gbc.gridy = 5;
		centerPanel.add(l_compressor, gbc);

		// Segunda Coluna

		gbc.gridx = 1;
		gbc.gridy = 0;
		gbc.anchor = GridBagConstraints.WEST;
		centerPanel.add(codItem, gbc);

		gbc.gridy = 1;
		centerPanel.add(qtdEtiq, gbc);

		gbc.gridy = 2;
		centerPanel.add(iesLote, gbc);

		gbc.gridy = 3;
		centerPanel.add(modelo, gbc);

		gbc.gridy = 4;
		centerPanel.add(numVias, gbc);

		gbc.gridy = 5;
		centerPanel.add(compressor, gbc);

		gbc.gridy = 6;
		gbc.anchor = GridBagConstraints.CENTER;
		centerPanel.add(_b1, gbc);

		// Terceira Coluna
		gbc.gridx = 2;
		gbc.gridy = 6;
		gbc.anchor = GridBagConstraints.CENTER;
		centerPanel.add(_b2, gbc);

		// Quarta Coluna

		gbc.gridx = 3;
		gbc.gridy = 0;
		gbc.anchor = GridBagConstraints.WEST;

		denItem.setEnabled(false);
		centerPanel.add(denItem, gbc);

		gbc.gridy = 2;
		centerPanel.add(obs_lote, gbc);

		gbc.gridy = 3;
		centerPanel.add(obs_modelo, gbc);

		gbc.gridy = 5;
		centerPanel.add(obs_compressor, gbc);

		gbc.gridy = 6;
		gbc.anchor = GridBagConstraints.CENTER;
		centerPanel.add(reImpressao, gbc);

		contentPane = (JPanel) getContentPane();
		contentPane.add(centerPanel, BorderLayout.CENTER);
		contentPane.add(fechar, BorderLayout.SOUTH);
	}

	/**
	 * Sets the codEmpresa.
	 * 
	 * @param codEmpresa
	 *            The codEmpresa to set
	 */
	public void setCodEmpresa(String codEmpresa) {
		this.codEmpresa = codEmpresa;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * charva.awt.event.ActionListener#actionPerformed(charva.awt.event.ActionEvent
	 * )
	 */
	public void actionPerformed(ActionEvent e) {

		JComponent w = (JComponent) e.getSource();

		if (e.getActionCommand().equals("OK")
				&& codItem.getText().length() == 10) {

			try {
				ImprimeEtiqueta etiq = new ImprimeEtiqueta(codEmpresa,
						codItem.getText(), qtdEtiq.getText(),
						iesLote.getText(), modelo.getText(), new Integer(
								numVias.getText()), compressor.getText(),
						connection, "", false, sisOper);

				etiq.processar();

				connection.commit();

				showMessagemAviso("Etiqueta foi Impressa LPT1!");

				codItem.setText("");
				denItem.setText("");
				qtdEtiq.setText("");
				iesLote.setText("N");
				modelo.setText("");
				numVias.setText("");
				compressor.setText("");
				compressor.setVisible(true);
				codItem.requestFocus();
				repaint();
			} catch (SQLException sqle) {
				try {
					showMessagemAviso("Erro na Impressao! 1");
					logger.debug(sqle.getMessage());
					connection.rollback();
				} catch (SQLException sql) {
				}
			} catch (SSException sse) {
				try {
					showMessagemAviso("Erro na Impressao! 2");
					logger.debug(sse.getMessage());
					connection.rollback();
				} catch (SQLException sql) {
				}
			} catch (Exception ex) {
				try {
					showMessagemAviso("Erro na Impressao! 3");
					logger.debug(ex.getMessage());
					connection.rollback();
				} catch (SQLException sql) {
				}
			}

		}
		if (e.getActionCommand().equals("Cancel")) {
			codItem.setText("");
			denItem.setText("");
			qtdEtiq.setText("");
			iesLote.setText("N");
			modelo.setText("");
			numVias.setText("");
			compressor.setText("");
			codItem.requestFocus();
			repaint();
		}

		if (e.getActionCommand().equals("Reimpressao")) {
			ReimpressaoEtiquetaTexto reimpressao = new ReimpressaoEtiquetaTexto(
					connection);

			reimpressao.setCodEmpresa(codEmpresa);
			reimpressao.setSisOper(sisOper);

			reimpressao.pack();
			reimpressao.setSize(80, 25);
			reimpressao.setVisible(true);

			codItem.requestFocus();
		}
		if (w == codItem) {
			conteudo = codItem.getText();
			if (conteudo.length() == 10) {
				String descItem = null;
				ConsultaItem consultaItem = new ConsultaItem();
				descItem = consultaItem.getDenItem(conteudo);
				if (descItem == null || "".equals(descItem)) {
					showMessagemAviso("Item Invalido !");
					codItem.setText("");
				}
				if (descItem != null && !"".equals(descItem)) {
					numEtiqCompleta = consultaItem.getNumEtiqCompleta(conteudo);
					denItem.setText(descItem);
					numVias.setText(numEtiqCompleta);
					qtdEtiq.requestFocus();
					repaint();
				}
			}
		}

		if (w == qtdEtiq) {
			conteudo = qtdEtiq.getText();
			if (conteudo.length() > 0) {
				try {
					int qtdEtiquetas = (new Integer(qtdEtiq.getText()))
							.intValue();
					if (qtdEtiquetas == 0 || qtdEtiquetas > qtdMaxEtiq)
						throw new NumberFormatException();
					iesLote.requestFocus();
				} catch (NumberFormatException nfe) {
					showMessagemAviso("Quantidade invalida !");
					qtdEtiq.setText("");
				}
			}

		}

		//
		if (w == iesLote) {
			conteudo = iesLote.getText();
			if (conteudo.length() > 0) {
				if (!"".equals(qtdEtiq.getText())
						&& !"S".equals(iesLote.getText())
						&& !"N".equals(iesLote.getText())) {
					showMessagemAviso("Tipo de Lote Invalido !");
					iesLote.setText("");
				} else
					modelo.requestFocus();
			}
		}
		if (w == modelo) {
			conteudo = modelo.getText();
			if (conteudo.length() > 0) {
				if (!"".equals(iesLote.getText())
						&& !"1".equals(modelo.getText())
						&& !"2".equals(modelo.getText())
						&& !"3".equals(modelo.getText())) {
					showMessagemAviso("Tipo de Modelo Invalido !");
					modelo.setText("");
				} else {
					if ("1".equals(modelo.getText()))
						compressor.setVisible(false);
					if ("2".equals(modelo.getText()))
						compressor.setVisible(true);
					if ("3".equals(modelo.getText()))
						compressor.setVisible(false);
					numVias.requestFocus();
				}
			}
		}
		if (w == numVias) {
			conteudo = numVias.getText();
			if (conteudo.length() > 0) {
				try {
					int numeroVias = (new Integer(numVias.getText()))
							.intValue();
					if (numeroVias == 0 || numeroVias > 2)
						throw new NumberFormatException();
					if ("1".equals(modelo.getText()))
						_b1.requestFocus();
					if ("2".equals(modelo.getText()))
						compressor.requestFocus();
					if ("3".equals(modelo.getText()))
						_b1.requestFocus();
				} catch (NumberFormatException nfe) {
					showMessagemAviso("Numero de vias invalido !");
					numVias.setText("");
				}
			}

		}
		if (w == compressor) {
			_b1.requestFocus();
		}
		if (w == fechar) {
			logger.debug("Encerrando Programa . Fechando Conexao");
			try {
				connection.close();
			} catch (SQLException sqle) {
				logger.debug("SQLException : Erro ao fechar conexao");
			}
			System.exit(0);
		}

	} // Fim

	public void showMessagemAviso(String msg) {
		JOptionPane.showMessageDialog(this, msg, "Erro",
				JOptionPane.PLAIN_MESSAGE);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * charva.awt.event.WindowListener#windowClosing(charva.awt.event.WindowEvent
	 * )
	 */
	public void windowClosing(WindowEvent arg0) {
		logger.debug("Encerrando programa ! Fechando conexao!!!");
		if (connection != null)
			try {
				connection.close();
			} catch (SQLException sqle) {
				logger.debug("Nao foi possivel fechar a conexao");
			}

	}

	/**
	 * @param string
	 */
	public void setSisOper(String string) {
		sisOper = string;
	}

}