<h2>Scanner un objet</h2>

<div class="clearfix" style="width:100%">

<div style="width:50%;float:left;position:relative">
	<div id="webcamBlock">
		<script language="JavaScript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script language="JavaScript" src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
		<script language="JavaScript" src="webroot/js/scriptcam.js"></script>
		<script>
			$(document).ready(function() {
				$("#webcam").scriptcam({
					onError:onError,
					onWebcamReady:onWebcamReady
				});
			});

			function onError(errorId,errorMsg) {
				alert(errorMsg);
			}          
			function changeCamera() {
				$.scriptcam.changeCamera($('#cameraNames').val());
			}
			
			var timer;
			function timeout_trigger(){
				var barcode = $.scriptcam.getBarCode();
				if(barcode) {
					window.clearTimeout(timer);
					$(location).attr('href','/favstory/new/scan/check/' + barcode);
				}
				
				//$('#decoded').html($.scriptcam.getBarCode());
				// TODO
				//Ex: code = 9782850699382
				// ajax send code;
				timer = window.setTimeout('timeout_trigger()', 100);
			}
			function onWebcamReady(cameraNames,camera,microphoneNames,microphone,volume) {
				$.each(cameraNames, function(index, text) {
					$('#cameraNames').append( $('<option></option>').val(index).html(text) )
				});
				$('#cameraNames').val(camera);
				
				setTimeout('timeout_trigger()', 100);
			}
		</script>
		<div style="width:330px;float:left;">
			<div id="webcam">
			</div>
			<div style="margin:5px;">
				<select id="cameraNames" size="1" onChange="changeCamera()" style="width:245px;font-size:10px;height:25px;">
				</select>
			</div>
		</div>
		<!--
		<div style="width:135px;float:left;">
			<p><button class="btn btn-small" id="btn1" onclick="$('#decoded').html($.scriptcam.getBarCode());">Decode image</button></p>
		</div>
		-->
		<div style="width:200px;float:left;">
			<p id="decoded"></p>
		</div>
	</div>
</div>

<div style="width:50%;float:right">
	<ol>
		<li>Autorisez l'accès à votre Webcam</li>
		<li>Passez le code barre de l'objet devant la caméra</li>
		<li>Appuyez sur le bouton scanner, ou attendez !</li>
		<li>Découvrez l'histoire de l'objet, et les messages que vos proches et son créateur vous ont laissé</li>
		<li>Laissez un message à vos proches</li>
	</ol>
</div>

</div>