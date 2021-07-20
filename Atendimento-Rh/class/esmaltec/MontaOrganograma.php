<?php
class MontaOrganograma{
	private $org = "";
	function __construct(Tree $tree){
		$root = $tree->getRoot();
		$child = $root->getChildren();
		$this->montaOrganograma($child[0]);
		return $this->org;
	}
	private function montaOrganograma(Node $node){
		$data = $node->getData();
		$this->org .= '<li>'.$data['id'];
		
		$children = $node->getChildren();
		//Debug::dump($children);
		if(count($children) > 0){
			$this->org .= '<ul>';
			foreach($children as $child){
				$this->montaOrganograma($child);
			}
			$this->org .= '</ul>';
		}
		$this->org .= '</li>';
	}
}
?>