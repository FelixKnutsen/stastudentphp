<?php
/**
 * Template Tags & UI Helpers for STA Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Partner Logos Section
 */
function sta_render_partner_logos() {
    $partners = get_option('sta_partner_logos', array(
        'nso' => array(
            'name'     => 'Norsk studentorganisasjon (NSO)',
            'name_en'  => 'The National union of students in Norway (NSO)',
            'url'      => 'https://student.no',
            'logo_url' => 'https://stastudent.no/wp-content/uploads/2024/05/NSO-logo.png',
            'alt'      => 'Norsk studentorganisasjon (NSO) logo'
        ),
        'uia' => array(
            'name'     => 'Universitetet i Agder (UiA)',
            'name_en'  => 'University of Agder (UiA)',
            'url'      => 'https://www.uia.no',
            'logo_url' => 'https://stastudent.no/wp-content/uploads/2020/11/uia-horisontal-med-navn-Positiv.png',
            'alt'      => 'Universitetet i Agder (UiA) logo'
        ),
        'sia' => array(
            'name'     => 'Studentsamskipnaden i Agder (SiA)',
            'name_en'  => 'Student Welfare Organization (SiA)',
            'url'      => 'https://sia.no',
            'logo_url' => 'https://stastudent.no/wp-content/uploads/2020/11/sialogo.png',
            'alt'      => 'Studentsamskipnaden i Agder (SiA) logo'
        ),
        'saih' => array(
            'name'     => 'Studentenes og Akademikernes Internasjonale Hjelpefond (SAIH)',
            'name_en'  => "Students' and Academics' International Assistance Fund (SAIH)",
            'url'      => 'https://www.saih.no/no',
            'logo_url' => 'https://stastudent.no/wp-content/uploads/2024/05/SAIH-logo-PNG-1-1024x1024.png',
            'alt'      => 'SAIH logo'
        ),
    ));

    $is_en = sta_is_en();
    ?>
    <section id="samarbeidspartnere" class="sta-partners-section" aria-label="<?php echo esc_attr(sta_t('Samarbeidspartnere', 'Collaborating Partners', 'Samarbeidspartnarar')); ?>">
        <div class="sta-container">
            <div class="sta-section-header">
                <h2 class="sta-section-title"><?php echo esc_html(sta_t('Samarbeidspartnere', 'Collaborating Partners', 'Samarbeidspartnarar')); ?></h2>
                <p class="sta-section-subtitle"><?php echo esc_html(sta_t('Vi samarbeider tett med sentrale aktører i UH-sektoren og Agder-regionen', 'We collaborate closely with key partners in the higher education sector and the Agder region', 'Vi samarbeider tett med sentrale aktørar i UH-sektoren og Agder-regionen')); ?></p>
            </div>

            <div class="sta-partners-grid">
                <?php foreach ($partners as $partner): 
                    $name = $is_en ? ($partner['name_en'] ?: $partner['name']) : $partner['name'];
                ?>
                    <a href="<?php echo esc_url($partner['url']); ?>" target="_blank" rel="noopener noreferrer" class="sta-partner-card" aria-label="<?php echo esc_attr($name . ' (' . sta_t('åpner i ny fane', 'opens in new tab', 'opnar i ny fane') . ')'); ?>">
                        <div class="sta-partner-logo-box">
                            <img src="<?php echo esc_url($partner['logo_url']); ?>" alt="<?php echo esc_attr($partner['alt']); ?>" width="180" height="60" loading="lazy">
                        </div>
                        <div class="sta-partner-info">
                            <span class="sta-partner-name"><?php echo esc_html($name); ?></span>
                            <svg class="sta-external-icon" viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Render Verv & Student Democracy CTA Banner
 */
function sta_render_verv_cta() {
    ?>
    <section class="sta-cta-section">
        <div class="sta-container">
            <div class="sta-cta-box">
                <div class="sta-cta-content">
                    <span class="sta-badge"><?php echo esc_html(sta_t('Bli engasjert', 'Get Involved', 'Bli engasjert')); ?></span>
                    <h2 class="sta-cta-title"><?php echo esc_html(sta_t('Vil du være med å forme studiehverdagen på UiA?', 'Want to help shape student life at UiA?', 'Vil du vere med å forme studiekvardagen ved UiA?')); ?></h2>
                    <p class="sta-cta-text"><?php echo esc_html(sta_t('Gjennom verv i STA, Studentparlamentet og universitetsorganer får du verdifull erfaring, nettverk og mulighet til å gjøre en forskjell for over 14 000 studenter.', 'Through positions in STA, the Student Parliament, and university bodies, you gain valuable experience, a broader network, and the chance to make a real difference for over 14,000 students.', 'Gjennom verv i STA, Studentparlamentet og universitetsorgan får du verdifull erfaring, nettverk og høve til å gjere ein skilnad for over 14 000 studentar.')); ?></p>
                    <div class="sta-cta-buttons">
                        <a href="<?php echo esc_url(sta_get_localized_url('/ledige-verv')); ?>" class="sta-btn sta-btn-orange">
                            <?php echo esc_html(sta_t('Se ledige verv', 'View vacant positions', 'Sjå ledige verv')); ?>
                        </a>
                        <a href="<?php echo esc_url(sta_get_localized_url('/rad-styrer-og-utvalg')); ?>" class="sta-btn sta-btn-outline">
                            <?php echo esc_html(sta_t('Les om råd, styrer og utvalg', 'Read about boards and committees', 'Les om råd, styre og utval')); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Render Board Group Photo with 3:2 aspect ratio and press link
 */
function sta_render_board_group_photo() {
    ?>
    <div class="sta-board-photo-container">
        <div class="sta-board-photo-frame">
            <img src="https://stastudent.no/wp-content/uploads/2026/08/Gruppebilde-STA-styret-1024x683.jpg" 
                 alt="<?php echo esc_attr(sta_t('Gruppebilde av STA-styret og sekretariatet', 'Group photo of the STA Board and Secretariat', 'Gruppebilete av STA-styret og sekretariatet')); ?>" 
                 class="sta-board-photo"
                 loading="eager">
        </div>
        <div class="sta-board-photo-meta">
            <p>
                <strong><?php echo esc_html(sta_t('Pressehenvendelser:', 'Press Inquiries:', 'Pressehenvendingar:')); ?></strong> 
                <?php echo esc_html(sta_t('Alle pressehenvendelser rettes til studentleder Karen Elise Stenersen.', 'All press inquiries should be directed to Student President Karen Elise Stenersen.', 'Alle pressehenvendingar rettast til studentleiar Karen Elise Stenersen.')); ?>
            </p>
            <a href="https://www.skyfish.com/p/uia/2397359" target="_blank" rel="noopener noreferrer" class="sta-press-link">
                <span><?php echo esc_html(sta_t('Last ned høyoppløselige pressebilder (Skyfish)', 'Download press photos (Skyfish)', 'Last ned høgoppløyselege pressebilete (Skyfish)')); ?></span>
                <svg class="sta-external-icon" viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Render Accessible Numbered Pagination
 */
function sta_render_pagination($query = null) {
    if (!$query) {
        global $wp_query;
        $query = $wp_query;
    }

    $total_pages = $query->max_num_pages;
    if ($total_pages <= 1) {
        return;
    }

    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : max(1, get_query_var('paged'));
    $base_link = (function_exists('sta_use_query_lang_routing') && sta_use_query_lang_routing())
        ? esc_url(add_query_arg('paged', '%#%'))
        : str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999)));

    echo '<nav class="sta-pagination" aria-label="' . esc_attr(sta_t('Sidenavigasjon', 'Pagination', 'Sidenavigasjon')) . '">';
    echo paginate_links(array(
        'base'      => $base_link,
        'format'    => '?paged=%#%',
        'current'   => $current_page,
        'total'     => $total_pages,
        'prev_text' => '&larr; ' . sta_t('Forrige', 'Previous', 'Førre'),
        'next_text' => sta_t('Neste', 'Next', 'Neste') . ' &rarr;',
        'type'      => 'list',
        'mid_size'  => 2,
        'end_size'  => 1,
    ));
    echo '</nav>';
}

