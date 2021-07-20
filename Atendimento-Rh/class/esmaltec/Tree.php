<?php
/**
 * Classe Tree<br>
 * Esta classe tem 4 atributos fundamentais:<br>
 * root: Nodo da raiz<br>
 * pre_order: o caminho do pre order da arvore<br>
 * pos_order: o caminho do pos order da arvore<br>
 * paths: um array contendo todos os caminhos possíveis da árvore
 * @see Node
 * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
 * @version 1.2.0 05/08/2014
 * @link https://github.com/c05mic/GenericN-aryTree Árvore Génerica em Java
 * @package 
 */
class Tree{

  /**
   * A raiz da árvore
   * @var Node 
   * @access private
   * @since 1.0.0
   */
  private $root;
  
  /**
   * Armazena o caminho da pre order de um Nodo
   * @var array Array de Node| Array de dados 
   * @see getPreOrderTraversal()
   * @access private
   * @since 1.0.0
   */
  private $pre_order;

  /**
   * Armazena o caminho da pos order de um Nodo
   * @var array Array de Node| Array de dados
   * @see getPosOrderTraversal()
   * @access private
   * @since 1.0.0
   */
  private $pos_order;
	
	/**
   * Armazena o caminho normal de um Nodo
   * @var array Array de Node| Array de dados
   * @see getOrderNormal()
   * @access private
   * @since 1.2.0
   */
  private $normal_order;
	
  /**
   * Armazena o caminho do nodo até a folha
   * @var array Array de array dos nodos
   * @access private
   * @since 1.0.0
   */
  private $paths;
  
  /**
   * Armazena o nível em que o nodo se encontra
   * @var int Nível do node 
   * @access private
   * @since 1.1.0
   */
  private $level;
	
	

  /**
   * Método construtor da classe Tree
   * @param mixed $data Qualquer informação a ser armazenda na árvore
   * @param Node $root Nodo para ser raiz da árvore
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function __construct(Node $root) {
    $this->root = $root;
  }

  /**
   * Método para verificar se árvore está vazia<br>
   * Retorna true se está vazia ou false caso contrário
   * @return boolean true|false
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function isEmpty() {
    return is_null($this->root) ? true : false;
  }
  
  /**
   * Método para retornar a raíz da árvore
   * @return Nodo Raíz da árvore
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getRoot() {
    return $this->root;
  }

  /**
   * Método para setar uma nova raíz da árvore
   * @param Node $root Nova raíz da árvore
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function setRoot(Node $root) {
    $this->root = $root;
  }
  
  /**
   * Método para verificar se existe o dado na árvore<br>
   * Retorna true se existe ou false caso contrário
   * @param mixed $key 
   * @return boolean true|false
   * @see find()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function exists($key) {
    return $this->find($this->root, $key);
  }
  
  /**
   * Retorna a quantidade de nodos da árvore
   * @return int Quantidade de nodos da árvore
   * @see getNumberOfDescendants()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getNumberOfNodes() {
    return $this->getNumberOfDescendants($this->root) + 1;
  }

  /**
   * Retorna a quantidade de descendentes de um nodo
   * @param Node $node
   * @return int
   * @see Node::getChildren()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getNumberOfDescendants(Node $node) {
    $n = count($node->getChildren());
    foreach($node->getChildren() as $child){
      $n += count($child->getChildren());
    }
    return $n;
  }
  
  /**
   * Método para encontrar um dado em um nodo<br>
   * Retorna true se encontrou ou false caso contrário<br>
   * Este método utiliza-se de recursão para percorrer a árvore
   * @param Node $node
   * @param mixed $keyNode Dado a ser encontrado
   * @return boolean true|false
   * @see Node::getData()
   * @see Node::getChildren()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access private
   * @version 1.0.0
   * @since 1.0.0
   */
  private function find(Node $node, $keyNode) {
    $res = false;
    if ($node->getData() == $keyNode){
      return true;
    }else{
      foreach($node->getChildren() as $child){
        if($this->find($child, $keyNode)){
          $res = true;
        }
      }
    }
    return $res;
  }
  
