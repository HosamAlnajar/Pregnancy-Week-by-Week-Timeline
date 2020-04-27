<?php

//$options = get_option( 'post_timeline_default_messages' );

//var_dump($options);
//foreach ($options as $key => $option){
//	echo '<h1>' . $key . '</h1>';
//	echo '<h1>' . $option . '</h1>';
//}

?>


<div class="timeline-container">
	<div id="timeline"></div>
</div>
<script>

	$( "#timeline" ).loadTimeline( );


</script>
