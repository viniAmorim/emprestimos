<?php 
@session_start();
@session_destroy();
echo '<script>window.location="https://localhost/login"</script>';
 ?>