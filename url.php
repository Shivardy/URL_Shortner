<html>
<head>
<title>URL-Shortner</title>
<style>
body{
padding:0;
margin:0;
}
.title{
font-weight:normal;

}
.container{
width:100%;
max-width:600px;
text-align:center;
margin:0 auto;
}


</style>
<link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-pink.min.css">
<script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</head>
<body>
<div class="container">
<h1 class="title">Shorten a URL</h1><br><br>

<?php
$host="localhost";
$user="root";
$password="";
$db_name="chat";
$con=mysqli_connect($host,$user,$password,$db_name);
session_start();
if(isset($_POST['url'])){
$url=$_POST['url'];
if($code=check($url,$con)){
$_SESSION['feedback']='Generated! Your short URL is : <a href="http://localhost:12/url.php/'.$code.'">http://localhost:12/url.php/'.$code.'</a>';

}else{
$_SESSION['feedback']='Sorry! Something went wrong';

}
}
function check($url,$con){
$url=trim($url);
if(!filter_var($url,FILTER_VALIDATE_URL)){
return "";
}

$exists=mysqli_query($con,"SELECT code FROM links WHERE url='{$url}'");
if($exists->num_rows){
return $exists->fetch_object()->code;
}
else{
$code=code();
$insert=mysqli_query($con,"INSERT INTO links(url,code,created) VALUES('{$url}','{$code}',NOW())");
return $code;

}

}
	  function code() {
	  $length=10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
if(isset($_SESSION['feedback'])){
echo "<p>".$_SESSION['feedback']."</p>";
unset($_SESSION['feedback']);
}
$self=$_SERVER['REQUEST_URI'];
$self=explode('/',$self);

if(sizeof($self)==3&&strlen($self['2'])==10){
$code=$self['2'];
$redirect=mysqli_query($con,"SELECT url FROM links WHERE code='{$code}'");
$redirect=$redirect->fetch_object()->url;
header("Location:{$redirect}");
}
?>
<form action="url.php" method="post">
  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" name="url" type="url" >
    <label class="mdl-textfield__label" >Enter url</label>
  </div><br>
	<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored">Submit</button>
</form>

</div>
<h6 style="position:absolute;left:0;right:0;bottom:-10;text-align:center;">Production <a style="color:#3F51B5;"href="https://www.facebook.com/shivareddy0">Shiva R'dy</a></h6>
</body>
</html>