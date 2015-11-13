<html>
<head>
<title>
ATM transaction program.
</title>
</head>
<body>
<?php 
include 'account.php';
include 'collection.php';

$Acct = new Collection(); //Declare a new collection for the .txt file acct.txt. 
$UpdatedInfo = new Collection(); //Declare a new collection for the updated account information.

ReadInAccInfo($Acct);    //Call function to read in the account info. 
PerformTranz($Acct, $UpdatedInfo);  //Call function to perform the transactions.

function ReadInAccInfo($Acct) {  
	$file_handle = fopen("acct.txt", "r");  //opening the file for reading.
	while (!feof($file_handle)) {           // while not at end of file.
		$line = fgets($file_handle);
		$AccArray = explode(" ",$line); //coolest function name out. 
		$AcctcNum = $AccArray[0];
		$Bal = $AccArray[1];
		$Acct->addItem(new AccountClass($AcctcNum, $Bal));   
	}		
	fclose($file_handle); //closing file. 
}

function PerformTranz($Acct, $UpdatedInfo) {
$M=0;
$transactionCounter=0;
$validTransactionCounter=0;
$lineCounter=0;
$InvalidAarrayCount=0;
$file_handle2 = fopen("tranz.txt", "r"); 					//Opening the file.
$lol = fgets($file_handle2); 								//Skipping the first line in a ghetto like fashion. 
while (!feof($file_handle2)) { 								//While not at end of file. 
		$line = fgets($file_handle2); 						//starts from second line. 
		$TransactionArray = explode(" ",$line); 						// loads an array with $TransactionArray[105], $TransactionArray[D], $TransactionArray[200].
		$AcctcNum2 = $TransactionArray[0]; 	//105
		$Acctction = $TransactionArray[1]; 	//D
		$Acctmount = $TransactionArray[2];   // 200
		$lineCounter+=1;
		$temp2= -1; 		  								//can't have a negative account number so this is ok. 
		$i=-1;											  //index starts negative because of where I have to increment it a few lines down. 	
		//$AcctccNumArray = array();
		
		while($temp2 != $AcctcNum2) { 							//need to be careful of invalid transactions 
			$i += 1;               							//incrementing. 
			if($Acct->isNull($i) == true){ 					 //Checking that the index in assessing is not null.
				break;			
			} else {
				$temp2 = $Acct->getItem($i);      				//temp2 == the object at index $i.  
				$temp2 = $temp2->getAccNum();				//temp2 == the account number of the object at index $i.	
			}
		}
		$transactionCounter +=1;
		
		if($Acct->isNull($i) == true){  						//Checking that the index in assessing is not null.
			// if it's null do nothing. 
		} else {
			if($Acctction == 'D' && $Acctmount >= 0){ 	//if depositing	
				$AcctccNumArray[$M] = $AcctcNum2;
				$M+=1;
				$validTransactionCounter+=1;
				$AcctccToDepositTo = $Acct->getItem($i);
				$AcctccToDepositTo->setDeposit($Acctmount);
				$n=0;
				$clash = false; 
				while($UpdatedInfo->isNull($n) == false){
					$CheckDoubleUp = $UpdatedInfo->getItem($n);
					$CheckDoubleUp = $CheckDoubleUp->getAccNum();
					if($CheckDoubleUp == $AcctcNum2){
						$clash = true;
						$CheckDoubleUp = $UpdatedInfo->getItem($n);
						$CheckDoubleUp->setDeposit($Acctmount);
					} 				
					$n+=1;				
				}
				if($clash == false){
					$UpdatedInfo->addItem(new AccountClass($AcctcNum2, $AcctccToDepositTo->returnBal()));
				}
				$clash = false; 			 								
			}
			
			if($Acctction == 'W' && $Acctmount >= 0) { 	//if withdrawing.
			  if(CheckValidTranz($UpdatedInfo, $Acctmount, $AcctcNum2, $Acct) == true){
				$AcctccNumArray[$M] = $AcctcNum2;
				$M+=1;
				$validTransactionCounter+=1;
				$AcctccToWithdrawFrom = $Acct->getItem($i);
				$AcctccToWithdrawFrom->getWithdraw($Acctmount);
				$n=0;
				$clash = false; 
				while($UpdatedInfo->isNull($n) == false){
					$CheckDoubleUp = $UpdatedInfo->getItem($n);
					$CheckDoubleUp = $CheckDoubleUp->getAccNum();
					if($CheckDoubleUp == $AcctcNum2){
						$clash = true;
						$CheckDoubleUp = $UpdatedInfo->getItem($n);
						$CheckDoubleUp->getWithdraw($Acctmount);
					} 				
						$n+=1;				
				}
				if($clash == false){
					$UpdatedInfo->addItem(new AccountClass($AcctcNum2, $AcctccToWithdrawFrom->returnBal()));
				}
				$clash = false;																	
			} else {
				$ValidTranz = false;
			}
		}
				WriteToFile($Acct, $UpdatedInfo); //This keeps the update.txt file up to date after every transaction.
	} 
		if($Acctction !== "D" and $Acctction !== "W" or (in_array($AcctcNum2, $AcctccNumArray)==false or $Acctmount < 0 or $ValidTranz = false)) {
						
			$TranzArray[$InvalidAarrayCount] = $lineCounter;
			$InvalidAarrayCount+=1;			
			$TranzArray[$InvalidAarrayCount] = $AcctcNum2;
			$InvalidAarrayCount+=1;
			$TranzArray[$InvalidAarrayCount] = $Acctction;
			$InvalidAarrayCount+=1;
			$TranzArray[$InvalidAarrayCount] = $Acctmount;		
			$InvalidAarrayCount+=1;
			$ValidTranz=true;
		}	
		//$ValidTranz=true;
    }
	echo "There where $transactionCounter transactions in total.";
	echo "<br>";
	echo "<br>";
	echo "There where $validTransactionCounter valid transactions in total.";
	fclose($file_handle2); //closing file.
	GenTable($TranzArray); 
}

