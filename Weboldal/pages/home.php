<div class='home_box'>
    <section class='home_hello_sec'>
        <?php if(!isset($_SESSION['fid'])):?>
            <div class='home_hello'>
                <h1>A szabadidőd másnak segítség. <br>Az ügyesség számodra bevétel.</h1>
                <p>Csatlakozz hogy ne maradj le a sürgős munkákról.</p>
            </div>
        <?php else:?>
            <div class='home_hello'>
                <h1>Üdvözöllek, <?php echo $_SESSION['fnev'].'!'?></h1>
                <p>Töltsd fel munkáidat vagy jelentkezz mások munkáira.</p>
            </div>
        <?php endif?>
    </section>

    <section class='slideshow-section'>
        <div class='slideshow-container'>

            <div class='slide active'>
                <img src='assets/images/slideshow/kerteszek.jpg' alt='Kertészek'>
                <div class='slide-content'>
                    <h1 class='slide-title'>Munkát keresel?</h1>
                    <p class='slide-link'>
                        <a href="<?=base_url('jobs')?>">
                            Nézz be a <span class='highlight'>munkák</span> közé
                        </a>
                    </p>
                </div>
            </div>
            

            <div class='slide'>
                <img src='assets/images/slideshow/festo.jpg' alt='Festés'>
                <div class='slide-content'>
                    <h1 class='slide-title'>Szeretnél munkát feladni?</h1>
                    <p class='slide-link'>
                        <a href="<?= isset($_SESSION['fid']) ? base_url('workUpload') : base_url('register') ?>">
                            Tölts fel munkát a <span class='highlight'>munkák feltöltése</span> részen
                        </a>
                    </p>
                </div>
            </div>
            

            <div class='slide'>
                <img src='assets/images/slideshow/tanulo.jpg' alt='Tanulás'>
                <div class='slide-content'>
                    <h1 class='slide-title'>Szeretnél tanulni és közben szórakozni vagy csinálnál ilyen menő weboldalt?</h1>
                    <p class='slide-link'><a href='https://infojegyzet.hu/' target='_blank'>Nézz fel az <span class='highlight'>infojegyzet.hu</span>-ra</a></p>
                </div>
            </div>
            

            <div class='slide'>
                <img src='assets/images/slideshow/iskola-iskola.jpg' alt='WM'>
                <div class='slide-content'>
                    <h1 class='slide-title'>Mi itt értük el a célunkat!</h1>
                    <p class='slide-link'><a href='https://wm-iskola.hu/' target='_blank'>Jelentkezz a <span class='highlight'>Weiss Manfréd</span> Technikumban!</a></p>
                </div>
            </div>
            

            <button class='slide-nav prev'>&#10094;</button>
            <button class='slide-nav next'>&#10095;</button>
            

            <div class='slide-dots'>
                <span class='dot active'></span>
                <span class='dot'></span>
                <span class='dot'></span>
                <span class='dot'></span>
            </div>
        </div>
    </section>


    <?php if(!isset($_SESSION['fid'])):?>

        <section class='home_steps_sec'>
            <div class='home_steps'>
                <div class='home_stem'>
                    <div class="home_step_icon">1</div>
                    <h3>Regisztráció</h3>
                    <p>Hozz létre egy ingyenes fiókot és töltsd ki profilodat</p>
                </div>
                <div class='home_stem'>
                    <div class="home_step_icon">2</div>
                    <h3>Munkák böngészése</h3>
                    <p>Böngéssz a kategóriák között vagy keress konkrét pozíciókat</p>
                </div>
                <div class='home_stem'>
                    <div class="home_step_icon">3</div>
                    <h3>Jelentkezés</h3>
                    <p>Jelentkezz pár kattintással és kövesd jelentkezésed állapotát</p>
                </div>
                <div class='home_stem'>
                    <div class="home_step_icon">4</div>
                    <h3>Kapcsolatfelvétel</h3>
                    <p>Beszéljétek meg a munka részleteit a megbízóval</p>
                </div>
            </div>
        </section>
    <?php endif; ?>
