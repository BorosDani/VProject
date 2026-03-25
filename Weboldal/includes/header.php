<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <title><?= get_page_title() ?></title>
</head>
<body>
    <div class="header_box">
        <div class="header_left">
            <a href="<?= base_url() ?>" class="header-logo-link">
                <img src="<?= base_url('assets/images/vm_images/logo.png') ?>" alt="Villám Meló logó">
                <p>Villám Meló</p>
            </a>
            
            <div class="mobile-menu-toggle">
                <span class="hamburger-icon">☰</span>
                <span class="close-icon">✕</span>
            </div>
        </div>

        <div class="header_mid">
            <a href="<?= base_url() ?>">Főoldal</a>
            <a href="<?= base_url('about') ?>">Rólunk</a>
            <a href="<?= base_url('contact') ?>">Kapcsolat</a>
            <a href="<?= base_url('jobs') ?>">Munkák</a>
        </div>

        <div class="header_right">
            <div class="theme-toggle-container">
                <label class="theme-toggle" for="theme-toggle-checkbox">
                    <input type="checkbox" id="theme-toggle-checkbox" class="theme-toggle-input">
                    <span class="theme-toggle-slider">
                        <span class="theme-toggle-sun">☀️</span>
                        <span class="theme-toggle-moon">🌙</span>
                    </span>
                </label>
            </div>

            <?php if (!isset($_SESSION['fid'])): ?>
                <a href="<?= base_url('login') ?>">Bejelentkezés</a>
                <a href="<?= base_url('register') ?>">Regisztráció</a>
            <?php else: ?>
                <div class="header_profile_dropdown">
                    <div class="profile-link">
                        <img src="<?= base_url('assets/images/profile/' . (isset($_SESSION['profilkep']) ? $_SESSION['profilkep'] : 'default.png')) ?>" alt="Profilkép">
                    </div>
                    <div class="header_dropdown_menu">
                        <?php if (isset($_SESSION['szerep']) && $_SESSION['szerep'] === 'admin'): ?>
                            <a href="<?= base_url('webadmin') ?>" class="webadmin-link">WebAdmin</a>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>
                        <a href="<?= base_url('rateProfile?fid=' . $_SESSION['fid']) ?>">Fiók</a>
                        <a href="<?= base_url('workUpload') ?>">Munka feltöltés</a>
                        <a href="<?= base_url('myJobs') ?>">Munkáim</a>
                        <a href="<?= base_url('Jelentkezeseim') ?>">Jelentkezéseim</a>
                        <a href="<?= base_url('logout') ?>" class="header_dp_m_logout">Kijelentkezés</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mobile-menu-content">
        <div class="mobile-menu-header">
            <a href="<?= base_url() ?>" class="mobile-logo-link">
                <img src="<?= base_url('assets/images/vm_images/logo.png') ?>" alt="Villám Meló logó" class="mobile-logo">
                <span class="mobile-site-title">Villám Meló</span>
            </a>
            <button class="mobile-close-button">✕</button>
        </div>
        
        <div class="mobile-menu-scrollable">          
            <div class="mobile-theme-toggle">
                <span>Világos téma</span>
                <label class="theme-toggle mobile-theme-toggle" for="mobile-theme-toggle-checkbox">
                    <input type="checkbox" id="mobile-theme-toggle-checkbox" class="theme-toggle-input">
                    <span class="theme-toggle-slider">
                        <span class="theme-toggle-sun">☀️</span>
                        <span class="theme-toggle-moon">🌙</span>
                    </span>
                </label>
            </div>
            
            <?php if (!isset($_SESSION['fid'])): ?>
                <a href="<?= base_url('login') ?>">Bejelentkezés</a>
                <a href="<?= base_url('register') ?>">Regisztráció</a>
                <hr>
                <a href="<?= base_url() ?>">Főoldal</a>
                <a href="<?= base_url('about') ?>">Rólunk</a>
                <a href="<?= base_url('contact') ?>">Kapcsolat</a>
                <a href="<?= base_url('jobs') ?>">Munkák</a>
            <?php else: ?>
                <div class="mobile-profile-section">
                    <div class="mobile-profile-header">
                        <img src="<?= base_url('assets/images/profile/' . (isset($_SESSION['profilkep']) ? $_SESSION['profilkep'] : 'default.png')) ?>" alt="Profilkép" class="mobile-profile-img">
                        <span class="mobile-profile-name"><?= $_SESSION['fnev'] ?></span>
                        <span class="mobile-profile-toggle">›</span>
                    </div>
                    <div class="mobile-profile-menu">
                        <?php if (isset($_SESSION['szerep']) && $_SESSION['szerep'] === 'admin'): ?>
                            <a href="<?= base_url('webadmin') ?>" class="webadmin-link">WebAdmin</a>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>
                        <a href="<?= base_url('rateProfile?fid=' . $_SESSION['fid']) ?>">Fiók</a>
                        <a href="<?= base_url('workUpload') ?>">Munka feltöltés</a>
                        <a href="<?= base_url('myJobs') ?>">Munkáim</a>
                        <a href="<?= base_url('applications') ?>">Jelentkezéseim</a>
                        <a href="<?= base_url('logout') ?>" class="header_dp_m_logout">Kijelentkezés</a>
                    </div>
                </div>

                <a href="<?= base_url() ?>">Főoldal</a>
                <a href="<?= base_url('about') ?>">Rólunk</a>
                <a href="<?= base_url('contact') ?>">Kapcsolat</a>
                <a href="<?= base_url('jobs') ?>">Munkák</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?= base_url('assets/js/tema_valtoztato.js') ?>"></script>
    <script src="<?= base_url('assets/js/header.js') ?>"></script>