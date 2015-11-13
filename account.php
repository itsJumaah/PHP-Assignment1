<?php
class AccountClass
{                              //Make sure that these are not 
	private $balance = 0;	  //setting to 0 with every time I use objects of this class. 
	private $accountNumber = 0;
	
	public function __construct($AcNum, $Bal){
		$this->accountNumber = $AcNum;
		$this->balance = $Bal; 
	}		

	
	
	public function setDeposit($amount) {
		$this->balance += $amount;
	}
	
	public function getWithdraw($amount) {
		$this->balance -= $amount;
	}
	
	public function checkBal(){
		echo $this->balance; 
	}
	
	public function returnBal(){
		return $this->balance; 
	}
	
	public function setAccNum($ToSet) {
		$this->accountNumber = $ToSet;
	
	}
	
	public function getAccNum() {
		return $this->accountNumber;
	}
}

/*class InvalidTransactions extends AccountClass 
{
	private $line = 0;	  
	private $accountNumber = 0;
	private $amount=0;
	private $action = 0; 
	
	
	public function __construct($Line, $AcNum, $Amount, $Action) {
		$this->line = $Line;
		$this->accountNumber = $AcNum;
		$this->amount = $Amount; 
		$this->action = $Action; 
	}
	
	public function returnLineNum() {
		return $this->line;
	}
	
	public function returnAmount() {
		return $this->amount; 
	}
	
	public function returnAction() { 
		return $this->action;
	}
	
	public function getAccNum() {
		return $this->accountNumber;
	}*/
	
	
	


?>