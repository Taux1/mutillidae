<?php
        session_start();
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure
				$lEnableHTMLControls = FALSE;
    			$lFormMethod = "GET";
				$lEnableJavaScriptValidation = FALSE;
				$lProtectAgainstMethodTampering = FALSE;
				$lEncodeOutput = FALSE;
                $lUseObjectDeserialization = TRUE;
				break;
    		
    		case "1": // This code is insecure
				$lEnableHTMLControls = TRUE;
    			$lFormMethod = "GET";
				$lEnableJavaScriptValidation = TRUE;
				$lProtectAgainstMethodTampering = FALSE;
				$lEncodeOutput = FALSE;
                 $lUseObjectDeserialization = TRUE;
			break;
	    		
			case "2":
			case "3":
			case "4":
    		case "5": // This code is fairly secure
				$lEnableHTMLControls = TRUE;
    			$lFormMethod = "POST";
				$lEnableJavaScriptValidation = TRUE;
				$lProtectAgainstMethodTampering = TRUE;
				$lEncodeOutput = TRUE;
                $lUseObjectDeserialization = FALSE;
			break;
    	}//end switch

class FileLogger {
    //Must NOT be initialised to empty string
    //public $name = '';
    //But what if we'd called the above field $fileName? I.E. something that's different to the fields defined in the Employee class?:
    public $fileName = '';
    public $serviceInformation = 0;
    
    public function testServiceStatus() {
           //echo 'Service status is being tested. Please wait...<br>';
           $this->serviceInformation = 'Service OK';
    }
    
    public function __destruct() {
        // Save content of serviceInformation to specified log file
        // Note can't use:  if ($lUseObjectDeserialization == FALSE) because $lUseObjectDeserialization is not available to the FileLogger class
        if ($this->fileName <> '') {
            file_put_contents(__DIR__. '/' . $this->fileName, $this->serviceInformation);
        }
    }
}

class Employee {
    public $empNo = 0;
    public $name = '';
     
    public function PrintData() {
        echo 'Employee ' . $this->name . ' has an employee number of ' . $this->empNo . '<br />';
    }
}

if (isset($_POST["employee"])) {
    $employee =unserialize($_POST["employee"]);
}
else
{
        $error = 'Post failed';
        throw new Exception($error);
}

//amend data and return as JSON object
$returnMessage = 'The following employee name of \'' . $employee->name . ' \'was passed to the server';


$myJSON = json_encode($returnMessage);

//only echo the VulnerableObject
echo $myJSON;

//tidy up and enforce creation of log file
//$employee = null;
?>