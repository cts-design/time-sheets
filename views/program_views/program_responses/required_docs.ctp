<script>
	$(document).ready(function(){
		$('#toggle').show();
		$('#toggle').toggle(function(){
			$('#instructions').show();
			$('#toggle').html('Hide Instructions');
		},
		function() {
			$('#instructions').hide();
			$('#toggle').html('Show Instructions');
		}
		)
	})
</script>
<a id="toggle" class="small" style="display: none">View Instructions</a>
<p id="instructions" style="display: none"><?php echo $instructions ?></p>
<noscript>
	<p id="instructions"><?php echo $instructions ?></p>
</noscript>
