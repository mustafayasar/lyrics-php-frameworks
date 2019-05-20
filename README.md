# PHP Frameworks Lyrics Project
Buradaki amaç gerçek bir proje üzerinde php frameworklerini incelemek ve performanslarını test etmektir.

Framework hız testleri genelde gerçek olmayan veya gerçekçi olmayan kod betikleriyle yapılır, burada gerçek bir proje ile bunu yaparak gerçek bir performans testi yapmış oluyoruz.

Geliştirme aşamasında şuna dikkat ettim, eğer o framework ile önceden bir tecrübeye sahipsem, bu tecrübelerimi kullanmadım. 
Her frameworkün öğrenme aşamasında kendi dökümantasyonundan başka bir şeyden yararlanmadım. 
Derinlenmesine öğrenmeye de çalışmadım. Dökümantasyonu takip ederek öğrenmeye çalıştım. 
Yani kendi dökümantasyonun beni yönlendirmesine tabi oldum.

Şuan 4 framework ile geliştirdiğim bu projeyi ileride daha da genişleteceğim. 
İlk fırsatta eklemeyi düşündüğüm frameworkler; CakePhp, Phalcon, Zend, Slim ...

#### Şarkı Sözleri Projesinin İçeriği ve Veritabanı
Projenin veritabanı şarkıcıları ve şarkıları içeren iki basit tablodan oluşuyor. 
*files/lyrics.sql* örnek olarak kullanılabilecek şarkıcı ve şarkıları içeriyor. 
Bu veritabanı dosyasını istediğiniz projede kullanabilirsiniz.  

Projeyi geliştirme amacımı yukarıda belirttim. Bundan dolayı geliştirme esnasında gözden kaçırdığım şeyler olabilir. Bunları yakalaycak olursanız, lütfen issue ile bildiriniz.
Ayrıca bu repositoryi istediğiniz gibi clone'layabilir, testleri kendiniz yapabilirsiniz. Her framworkun kurulum notlarını aşağıda kendi başlığında bulabilirsiniz.

Projede arama kısmında elasticsearch, cache olarak da redis kullanıldı. Proje gereksinimleri kısaca; Php >=7.1, mysql, redis, elasticsearch (Ben v2.5.3 kullandım fakat istediğiniz versiyonu kullanabilirsiniz, çünkü sadece temel özellikler  kullanıldı.)


#### Framework Puanlama ve Yorumlarım
Konulara göre genel puanlamamı tabloda bulabilirsiniz. 
Her framework için ayrıca yorumumu aşağıda başlıklar halinde bulabilirsiniz. 
Açıklama sıralamasını önerdiğim sıraya göre yaptım.
Yani şuanki durumda benim birincil tavsiyem Yii2 Framework oldu. 
Tabi bu sıralama ve puanlama benim kişisel deneyimim ve bakış açımın bir sonucu.  

|Konular  | Yii 2  | Laravel 5  | Symfony 4  | Codeigniter 3|
|:--- | :--- | :--- | :--- | :---|
|Hız | 8 | 7 | 6 | 9|
|Mimari | 8 | 8 | 9 | 3|
|Kolay Geliştirme | 8 | 8 | 6 | 7|
|Kolay Öğrenme | 7 | 8 | 7 | 8|
|Topluluk Kalitesi | 8 | 7 | 9 | 6|
|Dökümantasyon | 7 | 8 | 7 | 7|

#### Hız Testi
Test aşağıdaki sayfaların cache'siz ve cache ile çalıştırılıp, alınan sonuçların ortalaması (mili saniye cinsinden) alınarak yapıldı.
Bazı frameworklerde bir sayfayı ilk çalıştırma ile daha sonrası veya cache'siz çalıştırma ile cache ile çalıştırma çok farklı sonuçlara sahip olabiliyor. 
Mesela Laravel projeyi ilk çalıştırma da çok fazla vakit harcıyor ama daha sonra azalıyor. Bu gibi durumların hiçbiri aşağıdaki sonuçlarda dikkate alınmadı.

Ve en önemlisi her frameworkun kendine göre performans optimizasyonları bulunabiliyor. Fakat hiçbirinde bu gibi şeyler yapılmadı. Sadece ham hali baz alınarak ve her framework için aynı şeyler yapıldı, ve kendi debug sonuçları baz alındı. Hız ile ilgili görüşlerime ayrıca her frameworkun kendi başlığı altında yorumladım.

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

