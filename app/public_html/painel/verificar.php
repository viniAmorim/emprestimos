<?php 
@session_start();
if (@$_SESSION['id'] == ""){
	@session_destroy();
	echo '<script>window.location="../login"</script>';
	exit();
}

if (@$_SESSION['token_ATEFDFSFSFAF'] != "FDSFFDASFDSGFE"){
	@session_destroy();
	echo '<script>window.location="../login"</script>';
	exit();
}

 ?>
