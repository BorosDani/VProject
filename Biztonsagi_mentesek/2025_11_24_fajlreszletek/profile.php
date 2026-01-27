<?php
// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['fid'])) {
    header("Location: login.php");
    exit();
}

// Profil frissítés kezelése - ez fogja frissíteni az adatbázist ÉS a session-t
$eredmeny = update_profile();
$hibak = $eredmeny['hibak'] ?? [];
$siker = $eredmeny['siker'] ?? '';
$folyamatban = $eredmeny['folyamatban'] ?? ''; // Új változó a folyamatban lévő módosításokhoz
?>

<div class="profile_box">
    <h1 class="profile_h1">Profil Szerkesztése</h1>
    
    <!-- Profil információk -->
    <div class="profile-header-container">
        <div class="profile_pic_container">
            <div class="profile_pic">
                <img src="assets/images/profile/<?= htmlspecialchars($_SESSION['profilkep'] ?? 'default.png') ?>" alt="Profilkép" id="profile_image_preview">
                <div class="edit_icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="profile-info">
            <h2>Profil adatok</h2>
            <p><strong>Státusz:</strong> <?= htmlspecialchars($_SESSION['statusz'] ?? 'aktív') ?></p>
            <p><strong>Szerep:</strong> <?= htmlspecialchars($_SESSION['szerep'] ?? 'felh.') ?></p>
            <p class="profile-info-strong-d"><strong>Regisztráció dátuma:</strong> <?= isset($_SESSION['regisztralt']) ? date('Y. m. d.', strtotime($_SESSION['regisztralt'])) : 'Ismeretlen' ?></p>
            <p><strong>Utolsó belépés:</strong> <?= isset($_SESSION['belepett']) ? date('Y. m. d. H:i', strtotime($_SESSION['belepett'])) : 'Ismeretlen' ?></p>
            <p><strong>Utolsó módosítás:</strong> <?= isset($_SESSION['modositott']) ? date('Y. m. d. H:i', strtotime($_SESSION['modositott'])) : 'Még nem módosítva' ?></p>
        </div>
    </div>

    <!-- Hiba és siker üzenetek -->
    <?php if (!empty($hibak)): ?>
        <div class="hiba">
            <?php foreach ($hibak as $hiba): ?>
                <p><?= htmlspecialchars($hiba) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($siker)): ?>
        <div class="siker">
            <p><?= htmlspecialchars($siker) ?></p>
        </div>
    <?php endif; ?>

    <!-- Folyamatban lévő módosítások -->
    <?php if (!empty($folyamatban)): ?>
        <div class="folyamatban">
            <p>Módosítás alatt: <?= $folyamatban ?> mező</p>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" id="profileForm">
        <!-- Profilkép feltöltés - elrejtve -->
        <input type="file" id="profilkep" name="profilkep" accept="image/*" style="display: none;">
        
        <div class="profile_data_inputs">
            <!-- Bal oldali doboz - Általános adatok -->
            <div class="profile-data-group">
                <h3 class="group-title">Általános adatok</h3>
                
                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
                </div>

                <!-- Felhasználónév -->
                <div class="input-group">
                    <label for="fnev">Felhasználónév:</label>
                    <input type="text" id="fnev" name="fnev" value="<?= htmlspecialchars($_SESSION['fnev'] ?? '') ?>" required>
                </div>

                <!-- Keresztnév -->
                <div class="input-group">
                    <label for="knev">Keresztnév:</label>
                    <input type="text" id="knev" name="knev" value="<?= htmlspecialchars($_SESSION['knev'] ?? '') ?>">
                </div>

                <!-- Vezetéknév -->
                <div class="input-group">
                    <label for="vnev">Vezetéknév:</label>
                    <input type="text" id="vnev" name="vnev" value="<?= htmlspecialchars($_SESSION['vnev'] ?? '') ?>">
                </div>
            </div>

            <!-- Jobb oldali doboz - Személyes adatok -->
            <div class="profile-data-group">
                <h3 class="group-title">Személyes adatok</h3>
                
                <!-- Születési dátum -->
                <div class="input-group">
                    <label for="szuletett">Születési dátum:</label>
                    <input type="date" id="szuletett" name="szuletett" value="<?= htmlspecialchars($_SESSION['szuletett'] ?? '') ?>">
                </div>

                <!-- Telefon -->
                <div class="input-group">
                    <label for="telefon">Telefonszám:</label>
                    <input type="tel" id="telefon" name="telefon" value="<?= htmlspecialchars($_SESSION['telefon'] ?? '') ?>" inputmode="numeric">
                </div>

                <!-- Város -->
                <div class="input-group">
                    <label for="varos">Város:</label>
                    <input type="text" id="varos" name="varos" value="<?= htmlspecialchars($_SESSION['varos'] ?? '') ?>" autocomplete="off" placeholder="Kezd el gépelni...">
                    <div id="varos-autocomplete" class="autocomplete-suggestions"></div>
                </div>

                <!-- Nem -->
                <div class="input-group">
                    <label for="nem">Nem:</label>
                    <select id="nem" name="nem">
                        <option value="nem_publikus" <?= ($_SESSION['nem'] ?? 'nem_publikus') == 'nem_publikus' ? 'selected' : '' ?>>Nem publikus</option>
                        <option value="ferfi" <?= ($_SESSION['nem'] ?? '') == 'ferfi' ? 'selected' : '' ?>>Férfi</option>
                        <option value="no" <?= ($_SESSION['nem'] ?? '') == 'no' ? 'selected' : '' ?>>Nő</option>
                        <option value="egyeb" <?= ($_SESSION['nem'] ?? '') == 'egyeb' ? 'selected' : '' ?>>Egyéb</option>
                    </select>
                </div>
            </div>

            <!-- Jelszó változtatás - CSS szerint grid-column: 1 / -1 -->
            <div class="password-group">
                <h3 class="group-title-password-change">Új jelszó beállítása</h3>
                
                <div class="input-group">
                    <label for="jelszo">Új jelszó:</label>
                    <input type="password" id="jelszo" name="jelszo" placeholder="Hagyja üresen, ha nem változtat">
                    <br>
                    <label for="jelszo_ujra">Új jelszó mégegyszer:</label>
                    <input type="password" id="jelszo_ujra" name="jelszo_ujra" placeholder="Jelszó megerősítése">
                </div>
            </div>

            <!-- Gombok - CSS szerint grid-column: 1 / -1 -->
            <div class="form-actions">
                <button type="submit" name="profil_modositasa" class="save-btn">Mentés</button>
                <button type="reset" class="reset-btn">Visszaállítás</button>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript import -->
<script src="assets/js/profile.js"></script>