</div>



        <div class="home_top_workers_section_box">
            <section class='top-workers-section'>
                <div class='container'>
                    <h2 class='section-title'>Legjobb dolgozóink</h2>
                    
                    <?php

                    $top_workers = [];
                    
                    if (isset($conn)) {
                        try {

                            $sql = "SELECT 
                                    f.fid,
                                    f.fnev,
                                    f.profilkep,
                                    COALESCE(AVG(m.ertekeles), 0) as atlag_ertekeles,
                                    COUNT(m.ertekeles) as ertekelesek_szama
                                FROM felhasznalok f
                                LEFT JOIN megjegyzesek m ON f.fid = m.dolgozo_id
                                WHERE m.dolgozo_id IS NOT NULL
                                GROUP BY f.fid, f.fnev, f.profilkep
                                HAVING COUNT(m.ertekeles) > 0
                                ORDER BY 
                                    atlag_ertekeles DESC,
                                    ertekelesek_szama DESC,
                                    f.fnev ASC
                                LIMIT 9";
                            
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $top_workers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                        } catch (Exception $e) {
                            error_log("Hiba a dolgozók lekérdezésében: " . $e->getMessage());
                            $top_workers = [];
                        }
                    }
                    ?>
                    
                    <div class='top-workers-grid'>
                        <?php if(!empty($top_workers)): ?>
                            <?php foreach($top_workers as $worker): ?>
                                <?php

                                $profilkep_mappa = 'assets/images/profile/';
                                
                                $profilkep_nev = $worker['profilkep'] ?? 'default.png';
                                
                                if (empty($profilkep_nev) || trim($profilkep_nev) === '' || $profilkep_nev === 'NULL') {
                                    $profilkep_nev = 'default.png';
                                }
                                

                                $profil_kep = $profilkep_mappa . $profilkep_nev;
                                
                                $rating = number_format($worker['atlag_ertekeles'], 1);
                                $review_count = $worker['ertekelesek_szama'];
                                $worker_id = isset($worker['fid']) ? $worker['fid'] : 0;
                                ?>
                                
                                <a href="<?= base_url('rateProfile?fid=' . $worker_id) ?>" class='worker-card-link'>
                                    <div class='worker-card'>
                                        <div class='worker-avatar'>
                                            <img src="<?= base_url($profil_kep) ?>" 
                                                alt="<?= htmlspecialchars($worker['fnev']) ?>"
                                                onerror="this.onerror=null; this.src='<?= base_url('assets/images/profile/default.png') ?>';">
                                        </div>
                                        <div class='worker-info'>
                                            <h3 class='worker-name'><?= htmlspecialchars($worker['fnev']) ?></h3>
                                            <div class='worker-rating'>
                                                <div class='stars'>
                                                    <?php
                                                    $rating_val = (float)$worker['atlag_ertekeles'];
                                                    $full_stars = floor($rating_val);
                                                    $has_half = ($rating_val - $full_stars) >= 0.5;

                                                    for($i = 1; $i <= 5; $i++):
                                                        if($i <= $full_stars): ?>
                                                            <span class="star full">★</span>
                                                        <?php elseif($has_half && $i == $full_stars + 1): ?>
                                                            <span class="star half">★</span>
                                                        <?php else: ?>
                                                            <span class="star empty">★</span>
                                                        <?php endif;
                                                    endfor;
                                                    ?>
                                                </div>
                                                <span class='rating-value'><?= $rating ?></span>
                                            </div>
                                            <p class='review-count'>(<?= $review_count ?> értékelés)</p>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class='no-workers'>
                                <p>Még nincsenek értékelt dolgozók.</p>
                                <p><small>Amint értékeléseket kapnak a felhasználók, itt fognak megjelenni.</small></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>

<script src="<?= base_url('assets/js/home_slideshow.js') ?>"></script>

<style>

.home_top_workers_section_box
{
    margin-bottom: 3rem;
    margin-left: 3rem;
    margin-right: 3rem;
}

.top-workers-section {
    padding: 60px 20px;
    background: var(--secondary-bg);
    margin-top: 40px;
    border-radius: 2rem;
    position: relative;
    overflow: hidden;
}

.top-workers-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    z-index: 1;
}

.top-workers-section .container {
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    color: var(--primary-text);
    margin-bottom: 40px;
    font-weight: 700;
    position: relative;
    padding-bottom: 15px;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: var(--accent-gradient);
    border-radius: 2px;
}

.top-workers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    justify-items: center;
}

.worker-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
    width: 100%;
    max-width: 300px;
}

.worker-card {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 10px 30px var(--shadow-color);
    text-align: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    width: 100%;
    position: relative;
    overflow: hidden;
    border: 1px solid var(--border-color);
    cursor: pointer;
}

.worker-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    border-color: var(--accent-color);
}

.worker-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: var(--accent-gradient);
    border-radius: 16px 16px 0 0;
}

.worker-avatar {
    width: 120px;
    height: 120px;
    margin: 0 auto 20px;
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid var(--accent-color);
    position: relative;
    background: var(--tertiary-bg);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
    transition: all 0.3s ease;
}

.worker-card:hover .worker-avatar {
    transform: scale(1.05);
    border-color: var(--button-hover-bg);
    box-shadow: 0 12px 25px rgba(102, 126, 234, 0.3);
}

.worker-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.worker-card:hover .worker-avatar img {
    transform: scale(1.1);
}

.worker-info {
    padding: 15px 0 5px;
}

