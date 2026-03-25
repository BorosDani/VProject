<div class="filter-box">
  <div class="dropdown-filter">
    <div class="dropdown-header" onclick="toggleDropdown()">
      <span style="font-size: 20px;">🔍 Munkák szűrése</span>
      <span class="arrow">▼</span>
    </div>
    <div class="dropdown-content" id="dropdownContent">
      <details>
        <summary>Kültéri munkák</summary>
        <div class="filter-content">
          <label><input type="checkbox" name="kulteri" value="gyomlalas"> Gyomlálás</label><br>
          <label><input type="checkbox" name="kulteri" value="ultetes"> Ültetés</label><br>
          <label><input type="checkbox" name="kulteri" value="locsolas"> Locsolás</label><br>
          <label><input type="checkbox" name="kulteri" value="funyiras"> Fűnyírás</label><br>
          <label><input type="checkbox" name="kulteri" value="favagas"> Favágás</label><br>
          <label><input type="checkbox" name="kulteri" value="kerites"> Kerités javítás/építés</label><br>
          <label><input type="checkbox" name="kulteri" value="hazteto"> Háztető javítás</label><br>
          <label><input type="checkbox" name="kulteri" value="foldeles"> Földelés</label><br>



        </div>
      </details>

      <details>
        <summary>Beltéri munkák</summary>
        <div class="filter-content">
          <label><input type="checkbox" name="belteri" value="festes"> Festés</label><br>
          <label><input type="checkbox" name="belteri" value="vakolas"> Vakolás</label><br>
          <label><input type="checkbox" name="belteri" value="burkolas"> Burkolás</label><br>
          <label><input type="checkbox" name="belteri" value="takaritas"> Takarítás</label><br>
          <label><input type="checkbox" name="belteri" value="vizvezetekszereles"> Vízvezetékszerelés</label><br>
          <label><input type="checkbox" name="belteri" value="villanyszereles"> Villanyszerelés</label><br>
          <label><input type="checkbox" name="belteri" value="gazszerelesszereles"> Gázszerelés</label><br>
          <label><input type="checkbox" name="belteri" value="butorszereles"> Bútor szerelés</label><br>
          <label><input type="checkbox" name="belteri" value="ajtoablakszereles"> Ajtó/ablakszerelés</label><br>
          <label><input type="checkbox" name="belteri" value="klimatelepites"> Klíma telepítés</label><br>
          <label><input type="checkbox" name="belteri" value="diszites"> Díszítés</label><br>
          <label><input type="checkbox" name="belteri" value="szigeteles"> Szigetelés</label><br>
          <label><input type="checkbox" name="belteri" value="padlozas"> Padlózás</label><br>




        </div>
      </details>

      <details>
        <summary>Ár (Ft)</summary>
        <div class="filter-content">
          <input type="number" id="minAr" placeholder="Min" value="0" style="width:100px;"> -
          <input type="number" id="maxAr" placeholder="Max" value="100000" style="width:100px;">
        </div>
      </details>

      <details>
        <summary>Vármegye</summary>
        <div class="filter-content">
          <select id="megye">
            <option value="">Mind</option>
            <option value="gyor">Győr-Moson-Sopron</option>
            <option value="komarom">Komárom-Esztergom</option>
            <option value="pest">Pest</option>
            <option value="budapest">Budapest</option>
            <option value="nograd">Nográd</option>
            <option value="heves">Heves</option>
            <option value="borsod">Borsod-Abaúj-Zemplén</option>
            <option value="szabolcs">Szabolcs-Szatmár-Bereg</option>            
            <option value="hajdu">Hajdú-Bihar</option>
            <option value="jasz">Jász-Nagykun-Szolnok</option>
            <option value="bekes">Bekes</option>
            <option value="csongrad">Csongrád-Csanád</option>
            <option value="bacs">Bács-Kiskun</option>            
            <option value="fejer">Fejér</option>
            <option value="tolna">Tolna</option>
            <option value="baranya">Baranya</option>
            <option value="somony">Somogy</option>
            <option value="veszprem">Veszprém</option>            
            <option value="zala">Zala</option>
            <option value="vas">Vas</option>
          </select>
        </div>
      </details>

      <button class="szures" onclick="szures()">Szűrés</button>
    </div>
  </div>
</div>


<script>
function toggleDropdown() {
  const content = document.getElementById('dropdownContent');
  const arrow = document.querySelector('.arrow');
  const isOpen = content.classList.contains('open');
  
  if (isOpen) {
    content.classList.remove('open');
    arrow.classList.remove('rotate');
  } else {
    content.classList.add('open');
    arrow.classList.add('rotate');
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const allDetails = document.querySelectorAll('details');
  allDetails.forEach(detail => {
    detail.removeAttribute('open');
  });
});

function szures() {
  const kulteri = Array.from(document.querySelectorAll('input[name="kulteri"]:checked'))
                       .map(cb => cb.value);
  const belteri = Array.from(document.querySelectorAll('input[name="belteri"]:checked'))
                       .map(cb => cb.value);
  const minAr = parseInt(document.getElementById("minAr").value) || 0;
  const maxAr = parseInt(document.getElementById("maxAr").value) || 100000;
  const megye = document.getElementById("megye").value;

  const jobs = document.querySelectorAll(".job-card");
  jobs.forEach(job => {
    const jobKulteri = job.dataset.kulteri;
    const jobBelteri = job.dataset.belteri;
    const jobAr = parseInt(job.dataset.ar);
    const jobMegye = job.dataset.megye;

    const megfelel =
      (kulteri.length === 0 || kulteri.includes(jobKulteri)) &&
      (belteri.length === 0 || belteri.includes(jobBelteri)) &&
      (jobAr >= minAr && jobAr <= maxAr) &&
      (megye === "" || jobMegye === megye);

    job.style.display = megfelel ? "block" : "none";
  });
}
</script>