/**
 * Locate bundled JSON data file
 */
function sta_get_data_file($filename) {
    $paths = array(
        STA_THEME_PATH . '/data/' . $filename,
        STA_THEME_PATH . '/../data/' . $filename,
        ABSPATH . 'wp-content/themes/sta-theme/data/' . $filename,
    );
    foreach ($paths as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }
    return false;
}

/**
 * Format date in Norwegian Bokmål, Nynorsk or English
 */
function sta_format_date($timestamp_or_str = null, $format = 'j. F Y') {
    if ($timestamp_or_str === null || $timestamp_or_str === '') {
        $timestamp = (int) get_the_time('U');
        if (!$timestamp) {
            $timestamp = time();
        }
    } elseif (is_numeric($timestamp_or_str)) {
        $timestamp = (int) $timestamp_or_str;
    } else {
        $timestamp = strtotime($timestamp_or_str);
    }
    if (!$timestamp) {
        return '';
    }

    if (sta_is_en()) {
        return date('F j, Y', $timestamp);
    }

    $day = date('j', $timestamp);
    $month_num = (int) date('n', $timestamp);
    $year = date('Y', $timestamp);

    $months_no = array(
        1  => 'januar',
        2  => 'februar',
        3  => 'mars',
        4  => 'april',
        5  => 'mai',
        6  => 'juni',
        7  => 'juli',
        8  => 'august',
        9  => 'september',
        10 => 'oktober',
        11 => 'november',
        12 => 'desember'
    );

    $month_name = $months_no[$month_num] ?? date('F', $timestamp);

    return "{$day}. {$month_name} {$year}";
}

