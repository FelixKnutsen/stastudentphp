<?php
/**
 * Template Name: Dokumenter (Documents)
 * Faithful reproduction of src/app/dokumenter/page.tsx
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();

$ovrige_docs = array(
    array(
        'title'    => 'Vedtekter',
        'title_en' => 'Statutes',
        'subtitle' => 'Organisasjonens regler',
        'sub_en'   => 'Organizational bylaws and regulations',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/STAs-Vedtekter.pdf'
    ),
    array(
        'title'    => 'Handlingsplan',
        'title_en' => 'Action Plan',
        'subtitle' => 'Våre politiske prioriteringer',
        'sub_en'   => 'Our strategic political priorities',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/06/STAs-Handlingsplan-2026-2027_1.pdf'
    ),
    array(
        'title'    => 'Stillingsbeskrivelser',
        'title_en' => 'Job Descriptions',
        'subtitle' => 'Styrets arbeidsoppgaver',
        'sub_en'   => 'Board responsibilities and job descriptions',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/STAs-Stillingsbeskrivelser.pdf'
    ),
);

$politiske_docs = array(
    array(
        'title'    => 'Studentvelferd',
        'title_en' => 'Student Welfare',
        'subtitle' => 'Les om vår velferdspolitikk',
        'sub_en'   => 'Read about our welfare policies',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Studentvelferd-2026.pdf'
    ),
    array(
        'title'    => 'Læringsmiljø og bærekraft',
        'title_en' => 'Learning Environment and Sustainability',
        'subtitle' => 'Les om vår politikk på læringsmiljø og bærekraft',
        'sub_en'   => 'Read about learning environment and sustainability',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Laeringsmiljo-og-Baerekraft.pdf'
    ),
    array(
        'title'    => 'Utdanningskvalitet',
        'title_en' => 'Quality of Education',
        'subtitle' => 'Les om vår utdanning- og forskningspolitikk',
        'sub_en'   => 'Read about our education and research policy',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Utdanningskvalitet-2026.pdf'
    ),
    array(
        'title'    => 'Mangfold og internasjonalisering',
        'title_en' => 'Diversity and Internationalisation',
        'subtitle' => 'Les om vår politikk på mangfold og internasjonalisering',
        'sub_en'   => 'Read about diversity and internationalisation',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Mangfold-og-Internasjonalisering.pdf'
    ),
);

$andre_docs = array(
    array(
        'title' => 'Reglement for studentaktiviteter (PDF)',
        'href'  => 'https://stastudent.no/wp-content/uploads/2025/06/STAs-Reglement-for-studentaktivitetene.pdf'
    ),
    array(
        'title' => 'Forretningsorden (DOC)',
        'href'  => 'https://stastudent.no/wp-content/uploads/2024/09/Forretningsorden-SP.doc'
    ),
    array(
        'title' => 'Økonomireglement (PDF)',
        'href'  => 'https://stastudent.no/wp-content/uploads/2026/05/STAs-Okonomireglement.pdf'
    ),
    array(
        'title' => 'Valgreglement (PDF)',
        'href'  => 'https://stastudent.no/wp-content/uploads/2026/05/STAs-Valgreglement.pdf'
    ),
    array(
        'title' => 'Fraværssystem for Studentparlamentet (PDF)',
        'href'  => 'https://stastudent.no/wp-content/uploads/2025/06/Fravaerssystem-for-Studentparlamentet.pdf'
    ),
);

$resolusjoner = array(
    array(
        'year'  => '2025',
        'items' => array(
            array(
                'title' => '29.10.25 Resolusjon – Studentfrivillighet for fremtiden (PDF)',
                'href'  => 'https://stastudent.no/wp-content/uploads/2025/12/29.10.25-Resolusjon-Studentfrivillighet-for-fremtiden.pdf'
            ),
            array(
                'title' => '24.09.25 Resolusjon nynorsk på UiA (PDF)',
                'href'  => 'https://stastudent.no/wp-content/uploads/2025/12/24.09.25-Resolusjon-nynorsk-pa-UiA.pdf'
            ),
            array(
                'title' => '21.05.25 Resolusjon eksamensrettigheter (DOCX)',
                'href'  => 'https://stastudent.no/wp-content/uploads/2025/05/21.05.25-Resolusjon-eksamensrettigheter.docx'
            ),
            array(
                'title' => '22.01.25 Resolusjon kunstig intelligens (DOCX)',
                'href'  => 'https://stastudent.no/wp-content/uploads/2025/05/22.01.25-Resolusjon-KI.docx'
            ),
        )
    ),
    array(
        'year'  => '2024',
        'items' => array(
            array(
                'title' => '23.10.24 Resolusjon om fleksible studier og studenter (DOCX)',
                'href'  => 'https://stastudent.no/wp-content/uploads/2024/10/23.10.24-Resolusjon-om-fleksible-studier-og-studenter.docx'
            ),
            array(
                'title' => '23.10.24 Resolusjon om fravær blant undervisere (DOCX)',
                'href'  => 'https://stastudent.no/wp-content/uploads/2024/10/23.10.24-Resolusjon-om-fravaer-blant-undervisere.docx'
            ),
            array(
                'title' => '22.05.24 Resolusjon akademisk frihet og demokrati (DOCX)',
                'href'  => 'https://stastudent.no/wp-content/uploads/2024/09/22.05.24-Resolusjon-akademisk-frihet-og-demokrati.docx'
            ),
        )
    )
);
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Styringsdokumenter', 'Policy Documents')); ?></span>
        <h1 class="sta-hero-title" style="font-size:2.75rem; margin-top:0.5rem;">
            <?php echo esc_html(sta_t('Dokumenter', 'Documents')); ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.15rem; max-width:800px;">
            <?php echo esc_html(sta_t('Her kan du lese om Studentorganisasjonen i Agder sine politiske og organisatoriske dokumenter, vedtekter og protokoller.', 'Here you can read about STAs political and organizational documents, statutes, and resolutions.')); ?>
        </p>
    </header>

    <!-- 1. Øvrige dokumenter -->
    <div style="margin-bottom:3.5rem;">
        <h2 style="font-size:1.75rem; font-weight:800; color:#fff; margin-bottom:1.5rem;">
            <?php echo esc_html(sta_t('Øvrige dokumenter', 'Core Documents')); ?>
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
            <?php foreach ($ovrige_docs as $doc): ?>
                <a href="<?php echo esc_url($doc['href']); ?>" target="_blank" rel="noopener noreferrer" class="sta-card" style="display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        <div style="width:3rem; height:3rem; border-radius:0.75rem; background:rgba(239,115,48,0.15); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; margin-bottom:1rem;">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                        </div>
                        <h3 class="sta-card-title"><?php echo esc_html($is_en ? $doc['title_en'] : $doc['title']); ?></h3>
                        <p class="sta-card-desc"><?php echo esc_html($is_en ? $doc['sub_en'] : $doc['subtitle']); ?></p>
                    </div>
                    <div style="margin-top:1.5rem; padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.2); display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; font-weight:700; color:var(--sta-orange);">
                        <span><?php echo esc_html(sta_t('Last ned PDF', 'Download PDF')); ?></span>
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 2. Politiske dokumenter -->
    <div style="margin-bottom:3.5rem;">
        <h2 style="font-size:1.75rem; font-weight:800; color:#fff; margin-bottom:1.5rem;">
            <?php echo esc_html(sta_t('Politiske dokumenter', 'Political Policy Documents')); ?>
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
            <?php foreach ($politiske_docs as $doc): ?>
                <a href="<?php echo esc_url($doc['href']); ?>" target="_blank" rel="noopener noreferrer" class="sta-card" style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem;">
                    <div>
                        <h3 class="sta-card-title"><?php echo esc_html($is_en ? $doc['title_en'] : $doc['title']); ?></h3>
                        <p class="sta-card-desc"><?php echo esc_html($is_en ? $doc['sub_en'] : $doc['subtitle']); ?></p>
                        <span style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.8rem; font-weight:700; color:var(--sta-orange); margin-top:0.75rem;">
                            <?php echo esc_html(sta_t('Last ned dokument', 'Download document')); ?>
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        </span>
                    </div>
                    <div style="width:2.5rem; height:2.5rem; border-radius:0.5rem; background:rgba(239,115,48,0.15); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 3. Andre dokumenter & reglementer -->
    <div style="margin-bottom:3.5rem;">
        <h2 style="font-size:1.75rem; font-weight:800; color:#fff; margin-bottom:1.5rem;">
            <?php echo esc_html(sta_t('Andre dokumenter & reglementer', 'Other Documents & Regulations')); ?>
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1rem;">
            <?php foreach ($andre_docs as $doc): ?>
                <a href="<?php echo esc_url($doc['href']); ?>" target="_blank" rel="noopener noreferrer" style="padding:1rem; border-radius:var(--sta-radius-sm); background:rgba(9,50,47,0.5); border:1px solid rgba(61,148,139,0.3); font-size:0.85rem; font-weight:600; color:#fff; display:flex; align-items:center; justify-content:space-between; gap:0.5rem; transition:all 0.2s;">
                    <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?php echo esc_html($doc['title']); ?></span>
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 4. Vedtatte resolusjoner -->
    <div style="padding:2rem; border-radius:var(--sta-radius); background:rgba(7,38,36,0.4); border:1px solid rgba(61,148,139,0.3);">
        <h2 style="font-size:1.75rem; font-weight:800; color:#fff; margin:0 0 0.5rem;">
            <?php echo esc_html(sta_t('Vedtatte resolusjoner', 'Adopted Resolutions')); ?>
        </h2>
        <p style="font-size:0.85rem; color:var(--sta-teal-200); margin:0 0 1.5rem;">
            <?php echo esc_html(sta_t('Studentparlamentets politiske resolusjoner og prinsipielle vedtak.', 'Political resolutions and decisions adopted by the Student Parliament.')); ?>
        </p>

        <div style="display:flex; flex-direction:column; gap:1.75rem;">
            <?php foreach ($resolusjoner as $group): ?>
                <div>
                    <h3 style="font-size:0.9rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-orange); margin:0 0 0.75rem;">
                        <?php echo esc_html($group['year']); ?>
                    </h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:0.75rem;">
                        <?php foreach ($group['items'] as $res): ?>
                            <a href="<?php echo esc_url($res['href']); ?>" target="_blank" rel="noopener noreferrer" style="padding:0.85rem; border-radius:var(--sta-radius-sm); background:rgba(4,24,23,0.7); border:1px solid rgba(61,148,139,0.25); font-size:0.8rem; color:var(--sta-teal-100); display:flex; align-items:center; justify-content:space-between; gap:0.5rem; transition:all 0.2s;">
                                <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?php echo esc_html($res['title']); ?></span>
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
get_footer();
