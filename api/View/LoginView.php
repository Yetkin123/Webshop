<?php

namespace View;

use Resources\Config\Language;

class LoginView extends Framework\View
{
    function show()
    {
        $language = new Language();
        $error = '';

        if (isset($_SESSION['login_error'])) {
            $error = $_SESSION['login_error'];
            unset($_SESSION['login_error']);
        }

        if (isset($_POST['language'])) {
            $selectedLanguage = $_POST['language'];
            $url = $language->createUrl('/?lang=' . $selectedLanguage);
            header('Location: ' . $url);
            exit();
        }

        ?>
        <!DOCTYPE html>
        <html lang="<?= $_SESSION['lang'] ?? 'nl'; ?>">

        <head>
            <meta charset="UTF-8">
            <title><?= __('inloggen'); ?></title>
            <link rel="stylesheet" href='/api/Resources/css/stylewebshop.css'>
        </head>

        <body>
            <div class="login-container">
				<div class="navigation">
              	 <a class="home-button" href="/">Home</a>
                 <div class="language-selector">
                        <?php
                        $flagLinks = $language->generateFlagLinks();
                        $currentLang = $language->getCurrentLang();
                        $dropdownFlag = $flagLinks[$currentLang == 'nl' ? 'en' : 'nl'];
                        ?>
                        <div>
                            <a href="<?= $dropdownFlag['path'] . '?lang=' . $dropdownFlag['langParam']; ?>"><?= __('vertaal'); ?>
                            </a>
                        </div>
					</div>
                </div>
                <h2><?= __('inloggen'); ?></h2>
                <?php if (!empty($error)): ?>
                    <p class="error"><?= htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form method="post" action="<?= $language->createUrl('/?action=login'); ?>">
                    <label for="username"><?= __('email'); ?>:</label>
                    <input type="email" id="username" name="username" required>
                    <label for="password"><?= __('wachtwoord'); ?>:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit"><?= __('inloggen'); ?></button>
                </form>
            </div>
        </body>

        </html>
        <?php
    }
}

new LoginView();
