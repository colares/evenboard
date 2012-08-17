			    <script type="text/javascript">

			$(function () {
				$('#defaultCountdown').countdown({
					until: new Date(2012, 8-1, 19, 22, 30, 00),
					format: "HMS",
					regional: 'pt-BR'
				});

			});
		</script>
<div class="row-fluid" style="height:390px">
	<div class="span7"> 

      	<div class="row-fluid rounded">
			<h1 style="margin: 15px 0px 0px 15px; color:#999">Lançamento de <em>startups</em> em...</h1>
				<div id="defaultCountdown" ></div>		
		</div>
		<br>
      	<div class="row-fluid">
			<div class="alert alert-notice rounded" style="height:100px">
				<h2><em><?php print $message; ?></em></h2>
			</div>
		</div>
	</div>
	<div class="span5 rounded" style="font-size:60px !important">
		<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
		<script>
		new TWTR.Widget({
		  version: 2,
		  type: 'search',
		  search: 'swssa',
		  interval: 30000,
		  title: '',
		  subject: '',
		  width: 'auto',
		  height: 330,
		  theme: {
		    shell: {
		      background: '#ffffff',
		      color: '#ffffff'
		    },
		    tweets: {
		      background: '#ffffff',
		      color: '#444444',
		      links: '#1985b5'
		    }
		  },
		  features: {
		    scrollbar: false,
		    loop: true,
		    live: true,
		    behavior: 'default'
		  }
		}).render().start();
		</script>
	</div>
</div>