.worker-name {
    font-size: 1.3rem;
    color: var(--primary-text);
    margin-bottom: 15px;
    font-weight: 600;
    line-height: 1.3;
    min-height: 2.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.worker-rating {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-bottom: 10px;
    min-height: 2.2rem;
}

.stars {
    display: flex;
    gap: 4px;
}

.star {
    font-size: 1.4rem;
    line-height: 1;
    transition: transform 0.2s ease;
}

.star:hover {
    transform: scale(1.2);
}

.star.full {
    color: #FFD700;
    text-shadow: 0 2px 4px rgba(255, 215, 0, 0.3);
}

.star.half {
    color: #FFD700;
    position: relative;
    text-shadow: 0 2px 4px rgba(255, 215, 0, 0.3);
}

.star.half::after {
    content: '★';
    position: absolute;
    left: 0;
    color: #e0e0e0;
    clip-path: polygon(50% 0, 100% 0, 100% 100%, 50% 100%);
}

.star.empty {
    color: #e0e0e0;
}

.rating-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-text);
    min-width: 3rem;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.review-count {
    font-size: 0.95rem;
    color: var(--secondary-text);
    margin-top: 8px;
    font-weight: 500;
    padding: 5px 12px;
    background: var(--tertiary-bg);
    border-radius: 20px;
    display: inline-block;
    min-height: 1.4rem;
}

.worker-card:hover .review-count {
    background: var(--border-color);
    color: var(--primary-text);
}

.no-workers {
    grid-column: 1 / -1;
    text-align: center;
    padding: 50px;
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 10px 30px var(--shadow-color);
    border: 2px dashed var(--border-color);
}

.no-workers p {
    font-size: 1.2rem;
    color: var(--secondary-text);
    margin: 0 0 15px 0;
    font-weight: 500;
}

.no-workers small {
    font-size: 1rem;
    color: var(--border-color);
    display: block;
    line-height: 1.5;
}

.worker-card:first-child {
    position: relative;
}

.worker-card:first-child::after {
    content: '🏆 LEGJOBB';
    position: absolute;
    top: 15px;
    right: -35px;
    background: linear-gradient(135deg, #FFD700, #FFA500);
    color: #2c3e50;
    padding: 5px 40px;
    font-size: 0.8rem;
    font-weight: 700;
    transform: rotate(45deg);
    box-shadow: 0 3px 10px rgba(255, 165, 0, 0.3);
    z-index: 3;
}

[data-theme="dark"] .worker-card:first-child::after {
    color: #ecf0f1;
    background: linear-gradient(135deg, #FFD700, #FF8C00);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.worker-card {
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
}

.worker-card:nth-child(1) { animation-delay: 0.1s; }
.worker-card:nth-child(2) { animation-delay: 0.2s; }
.worker-card:nth-child(3) { animation-delay: 0.3s; }
.worker-card:nth-child(4) { animation-delay: 0.4s; }
.worker-card:nth-child(5) { animation-delay: 0.5s; }
.worker-card:nth-child(6) { animation-delay: 0.6s; }
.worker-card:nth-child(7) { animation-delay: 0.7s; }
.worker-card:nth-child(8) { animation-delay: 0.8s; }
.worker-card:nth-child(9) { animation-delay: 0.9s; }
.worker-card:nth-child(10) { animation-delay: 1s; }

@media (max-width: 1200px) {
    .top-workers-grid {
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 25px;
    }
}

@media (max-width: 992px) {
    .top-workers-section {
        padding: 50px 15px;
    }
    
    .section-title {
        font-size: 2.2rem;
        margin-bottom: 35px;
    }
    
    .top-workers-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }
    
    .worker-card {
        padding: 20px;
    }
    
    .worker-avatar {
        width: 100px;
        height: 100px;
    }
}

@media (max-width: 768px) {
    .top-workers-section {
        padding: 40px 10px;
    }
    
    .section-title {
        font-size: 1.9rem;
        margin-bottom: 30px;
    }
    
    .top-workers-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .worker-card {
        max-width: 100%;
        padding: 15px;
    }
    
    .worker-avatar {
        width: 90px;
        height: 90px;
        margin-bottom: 15px;
    }
    
    .worker-name {
        font-size: 1.1rem;
        margin-bottom: 12px;
    }
    
    .star {
        font-size: 1.2rem;
    }
    
    .rating-value {
        font-size: 1.1rem;
    }
    
    .review-count {
        font-size: 0.85rem;
    }
    
    .worker-card:first-child::after {
        font-size: 0.7rem;
        padding: 4px 30px;
        right: -30px;
    }
}

@media (max-width: 576px) {
    .top-workers-grid {
        grid-template-columns: 1fr;
        max-width: 320px;
        margin: 0 auto;
    }
    
    .section-title {
        font-size: 1.7rem;
    }
    
    .top-workers-section {
        padding: 30px 10px;
    }
    
    .worker-card {
        padding: 20px;
    }
    
    .no-workers {
        padding: 30px 20px;
    }
}

[data-theme="dark"] .worker-card:hover {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}

[data-theme="dark"] .worker-card:hover .worker-avatar {
    box-shadow: 0 12px 25px rgba(33, 150, 243, 0.3);
}

[data-theme="dark"] .star.empty {
    color: #4a5568;
}

[data-theme="dark"] .star.half::after {
    color: #4a5568;
}
</style>