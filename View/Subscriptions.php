<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <title>Abonnementen - Yerothia Coffee</title>
    <link rel="icon" type="image/ico" href="images/favicon.ico">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/aside.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/abonnementen.css">
    <link href="https://db.onlinewebfonts.com/c/36fda95b699fea73e18a8f2d1ad6a6c0?family=Amasis+MT" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=ADLaM%20Display' rel='stylesheet'>
    <link href="https://db.onlinewebfonts.com/c/4d138feded0a0dc57c384e7976428aa9?family=Abadi+MT+Std+Extra+Bold+It"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+NKo+Unjoined&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <aside id="aside">

        </aside>
        <header id="header">
            <?php include 'includes/header.php'; ?>
        </header>
        <section id="content">
            <div id="abonnementen-pagina">
                <!-- <h1 class="category-title">Abonnementen</h1> -->
                <div id="totaalconcept">
                    <div class="flex-container-totaalconcept">
                        <div class="flex-container-links">
                            <img src="./images/Koffiebonen.jpg" alt="Koffiebonen">
                        </div>
                        <div class="flex-container-rechts">
                            <h1>Proef de Perfectie</h1>
                            <p>Onze abonnementen vormen complete koffie-ervaringen, samengesteld met zorg en aandacht
                                voor
                                elk
                                detail. Elk
                                abonnement omvat niet alleen een hoogwaardig koffiezetapparaat, maar ook de meest
                                smaakvolle
                                koffiebonen,
                                toegewijde service, essentiële accessoires, en nog veel meer. Wij streven ernaar om
                                koffieliefhebbers zoals
                                jij een moeiteloze en genietbare koffieroutine te bieden, en ons abonnement is de
                                sleutel
                                tot het bereiken van dat perfecte kopje koffie, elke keer weer.</p>
                        </div>
                    </div>
                </div>
                <div id="maak-je-keuze">
                    <h1>Maak je keuze</h1>
                    <p>Yerothia biedt drie verschillende abonnementen aan. De Essentials-abonnement voor bedrijven met
                        10-50 medewerkers. De Prestige-abonnement voor bedrijven met 50-250 medewerkers en de
                        Signature-abonnement voor bedrijven met 250 medewerkers of meer. Voor de rest bent u vrij in
                        welke koffiemachines en koffiebonen uw voorkeur hebben. Ook krijgen alle drie de abonnementen
                        dezelfde service. Dus als u een kleine onderneming heeft, hoeft u zich geen zorgen te maken dat
                        u minder aandacht krijgt omdat u een kleinere abonnement heeft.</p>
                    <div class="flex-container">
                        <div class="abonnement-card">
                            <h2 class="abonnement-title">Essentials</h2>
                            <img class="abonnement-image" src="./images/Essentials.png" alt="Abonnement Essentials">
                            <div class="abonnement-info">
                                <p class="abonnement-bedrijfssoort">Geschikt voor bedrijven met 10-50 medewerkers</p>
                                <ul>
                                    <li>2-4 koffiemachines</li>
                                    <li>Vrije keuze uit koffiemachines</li>
                                    <li>Vrije keuze uit koffiebonen</li>
                                    <li>Geautomatiseerde bestelproces</li>
                                    <li>Toegang tot alle verzamelde data</li>
                                    <li>Tussentijds koffie bestellen mogelijk</li>
                                    <li>24 uurs service bij storingen</li>
                                </ul>
                            </div>
                            <form action="#stel-een-vraag">
                                <button class="vraag-aan-button">Vraag aan</button>
                            </form>
                        </div>
                        <div class="abonnement-card">
                            <h2 class="abonnement-title">Prestige</h2>
                            <img class="abonnement-image" src="./images/Prestige.png" alt="Abonnement Prestige">
                            <div class="abonnement-info">
                                <p class="abonnement-bedrijfssoort">Geschikt voor bedrijven met 50-250 medewerkers</p>
                                <ul>
                                    <li>4-10 koffiemachines</li>
                                    <li>Vrije keuze uit koffiemachines</li>
                                    <li>Vrije keuze uit koffiebonen</li>
                                    <li>Geautomatiseerde bestelproces</li>
                                    <li>Toegang tot alle verzamelde data</li>
                                    <li>Tussentijds koffie bestellen mogelijk</li>
                                    <li>24 uurs service bij storingen</li>
                                </ul>
                            </div>
                            <form action="#stel-een-vraag">
                                <button class="vraag-aan-button">Vraag aan</button>
                            </form>
                        </div>
                        <div class="abonnement-card">
                            <h2 class="abonnement-title">Signature</h2>
                            <img class="abonnement-image" src="./images/Signature.png" alt="Abonnement Signature">
                            <div class="abonnement-info">
                                <p class="abonnement-bedrijfssoort">Geschikt voor bedrijven met meer dan 250 medewerkers
                                </p>
                                <ul>
                                    <li>Meer dan 10 koffiemachines</li>
                                    <li>Vrije keuze uit koffiemachines</li>
                                    <li>Vrije keuze uit koffiebonen</li>
                                    <li>Geautomatiseerde bestelproces</li>
                                    <li>Toegang tot alle verzamelde data</li>
                                    <li>Tussentijds koffie bestellen mogelijk</li>
                                    <li>24 uurs service bij storingen</li>
                                </ul>
                            </div>
                            <form action="#stel-een-vraag">
                                <button class="vraag-aan-button">Vraag aan</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="stel-een-vraag">
                    <div id="contact-formulier">
                        <h1>Contactformulier</h1>
                        <h2>Uw gegevens</h2>
                        <div id="contact-gegevens">
                            <label class="input-label" for="bedrijfsnaam">Bedrijfsnaam</label>
                            <input class="input-field" type="text" id="bedrijfsnaam" name="bedrijfsnaam">
                            <label class="input-label" for="voornaam">Voornaam</label>
                            <input class="input-field" type="text" id="voornaam" name="voornaam">
                            <label class="input-label" for="achternaam">Achternaam</label>
                            <input class="input-field" type="text" id="achternaam" name="achternaam">
                            <label class="input-label" for="email">E-mail</label>
                            <input class="input-field" type="email" id="email" name="email">
                            <label class="input-label" for="telefoonnummer">Telefoonnummer</label>
                            <input class="input-field" type="tel" id="telefoonnummer" name="telefoonnummer">
                        </div>
                        <h2>Uw vraag</h2>
                        <textarea class="input-field" placeholder="U kunt hier uw vragen stellen" name="vraag" id="vraag"></textarea>
                        <div id="flex-button">
                            <button id="verzenden-button" type="submit">VERZENDEN</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer id="footer">
            <?php include 'includes/footer.php'; ?>
        </footer>

        <!-- Modal -->
        <div id="modal">
            <div id="modal-content">
                <span id="close-modal">&times;</span>
                <p>Bedankt voor uw bericht. Uw bericht is verzonden. Wij zullen zo spoedig mogelijk contact met u
                    opnemen!</p>
            </div>
        </div>
    </div>
    <!-- <script src="https://kit.fontawesome.com/b086454024.js" crossorigin="anonymous"></script> -->
    <script src="scripts/abonnementen-modal.js"></script>
    <script src="scripts/general.js"></script>
    <script src="scripts/aside.js"></script>
    <script>
        loadHtmlContent('includes/aside.html', 'aside', () => {
            addClickListenerToAsideLogo();
            loadAsideData(6);
        });
        loadHtmlContent('includes/header.php', 'header', () => {
            changeBackgroundOnScroll();
        });
    </script>
</body>

</html>