  /**
   * Método para retornar o caminho do pre order da árvore
   * @return array Array contendo os dados dos nodos
   * @see $pre_order
   * @see buildPreOrder()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getPreOrderTraversal() {
    $this->pre_order = array();
    $this->buildPreOrder($this->root);
    return $this->pre_order;
  }
  
  /**
   * Método para construir o caminho do pre order<br>
   * Este método percorre cada filho de um nodo e utiliza-se a recursão
   * @param Node $node
   * @see $pre_order
   * @see Node::getChildren()
   * @see Node::getData()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access private
   * @version 1.0.0
   * @since 1.0.0
   */
  private function buildPreOrder(Node $node) {
    $this->pre_order[] = $node->getData();
    foreach($node->getChildren() as $child){
      $this->buildPreOrder($child);
    }
    
  }

  /**
   * Método para retornar o caminho do pos order da árvore
   * @return array Array contendo os dados dos nodos
   * @see $pos_order
   * @see buildPosOrder()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getPosOrderTraversal() {
    $this->pos_order = array();
    $this->buildPosOrder($this->root);
    return $this->pos_order;
  }
  
  /**
   * Método para construir o caminho do pos order<br>
   * Este método percorre cada filho de um nodo e utiliza-se a recursão
   * @param Node $node
   * @see $pos_order
   * @see Node::getChildren()
   * @see Node::getData()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access private
   * @version 1.0.0
   * @since 1.0.0
   */
  private function buildPosOrder(Node $node) {
    foreach($node->getChildren() as $child){
      $this->buildPosOrder($child);
    }
    $this->pos_order[] = $node->getData();
  }

  /**
   * Metodo para percorrer o maior caminho da Raiz até a folha mais extrema<br>
   * Pode ser retornado em forma de array de dados ou array de nodos
   * @param boolean $just_data true pra retornar apenas dados, false pra retornar nodos
   * @return array Array de Nodos | Array dos dados
   * @see getPathsFromRootToAnyLeaf()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getLongestPathFromRootToAnyLeaf($just_data = true){
    $longest_path = array();
    $longest_path_data = array();
    $max = 0;
    foreach($this->getPathsFromRootToAnyLeaf($just_data) as $path){
      if(count($path) > $max){
        $max = count($path);
        $longest_path = $path;
      }
    }
    return $longest_path;
  }
	
  

  /**
   * Método para retornar todos caminhos possíveis da árvore
   * @param boolean $just_data true pra retornar o dado e false pra retornar os nodos
   * @return array Array de Array dados|Array de Array de nodos
   * @see $paths
   * @see getPath()
   * @see Node::getData()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getPathsFromRootToAnyLeaf($just_data){
    $this->paths = array();
    $current_path = $paths_data = array();
    $this->getPath($this->root, $current_path, $this->paths);
    if($just_data){
      foreach($this->paths as $path){
        $path_data = array();
        foreach($path as $node){
          array_push($path_data,$node->getData());
        }
        array_push($paths_data, $path_data);
      }
      return $paths_data;
    }else{
      return $this->paths;
    }
  }

  /**
   * Método para pegar o caminho de um nodo
   * @param Node $node Nodo a ser percorrido
   * @param array $current_path Array contendo o caminho atual
   * @param array $paths Array contendo todos os caminhos
   * @return empty Retorna vazio se o caminho atual for nulo
   * @see $paths
   * @see Node::getChildren()
   * @see clonar()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access private
   * @version 1.0.0
   * @since 1.0.0
   */
  private function getPath(Node $node, $current_path, $paths){
    if(is_null($current_path)){
      return;	
    }
    $current_path[] = $node;
    if(count($node->getChildren()) == 0){
      //Esta á a folha
      $this->paths[] = $this->clonar($current_path);	
    }
    foreach($node->getChildren() as $child){
      $this->getPath($child, $current_path, $paths);
    }	
    $aux = array_keys($current_path, $node);
    $index = $aux[0];
    for($i = $index; $i < count($current_path); $i++){
      unset($current_path[$i]);	
    }
  }