function WriteToFile($Acct, $UpdatedInfo){
		$upatedAccBalances = fopen("update.txt", "w");  //This block of code writes the updated info to the .txt file 
		$k=0;											//in the ascending order by acc number.
		while($Acct->isNull($k) == false){					//While not a the end of the collection $Acct.
		$nextToWrite = $Acct->getItem($k); 			
		$nextToWrite = $nextToWrite->getAccNum(); 
		$p=0;
		while($UpdatedInfo->isNull($p) == false){
			$checkAccNum = $UpdatedInfo->getItem($p);
			$checkAccNum = $checkAccNum->getAccNum(); 
			if($nextToWrite == $checkAccNum){				//here we check the Accnum from $Acct against the Accnum from $UpdatedInfo
				$updateAcc = $UpdatedInfo->getItem($p);
				$updateAcc = $updateAcc->getAccNum();
				$updateBal = $UpdatedInfo->getItem($p);
				$updateBal = $updateBal->returnBal();
				$result = $updateAcc . ' ' . $updateBal;       //concatenating the strings.
				fwrite($upatedAccBalances, $result . "\r\n"); //writing to the file after concatenating the strings. 
			}
			$p+=1;
		}
		$k+=1;	
	}
fclose($upatedAccBalances); //closing the file. 
}

function GenTable($TranzArray) {
	echo "<br>"; echo "<br>";
	echo "Invalid Transactions."; echo "<br>";
	echo "<table border='1'>"; echo "<th>Line #</th>"; echo "<th>ID</th>"; 
	echo "<th>Type</th>"; echo "<th>Amount</th>"; echo "<tr>";	
	$W=0; $N=0;	
	$result = count($TranzArray);
	while($W < $result) {
		$Temp = $TranzArray[$W];
		echo "<td>".$Temp."</td>";
		$W+=1;
		$N+=1;
		if($N>=4) {
			echo "<tr>";
			$N=0;
		}
	}
}

 function CheckValidTranz($UpdatedInfo, $Acctmount, $AcctcNum2, $Acct) {
	$n=0;
	while($UpdatedInfo->isNull($n) == false){
		$Object = $UpdatedInfo->getItem($n);
		$AcctccountNum = $Object->getAccNum();
		if($AcctccountNum == $AcctcNum2){
			$Temp = $Object->returnBal();
			if(($Temp - $Acctmount) < 0){
				return false;
				break;
			} else {
				return true;
				break; 
			}
		}
		$n+=1;
	}
	$n=0;
	while($Acct->isNull($n) == false){
	$Object = $Acct->getItem($n);
	$AcctccountNum = $Object->getAccNum();
	if($AcctccountNum == $AcctcNum2){
		$Temp = $Object->returnBal();
		if(($Temp - $Acctmount) < 0){
			return false;
			break;
		} else {
			return true;
			break; 
		}
			
	  }
		$n+=1;
	}
 }


?>
</body>