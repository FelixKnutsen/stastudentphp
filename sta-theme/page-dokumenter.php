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
$is_nn = sta_is_nn();

$ovrige_docs = array(
    array(
        'title'    => 'Vedtekter',
        'title_en' => 'Statutes',
        'title_nn' => 'Vedtekter',
        'subtitle' => 'Organisasjonens regler',
        'sub_en'   => 'Organizational bylaws and regulations',
        'sub_nn'   => 'Reglane til organisasjonen',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/STAs-Vedtekter.pdf'
    ),
    array(
        'title'    => 'Handlingsplan',
        'title_en' => 'Action Plan',
        'title_nn' => 'Handlingsplan',
        'subtitle' => 'Våre politiske prioriteringer',
        'sub_en'   => 'Our strategic political priorities',
        'sub_nn'   => 'Dei politiske prioriteringane våre',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/06/STAs-Handlingsplan-2026-2027_1.pdf'
    ),
    array(
        'title'    => 'Stillingsbeskrivelser',
        'title_en' => 'Job Descriptions',
        'title_nn' => 'Stillingsomaler',
        'subtitle' => 'Styrets arbeidsoppgaver',
        'sub_en'   => 'Board responsibilities and job descriptions',
        'sub_nn'   => 'Arbeidsoppgåvene til styret',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/STAs-Stillingsbeskrivelser.pdf'
    ),
);

