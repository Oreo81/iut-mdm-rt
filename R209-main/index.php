<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
    <title>Movie Planet</title>
</head>
<body>
    <header>
    <?php
        $db = new SQLite3("db/db.db");
        session_start();
        include("templates/header_index.php");?>
    </header>

<?php 
    
    $fid_req = $db->query("SELECT fid FROM produits");
    while ($fid = $fid_req->fetchArray()) {
        $list_fid[] = $fid['fid'];
    }
?>

    <div class="main_page">
    <div class="main_container">
        <div class="main_slider">
            <h1>Films à la une</h1>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php              
                    $rand_keys = array_rand($list_fid, 6);
                        
                    $f0 = $list_fid[$rand_keys[0]];
                    $f1 = $list_fid[$rand_keys[1]];
                    $f2 = $list_fid[$rand_keys[2]];
                    $f3 = $list_fid[$rand_keys[3]];
                    $f4 = $list_fid[$rand_keys[4]];
                    $f5 = $list_fid[$rand_keys[5]];

                    echo "<div class='swiper-slide'><a href='./php/fiche.php?movie={$f0}'><img src='img/film_img/{$f0}.jpg'></a></div>";
                    echo "<div class='swiper-slide'><a href='./php/fiche.php?movie={$f1}'><img src='img/film_img/{$f1}.jpg'></a></div>";
                    echo "<div class='swiper-slide'><a href='./php/fiche.php?movie={$f2}'><img src='img/film_img/{$f2}.jpg'></a></div>";
                    echo "<div class='swiper-slide'><a href='./php/fiche.php?movie={$f3}'><img src='img/film_img/{$f3}.jpg'></a></div>";
                    echo "<div class='swiper-slide'><a href='./php/fiche.php?movie={$f4}'><img src='img/film_img/{$f4}.jpg'></a></div>";
                    echo "<div class='swiper-slide'><a href='./php/fiche.php?movie={$f5}'><img src='img/film_img/{$f5}.jpg'></a></div>";
                    ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script>
    const swiper = new Swiper('.swiper', {
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
  
  loop: true,

  pagination: {
    el: '.swiper-pagination',
    clickable: true,
    type: 'bullets',
  },

  
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

});

</script>
            <div class="main_video">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/UkWDX_lGdKI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>  
    <footer class="main_footer">
        <?php include("templates/footer.php");?>
    </footer>
</body>