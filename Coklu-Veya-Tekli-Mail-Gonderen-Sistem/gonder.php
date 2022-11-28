<?php

try {

	$database = new PDO("mysql:host=localhost;dbname=httpdnfw_mailislem;","httpdnfw_root","Password");
	$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
} catch (PDOException $e) {

	die($e->getMessege());

}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(true);
$mail->SMTPDebug = 0; 
$mail->isSMTP();
$mail->CharSet = 'UTF-8';
$mail->Host = 'mail.kayseriphpegitim.shop';
$mail->SMTPAuth = true;
$mail->Username = 'Mail';
$mail->Password = 'Password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->setFrom($mail->Username , 'İbrahim Hastorun');
$mail->addReplyTo($mail->Username , 'Geri Cevap');
$mail->isHTML(true);


function durumBak($database) {
	$durumbak = $database->prepare("SELECT * FROM mailicerik");
	$durumbak->execute();
	$durumbakcek = $durumbak->fetch();
	return $durumbakcek['durum'];
}

function bekleyenMailbak($database) {
	$bekleyenMailbak = $database->prepare("SELECT * FROM kisiler WHERE durum = ?");
	$bekleyenMailbak->bindValue(1,0,PDO::PARAM_INT);
	$bekleyenMailbak->execute();
	$bekleyenMailbakcek = $bekleyenMailbak->rowCount();
	return $bekleyenMailbakcek;
}

function sureAl($database) {
	$sureBak = $database->prepare("SELECT * FROM mailicerik");
	$sureBak->execute();
	$sureBakcek = $sureBak->fetch();
	return $sureBakcek['sure'];
}

function mailAdbak($database) {
	$mailBak = $database->prepare("SELECT * FROM kisiler WHERE durum = ? LIMIT 1");
	$mailBak->bindValue(1,0,PDO::PARAM_INT);
	$mailBak->execute();
	$mailBakcek = $mailBak->fetch();
	return $mailBakcek['mailadres'];
}

