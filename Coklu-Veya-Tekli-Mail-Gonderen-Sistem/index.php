<?php
    include("gonder.php");

    if (durumBak($database) == 0) {

        $deger = "BAŞLAT";
        $gizliDeger = 1;
        $gelecek = 0;
        
    }else {
        $deger = "DURDUR";
        $gizliDeger = 0;
        $gelecek = 1;
    }

    if (bekleyenMailbak($database) > 0) {

        $var = 1;

    }else {

        $var = 0;

    }

    $tercihSure = sureAl($database);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PHP MAİL ÖRNEĞİ</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>        
    <script>
        $(document).ready(function(e) {
            $('#baslatdurduryeri').hide();
            $('#kalanmailler').hide();

            var durum = "<?php echo $gelecek; ?>";

            if (durum == 1) {
                $('#kapsayici').hide();
                $('#durumbtn').val("DURDUR");
                $('#baslatdurduryeri').show();
                $('#istatistikyeri').load("gonder.php?tur=istatistik");
            }

            var bekleyen = "<?php echo $var; ?>";

            if (bekleyen != 0) {
                $('#kalanmailler').show();
            }

            $('#tekbuton').click(function(e) {
                e.preventDefault();
                var formData = new FormData($('#tekliyukle')[0]);
                $.ajax({
                    beforeSend : function(e) {
                        $('#tekbuton').val("Gönderiliyor...");
                        $('#teklimailsonuc').html("<div class='alert alert-success mt-5'><img src='load.gif'>Gönderme Başladı..</div>");
                    },
                    type : 'POST',
                    url : 'gonder.php?tur=tek',
                    enctype : 'multipart/form-data',
                    data : formData,
                    processData : false,
                    contentType : false
                }).done(function(result) {
                    $('#tekbuton').val("Gönderildi.");
                    $('#tekliyukle').trigger('reset');
                    $('#teklimailsonuc').html("<div class='alert alert-warning mt-5'>"+result+"</div>").fadeIn(3000,function(e) {
                        setTimeout(function(e) {
                            $('#teklimailsonuc').html("").fadeOut(1000);
                            $('#tekbuton').val("Gönder");
                            window.location.reload();
                        },3000);
                    });
                });
            });

            $('#cokbuton').click(function(e) {
                $('#kapsayici').slideUp(500);
                e.preventDefault();
                var formData = new FormData($('#cokluyukle')[0]);
                $.ajax({
                    beforeSend : function(e) {
                        $('#coklumailsonuc').append("<div class='alert alert-success mt-5'><img src='load.gif'>Aktarma Başladı..</div>");
                    },
                    type : 'POST',
                    url : 'gonder.php?tur=bilgikaydet',
                    enctype : 'multipart/form-data',
                    data : formData,
                    processData : false,
                    contentType : false
                }).done(function(result) {
                    $('#cokluyukle').trigger('reset');
                    $('#coklumailsonuc').append("<div class='alert alert-warning mt-5'>"+result+"</div>").fadeIn(3000,function(e) {
                        setTimeout(function(e) {
                            $('#coklumailsonuc').html("").fadeOut(1000);
                            $('#baslatdurduryeri').show();
                            $('#istatistikyeri').load("gonder.php?tur=istatistik");
                        },3000);
                    });
                });
            });

            $('#durumbtn').click(function() {
                $.ajax({
                    type : 'POST',
                    url : 'gonder.php?tur=durumdegistir',
                    enctype : 'multipart/form-data',
                    data : $('#durumdegistir').serialize(),
                    success : function() {
                        window.location.reload();
                    }
                });
            });

            $('#kalanlar').click(function(e) {
                $.post("gonder.php?tur=durumdegistir",{"durumdeger":1},function() {
                    window.location.reload();
                });
            });

            $('#temizle').click(function(e) {
                $.post("gonder.php?tur=herseytemizle",{"temizle":1},function() {
                    window.location.reload();
                });
            });

            $('#vtemizle').click(function(e) {
                $.post("gonder.php?tur=herseytemizle",{"temizle":1},function() {
                    window.location.reload();
                });
            });

            $('#dosyasil').click(function(e) {
                $.post("gonder.php?tur=dosyasil",{"sil":1},function() {
                    window.location.reload();
                });
            });

            if ($('#durumbtn').val() == "DURDUR") {
                setInterval(function() { //setInterval() => içine yazılan işlemler 2.parametreki süre kadar tekrar eder. 2.parametre 2000 se 2 saniyedeki bir tekrar eder.
                    $.post("gonder.php?tur=mailAdbak",{"adresgelsin":"ok"},function(result) {
                        $('#islemmail').html(result);
                    });
                    
                    var adres = $('#islemmail').html();

                    $.post("gonder.php?tur=mailgonder",{"mail":adres},function() {
                        $('#islemmail').html(adres);
                        $('#istatistikyeri').load("gonder.php?tur=istatistik");
                    });
    
                    var adet = $('#bekleyendegeryakala').html();

                    if (adet == 0) {
                        $.post("gonder.php?tur=durumdegistir",{"durumdeger":adet},function() {
                            window.location.reload();
                        });
                    }

                },<?php echo $tercihSure; ?>);
            }
        });
    </script>