  /**
   * Método para clonar um array de Nodos
   * @param array $list Array de Nodos
   * @return array Array de Nodos
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access private
   * @version 1.0.0
   * @since 1.0.0
   */
  private function clonar($list){
    $new_list = array();
    foreach($list as $node){
      $new_list[] = $node;
    }
    return $new_list;
  }

  /**
   * Metodo para percorrer o maior caminho de qualquer Nodo até a folha mais extrema<br>
   * Pode ser retornado em forma de array de dados ou array de nodos
   * @param boolean $just_data true pra retornar apenas dados, false pra retornar nodos
   * @return array Array de Nodos | Array dos dados
   * @see getPathsFromNodeToAnyLeaf()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getLongestPathFromNodeToAnyLeaf(Node $node, $just_data = true){
    $longest_path = array();
    $longest_path_data = array();
    $max = 0;
    if(is_null($node)){
      $node = $this->root;	
    }
    foreach($this->getPathsFromNodeToAnyLeaf($node, $just_data) as $path){
      if(count($path) > $max){
        $max = count($path);
        $longest_path = $path;
      }
    }
    return $longest_path;
  }
	
  /**
   * Método para retornar todos caminhos possíveis da árvore a partir de um Nodo
   * @param boolean $just_data true pra retornar o dado e false pra retornar os nodos
   * @return array Array de Array dados|Array de Array de nodos
   * @see $paths
   * @see getPath()
   * @see Node::getData()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getPathsFromNodeToAnyLeaf(Node $node, $just_data){
    $this->paths = array();
    $current_path = $paths_data = array();
    $this->getPath($node, $current_path, $this->paths);
    if($just_data){
      foreach($this->paths as $path){
        $path_data = array();
        foreach($path as $node){
          array_push($path_data,$node->getData());
        }
        array_push($paths_data, $path_data);
      }
      return $paths_data;
    }else{
      return $this->paths;
    }
  }
  
  /**
   * Método para retornar a altura máxima da árvore
   * @return int Altura da árvore
   * @see getLongestPathFromRootToAnyLeaf()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getMaxHeight(){
    return count($this->getLongestPathFromRootToAnyLeaf(true));
  }

  /**
   * Método para retornar a altura a partir do Nodo
   * @return int Altura de um Nodo até a folha mais extrema
   * @see getLongestPathFromNodeToAnyLeaf()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getMaxHeightFromNode(Node $node){
    return count($this->getLongestPathFromNodeToAnyLeaf($node, true));
  }
	

  /**
   * Método para buscar o pai do Nodo
   * @param Node $node Nodo a ser pesquisado
   * @param boolean $just_data true caso retorna apenas o dado ou false pra retornar nodo
   * @return null|mixed Nulo caso o nodo não tem pai|Dado armazenado caso seja true ou Nodo caso seja falso
   * @see Node::getParent()
   * @see Node::getData()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getParentByNode(Node $node, $just_data = true){
    if(is_null($node)){
      return NULL;
    }
    $parent = $node->getParent();
    if(is_null($parent)){
      return NULL;	
    }else{
      if($just_data){
        return $parent->getData();
      }else{
        return $parent;
      }
    }
  }
	
  /**
   * Método para buscar todos os filhos de um Nodo
   * @param Node $node Nodo a ser pesquisado
   * @param boolean $just_data true caso retorna apenas o dado ou false pra retornar nodo
   * @return null|array Nulo caso o nodo não tem pai|Array de dado armazenado caso seja true ou array de Nodo caso seja falso
   * @see Node::getChildren()
   * @see Node::getData()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.0.0
   */
  public function getChildrenByNode(Node $node, $just_data = true){
    if(is_null($node)){
      return NULL;
    }
    $children = $node->getChildren();
    $child_array = array();
    if(is_null($children)){
      return NULL;	
    }else{
      foreach($children as $child){
        if($just_data){
          array_push($child_array, $child->getData());
        }else{
          array_push($child_array, $child);
        }
      }
      return $child_array;
    }
  }
  
