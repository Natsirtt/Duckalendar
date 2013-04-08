<?php
$title = "Inscription";
include_once 'inc/header.inc.php';

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['rePassword'])) {
    
}
?>

<div id="inscription"
     <form action="" method="post">
        <input type="text" name="login" value="Login" class="round" /><br />
        <input type="password" name="password" value="pasword" class="round" /><br />
        <input type="password" name="rePassword" value ="password" class="round" /><br />
        <input type="submit" value="Inscription" />
    </form>
</div>

<?php include_once 'inc/footer.inc.php'; ?>