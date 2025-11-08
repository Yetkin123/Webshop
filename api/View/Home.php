<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <meta name="description" content="Home pagina van Yerothia Coffee. Koffie met een glimlach. Daar staan we voor. Alles in huis voor zakelijke klanten, webshop, informatie over koffie.">
    <meta name="keywords" content="Koffie, Coffee, Yerothia, Thee, Suiker, Lekkker, Glimlach, Mok, Koffiemok, Zakelijk, Professioneel, Koffie-producten, Yerothia-Coffee, Bonen, Koffiebonen, Koffie bedrijf">
    <title>Home - Yerothia Coffee</title>
    <link rel="icon" type="image/ico" href="/api/images/favicon.ico">
    <link rel="stylesheet" href="/api/styles/footer.css">
    <link rel="stylesheet" href="/api/styles/style.css">
    <link rel="stylesheet" href="/api/styles/home.css">
    <link rel="stylesheet" href="/api/styles/header.css">
    <link rel="stylesheet" href="/api/styles/gallery.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://db.onlinewebfonts.com/c/36fda95b699fea73e18a8f2d1ad6a6c0?family=Amasis+MT" rel="stylesheet"> 
    <link href='https://fonts.googleapis.com/css?family=ADLaM%20Display' rel='stylesheet'>
    <link href="https://db.onlinewebfonts.com/c/51a69624ea6dd3b2f3e808c39d367a95?family=Abadi+MT+Std+Extra+Light+It" rel="stylesheet"> 
</head>
<body>
    <div class="container">
        <header id="header">
            <?php include __DIR__ . '/../includes/header.php'; ?>
        </header>

        <section id="content">
            <?php include __DIR__ . '/../includes/gallery.php'; ?>
            <div class="feature-grid">
                <article class="feature-card">
                    <div class="feature-media">
                        <img src="/api/images/feature-products-800.png"
                             srcset="/api/images/feature-products-400.png 400w, /api/images/feature-products-800.png 800w, /api/images/feature-products-1200.png 1200w"
                             sizes="(max-width:600px) 100vw, (max-width:1200px) 50vw, 33vw"
                             alt="Assortiment Yerothia Coffee"
                             loading="lazy">
                    </div>
                    <div class="feature-body">
                        <h3>Onze producten</h3>
                        <p>Ontdek koffiebonen, blends en accessoires — met zorg geselecteerd voor de perfecte kop.</p>
                        <a class="btn" href="/products">Bekijk producten</a>
                    </div>
                </article>

                <article class="feature-card">
                    <div class="feature-media">
                        <img src="/api/images/feature-subscription-800.png"
                             srcset="/api/images/feature-subscription-400.png 400w, /api/images/feature-subscription-800.png 800w, /api/images/feature-subscription-1200.png 1200w"
                             sizes="(max-width:600px) 100vw, (max-width:1200px) 50vw, 33vw"
                             alt="Abonnementen Yerothia Coffee"
                             loading="lazy">
                    </div>
                     <div class="feature-body">
                         <h3>Abonnementen</h3>
                         <p>Laat verse koffie maandelijks thuisbezorgen — flexibel en gemakkelijk op maat.</p>
                         <a class="btn" href="/subscriptions">Abonnementen</a>
                     </div>
                 </article>
 
                 <article class="feature-card">
                    <div class="feature-media">
                        <img src="/api/images/feature-wiki-800.png"
                             srcset="/api/images/feature-wiki-400.png 400w, /api/images/feature-wiki-800.png 800w, /api/images/feature-wiki-1200.png 1200w"
                             sizes="(max-width:600px) 100vw, (max-width:1200px) 50vw, 33vw"
                             alt="Koffiepedia - zetmethodes en smaken"
                             loading="lazy">
                    </div>
                     <div class="feature-body">
                         <h3>Koffiepedia</h3>
                         <p>Leer meer over zetmethodes, smaakprofielen en boonherkomst in onze Koffiepedia.</p>
                         <a class="btn" href="/coffeepedia">Lees meer</a>
                     </div>
                 </article>
            </div>
        </section>
        <footer id="footer">
            <?php include __DIR__ . '/../includes/footer.php'; ?>
        </footer>
    </div>
    <!-- <script src="https://kit.fontawesome.com/b086454024.js" crossorigin="anonymous"></script> -->
    <script src="/api/scripts/general.js"></script>
    <script src="/api/scripts/aside.js"></script>
    <script src="/api/scripts/gallery.js"></script>
    <script>
        loadHtmlContent('/api/includes/header.php', 'header', () => {
            changeBackgroundOnScroll();
            // forceer herberekening zodat gallery correct positioneert
            window.dispatchEvent(new Event('resize'));
        });
    </script>
</body>
</html>