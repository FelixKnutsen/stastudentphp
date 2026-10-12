<?php
/**
 * Template Name: Lokaler (Premises)
 * Faithful reproduction of src/app/lokaler/page.tsx with interactive Alibiet slider and Bluebox info
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();

$alibiet_images = array(
    array(
        'url'     => 'https://stastudent.no/wp-content/uploads/2022/08/PXL_20220819_092836179-768x576.jpg',
        'caption' => 'Utenfor Alibiet'
    ),
    array(
        'url'     => 'https://stastudent.no/wp-content/uploads/2022/08/PXL_20220819_093006806-768x576.jpg',
        'caption' => 'Inngangen til Alibiet'
    ),
    array(
        'url'     => 'https://stastudent.no/wp-content/uploads/2022/08/PXL_20220819_093021348.MP_-768x576.jpg',
        'caption' => 'Fest- og arrangementsarealet'
    ),
    array(
        'url'     => 'https://stastudent.no/wp-content/uploads/2022/08/PXL_20220819_093039894.MP_-768x576.jpg',
        'caption' => 'Kjøkkenet til festlokalet'
    ),
    array(
        'url'     => 'https://stastudent.no/wp-content/uploads/2022/08/PXL_20220819_113150219-768x576.jpg',
        'caption' => 'Inngangen til kontorlokalet'
    ),
    array(
        'url'     => 'https://stastudent.no/wp-content/uploads/2022/08/PXL_20220819_113156984.MP_-768x576.jpg',
        'caption' => 'TV-krok og sittegruppe'
    ),
    array(
        'url'     => 'https://stastudent.no/wp-content/uploads/2022/08/PXL_20220819_113132165-768x576.jpg',
        'caption' => 'Møteplass'
    ),
);
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Studentlokaler', 'Student Venues')); ?></span>
        <h1 class="sta-hero-title" style="font-size:2.75rem; margin-top:0.5rem;">
            <?php echo esc_html(sta_t('Lokaler', 'Our Premises')); ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.15rem; max-width:800px;">
            <?php echo esc_html(sta_t('Alle studentaktiviteter og linjeforeninger som er medlem av STA kan booke Alibiet og Bluebox lokalene til STA.', 'All student activities and academic associations that are members of STA can book STAs venues at Alibiet and Bluebox.')); ?>
        </p>
    </header>

    <!-- Venue 1: Alibiet (Campus Kristiansand) -->
    <div style="margin-bottom:5rem;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
            <span class="sta-badge" style="background:rgba(239,115,48,0.2); color:var(--sta-orange); border:none; font-size:0.75rem; font-weight:800;">
                CAMPUS KRISTIANSAND
            </span>
            <h2 style="font-size:2rem; font-weight:800; color:#fff; margin:0;">Alibiet</h2>
        </div>

        <div class="sta-venue-grid">
                <!-- Interactive Image Slider -->
                <div>
                    <div class="sta-slider-container">
                        <img id="sta-alibiet-main-img" 
                             src="<?php echo esc_url($alibiet_images[0]['url']); ?>" 
                             alt="<?php echo esc_attr($alibiet_images[0]['caption']); ?>" 
                             class="sta-slider-img">

                        <button type="button" id="sta-slider-prev" class="sta-slider-btn prev" aria-label="Forrige bilde">
                            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </button>
                        <button type="button" id="sta-slider-next" class="sta-slider-btn next" aria-label="Neste bilde">
                            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>

                        <div class="sta-slider-caption-bar">
                            <span id="sta-slider-caption"><?php echo esc_html($alibiet_images[0]['caption']); ?></span>
                            <span id="sta-slider-counter" style="color:var(--sta-teal-200);">1 / <?php echo count($alibiet_images); ?></span>
                        </div>
                    </div>

                    <!-- Thumbnails row -->
                    <div class="sta-slider-thumbs">
                        <?php foreach ($alibiet_images as $idx => $img): ?>
                            <button type="button" class="sta-slider-thumb-btn <?php echo $idx === 0 ? 'is-active' : ''; ?>" data-index="<?php echo $idx; ?>" aria-label="<?php echo esc_attr($img['caption']); ?>">
                                <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['caption']); ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Info Card -->
                <div style="display:flex; flex-direction:column; gap:1.5rem;">
                    <div class="sta-card" style="display:flex; flex-direction:column; gap:1.25rem;">
                        <h3 style="font-size:1.25rem; font-weight:700; color:#fff; margin:0;">
                            <?php echo esc_html(sta_t('Om Alibiet', 'About Alibiet')); ?>
                        </h3>
                        <p style="font-size:0.9rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                            <?php echo esc_html(sta_t('Alibiet inneholder musikkanlegg, kjøleskap og sitteplasser med tilhørende bord. Lokalet er perfekt for foreningskvelder, fester, brettspill og sosiale sammenkomster.', 'Alibiet includes a sound system, refrigerators, and seating with tables. The venue is ideal for association gatherings, parties, board game nights, and social events.')); ?>
                        </p>

                        <div style="padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.2);">
                            <h4 style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-orange); margin:0 0 0.5rem;">
                                <?php echo esc_html(sta_t('Bookingregler', 'Booking Rules')); ?>
                            </h4>
                            <ul style="font-size:0.85rem; color:var(--sta-teal-100); line-height:1.6; padding-left:1rem; margin:0;">
                                <li>Booking foregår via e-post til: <a href="mailto:sta@uia.no" style="color:var(--sta-orange); text-decoration:underline;">sta@uia.no</a></li>
                                <li>Foreninger bookes inn fortløpende i kalenderen.</li>
                                <li>Skriftlig bekreftelse sendes innen 1–3 virkedager.</li>
                                <li>Booking må sendes fra foreningens offisielle e-post.</li>
                            </ul>
                        </div>

                        <div style="padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.2);">
                            <h4 style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-orange); margin:0 0 0.5rem;">
                                <?php echo esc_html(sta_t('Kontorlokalet', 'Office Space')); ?>
                            </h4>
                            <p style="font-size:0.85rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                                Kontorlokalet på Alibiet er gratis tilgjengelig for studentaktiviteter og linjeforeninger uten forhåndsbooking. For nøkkelkortadgang, kontakt <a href="mailto:sta@uia.no" style="text-decoration:underline;">sta@uia.no</a>.
                            </p>
                        </div>

                        <div style="padding-top:0.5rem;">
                            <a href="https://stastudent.no/wp-content/uploads/2025/10/Alibireglement.pdf" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.85rem; font-weight:700; color:var(--sta-orange);">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                <span>Last ned Alibireglementet (PDF)</span>
                            </a>
                        </div>
                    </div>

                    <a href="mailto:sta@uia.no?subject=Booking%20av%20Alibiet" class="sta-btn sta-btn-orange" style="justify-content:center; padding:0.85rem 1.5rem; font-size:0.95rem;">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.5rem;" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span>Book Alibiet via e-post</span>
                    </a>
                </div>
            </div>
        </div>

    <!-- Venue 2: Bluebox (Campus Grimstad) -->
    <div>
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
            <span class="sta-badge" style="background:rgba(239,115,48,0.2); color:var(--sta-orange); border:none; font-size:0.75rem; font-weight:800;">
                CAMPUS GRIMSTAD
            </span>
            <h2 style="font-size:2rem; font-weight:800; color:#fff; margin:0;">Bluebox</h2>
        </div>

        <div class="sta-venue-grid">
            <div style="position:relative; aspect-ratio:4/3; border-radius:var(--sta-radius); overflow:hidden; border:1px solid rgba(61,148,139,0.3); background:var(--sta-teal-800); box-shadow:0 20px 25px -5px rgba(0,0,0,0.4);">
                <img src="https://stastudent.no/wp-content/uploads/2022/09/20220901_090008-1024x766.jpg" 
                     alt="Bluebox Grimstad" 
                     style="width:100%; height:100%; object-fit:cover;"
                     loading="lazy">
                <div style="position:absolute; bottom:0; inset-x:0; background:rgba(0,0,0,0.65); backdrop-filter:blur(4px); padding:0.75rem 1rem; font-size:0.8rem; color:#fff;">
                    <span>Møterom og lager på Bluebox • Foto: Irene Bredal / STA</span>
                </div>
            </div>

            <div class="sta-card" style="display:flex; flex-direction:column; gap:1.25rem;">
                <h3 style="font-size:1.25rem; font-weight:700; color:#fff; margin:0;">
                    <?php echo esc_html(sta_t('Om lokalene på Bluebox', 'About Bluebox Premises')); ?>
                </h3>
                <p style="font-size:0.9rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                    <?php echo esc_html(sta_t('På Bluebox har STA to møterom og et lager som medlemsforeninger og studentaktiviteter kan benytte gratis til møter og arrangementer.', 'At Bluebox, STA has two meeting rooms and storage that member associations and student activities can use free of charge for meetings and events.')); ?>
                </p>

                <div style="padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.2);">
                    <h4 style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-orange); margin:0 0 0.5rem;">
                        <?php echo esc_html(sta_t('Bookingmetode', 'Booking Method')); ?>
                    </h4>
                    <p style="font-size:0.85rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                        <?php echo esc_html(sta_t('Manuell fysisk booking foregår ved å gå opp i 2. etasje på Bluebox og skrive opp foreningen på listen som henger på døren til møterommet.', 'Manual physical booking takes place by going up to the 2nd floor of Bluebox and signing up your association on the list hanging on the meeting room door.')); ?>
                    </p>
                </div>

                <div style="padding-top:0.5rem;">
                    <a href="https://bluebox.no" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.85rem; font-weight:700; color:var(--sta-orange);">
                        <span>Besøk Bluebox sin offisielle nettside</span>
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var images = <?php echo json_encode($alibiet_images); ?>;
    var currentIndex = 0;
    var mainImg = document.getElementById('sta-alibiet-main-img');
    var captionElem = document.getElementById('sta-slider-caption');
    var counterElem = document.getElementById('sta-slider-counter');
    var thumbs = document.querySelectorAll('.sta-slider-thumb-btn');

    function updateSlide(idx) {
        if (idx < 0) idx = images.length - 1;
        if (idx >= images.length) idx = 0;
        currentIndex = idx;
        mainImg.src = images[currentIndex].url;
        mainImg.alt = images[currentIndex].caption;
        captionElem.textContent = images[currentIndex].caption;
        counterElem.textContent = (currentIndex + 1) + ' / ' + images.length;

        thumbs.forEach(function(btn, i) {
            btn.classList.toggle('is-active', i === currentIndex);
        });
    }

    var prevBtn = document.getElementById('sta-slider-prev');
    var nextBtn = document.getElementById('sta-slider-next');

    if (prevBtn) prevBtn.addEventListener('click', function() { updateSlide(currentIndex - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function() { updateSlide(currentIndex + 1); });

    thumbs.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var idx = parseInt(btn.getAttribute('data-index'), 10);
            updateSlide(idx);
        });
    });
});
</script>

<?php
get_footer();
