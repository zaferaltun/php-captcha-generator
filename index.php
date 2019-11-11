<?php
    session_start();
    
    if (sizeof($_POST)){
        if ($_POST["captcha"] == $_SESSION["securityCode"]){
            // Burada kullanıcı doğrulaması aşamasına geçilebilir
            // ...
            
            die("İnsan olduğunuzu kanıtladınız!");
        } else {
            die("Güvenlik kodu yanlış!!");
        }
        die();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CAPTCHA Test</title>
        <style type="text/css">
            body { padding:15px; }
            input, img, button { display:block; margin-top:15px; width:150px; border:1px solid #ccc }
            input { padding:5px; width:140px; }
            button { padding:5px }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            function login(){
                $.post("/", {
                    username: $("#username").val().trim(),
                    password: $("#password").val().trim(),
                    captcha: $("#captcha").val().trim()
                }, function(data, status){
                    alert(data);
                    $("#captcha").focus();
                })
            }
            
            function refreshCaptcha(){
                $("img").removeAttr("src");
                $("img").attr("src", "/captcha-generator.php");
            }
        </script>
    </head>
    <body>
        <input type="text" id="username" value="zafer" placeholder="Kullanıcı adı" />
        <input type="password" id="password" value="1" placeholder="Şifre" />
        <img src="/captcha-generator.php" />
        <button onclick="refreshCaptcha()">Resmi Yenile</button>
        <input type="text" id="captcha" value="" placeholder="Güvenlik kodu" />
        <button onclick="login()">Giriş</button>
    </body>
</html>
