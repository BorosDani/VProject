<?php
require_once 'functions.php'
?>
<div class='footer_box'>
    <div class='footer_left'>
        <p>Elérhetőség</p>
        <p>Villám Meló Közösség</p>
        <p>Email: info@villammelo.hu</p>
    </div>

    <div class='footer_right'>
        <?php 
        $footerStats = getFooterStats(); 
        ?>
        <p>Tagok száma: <strong><?php echo $footerStats['tagok_szama']; ?></strong></p>
        <p>Munkák száma: <strong><?php echo $footerStats['munkak_szama']; ?></strong></p>
        <p>Elégedett ügyfelek száma: <strong><?php echo $footerStats['elegedett_ugyfelek_szama']; ?></strong></p>
    </div>

    <div class='footer_mid_bottom'>
        <footer>
            <div class="social-icons">
                <a href="https://www.instagram.com/h4nzo_art/#" target="_blank">
                    <img src="assets/images/vm_images/instagram2.jpg" alt="Instagram">
                </a>
                <a href="https://facebook.com" target="_blank">
                    <img src="assets/images/vm_images/facebook.png" alt="Facebook">
                </a>
            </div>
            <p>&copy; <?= date('Y') ?> Villám Meló. Minden jog fenntartva.</p>
            
            <p style="font-size: 0.9em; margin-top: 5px;">
                Ezt az aloldalt eddig <strong id="api-view-count">...</strong> alkalommal nézték meg.
            </p>
            
            <p>
                <a href="<?= base_url("contact")?>">GYIK</a> | 
                <a href="<?= base_url("about")?>">Tudj meg rólunk többet</a> | 
                <a href="<?= base_url("jobs")?>">Keress munkákat</a>
            </p>
        </footer>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let myId = window.location.pathname.replace(/[^a-zA-Z0-9]/g, '_');
    if (myId === '_' || myId === '') {
        myId = 'fooldal';
    }
    const urlParams = new URLSearchParams(window.location.search);
    const queryId = urlParams.get('id');
    if (queryId) {
        myId = myId + '_id_' + queryId;
    }
    const azonosito = 'oldal_' + myId;

    const marLatta = localStorage.getItem('megtekintve_' + azonosito);
    
    const action = marLatta ? 'read' : 'add';

    fetch(`/api/counter.php?id=${azonosito}&action=${action}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {

                document.getElementById('api-view-count').innerText = data.views;
                
                if (action === 'add') {
                    localStorage.setItem('megtekintve_' + azonosito, 'igen');
                }
            }
        })
        .catch(error => console.error('Hiba az API elérésekor:', error));
});
</script>

</body>
</html>