/**
 * Normalize a document URL for deduplication (strips staging subdirectory prefixes like /reliant/)
 */
function sta_normalize_doc_url_key($url) {
    $u = rtrim(trim((string) $url), '/');
    $base_path = function_exists('sta_get_home_base_path') ? sta_get_home_base_path() : '';
    if ($base_path !== '') {
        $u = str_replace($base_path . '/', '/', $u);
    }
    return strtolower($u);
}

/**
 * Parse year-grouped document links from WordPress page content (Elementor / Block / Classic editor)
 * and merge any newly added links with the default structured archive so future edits in WordPress
 * immediately appear on the page.
 */
function sta_merge_editable_year_docs($html, $default_groups) {
    if (empty($html) || !is_string($html)) {
        return $default_groups;
    }

    // Track all existing URLs across default groups
    $existing_urls = array();
    foreach ($default_groups as $yr => $items) {
        foreach ($items as $it) {
            if (!empty($it['href'])) {
                $existing_urls[sta_normalize_doc_url_key($it['href'])] = true;
            }
        }
    }

    // Split content by year headings (<h1>2026</h1>, <h2>2025</h2>, <h3>2024</h3>, etc.)
    $pattern = '/<h[1-6][^>]*>\s*(20\d{2})\s*<\/h[1-6]>/i';
    $parts = preg_split($pattern, $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    if (!is_array($parts) || count($parts) < 3) {
        return $default_groups;
    }

    $merged = $default_groups;
    for ($i = 1; $i < count($parts); $i += 2) {
        $year = trim($parts[$i]);
        $section_html = $parts[$i + 1] ?? '';

        if (preg_match_all('/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $section_html, $matches, PREG_SET_ORDER)) {
            $new_for_year = array();
            foreach ($matches as $m) {
                $href  = trim( html_entity_decode($m[1], ENT_QUOTES, 'UTF-8') );
                $title = trim( wp_strip_all_tags( html_entity_decode($m[2], ENT_QUOTES, 'UTF-8') ) );
                $title = preg_replace('/\s+/u', ' ', str_replace("\xC2\xA0", ' ', $title));
                if ($href === '' || $href === '#' || $title === '' || strtolower($title) === 'klikk her') {
                    continue;
                }
                $norm_href = sta_normalize_doc_url_key($href);
                if (!isset($existing_urls[$norm_href])) {
                    $existing_urls[$norm_href] = true;
                    $new_for_year[] = array(
                        'title' => $title,
                        'href'  => $href,
                    );
                }
            }
            if (!empty($new_for_year)) {
                if (!isset($merged[$year])) {
                    $merged = array($year => $new_for_year) + $merged;
                } else {
                    $merged[$year] = array_merge($new_for_year, $merged[$year]);
                }
            }
        }
    }

    krsort($merged);
    return $merged;
}
