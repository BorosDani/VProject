<?php
if (!isset($_SESSION['fid'])) {
    header("Location: login.php");
    exit();
}

$eredmeny = update_profile();
$hibak = $eredmeny['hibak'] ?? [];
$siker = $eredmeny['siker'] ?? '';
$folyamatban = $eredmeny['folyamatban'] ?? '';

if (isset($eredmeny['friss_adatok'])) {
    $_SESSION = array_merge($_SESSION, $eredmeny['friss_adatok']);
}
?>

<div class="profile_box">
    <a href="<?= base_url('rateProfile?fid=' . $_SESSION['fid']) ?>"><p style="color: gray; font-style: italic;">Vissza a fiókhoz</p></a>
    <h1 class="profile_h1">Profil Szerkesztése</h1>
    
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

    <?php if (!empty($folyamatban)): ?>
        <div class="folyamatban">
            <p>Módosítás alatt: <?= $folyamatban ?> mező</p>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" id="profileForm">

        <input type="file" id="profilkep" name="profilkep" accept="image/*" style="display: none;">
        
        <div class="profile_data_inputs">
            
            <div class="input-group full-width">
                <label for="reszletek">Bemutatkozás:</label>
                <textarea id="reszletek" name="reszletek" rows="6" placeholder="Írj magadról néhány mondatot... (hobbik, érdeklődés, tapasztalat, stb.)"><?= htmlspecialchars($_SESSION['reszletek'] ?? '') ?></textarea>
            </div>
            
            <div class="profile-data-group">
                <h3 class="group-title">Általános adatok</h3>
                
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
                </div>

                <div class="input-group">
                    <label for="fnev">Felhasználónév:</label>
                    <input type="text" id="fnev" name="fnev" value="<?= htmlspecialchars($_SESSION['fnev'] ?? '') ?>" required>
                </div>

                <div class="input-group">
                    <label for="knev">Keresztnév:</label>
                    <input type="text" id="knev" name="knev" value="<?= htmlspecialchars($_SESSION['knev'] ?? '') ?>">
                </div>

                <div class="input-group">
                    <label for="vnev">Vezetéknév:</label>
                    <input type="text" id="vnev" name="vnev" value="<?= htmlspecialchars($_SESSION['vnev'] ?? '') ?>">
                </div>
            </div>

            <div class="profile-data-group">
                <h3 class="group-title">Személyes adatok</h3>
                
                <div class="input-group">
                    <label for="szuletett">Születési dátum:</label>
                    <input type="date" id="szuletett" name="szuletett" value="<?= htmlspecialchars($_SESSION['szuletett'] ?? '') ?>">
                </div>

                <div class="input-group">
                    <label for="telefon">Telefonszám:</label>
                    <input type="tel" id="telefon" name="telefon" value="<?= htmlspecialchars($_SESSION['telefon'] ?? '') ?>" inputmode="numeric">
                </div>
                
                <div class="input-group">
                    <label for="varmegye">Vármegye:</label>
                    <select id="varmegye" name="varmegye">
                        <option value="">-- Válassz vármegyét --</option>
                        <option value="Budapest" <?= ($_SESSION['varmegye'] ?? '') == 'Budapest' ? 'selected' : '' ?>>Budapest</option>
                        <option value="Bács-Kiskun" <?= ($_SESSION['varmegye'] ?? '') == 'Bács-Kiskun' ? 'selected' : '' ?>>Bács-Kiskun</option>
                        <option value="Baranya" <?= ($_SESSION['varmegye'] ?? '') == 'Baranya' ? 'selected' : '' ?>>Baranya</option>
                        <option value="Békés" <?= ($_SESSION['varmegye'] ?? '') == 'Békés' ? 'selected' : '' ?>>Békés</option>
                        <option value="Borsod-Abaúj-Zemplén" <?= ($_SESSION['varmegye'] ?? '') == 'Borsod-Abaúj-Zemplén' ? 'selected' : '' ?>>Borsod-Abaúj-Zemplén</option>
                        <option value="Csongrád-Csanád" <?= ($_SESSION['varmegye'] ?? '') == 'Csongrád-Csanád' ? 'selected' : '' ?>>Csongrád-Csanád</option>
                        <option value="Fejér" <?= ($_SESSION['varmegye'] ?? '') == 'Fejér' ? 'selected' : '' ?>>Fejér</option>
                        <option value="Győr-Moson-Sopron" <?= ($_SESSION['varmegye'] ?? '') == 'Győr-Moson-Sopron' ? 'selected' : '' ?>>Győr-Moson-Sopron</option>
                        <option value="Hajdú-Bihar" <?= ($_SESSION['varmegye'] ?? '') == 'Hajdú-Bihar' ? 'selected' : '' ?>>Hajdú-Bihar</option>
                        <option value="Heves" <?= ($_SESSION['varmegye'] ?? '') == 'Heves' ? 'selected' : '' ?>>Heves</option>
                        <option value="Jász-Nagykun-Szolnok" <?= ($_SESSION['varmegye'] ?? '') == 'Jász-Nagykun-Szolnok' ? 'selected' : '' ?>>Jász-Nagykun-Szolnok</option>
                        <option value="Komárom-Esztergom" <?= ($_SESSION['varmegye'] ?? '') == 'Komárom-Esztergom' ? 'selected' : '' ?>>Komárom-Esztergom</option>
                        <option value="Nógrád" <?= ($_SESSION['varmegye'] ?? '') == 'Nógrád' ? 'selected' : '' ?>>Nógrád</option>
                        <option value="Pest" <?= ($_SESSION['varmegye'] ?? '') == 'Pest' ? 'selected' : '' ?>>Pest</option>
                        <option value="Somogy" <?= ($_SESSION['varmegye'] ?? '') == 'Somogy' ? 'selected' : '' ?>>Somogy</option>
                        <option value="Szabolcs-Szatmár-Bereg" <?= ($_SESSION['varmegye'] ?? '') == 'Szabolcs-Szatmár-Bereg' ? 'selected' : '' ?>>Szabolcs-Szatmár-Bereg</option>
                        <option value="Tolna" <?= ($_SESSION['varmegye'] ?? '') == 'Tolna' ? 'selected' : '' ?>>Tolna</option>
                        <option value="Vas" <?= ($_SESSION['varmegye'] ?? '') == 'Vas' ? 'selected' : '' ?>>Vas</option>
                        <option value="Veszprém" <?= ($_SESSION['varmegye'] ?? '') == 'Veszprém' ? 'selected' : '' ?>>Veszprém</option>
                        <option value="Zala" <?= ($_SESSION['varmegye'] ?? '') == 'Zala' ? 'selected' : '' ?>>Zala</option>
                    </select>
                </div>

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

            <div class="form-actions">
                <button type="submit" name="profil_modositasa" class="save-btn">Mentés</button>
                <button type="reset" class="reset-btn">Visszaállítás</button>
            </div>
        </div>
    </form>
</div>

<script src="assets/js/profile.js"></script>