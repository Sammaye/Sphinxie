<?php
namespace Sphinxie;

class Cursor implements \Iterator, \Countable{
	
	public $className;
	
	public $maxPage;
	public $matches;
	public $term;
	public $totalFound = 0;

	public $iteratorCallback;
	
	public function __construct($result,$className=null) {
		$this->matches = isset($result['matches'])?$result['matches']:array();
		reset($this->matches);
		
		$this->totalFound=isset($result['total_found'])?$result['total_found']:0;
		$this->className=$className;
	}
	
	public function setIteratorCallback($callback){
		$this->iteratorCallback=$callback;
	}	
	
	function matches(){
		return $this->matches;
	}
	
	function currentMatch(){
		return current($this->matches);
	}
	
	function current() {
		if(($c=current($this->matches)) !== false){
			$fn=$this->iteratorCallback;
			if((is_string($fn) && function_exists($fn)) || (is_object($fn) && $fn instanceof \Closure))
				return $fn($c['attrs'],$this->className);
			//elseif($this->className) // Commented this out but this is where you would integrate with your ORM etc
				//$className::model()->findOne(array('_id' => new \MongoId($c['attrs']['_id'])));
			else 
				return (Object)$c['attrs'];
		}else
			return  false;
	}
	
	public function count(){
		return count($this->matches);
	}
	
	public function key() {
		return key($this->matches);
	}
	
	public function next() {
		return next($this->matches);
	}
	
	public function valid() {
		return $this->currentMatch() !== false;
	}
	
    public function rewind() {
        reset($this->matches);
    }
}