<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <meta name="description" content="Home pagina van Yerothia Coffee. Koffie met een glimlach. Daar staan we voor. Alles in huis voor zakelijke klanten, webshop, informatie over koffie.">
    <meta name="keywords" content="Koffie, Coffee, Yerothia, Thee, Suiker, Lekkker, Glimlach, Mok, Koffiemok, Zakelijk, Professioneel, Koffie-producten, Yerothia-Coffee, Bonen, Koffiebonen, Koffie bedrijf">
    <title>Home - Yerothia Coffee</title>
    <link rel="icon" type="image/ico" href="images/favicon.ico">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://db.onlinewebfonts.com/c/36fda95b699fea73e18a8f2d1ad6a6c0?family=Amasis+MT" rel="stylesheet"> 
    <link href='https://fonts.googleapis.com/css?family=ADLaM%20Display' rel='stylesheet'>
    <link href="https://db.onlinewebfonts.com/c/51a69624ea6dd3b2f3e808c39d367a95?family=Abadi+MT+Std+Extra+Light+It" rel="stylesheet"> 
</head>
<body>
    <div class="container">
        <!-- <aside id="aside">
            
        </aside> -->
        <header id="header">
            <?php include 'includes/header.php'; ?>
        </header>
        <section id="content">
            <div class="background-image-home">
                
            </div>

            <div class="content-page-block-grid">
                <a href="/products" class="content-page-block" id="page-block-producten">
                    <h2>Producten</h2>
                </a>
                <a href="/subscriptions" class="content-page-block" id="page-block-abonnementen">
                    <h2>Abonnementen</h2>
                </a>
                <a href="/coffeepedia" class="content-page-block" id="page-block-wiki">
                    <h2>Koffiepedia</h2>
                </a>
                <a href="/about" class="content-page-block" id="page-block-overons">
                    <h2>Over ons</h2>
                </a>
                <a href="/webshop" class="content-page-block" id="page-block-webshop">
                    <h2>Webshop</h2>
                </a>    
            </div>
        </section>
        <footer id="footer">
            <?php include 'includes/footer.php'; ?>
        </footer>
    </div>
    <!-- <script src="https://kit.fontawesome.com/b086454024.js" crossorigin="anonymous"></script> -->
    <script src="scripts/general.js"></script>
    <script src="scripts/aside.js"></script>
    <script>
        loadHtmlContent('includes/header.php', 'header', () => {
            changeBackgroundOnScroll();
        });

    </script>
</body>
</html>