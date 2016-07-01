# sozluk
php ile yazılmış basit bir sözlük sistemi

## temel özellikler
- başlık açma
- başlığa kategori bağlama (subreddit tarzında, sadece tek bir kategori)
- entry girme
- entry'lere cevap verme (aynı zamanda cevaplara cevap verme, redditteki cevap sistemi gibi)
- entry oylama (görünür oy sayısı)
- kullanıcılar arası özel mesajlaşma
- basit tema desteği (bootstrap temelli)
- ve diğer temel sözlük yapıları

şuradan canlı bir örneğini görebilirsiniz: http://sozluk.isamert.net


## kurulum
`build` dizininin içinde bulunan sql dosyası ile gerekli database kurulumunu yapabilirsiniz. daha sonra `config.php` içindeki ayarları da yaptıktan sonra sözlüğünüz kullanıma hazır.

## yapılacaklar
- admin paneli
    * şu an için iki tip kullanıcı türü var, normal kullanıcı ve mod. mod'lar her entry'i düzenleme ve silme yetkisine sahip. bir kullanıcıyı mod yapmanın tek yolu databaseyi elle düzenlemek. bir kullanıcıyı mod yapmak için database'yi açıp, `member` tablosundan `member_status` sütununu `m` olarak değiştirmek gerekiyor.
- yazar sayfasının geliştirilmesi
    - şu an için sadece yazarın son girdiği 10 entry görülüyor. bunların hepsinin görüntülenmesi, abone olduğu kategoriler, yazdığı kategoriler ve diğer istatiksel bilgilerin görüntülenmesi gibi şeyler.
- kategori sisteminin geliştirilmesi
    - bu sözlük sisteminin temel amacı reddit ile ekşi sözlük arası bir forma sahip olması. bunun tamamlanabilmesi için kategori sisteminin düzenlemesi gerekiyor. kategorisiz başlıkların açılmaması, kategori ekleme gibi özellikler bu alanda yapılması gereken şeyler.
- abonelik sisteminin yazılması
    - kategori sistemi için öncelikli adım.
