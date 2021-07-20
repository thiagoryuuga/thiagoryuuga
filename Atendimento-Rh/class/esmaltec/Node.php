<?php

/**
 * Classe Node<br>
 * Esta classe tem 3 atributos privados fundamentais:<br>
 * data: dado a ser armamzenado, sendo de qualquer tipo<br>
 * children: filhos deste Node, sendo uma array de Nodes<br>
 * parent: pai deste Node, sendo do tipo um Node<br>
 * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
 * @version 1.0.0 27/11/2013
 * @link https://github.com/c05mic/GenericN-aryTree Árvore Génerica em Java
 * @package esmaltec
 */
class Node {
  
  /**
   * Informação do nodo
   * @var mixed Qualquer informação
   * @access private
   * @since 1.0.0
   */
  private $data;
  
  /**
   * Um array de Nodes contendo os filhos do nodo
   * @var array Array de Nodes
   * @access private
   * @since 1.0.0
   */
  private $children;
  
  /**
   * Pai do nodo
   * @var Node
   * @access private
   * @since 1.0.0
   */
  private $parent;
  
  /**
   * Método construtor da classe Node
   * Tem que se escolher apenas um argumento a ser passado: dado ou nodo a ser armazenado
   * @param mixed $data Qualquer informação a ser armazenda na árvore
   * @param Node $node Nodo para ser armazenado
   * @see getData()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function __construct($data = null, Node $node = null) {
    if(!is_null($data)){
      
      $this->data = $data;
      $this->children = array();
      
    }
    if(!is_null($node) && is_null($data)){
      $this->data = $node->getData();
      $this->children = array();
    }
  }
  
  
  /**
   * Adiciona um Nodo como filho<br>
   * @param Node $child Nodo a ser adicionado como filho
   * @see setParent()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function addChild(Node $child) {
    $child->setParent($this);
    $this->children[] = $child;
  }
  
  /**
   * Pega um filho pelo índice
   * @param int $index Índice do array
   * @return Node Filho do tipo Nodo
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getChildAt(int $index) {
    return $this->children[$index];
  }
  
  /**
   * Remove todos os filhos de um nodo
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function removeChildren() {
    unset($this->children);
    $this->children = array();
  }

  /**
   * Remove um filho pelo índice do array
   * @param int $index Índice do array
   * @return Node Nodo que foi removido
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function removeChildAt(int $index) {
    $node = $this->children[$index];
    unset($this->children[$index]);
    return $node;
  }

  /**
   * Retorna o dado armazenado do Nodo
   * @return mixed O dado que foi armazenado
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getData(){
    return $this->data;
  }
  
  /**
   * Seta o dado para o nodo
   * @param mixed $data O dado a ser armazenado
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function setData($data){
    $this->data = $data;
  }
  
  /**
   * Seta o pai para o nodo
   * @param Node $parent Nodo a se tornar 
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function setParent(Node $parent){
    $this->parent = $parent;
  }
  
  /**
   * Retorna o pai do nodo
   * @return Nodo Pai do Nodo filho
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getParent(){
    return $this->parent;
  }
  
  /**
   * Seta os filhos do tipo array de nodos
   * @param array $children Array de nodos
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function setChildren($children){
    $this->children = $children;
  }
  
  /**
   * Retorna um array de nodos do Nodo
   * @return array Array de nodos
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getChildren(){
    return $this->children;
  }
  
  

}