$politiske_docs = array(
    array(
        'title'    => 'Studentvelferd',
        'title_en' => 'Student Welfare',
        'title_nn' => 'Studentvelferd',
        'subtitle' => 'Les om vår velferdspolitikk',
        'sub_en'   => 'Read about our welfare policies',
        'sub_nn'   => 'Les om velferdspolitikken vår',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Studentvelferd-2026.pdf'
    ),
    array(
        'title'    => 'Læringsmiljø og bærekraft',
        'title_en' => 'Learning Environment and Sustainability',
        'title_nn' => 'Læringsmiljø og berekraft',
        'subtitle' => 'Les om vår politikk på læringsmiljø og bærekraft',
        'sub_en'   => 'Read about learning environment and sustainability',
        'sub_nn'   => 'Les om politikken vår på læringsmiljø og berekraft',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Laeringsmiljo-og-Baerekraft.pdf'
    ),
    array(
        'title'    => 'Utdanningskvalitet',
        'title_en' => 'Quality of Education',
        'title_nn' => 'Utdanningskvalitet',
        'subtitle' => 'Les om vår utdanning- og forskningspolitikk',
        'sub_en'   => 'Read about our education and research policy',
        'sub_nn'   => 'Les om utdannings- og forskingspolitikken vår',
        'href'     => 'https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Utdanningskvalitet-2026.pdf'
    ),
    array(
        'title'    => 'Mangfold og internasjonalisering',
        'title_en' => 'Diversity and Internationalisation',
        'title_nn' => 'Mangfald og internasjonalisering',
        'subtitle' => 'Les om vår politikk på mangfold og internasjonalisering',
        'sub_en'   => 'Read about diversity and internationalisation',
        'sub_nn'   => 'Les om politikken vår på mangfald og internasjonalisering',
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
        <span class="sta-badge"><?php echo esc_html(sta_t('Styringsdokumenter', 'Policy Documents', 'Styringsdokument')); ?></span>
        <h1 class="sta-hero-title" style="font-size:2.75rem; margin-top:0.5rem;">
            <?php echo esc_html(sta_t('Dokumenter', 'Documents', 'Dokument')); ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.15rem; max-width:800px;">
            <?php echo esc_html(sta_t('Oversikt over STAs vedtekter, handlingsplaner, politiske dokumenter og resolusjoner.', 'Overview of STA\'s bylaws, action plans, policy documents and resolutions.', 'Oversikt over STA sine vedtekter, handlingsplanar, politiske dokument og resolusjonar.')); ?>
        </p>
    </header>

    <!-- 1. Øvrige styringsdokumenter (3 Cards) -->
    <div style="margin-bottom:4rem;">
        <h2 style="font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:1.5rem;">
            <?php echo esc_html(sta_t('Øvrige styringsdokumenter', 'Other Policy Documents', 'Andre styringsdokument')); ?>
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
            <?php foreach ($ovrige_docs as $doc): 
                $title = $is_en ? $doc['title_en'] : ($is_nn ? ($doc['title_nn'] ?? $doc['title']) : $doc['title']);
                $sub = $is_en ? $doc['sub_en'] : ($is_nn ? ($doc['sub_nn'] ?? $doc['subtitle']) : $doc['subtitle']);
            ?>
                <a href="<?php echo esc_url($doc['href']); ?>" target="_blank" rel="noopener noreferrer" class="sta-card" style="display:flex; flex-direction:column; justify-content:space-between; text-decoration:none;">
                    <div>
                        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
                            <div style="width:2.5rem; height:2.5rem; border-radius:0.5rem; background:rgba(239,115,48,0.2); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </div>
                            <h3 style="font-size:1.25rem; font-weight:700; color:#fff; margin:0;">
                                <?php echo esc_html($title); ?>
                            </h3>
                        </div>
                        <p style="font-size:0.875rem; color:var(--sta-teal-100); margin:0; line-height:1.5;">
                            <?php echo esc_html($sub); ?>
                        </p>
                    </div>
                    <div style="margin-top:1.25rem; font-size:0.8rem; font-weight:700; color:var(--sta-orange); display:flex; align-items:center; gap:0.25rem;">
                        <span><?php echo esc_html(sta_t('Last ned (PDF)', 'Download (PDF)', 'Last ned (PDF)')); ?></span>
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 2. Politiske dokumenter (4 Cards) -->
    <div style="margin-bottom:4rem;">
        <h2 style="font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:1.5rem;">
            <?php echo esc_html(sta_t('Politiske dokumenter', 'Political Documents', 'Politiske dokument')); ?>
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
            <?php foreach ($politiske_docs as $doc): 
                $title = $is_en ? $doc['title_en'] : ($is_nn ? ($doc['title_nn'] ?? $doc['title']) : $doc['title']);
                $sub = $is_en ? $doc['sub_en'] : ($is_nn ? ($doc['sub_nn'] ?? $doc['subtitle']) : $doc['subtitle']);
            ?>
                <a href="<?php echo esc_url($doc['href']); ?>" target="_blank" rel="noopener noreferrer" class="sta-card" style="display:flex; flex-direction:column; justify-content:space-between; text-decoration:none;">
                    <div>
                        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
                            <div style="width:2.5rem; height:2.5rem; border-radius:0.5rem; background:rgba(239,115,48,0.2); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            </div>
                            <h3 style="font-size:1.25rem; font-weight:700; color:#fff; margin:0;">
                                <?php echo esc_html($title); ?>
                            </h3>
                        </div>
                        <p style="font-size:0.875rem; color:var(--sta-teal-100); margin:0; line-height:1.5;">
                            <?php echo esc_html($sub); ?>
                        </p>
                    </div>
                    <div style="margin-top:1.25rem; font-size:0.8rem; font-weight:700; color:var(--sta-orange); display:flex; align-items:center; gap:0.25rem;">
                        <span><?php echo esc_html(sta_t('Last ned (PDF)', 'Download (PDF)', 'Last ned (PDF)')); ?></span>
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 3. Resolusjoner vedtatt av Studentparlamentet (Grouped by Year) -->
    <div style="margin-bottom:4rem;">
        <h2 style="font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:1.5rem;">
            <?php echo esc_html(sta_t('Resolusjoner vedtatt av Studentparlamentet', 'Resolutions Adopted by Parliament', 'Resolusjonar vedtekne av Studentparlamentet')); ?>
        </h2>
        <div style="display:flex; flex-direction:column; gap:2rem;">
            <?php foreach ($resolusjoner as $group): ?>
                <div class="sta-card" style="padding:1.5rem 2rem;">
                    <h3 style="font-size:1.2rem; font-weight:800; color:var(--sta-orange); margin:0 0 1rem 0; border-bottom:1px solid rgba(158,207,201,0.2); padding-bottom:0.5rem;">
                        <?php echo esc_html($group['year']); ?>
                    </h3>
                    <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.75rem;">
                        <?php foreach ($group['items'] as $item): ?>
                            <li>
                                <a href="<?php echo esc_url($item['href']); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--sta-teal-100); text-decoration:none; display:flex; align-items:center; justify-content:space-between; font-size:0.9rem; padding:0.4rem 0; border-bottom:1px dashed rgba(158,207,201,0.15);">
                                    <span><?php echo esc_html($item['title']); ?></span>
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 4. Andre reglementer og retningslinjer -->
    <div>
        <h2 style="font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:1.5rem;">
            <?php echo esc_html(sta_t('Andre reglementer og retningslinjer', 'Other Regulations and Guidelines', 'Andre reglement og retningslinjer')); ?>
        </h2>
        <div class="sta-card" style="padding:1.5rem 2rem;">
            <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.75rem;">
                <?php foreach ($andre_docs as $item): ?>
                    <li>
                        <a href="<?php echo esc_url($item['href']); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--sta-teal-100); text-decoration:none; display:flex; align-items:center; justify-content:space-between; font-size:0.9rem; padding:0.4rem 0; border-bottom:1px dashed rgba(158,207,201,0.15);">
                            <span><?php echo esc_html($item['title']); ?></span>
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<?php
get_footer();
