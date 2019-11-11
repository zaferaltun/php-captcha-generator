<?php

session_start();

// Hata ayıklama için bu kısmı kullanabilirsiniz
/*
if ($_REQUEST["debug"] == "true" && $_SESSION["securityCode"]){
    die($_SESSION["securityCode"]);
}*/

function generateRandomString($length = 7) {
    // Karakter setini belirle. l(küçük l), I(büyük I) ve 1(bir) gibi benzer harfleri içermemeli
    $characters = '23456789abcdeghkmnopqrsuvwxyz';
    
    // İstenen uzunluk kadar rastgele karakter seç
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $randomString;
}

function generateCaptchaImage($text) {
    // Tarayıcının sayfaya basılan veriyi resim olarak algılaması için gerekli header ı tanımla
    header('Content-Type: image/jpg');
    
    // Taslak görselden resim oluştur
    $imgRes = imagecreatefromjpeg($_SERVER["DOCUMENT_ROOT"] . "/captcha-bg.jpg");
    if ($imgRes){
        $width = imagesx($imgRes);
        $height = imagesy($imgRes);
    }

    // RGB değerlerine göre çeşitli renkler oluştur
    $white = imagecolorallocate($imgRes, 255, 255, 255);
    $grey = imagecolorallocate($imgRes, 128, 128, 128);
    $black = imagecolorallocate($imgRes, 0, 0, 0);
    $colors = array(
        imagecolorallocate($imgRes, 73, 92, 229),
        imagecolorallocate($imgRes, 0, 178, 121),
        imagecolorallocate($imgRes, 229, 107, 195),
        imagecolorallocate($imgRes, 229, 108, 100)
    );

    // Taslak resmin üzerine basılacak karakterler için font yolunu tayin set
    $font = $_SERVER["DOCUMENT_ROOT"] . '/OpenSans-Regular.ttf';

    // Oluşturulacak metindeki karakterleri döndürerek taslak resmin üzerine bas
    $fontCharSize = 16;
    $fontCharSpace = 20;
    for ($i = 0; $i < strlen($text); $i++){
        $angle = 0; // Karakteri döndürmek için bu değere rand(-30, 30) gibi bir değerle rastgele değer atayabilirsiniz 
        $paddingX = ($width - strlen($text) * $fontCharSpace) / 2;
        
        // Karakterler için gölge ekle
        imagettftext($imgRes, $fontCharSize, $angle, $paddingX + ($i * $fontCharSpace) + 1, $height - 7, $grey, $font, $text[$i]);

        // Karakteri rastgele renkte bas
        imagettftext($imgRes, $fontCharSize, $angle, $paddingX + ($i * $fontCharSpace), $height - 8, $colors[rand(0, count($colors) - 1)], $font, $text[$i]);
    }

    // Resmin son halini sayfaya bas
    imagepng($imgRes);
    
    // Resim verisini RAM 'dan kaldır
    imagedestroy($imgRes);
    
    // Oluşturulan resme ait metni daha sonra karşılaştırmak için session 'a yaz
    $_SESSION["securityCode"] = $text;
}

// Rastgelen bir metin oluştur, varsayılan olarak 7 karakter uzunluğunda
$randomString = generateRandomString(7);

// Oluşturulan metin için captcha resmi oluştur
generateCaptchaImage($randomString);
?>