  /**
   * Método para buscar em que nível o Nodo se encontra
   * @param Node $node
   * @return int Nível que o nodo se encontra
   * @see searchLevelByNode()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.1.0
   */
  public function getLevelByNode(Node $node){
    $this->level = 0;
    $this->searchLevelByNode($node);
    return $this->level;
  }
  
  /**
   * Método que faz a pesquisa em que nível se encontra
   * @param Node $node Nodo a ser pesquisado
   * @see getParentByNode()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access private
   * @version 1.0.0
   * @since 1.1.0
   */
  private function searchLevelByNode(Node $node){
    $parent = $this->getParentByNode($node, false);
    if(is_null($parent)){
      
    }else{
      $this->level++;
      $this->searchLevelByNode($parent);
    }
  }
  
  /**
   * Método para retornar o caminho de um Nodo até a Raiz, com a opção de retornar apenas dados ou nodo, e se inverte o caminho
   * @param Node $node
   * @param boolean $just_data Se for true retorna apenas os dados, o node caso contrário
   * @param boolean $inverse Inverte a posição do array e retira a raiz caso seja true, se for falso retorna o caminho normal
   * @param boolean $without_root Se for true retira a raiz do caminho, caso contrário permanece a raiz no caminho
   * @return array Array de dados|Array de nodos
   * @see searchPathFromNodeToRoot
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.1.0
   */
  public function getPathFromNodeToRoot(Node $node, $just_data = true, $inverse = true, $without_root = true){
    $this->paths = array();
    $this->searchPathFromNodeToRoot($node);
    $path = array();
    if($inverse){
      $path = array_reverse($this->paths);
    }else{
      $path = array_reverse($this->paths);
      krsort($path);
    }
    if($without_root){
      unset($path[0]);
    }
    return $path;
  }
  
  /**
   * Método para pesquisar o caminho do Nodo até a Raiz
   * @param Node $node
   * @param boolean $just_data Se true retorna o dado, ou node caso contrário
   * @see getParentByNode()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.1.0
   */
  private function searchPathFromNodeToRoot(Node $node, $just_data = true){
    $parent = $this->getParentByNode($node, false);
    if($just_data){
      array_push($this->paths, $node->getData());
    }else{
      array_push($this->paths, $node);
    }
    if(is_null($parent)){
      
    }else{
      
      $this->searchPathFromNodeToRoot($parent);
    }
  }
	
	/**
   * Método para retornar o caminho da ordem normal da árvore
   * @return array Array contendo os dados dos nodos
   * @see $normal_order
   * @see buildNormalOrder()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access public
   * @version 1.0.0
   * @since 1.2.0
   */
	public function getNormalOrder(){
		$this->normal_order = array();
		$this->normal_order[0] = $this->root->getData();
    $this->buildNormalOrder($this->root);
    return $this->normal_order;
	}
	
	/**
   * Método para construir o caminho da order normal<br>
   * Este método percorre cada filho de um nodo e utiliza-se a recursão
   * @param Node $node
   * @see $normal_order
   * @see Node::getChildren()
   * @see Node::getData()
   * @author Leandro Pedrosa <leandro.pedrosa@esmaltec.com.br>
   * @access private
   * @version 1.0.0
   * @since 1.2.0
   */
  private function buildNormalOrder(Node $node) {
		$children = $node->getChildren();
		$qtd = count($children);
		if($qtd > 0){
			foreach($children as $child){
				$this->normal_order[] = $child->getData();
			}
			foreach($children as $child){
				$this->buildNormalOrder($child);
			}
		}
  }

}
?>