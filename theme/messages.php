<?php

//ST. JOSEPH CATHOLIC SCHOOL OF SAGAY INC.

if (isset($_GET['msg'])) {
	//if ($_GET['msg'] == 'success'){
		echo '
<script type="text/javascript">

$(document).ready(function(){

  Swal.fire(
            "Good job!",
            "You clicked the button!",
            "success"
          )
});

</script>';
}
?>