</head>
<body>
    <div class="container ">
        <div class="row mt-5 text-center">
            <!--ÇOKLU MAİL-->
            <div class="col-md-5 border border-right bg-white" style="border-radius:10px;">
        		<div id="kapsayici">
                    <div class="row mt-2 pb-1 border-bottom">
                        <div class="col-md-5 text-primary text-right pt-2">
                            Mail listesi Yükle
                        </div>
                        <div class="col-md-7">
                            <form id="cokluyukle">
                                <input type="file" name="txt"/>
                        </div>
                    </div>        
                    <div class="row mt-2 pb-1 border-bottom">
                        <div class="col-md-5 text-primary text-right pt-2">
                            Dosya Ekleyebilirsin
                        </div>
                        <div class="col-md-7">
                            <input type="file" name="ek"/>
                        </div>   
                    </div>
                    <div class="row mt-2 pb-1 border-bottom">
                        <div class="col-md-5 text-primary text-right pt-2">
                            Mail Konusu
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="konu"  class="form-control" required="required"/>
                        </div>
                    </div>  
                    <div class="row mt-2 text-center pb-1 border-bottom">
                        <div class="col-md-12 text-primary font-weight-bold">
                            MAİL İÇERİĞİ
                        </div>                        
                    </div>   
                    <div class="row mt-2  pb-1">
                        <div class="col-md-12">
                            <textarea  style="height:300px;width:100%;" name="cokicerik" ></textarea>
                        </div>                       
                    </div>
                    <div class="row mt-2 pb-1 border-bottom">
                        <div class="col-md-5 text-primary pt-1 text-right">
                            Zaman Aralığı
                        </div>
                        <div class="col-md-7">
                            <select name="sure" class="form-control">
                                <option value="30000">30 Saniye</option>
                                <option value="60000">1 Dakika</option>
                                <option value="180000">3 Dakika</option>
                                <option value="300000">5 Dakika</option>
                            </select>
                        </div>   
                    </div>   
                    <div class="row mt-2  pb-1 border-bottom text-center">
                        <div class="col-md-12">
                            <input id="cokbuton"  type="button" value="OLUŞTUR" class="btn btn-success"/>
                            </form>
                        </div>                        
                    </div>
                    <div class="row mt-2  pb-1 text-center">
                	    <div class="col-md-12" id="kalanmailler">
                            <a id="kalanlar" class="btn btn-danger text-white btn-block">BEKLEYEN MAİLLER VAR !</a>
                            <a id="temizle" class="btn btn-danger text-white btn-block">HERŞEYİ SIFIRLA</a>
                        </div>                       
                    </div>
                </div>        
                <div class="row mt-2  pb-1 text-center">
                	<div class="col-md-12" id="coklumailsonuc"> </div>                       
                </div>
                <div class="row mt-2  pb-1 text-center">
                	<div class="col-md-12" id="baslatdurduryeri"> 
                        <form id="durumdegistir">
                            <input value="<?php echo $gizliDeger ?>" type="hidden" name="durumdeger">
                            <input id="durumbtn" value="<?php echo $deger ?>" type="button" class="btn btn-success">
                        </form>
                    </div>                       
                </div>
                <div class="row mt-2  pb-1 text-center">
                	<div class="col-md-12" id="mailgonderyeri"> </div>                       
                </div>
                <div class="row mt-2  pb-1 text-center">
                	<div class="col-md-12" id="istatistikyeri"> </div>                       
                </div>
            </div>
            <!--ÇOKLU MAİL-->       
            <div class="col-md-2">
                <div class="row mt-2  pb-1 text-center">
                	<div class="col-md-12" id="dosyakontrol">
                        <?php
                            $sayi = 0;
                            foreach(scandir("mailicerik") as $dosya) {
                                if ($dosya == '.' || $dosya == '..') {
                                    continue;
                                }
                                $sayi++;
                            }
                            if ($sayi != 0) { ?>
                                <a id="dosyasil" class="btn btn-info text-white btn-block">DOSYA SİL</a> <?php
                            }
                        ?>
                    </div>                       
                </div>
                <div class="row mt-2  pb-1 text-center">
                	<div class="col-md-12" id="veritabanitemizle">
                        <?php
                            $btnbak = $database->prepare("SELECT * FROM mailicerik");
                            $btnbak->execute();
                            if ($btnbak->rowCount() > 0) { ?>
                                <a id="vtemizle" class="btn btn-info text-white btn-block">VT TEMİZLE</a> <?php
                            }
                        ?>
                        
                    </div>                       
                </div>
            </div>
            <!-- TEKLİ MAİL -->
            <div class="col-md-5 border border-right bg-white  " style="border-radius:10px;">
                <div class="row mt-2 pb-1 border-bottom">
                    <div class="col-md-5 text-primary text-right pt-2">
                        Gönderilecek Adres
                    </div>
                    <div class="col-md-7">
                        <form id="tekliyukle">
                            <input type="text" name="mailadresi"  class="form-control" />
                    </div>
                </div>
                <div class="row mt-2 pb-1 border-bottom">
                	<div class="col-md-5 text-primary text-right pt-2">
                        Dosya Ekleyebilirsin
                    </div>
                    <div class="col-md-7">
                        <input type="file" name="ek" value="Yükle"/>
                    </div>
                </div>           
                <div class="row mt-2 pb-1 border-bottom">
                	<div class="col-md-5 text-primary text-right pt-2">
                        Mail Konusu
                    </div>
                    <div class="col-md-7"> 
                        <input type="text" name="konu"  class="form-control" required="required" />
                    </div>        
                </div>
                <div class="row mt-2 text-center pb-1 border-bottom">
                	<div class="col-md-12 text-primary font-weight-bold">
                        MAİL İÇERİĞİ
                    </div>
                </div>      
                <div class="row mt-2  pb-1 ">
                	<div class="col-md-12">
                        <textarea id="tekli" style="height:300px;width:100%;" name="tekicerik" ></textarea>
                    </div>                        
                </div>
                <div class="row mt-2  pb-1 text-center">
                	<div class="col-md-12">
                        <input id="tekbuton"  type="button" value="Gönder" class="btn btn-success"/>
                        </form>
                    </div>                               
                </div>    
                <div class="row mt-2  pb-1 text-center">
                	<div class="col-md-12" id="teklimailsonuc"> </div>                       
                </div>
            </div>
            <!-- TEKLİ MAİL-->   
        </div>
    </div>
</body>
</html>