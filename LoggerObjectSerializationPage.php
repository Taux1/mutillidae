
<?php 
	try{
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

    	$lFormSubmitted = FALSE;
		if (isset($_POST["objectDeserializationSubmitButtonn"]) || isset($_REQUEST["objectDeserializationSubmitButton"])) {
			$lFormSubmitted = TRUE;
		}// end if
		
		if ($lFormSubmitted){
	    	if ($lProtectAgainstMethodTampering) {
	   			$lObjectDeserializationSubmitButton = $_POST["objectDeserializationSubmitButton"];
				$lLogfileName = $_POST["logfileName"];
				$lServiceInformation = $_POST["serviceInformation"];
	    	}else{
    			$lObjectDeserializationSubmitButton = $_REQUEST["objectDeserializationSubmitButton"];
				$lLogfileName = $_REQUEST["logfileName"];
				$lServiceInformation = $_REQUEST["serviceInformation"];
	    	}// end if $lProtectAgainstMethodTampering
		}// end if $lFormSubmitted

   	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $lQueryString);
   	}// end try;
?>

<script type="text/javascript">
	<?php 
	if($lEnableJavaScriptValidation){
		echo "var lValidateInput = \"TRUE\"" . PHP_EOL;
	}else{
		echo "var lValidateInput = \"FALSE\"" . PHP_EOL;
	}// end if		
	?>
    function onSubmitOfForm(/*HTMLFormElement*/ theForm){
		try{
			var lUnsafeCharacters = /[`~!@#$%^&*()-_=+\[\]{}\\|;':",./<>?]/;

			if(lValidateInput == "TRUE"){
				if (theForm.logfileName.value.length > 15 || 
					theForm.password.value.length > 15){
						alert('Log file name too long. We dont want to allow too many characters.\n\nSomeone might have enough room to enter a hack attempt.');
						return false;
				}// end if
				
				if (theForm.logfileName.value.search(lUnsafeCharacters) > -1 || 
					theForm.serviceInformation.value.search(lUnsafeCharacters) > -1){
						alert('Dangerous characters detected. We can\'t allow these. This all powerful blacklist will stop such attempts.\n\nMuch like padlocks, filtering cannot be defeated.\n\nBlacklisting is l33t like l33tspeak.');
						return false;
				}// end if
			}// end if(lValidateInput)
			
			return true;
		}catch(e){
			alert("Error: " + e.message);
		}// end catch
	}// end function onSubmitOfForm(/*HTMLFormElement*/ theForm)
    "
	
</script>
<!-- Bubble hints code -->


<script type="text/javascript">
    
     class Logger {
        constructor(fileName, serviceInformation) {
            this.fileName = fileName;
            this.serviceInformation = serviceInformation;
        }
    }
    
 function OKClick()
    {
            var logfileName = document.getElementById("logfileName");
            var serviceInformation = document.getElementById("serviceInformation");

        	var logger = new Logger(logfileName.value, serviceInformation.value );
            
            var serializedLogger = phpSerialize(logger);
            
            $.ajax({
                url: 'LoggerObjectDeserializationPage.php',
                type: 'post',
                data: {"logger" : serializedLogger},
                success: function(data) {
                    var logger = JSON.parse(data);
                    document.getElementById("fileCreatedSpan").innerHTML = logger.fileName; 
                    document.getElementById("logfileMessageSpan").innerHTML = logger.serviceInformation;
                    if (logger.fileName == "") {
                        document.getElementById("fileCreatedRow").style = "visibility:hidden;";
                        document.getElementById("logfileMessageRow").style = "visibility:hidden;";
                    }
                    else{
                        document.getElementById("fileCreatedRow").style = "visibility:visible;";
                        document.getElementById("logfileMessageRow").style = "visibility:visible;";
                    }
                },
                error: function() {
                     document.getElementById("logfileMessage").innerHTML = data;
                  }
            });
    }

    
function phpSerialize (mixedValue) {
  var val, key, okey
  var ktype = ''
  var vals = ''
  var count = 0

  var _utf8Size = function (str) {
    return ~-encodeURI(str).split(/%..|./).length
  }

  var _getType = function (inp) {
    var match
    var key
    var cons
    var types
    var type = typeof inp

    if (type === 'object' && !inp) {
      return 'null'
    }

    if (type === 'object') {
      if (!inp.constructor) {
        return 'object'
      }
      cons = inp.constructor.toString()
      match = cons.match(/(\w+)\(/)
      if (match) {
        cons = match[1].toLowerCase()
      }
      types = ['boolean', 'number', 'string', 'array']
      for (key in types) {
        if (cons === types[key]) {
          type = types[key]
          break
        }
      }
    }
    return type
  }

  var type = _getType(mixedValue)

  switch (type) {
    case 'function':
      val = ''
      break
    case 'boolean':
      val = 'b:' + (mixedValue ? '1' : '0')
      break
    case 'number':
      val = (Math.round(mixedValue) === mixedValue ? 'i' : 'd') + ':' + mixedValue
      break
    case 'string':
      val = 's:' + _utf8Size(mixedValue) + ':"' + mixedValue + '"'
      break
    //case 'array':
          //val = 'a'
    case 'object':
         var objname = mixedValue.constructor.name;
        if (objname === undefined) {
          return;
        }
        val = 'O:' + objname.length + ':"' + objname + '"';

        for (key in mixedValue) {
            if (mixedValue.hasOwnProperty(key)) {
              ktype = _getType(mixedValue[key])
              if (ktype === 'function') {
                continue
              }

              okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key)
              vals += phpSerialize(okey) + phpSerialize(mixedValue[key])
              count++
            }
        }
        val += ':' + count + ':{' + vals + '}'
        break
    case 'undefined':
    default:
      // Fall-through
      // if the JS object has a property which contains a null value,
      // the string cannot be unserialized by PHP
      val = 'N'
      break
  }
  if (type !== 'object' && type !== 'array') {
    val += ';'
  }
   
  return val
}
</script>
 
<div class="page-title">Insecure Deserialization</div>

&nbsp;&nbsp;&nbsp;

<form 	action="./index.php?page=user-info.php"
		method="<?php echo $lFormMethod; ?>" 
		enctype="application/x-www-form-urlencoded"
		onsubmit="return onSubmitOfForm(this);"
>
	<input type="hidden" name="page" value="user-info.php" />	
	<table style="margin-left:auto; margin-right:auto;">
    
        <tr>
            <td colspan="2" id="fileCreatedRow" class="form-header" style="visibility:hidden;">   <span id="fileCreatedSpan" ></span> </td>
        </tr>
                <tr>
            <td colspan="2" id="logfileMessageRow" class="form-header" style="visibility:hidden;">   <span id="logfileMessageSpan"></span> </td>
        </tr>
		<tr>
			<td colspan="2" class="form-header">Please update the log by entering the log file name and service information</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td class="label">Log File</td>
			<td>
				<input type="text" name="logfileName" id="logfileName" size="20" autofocus="autofocus" 
				/>
			</td>
		</tr>
		<tr>
			<td class="label">Service Information</td>
			<td>
				<input  type="test" name="serviceInformation" id="serviceInformation" size="20"
				/>
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center;">
                <input type="button" onclick="javascript:OKClick();" value="OK" \>
			</td>
		</tr>
		<tr><td></td></tr>
	</table>	
</form>

