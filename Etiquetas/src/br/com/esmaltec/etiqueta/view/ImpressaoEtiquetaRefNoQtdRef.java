package br.com.esmaltec.etiqueta.view;
 
import java.sql.Connection;
import java.sql.SQLException;

import charva.awt.*;
import charva.awt.event.*;
import charvax.swing.*;

import org.apache.log4j.Logger;

import br.com.esmaltec.etiqueta.business.ConnectionOracle;
import br.com.esmaltec.etiqueta.business.ConsultaItem;
import br.com.esmaltec.etiqueta.business.ImprimeEtiqueta;
import br.com.softsite.sfc.exception.SSException;
import br.com.softsite.sfc.persistence.SSPersistenceFactory;

public class ImpressaoEtiquetaRefNoQtdRef extends JFrame implements ActionListener {

	String codEmpresa = null;
	String codItem = null;
	String sisOper = null;
	private JLabel l_codItem = null;
	private JLabel l_voltagem = null;

	private static int qtdMaxEtiq;

	private Logger logger;
	private Connection connection;

	String numEtiqCompleta = null;

	String infoItem = "";

	private JTextField modeloCor = new JTextField(2);
	private JTextField denModeloCor = new JTextField(40);
	private JTextField voltagem = new JTextField(1);

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

		Logger log = Logger.getLogger(ImpressaoEtiquetaRefNoQtdRef.class);

		codEmpresa = "106";

		if (args.length != 0) {
			try {
				qtdMaxEtiq = new Integer(args[0]).intValue();
			} catch (Exception e) {
				log.debug("Exception no numero max. Usando padrao 200");
				qtdMaxEtiq = 200;
			}
			try {
				codEmpresa = args[1];
			} catch (Exception e) {
				log.debug("Exception no codigo da empresa. Usando padrao 106");
				codEmpresa = "106";
			}
			try {
				sisOper = args[2];
			} catch (Exception e) {
				log.debug("Exception no SO. Usando padrao Linux");
				sisOper = "LNX";
			}

		} else {
			qtdMaxEtiq = 100;
			codEmpresa = "106";
			sisOper = "LNX";
		}

		log.debug("Qtd Max de Etiquetas " + qtdMaxEtiq);
		log.debug("Cod Empresa " + codEmpresa);
		log.debug("Sistema Operacional " + sisOper);

		try {

			// Mudar para /sisetiq quando não estiver em ambiente de
			// desenvolvimento

			// SSPersistenceFactory.setPath("F:/informatica/Rafael/Projetos/etiquetas30");
			SSPersistenceFactory.setPath("/sisetiq");

			connection = ConnectionOracle.getConnection();

			ImpressaoEtiquetaRefNoQtdRef sisEtiq = new ImpressaoEtiquetaRefNoQtdRef(connection);

			sisEtiq.setCodEmpresa(codEmpresa);
			sisEtiq.setSisOper(sisOper);

			sisEtiq.pack();
			sisEtiq.setSize(80, 25);
			sisEtiq.setVisible(true);

		} catch (Exception e) {
			log.debug(e.getMessage());
		}
	}

	public ImpressaoEtiquetaRefNoQtdRef(Connection connection) {

		this.connection = connection;
		logger = Logger.getLogger(ImpressaoEtiquetaRefNoQtdRef.class);

		this.setTitle("Sistema de Impressao de Etiqueta");

		setForeground(Color.white);
		setBackground(Color.blue);

		Color cor = Color.black;

		modeloCor.setBackground(cor);
		voltagem.setBackground(cor);

		l_codItem = new JLabel("Modelo   ");
		l_voltagem = new JLabel("Voltagem   ");

		JLabel obs_voltagem = new JLabel("1 = 110 Volts | 2 = 220 Volts");

		modeloCor.addActionListener(this);
		voltagem.addActionListener(this);

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
		centerPanel.add(l_voltagem, gbc);

		// Segunda Coluna

		gbc.gridx = 1;
		gbc.gridy = 0;
		gbc.anchor = GridBagConstraints.WEST;
		centerPanel.add(modeloCor, gbc);

		gbc.gridy = 1;
		centerPanel.add(voltagem, gbc);

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

		denModeloCor.setEnabled(false);
		centerPanel.add(denModeloCor, gbc);

		gbc.gridy = 1;
		centerPanel.add(obs_voltagem, gbc);

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
				&& modeloCor.getText().length() >= 1) {

			try {

				ConsultaItem consultaItem = new ConsultaItem();

				codItem = consultaItem.obterCodItem(codEmpresa,
						modeloCor.getText(), voltagem.getText(), connection);

				ImprimeEtiqueta etiq = new ImprimeEtiqueta(codEmpresa, codItem,
						"1", "N", "2", new Integer(2), "", connection, "",
						false, sisOper);

				etiq.processar();

				connection.commit();

				modeloCor.setText("");
				denModeloCor.setText("");
				voltagem.setText("");

				modeloCor.requestFocus();
				repaint();
			} catch (SQLException sqle) {
				try {
					showMessagemAviso("Erro na Impressao SQL!");
					logger.debug(sqle.getMessage());
					connection.rollback();
				} catch (SQLException sql) {
				}
			} catch (SSException sse) {
				try {
					showMessagemAviso("Erro na Impressao SSE!");
					logger.debug(sse.getMessage());
					connection.rollback();
				} catch (SQLException sql) {
				}
			} catch (Exception ex) {
				try {
					showMessagemAviso("Erro na Impressao EX!");
					logger.debug(ex.getMessage());
					connection.rollback();
				} catch (SQLException sql) {
				}
			}

		}
		if (e.getActionCommand().equals("Cancel")) {
			modeloCor.setText("");
			denModeloCor.setText("");
			voltagem.setText("");
			modeloCor.requestFocus();
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

			modeloCor.requestFocus();
		}
		if (w == modeloCor) {
			conteudo = modeloCor.getText();
			if (conteudo.length() >= 1) {
				ConsultaItem consultaItem = new ConsultaItem();
				String descModeloCor = null;

				Connection con = ConnectionOracle.getConnection();
				try {
					descModeloCor = consultaItem.obterModeloCor(conteudo, con);
				} catch (SSException sse) {
					showMessagemAviso("Erro modelo!");
				}

				if (descModeloCor == null || "".equals(descModeloCor)) {
					showMessagemAviso("Modelo Invalido !");
					modeloCor.setText("");
				}
				if (descModeloCor != null && !"".equals(descModeloCor)) {
					denModeloCor.setText(descModeloCor);
					voltagem.requestFocus();
					repaint();
				}
			}

		}

		if (w == voltagem) {
			conteudo = voltagem.getText();
			if (conteudo.length() > 0) {
				if (!"".equals(modeloCor.getText())
						&& !"1".equals(voltagem.getText())
						&& !"2".equals(voltagem.getText())) {
					showMessagemAviso("Voltagem Invalida !");
					voltagem.setText("");
				} else
					_b1.requestFocus();
			}

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