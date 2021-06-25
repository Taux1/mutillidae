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

class Logger {
    //Must NOT be initialised to empty string
    public $fileName = '';
    public $serviceInformation = 0;
    
    public function testServiceStatus() {
           //echo 'Service status is being tested. Please wait...<br>';
           $this->serviceInformation = 'Service OK';
    }
    
    public function __destruct() {
        // Save content of serviceInformation to specified log file
        if ($this->fileName <> '') {
            file_put_contents(__DIR__. '/' . $this->fileName, $this->serviceInformation);
        }
    }
}

if (isset($_POST["logger"])) {
    // This would be unserialize a JSON object stream
    //$logger = json_decode($_POST["stuff"]);
    
    //No echoing here because it would corrupt the data that's being returned to the browser.
    $logger =unserialize($_POST["logger"]);
}
else
{
        $error = 'Post failed';
        throw new Exception($error);
}

//amend data and return as JSON object
$logger->serviceInformation = $logger->serviceInformation;

if($lUseObjectDeserialization == FALSE) {
    //Ensure file extension is OK
    $file_parts = pathinfo($logger->fileName);
    $invalidExtensions = array("php", "exe");
    //Black list files with dangerous extensions
    if (in_array($file_parts['extension'], $invalidExtensions))
    {
        $logger->fileName = '';
        $logger->serviceInformation = "The extension given for the LogfileName is illegal";
    }
}

$myJSON = json_encode($logger);

//only echo the VulnerableObject
echo $myJSON;

//tidy up and enforce creation of log file
//$logger = null;
?>