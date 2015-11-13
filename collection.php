<?php
class Collection 
{
    private $items = array();

	public function addItem($obj, $key = null) {
		if ($key == null) {
			$this->items[] = $obj;
		}
		else {
			if (isset($this->items[$key])) {
				throw new KeyHasUseException("Key $key already in use.");
			}
			else {
				$this->items[$key] = $obj;
			}
		}
	}

	public function getItem($key) {
		if (isset($this->items[$key])) {
			return $this->items[$key];
		}
		else {
			//throw new KeyInvalidException("Invalid key $key.");
			echo "Error"; //bug checking.
		}
	}
	
	public function isNull($key){
		if (isset($this->items[$key])){
			return false; 
		} else {
			return true; 
		}
	}
	
	public function returnArray(){
		return $this->items;
	}
	
}




?>