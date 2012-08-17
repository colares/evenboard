<style>
/* jQuery Countdown styles 1.6.0. */

body {}
.hasCountdown {

}
#defaultCountdown { margin: 0px auto; 	background-color: #fff;
	border-radius: 30px; 
-moz-border-radius: 30px; 
-webkit-border-radius: 30px; 

width:500px;
text-align: center;


background: url('../img/logo.png') #fff no-repeat 950px 230px;
}

.countdown_rtl {
	direction: rtl;
}
.countdown_holding span {
	color: #888;
}
.countdown_row {
	clear: both;
	width: 1000px;
	padding: 0px 2px;
	text-align: center;
}
.countdown_show1 .countdown_section {
	width: 98%;
}
.countdown_show2 .countdown_section {
	width: 48%;
}
.countdown_show3 .countdown_section {
	width: 32.5%;
}
.countdown_show4 .countdown_section {
	width: 24.5%;
}
.countdown_show5 .countdown_section {
	width: 19.5%;
}
.countdown_show6 .countdown_section {
	width: 16.25%;
}
.countdown_show7 .countdown_section {
	width: 14%;
}
.countdown_section {
	display: block;
	float: left;
	font-size: 150%;
	text-align: center;
	width: 450px !important;
	margin: 0px auto;
	padding: 20px 0px 20px 0px;
}
.countdown_amount {
	font-size: 1700%;
	width: 450px;
	display: block;
	line-height: 315px
}
.countdown_descr {
	display: block;
	width: 100%;
}

</style>

<script type="text/javascript">

	$(function () {
		$('#defaultCountdown').countdown({
			until: "+60s",
			format: "S",
			regional: 'pt-BR'
		});
		$('#defaultCountdown').countdown('pause');

	$('#pauseButton').toggle(
		function() { 
	        $(this).text('Pausar'); 
	        $(this).removeClass('btn-success');
	        $(this).addClass('btn-danger');
	        $('#defaultCountdown').countdown('resume'); 
	    } ,
		function() { 
	        $(this).text('Continuar'); 
	        $(this).removeClass('btn-danger');
	        $(this).addClass('btn-success');
	        $('#defaultCountdown').countdown('pause'); 
	    }
	); 

	});


</script>
<div class="row-fluid" style="height:405px">
	<div class="span12"> 

      	<div class="row-fluid rounded" style="text-align:center">
				<div id="defaultCountdown" ></div>
				<button class="btn btn-success btn-large" type="button" id="pauseButton">Continuar</button>
		</div>
	</div>
	
</div>
