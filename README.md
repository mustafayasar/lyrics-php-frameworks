# php-frameworks-lyrics
Php Frameworks with an example Lyrics Project


####hız testleri
    hepsi bir defa cache ile bir defa cachesiz çalıştırıldı.
    ##sayfalar
        /
        /singers/hit
        /singers/c
        /songs/hit
        /songs/m
        /beyonce-songs
        /zayn-songs
        /zayn/still-got-time-lyrics
        /beyonce/start-over-lyrics
        /search?q=hello
        
        
    #codeigniter
    141,3,12,3,10,2,200,2,141,2,19,4,22,3,12,2,12,2
    32
    
    #yii2
    109,10,20,9,15,7,84,10,172,8,65,8,67,10,14,6,17,6, 20
    35
    
    #laravel5
    437,29,29,23,24,20,142,14,173,15,31,22,27,18,30,18,18,29,16 20
    57
    
    #symfony4
    430,21,35,28,32,18,102,19,102,21,132,22,58,21,63,20,51,23 23
    64
    
    
##Codeigniter 3
Açıkcası hız testlerinde en hızlı çıktığına aldanmayın. Çok ilkel bir framework, listelerde çok adı geçiyor diye test ettim.
Sadece temel sayfaları yaptım, admin paneli, elasticsearch vs. kısımları yapmadım.
Önemli bir proje geliştirmek isteyen birine önermem, sadece PHP'de OOP, framework gibi kavramlara yeni giriş yapan birisi için iyi bir anlama ve inceleme seçeneği olabilir.

##Yii 2
Eğer Yii2 Framework'un advance versiyonunu kurarsanız, size backend/frontend birbirinden ayrılmış şlekilde bir yapı sunar, ve kullanıcı girişi ayarlarını hazır yapılı bir şekilde karşınıza getirir.

user için migrasyonları çalıştırabilirsiniz.

php yii migrate/up

admin 123456
   
##Laravel 5
php artisan migrate:install
php artisan migrate:refresh
php artisan db:seed

admin@admin.com
123456

##Symfony 4