@$tur = $_GET['tur'];
switch ($tur) {
	case 'tek':
		$gelenMail = $_POST['mailadresi'];
		$konu = $_POST['konu'];
		$tekIcerik = $_POST['tekicerik'];

		$ekKaynak = $_FILES['ek']['tmp_name'];
		$tekAd = $_FILES['ek']['name'];
		move_uploaded_file($ekKaynak,$tekAd);

		$mail->addAttachment($tekAd);
		$mail->addAddress($gelenMail);    
		$mail->Subject = $konu;
		$mail->Body = $tekIcerik;

		if ($mail->send()) {
    
			unlink($tekAd);
			echo "Mail Gönderildi";
		
		}else {
					
			echo "Malesef hata var . Hata kodu : ".$mail->ErrorInfo;
		
		} 
	break;

	case 'durumdegistir':
		$durumdeger = $_POST['durumdeger'];
		$durumdegistir = $database->prepare('UPDATE mailicerik SET durum = ?');
		$durumdegistir->bindParam(1,$durumdeger,PDO::PARAM_INT);
		$durumdegistir->execute();
	break;

	case 'bilgikaydet':
		$txtKaynak = $_FILES['txt']['tmp_name'];
		$txtAd = "mailicerik/".$_FILES['txt']['name'];
		move_uploaded_file($txtKaynak,$txtAd);

		$ekKaynak = $_FILES['ek']['tmp_name'];
		$ekAd = "mailicerik/".$_FILES['ek']['name'];
		move_uploaded_file($ekKaynak,$ekAd);

		$konu = $_POST['konu'];
		$cokIcerik = $_POST['cokicerik'];
		$sure = $_POST['sure'];

		$mailicerikkaydet = $database->prepare("INSERT INTO mailicerik (ekdosya,konu,icerik,sure) VALUES (?,?,?,?)");
		$mailicerikkaydet->bindParam(1,$ekAd,PDO::PARAM_STR);
		$mailicerikkaydet->bindParam(2,$konu,PDO::PARAM_STR);
		$mailicerikkaydet->bindParam(3,$cokIcerik,PDO::PARAM_STR);
		$mailicerikkaydet->bindParam(4,$sure,PDO::PARAM_INT);
		$mailicerikkaydet->execute();

		echo "Mail İcerik Bilgileri Kayıt Edildi.<br>";

		$dosya = fopen($txtAd,"r");
		while (!feof($dosya)) {
			$mailadres = fgets($dosya);
			$mailkaydet = $database->prepare("INSERT INTO kisiler (mailadres) VALUES (?)");
			$mailkaydet->bindParam(1,$mailadres,PDO::PARAM_STR);
			$mailkaydet->execute();
		}
		fclose($dosya);
		unlink($txtAd);
		echo "Mail Bilgileri Kayıt Edildi.<br>";		
	break;

	case 'istatistik':
		$toplamDegerbak = $database->prepare("SELECT * FROM kisiler");
		$toplamDegerbak->execute();
		$toplamDeger = $toplamDegerbak->rowCount();

		$gonderilenDegerbak = $database->prepare("SELECT * FROM kisiler WHERE durum = ?");
		$gonderilenDegerbak->bindValue(1,1,PDO::PARAM_INT);
		$gonderilenDegerbak->execute();
		$gonderilenDeger = $gonderilenDegerbak->rowCount();

		$islemmail = $database->prepare("SELECT * FROM kisiler WHERE durum = ?");
		$islemmail->bindValue(1,0,PDO::PARAM_INT);
		$islemmail->execute();
		$islemmailbak = $islemmail->fetch();

		$bekleyenDeger = ($toplamDeger - $gonderilenDeger); ?>

		<div class="row mt-2  pb-1  text-center text-success">
            <div class="col-md-6">İşlem Gören Mail : </div>
            <div id="islemmail" class="col-md-6 text-left text-danger"><?php echo $islemmailbak['mailadres']; ?></div>                          
        </div>
		<div class="row mt-2  pb-1  text-center text-success">
            <div class="col-md-6">Gönderilen</div>
            <div class="col-md-6 text-left text-danger"><?php echo $gonderilenDeger; ?></div>                          
        </div>     
        <div class="row mt-2  pb-1  text-center text-success">
            <div class="col-md-6">Bekleyen</div>
            <div id="bekleyendegeryakala" class="col-md-6 text-left text-danger"><?php echo $bekleyenDeger; ?></div>                          
        </div>    
        <div class="row mt-2  pb-1  text-center text-success">
            <div class="col-md-6">Toplam</div>
            <div class="col-md-6 text-left text-danger"><?php echo $toplamDeger; ?></div>                        
        </div> <?php	
	break;

	case 'mailgonder':
		$mailbilgi = $database->prepare("SELECT * FROM mailicerik");
		$mailbilgi->execute();
		$mailbilgicek = $mailbilgi->fetch();

		$gelenMail = $_POST['mail'];

		$mail->addAttachment($mailbilgicek['ekdosya']);
		$mail->addAddress($gelenMail);    
		$mail->Subject = $mailbilgicek['konu'];
		$mail->Body = $mailbilgicek['icerik'];
		$mail->send();

		$durumguncelle = $database->prepare("UPDATE kisiler SET durum = ? WHERE durum = ? LIMIT 1");
		$durumguncelle->bindValue(1,1,PDO::PARAM_INT);
		$durumguncelle->bindValue(2,0,PDO::PARAM_INT);
		$durumguncelle->execute();
	break;

	case 'herseytemizle':
		if ($_POST['temizle'] == 1) {
			$database->prepare("TRUNCATE TABLE kisiler")->execute(); // kisiler tablosnu komplle temizle
			$database->prepare("TRUNCATE TABLE mailicerik")->execute();
		}
	break;

	case 'mailAdbak':

		echo mailAdbak($database);

	break;

	case 'dosyasil':
		foreach(scandir("mailicerik") as $dosya) {
			if ($dosya == '.' || $dosya == '..') {
				continue;
			}
			unlink("mailicerik/".$dosya);
		}
	break;
}
?>
