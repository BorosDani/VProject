<?php
$contact_result = process_contact_form();
$hibak = $contact_result['hibak'];
$siker = $contact_result['siker'];

$logged_in = isset($_SESSION['fid']) && isset($_SESSION['fnev']);

if (!isset($_SESSION['contact_csrf_token'])) {
    $_SESSION['contact_csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contacts-submit']) && !empty($siker) && empty($hibak)) {
    echo '<script>
        alert("' . addslashes($siker) . '");
        window.location.href = "contact";
    </script>';
    exit;
}
?>

<div class="contacts_box">
    <div class="contacts_main_content">
        <div class="contacts-page-header">
            <h1>Kapcsolat felvétel a Villám Melóval</h1>
        </div>
        
        <div class="contacts-intro-section">
            <p>A Villám Meló csapata mindig rendelkezésére áll, hogy válaszoljon kérdéseire, segítsen problémáinak megoldásában, vagy fogadja észrevéleteit. Célunk, hogy a lehető leggyorsabban és legpontosabban válaszoljunk üzeneteire.</p>
            <p class="contacts-response-time">Átlagos válaszidő: <strong>24 órán belül</strong></p>
        </div>
        


        <?php if (!$logged_in): ?>
            <div class="contacts-login-required-box">
                <div class="contacts-login-required">
                    <div class="contacts-login-icon" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <h3>Üzenet küldéshez bejelentkezés szükséges</h3>
                    <p>A kapcsolati űrlap használatához először be kell jelentkezned!</p>
                    <div class="contacts-form-actions">
                        <button name="contacts-submit" id="submitBtn" class="contacts-submit-btn"><a href="<?php echo base_url('login'); ?>">Bejelentkezés</a></button>
                    </div>
                </div>
            </div>



        <?php else: ?>
            <div class="contacts-form-section">
                <?php if (!empty($hibak)): ?>
                    <div class="contacts-message contacts-message-error">
                        <h3>❌ Hiba történt</h3>
                        <?php foreach ($hibak as $hiba): ?>
                            <p><?php echo $hiba; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h2>Küldj üzenetet nekünk</h2>
                
                <form class="contacts-form" id="contactForm" method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['contact_csrf_token']; ?>">
                    
                    <div class="contacts-form-row">
                        <div class="contacts-form-group">
                            <label for="contacts-name">Név *</label>
                            <input type="text" id="contacts-name" name="name" required placeholder="Add meg a neved" minlength="2" 
                                   value="<?php 
                                   if (isset($_SESSION['knev']) && isset($_SESSION['vnev']) && !empty($_SESSION['knev']) && !empty($_SESSION['vnev'])) {
                                       echo htmlspecialchars($_SESSION['knev'] . ' ' . $_SESSION['vnev']);
                                   } else {
                                       echo htmlspecialchars($_SESSION['fnev'] ?? '');
                                   }
                                   ?>">
                        </div>
                        
                        <div class="contacts-form-group">
                            <label for="contacts-email">Email cím *</label>
                            <input type="email" id="contacts-email" name="email" required placeholder="email@pelda.hu" 
                                   value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="contacts-form-group">
                        <label for="contacts-category">Kategória *</label>
                        <select id="contacts-category" name="category" required>
                            <option value="">Válassz kategóriát</option>
                            <option value="altalanos">Általános kérdés</option>
                            <option value="technikai">Technikai probléma</option>
                            <option value="munka_kapcsan">Munka kapcsán</option>
                            <option value="javaslat">Javaslat</option>
                            <option value="egyeb">Egyéb</option>
                        </select>
                    </div>
                    
                    <div class="contacts-form-group">
                        <label for="contacts-message">Üzenet *</label>
                        <div class="contacts-textarea-container">
                            <textarea id="contacts-message" name="message" required placeholder="Írd le üzeneted részletesen..." rows="6" minlength="10" maxlength="5000"></textarea>
                            <div class="contacts-char-counter" id="charCounter">
                                <span id="charCount">0</span> / 5000 karakter
                            </div>
                        </div>
                    </div>

                    <div class="g-recaptcha" data-sitekey="6LcpQwosAAAAAIFPktMIrCVcomaPjVKugcZ4M6J9"></div>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    
                    <div class="contacts-form-actions">
                        <button type="submit" name="contacts-submit" id="submitBtn" class="contacts-submit-btn">Üzenet küldése</button>
                    </div>
                    
                    <p class="contacts-form-note">A *-al jelölt mezők kitöltése kötelező.</p>
                </form>
            </div>
        <?php endif; ?>
        
        <div class="contacts-faq-section">
            <h2>Gyakran Ismételt Kérdések (GYIK)</h2>
            
            <div class="contacts-faq-container">
                <div class="contacts-faq-item">
                    <details class="contacts-faq-details">
                        <summary class="contacts-faq-question">Mennyi idő alatt kapok választ üzenetemre?</summary>
                        <div class="contacts-faq-answer">
                            <p>Átlagosan 24 órán belül válaszolunk minden üzenetre. Hétvégéken és ünnepnapokon a válaszidő hosszabb lehet.</p>
                        </div>
                    </details>
                </div>
                
                <div class="contacts-faq-item">
                    <details class="contacts-faq-details">
                        <summary class="contacts-faq-question">Hogyan regisztrálhatok a Villám Meló-ra?</summary>
                        <div class="contacts-faq-answer">
                            <p>A regisztrációhoz kattints a "<b>Regisztráció</b>" menüpontra a főoldalon, vagy <a href="<?php echo base_url('register'); ?>">ide kattintva</a>. Töltsd ki az űrlapot és erősítsd meg az email címedet a kapott link segítségével.</p>
                        </div>
                    </details>
                </div>

                <div class="contacts-faq-item">
                    <details class="contacts-faq-details">
                        <summary class="contacts-faq-question">Hogyan törölhetem a fiókom</summary>
                        <div class="contacts-faq-answer">
                            <p>Egyszerűen, ha már regisztráltál akkor <b>itt Kapcsolat</b> menüpontban írsz egy kérelmet a fiókod törlésére, és minél hamarabb töröljük a fiókod.</p>
                        </div>
                    </details>
                </div>
                
                <div class="contacts-faq-item">
                    <details class="contacts-faq-details">
                        <summary class="contacts-faq-question">Elfelejtettem a jelszavam. Mit tegyek?</summary>
                        <div class="contacts-faq-answer">
                            <p>Használd a "<b>Jelszó visszaállítás</b>" funkciót a bejelentkezési oldalon. Megküldjük a jelszó visszaállítási linket a regisztrált email címedre.</p>
                        </div>
                    </details>
                </div>
                
                <div class="contacts-faq-item">
                    <details class="contacts-faq-details">
                        <summary class="contacts-faq-question">Hogyan tölthetek fel munkát?</summary>
                        <div class="contacts-faq-answer">
                            <p>Bejelentkezés után a "<b>Munka feltöltés</b>" menüpontban találod a munkafeladási űrlapot. Töltsd ki a részleteket és adj hozzá képeket a munka bemutatásához.</p>
                        </div>
                    </details>
                </div>
                
                <div class="contacts-faq-item">
                    <details class="contacts-faq-details">
                        <summary class="contacts-faq-question">Hogyan jelentkezhetek munkákra?</summary>
                        <div class="contacts-faq-answer">
                            <p>Böngéssz a "<b>Munkák</b>" oldalon, és ha találsz egy számodra megfelelőt, kattints a "<b>Jelentkezem</b>" gombra. A megrendelő értesítés után fel fogja venni veled a kapcsolatot.</p>
                        </div>
                    </details>
                </div>
                
                <div class="contacts-faq-item">
                    <details class="contacts-faq-details">
                        <summary class="contacts-faq-question">Milyen biztonsági intézkedéseitek vannak?</summary>
                        <div class="contacts-faq-answer">
                            <p>Adataidat titkosított kapcsolaton keresztül tároljuk, és soha nem adjuk ki harmadik félnek. Jelszavakat hash-elve tároljuk, és kétfaktoros hitelesítést használunk bizonyos műveletekhez.</p>
                        </div>
                    </details>
                </div>

                <div class="contacts-faq-item">
                    <details class="contacts-faq-details">
                        <summary class="contacts-faq-question">Egyéb elérhetőség</summary>
                        <div class="contacts-faq-answer">
                            <h3>Email</h3>
                            <p><a href="mailto:info@villammelo.hu">info@villammelo.hu</a></p>
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/contacts.js"></script>