![](https://raw.githubusercontent.com/mustafayasar/lyrics-php-frameworks/master/files/speed_chart.png)


## Yii 2
Türkiye'de çok yaygın olmayan, dünyada yaygın olarak kullanılan bir framework. 
Hız konusunda sadece bizim hız testimizde değil, bir çok benchmark da ön plana çıkan bir performansa sahip. 

Ayrıca modern bir yapıya ve kullanışlı sınıflara sahip. Günümüzde gelişmiş bir web frameworku neye sahip olması gerekiyorsa hepsine sahip.

En beğendiğim artıları; Hızlı olmasının haricinde; 
Advanced yapıyla hazır kullanılabilen bir admin paneli sunması ve bunun yanında GridView, ListView, Pjax, ActiveForm gibi widgetlar ile bilhassa panel tarafındaki geliştirmeler için hızlı geliştirme yapabileceğin bir ortam sunuyor.

Ayrıca uzun süre bir versiyon üzerinde durmaları, Laravel gibi çok sık versiyon çıkarmamaları.

Eklenti konusunda da sağlam bir topluluk desteği var.

Bazı eksiler; Dökümantasyon daha iyi olabilirdi. Ayrıca resmi eklentilerinden bazılarını geliştirme hızında sıkıntı var gibi. Örneğin; elasticsearch eklentisi versiyon 5 sonrasını desteklemiyor.


#### Kurulum Notları
Burada [Yii2 Advanced Project Template](https://www.yiiframework.com/extension/yiisoft/yii2-app-advanced/doc/guide/2.0/en) kullanıldı. Dolayısıyla site ve admin paneli dizinleri farklı.

Eğer Nginx kullanıyorsanız örnek ayarlar için */files/nginx.conf* dosyasını inceleyebilirsiniz.


Veritabanını (*files/lyrics.sql*) kendi localinizde yükledikten sonra ve mysql, redis, elasticsearch ayarlarını yapınız. Composer install etmeyi unutmayınız.
Dosya yapılarını ve temel ayarların nasıl yapıldığını [Yii Framework sitesi](https://www.yiiframework.com/)nden öğrenebilirsiniz.

Son olarak aşağıdaki kod ile *migration up* yapıp admin paneline giriş yapabilirsiniz.

    php yii migrate/up

    username: admin 
    password: 123456

Mysql verilerini Elasticsearch'e aktarmak için admin panelindeki butonuna tıklayarak aktarma scriptini çalıştırmanız gerekiyor. İlk aktarmadan sonra yapacağınız değişiklikler eş zamanlı olarak Elasticsearch'e yansıyor.

## Laravel 5
Türkiye'de ve Dünyada en çok kullanılan framework. Modern bir yapıya sahip, gerekli ayarları yapıldığı takdirde çok performanslıdır. Kütüphanesinde symfony'nin bir çok sınıfı mevcut.

Çok geniş bir eko sisteme sahip ve gittikçe de genişliyor. Önemli artılarından biri çabuk öğrenilebilir olması ve öğrenmek için çok fazla kaynak bulunması. 
Geliştirme topluluğu çok geniş değil, kurucusu Taylor Otwell bu konuda titiz, kolay kolay herkesi dahil etmiyor.
Fakat kullanıcıların oluşturduğu çok fazla paket var, bu bakımdan zengin, ama kullanıcı kitlesinin çok kaliteli olduğunu söylemek zor. Yani programlamada profesyonel olan olmayan herkes Laravel kullanıyor, bu öğrenim ve kullanım kolaylığı onun topluluk kalitesini düşürüyor gibi.

Eksileri; bir versiyon üzerinde uzun zaman durulmuyor, örneğin Yii Framework çok daha eski olmasına rağmen 2 ana sürümü var. Laravel ise 5.8 de, ve internette okuduğum en önemli eleştirileri 5.x'den 5.y'ye geçerken bile önemli değişiklikler yapılıyor olması geliştiricilerin canını sıkıyor.

Yii2'de bahsettiğim GridView, ListView, Pjax, ActiveForm türünde benzeri modüller yok. Bu bir eksi midir? Bir web frameworkünden beklentinize göre değişir.

#### Kurulum Notları
Veritabanını (*files/lyrics.sql*) kendi localinizde yükledikten sonra; mysql, redis, elasticsearch ayarlarını yapınız. Composer install etmeyi unutmayınız.
Dosya yapılarını ve temel ayarların nasıl yapıldığını [Laravel sitesi](http://laravel.com)nden öğrenebilirsiniz.

Daha sonra aşağıdaki kodları sırasıyla çalıştırınız.

    php artisan migrate:install
    
    php artisan migrate:refresh
    
    php artisan db:seed

Eğer web server olarak Nginx kullanıyorsanız örnek ayarlar için */files/nginx.conf* dosyasını inceleyebilirsiniz.

/admin dizininden admin paneline ulaşabilirsiniz.

    mail: admin@admin.com 
    password: 123456

Mysql verilerini Elasticsearch'e aktarmak için admin panelindeki butonuna tıklayarak aktarma scriptini çalıştırmanız gerekiyor. İlk aktarmadan sonra yapacağınız değişiklikler eş zamanlı olarak Elasticsearch'e yansıyor.


## Symfony 4
Symfony'e biraz farklı bakmak gerekiyor, nedeni ise yapısal olarak diğer frameworklerden farklı olması ve daha profesyonel bir yapıya sahip olması.
Java'nın Spring frameworküne çok benzettim. Doctrine etkisiyle Entity, Repository yapısı. Annotion'lar gerçekten tam bir modern yazılım mimarisi havasına sokuyor.
Bu bakımdan diğer PHP frameworklerden farklı yanları var. Amatörlerin uyum sağlamakta ve öğrenmekte zorlanabileceği bir framework. 
Symfony kütüphaneleri Laravel gibi birçok framework bünyesinde de kullanılıyor. Geliştirici kitlesi çok kalitelidir. Geliştirme süreçleri bir versiyonu ne kadar süre geliştirileceği vs. hepsi takvime bağlıdır.

Hız konusunda diğer seçeneklerden biraz geri kalır gibi olsa da  4. versiyon ile birlikte çoğu şeyi aşmışlar. Eğer sisteme ve mimariye ayak uydurur ve iyi bir geliştirme yaparsanız hızlı bir ürün çıkarabilirsiniz.

Eksileri;
Geliştirme süreci diğer frameworklere göre yavaş ilerleyebilir. 
Örneğin laravel veya yii2 de yaptığımız gibi tek bir fonksiyon eklemesiyle pagination oluşturma, hatta pagination html'ini bile oluşturma işini buradaki default gelen çatıda yapamazsınız. 
Tabi bu sadece bir örnek, DataGrid, Form oluşturucu filan gibi şeyler yok. 
Bunlar eksi değil aslında fakat Php frameworklerinden beklenilen genel şeyler haline geldiği için sadece belirtiyorum.

Admin panelinde resmi olarak desteklenen SonataAdmin eklentisini kullandım, çok uğraşmamak için. Django'ya benzer bir yapıya sahip, ama o kadar iyi değil, çok beğenemedim.

Profesyonelliğin önemli olduğu, zaman sıkıntınızın olmadığı bir proje geliştirmek için kullanabilirsiniz.


#### Kurulum Notları
Veritabanını (*files/lyrics.sql*) kendi localinizde yükledikten sonra; mysql, redis, elasticsearch ayarlarını yapınız. Composer install etmeyi unutmayınız.
Dosya yapılarını ve temel ayarların nasıl yapıldığını [Symfony sitesi](https://symfony.com/)nden öğrenebilirsiniz.

Daha sonra aşağıdaki kodu çalıştırınız.

    php bin/console doctrine:migrations:migrate
    
Eğer Nginx kullanıyorsanız örnek ayarlar için */files/nginx.conf* dosyasını inceleyebilirsiniz.

/admin dizininden admin paneline ulaşabilirsiniz.

    username: admin 
    password: 123456

Mysql verilerini Elasticsearch'e aktarmak için admin panelindeki butonuna tıklayarak aktarma scriptini çalıştırmanız gerekiyor. İlk aktarmadan sonra yapacağınız değişiklikler eş zamanlı olarak Elasticsearch'e yansıyor.

## Codeigniter 3
Açıkcası hız testlerinde en hızlı çıktığına aldanmayın. Çok ilkel bir framework, listelerde çok adı geçiyor diye test ettim, eskinin şartlarında iyi bir frameworktü, fakat şuan profesyonel bir tercih değil.
Sadece temel sayfaları yaptım, admin paneli, elasticsearch vs. kısımları yapmadım.
Önemli bir proje geliştirmek isteyen birine önermem, sadece PHP'de OOP, MVC, framework gibi kavramlara yeni giriş yapan birisi için iyi bir anlama ve inceleme seçeneği olabilir.




## Projeden bazı ekran görüntüleri

![](https://raw.githubusercontent.com/mustafayasar/lyrics-php-frameworks/master/files/lyrics-ss-1.jpg)

![](https://raw.githubusercontent.com/mustafayasar/lyrics-php-frameworks/master/files/lyrics-ss-2.jpg)

![](https://raw.githubusercontent.com/mustafayasar/lyrics-php-frameworks/master/files/lyrics-ss-3.jpg)

![](https://raw.githubusercontent.com/mustafayasar/lyrics-php-frameworks/master/files/lyrics-ss-4.jpg)

![](https://raw.githubusercontent.com/mustafayasar/lyrics-php-frameworks/master/files/lyrics-ss-5.jpg)
