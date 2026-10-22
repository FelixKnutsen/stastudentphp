<?php
/**
 * Header template for STA Theme
 * Matches the Next.js layout, navigation, search modal, and language switcher.
 * Supports Bokmål ('no'), Nynorsk ('nn'), and English ('en').
 */
if (!defined('ABSPATH')) {
    exit;
}
$is_en = sta_is_en();
$is_nn = sta_is_nn();
$current_url = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '/';

$current_lang_label = 'Norsk bokmål';
if ($is_en) {
    $current_lang_label = 'English';
} elseif ($is_nn) {
    $current_lang_label = 'Norsk nynorsk';
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <!-- Favicon & App Icons matching stastudent.no -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/favicon-32x32.png'); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/favicon-192x192.png'); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/apple-touch-icon.png'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/favicon.svg'); ?>">
    <link rel="shortcut icon" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/favicon.ico'); ?>">
    <?php if (empty($_GET['elementor-preview'])): ?>
    <script>window.elementorFrontendConfig = window.elementorFrontendConfig || {environmentMode:{edit:false,wpPreview:false,isScriptDebug:false},i18n:{},is_rtl:false,breakpoints:{xs:0,sm:480,md:768,lg:1025,xl:1440,xxl:1600},version:"3.25.0",is_static:false,experimentalFeatures:{},urls:{assets:""},settings:{page:[],editorPreferences:[]},kit:{},post:{id:0,title:"",excerpt:""}};</script>
    <?php endif; ?>
    <?php wp_head(); ?>
    <style>
        html, body, body.custom-background, body[class*="elementor-kit-"], #page, #content, #main-content, .sta-site-main {
            background-color: #0e4c47 !important;
            background-image: none !important;
            color: #ffffff !important;
        }
        #trp-floater-ls, .trp-language-switcher-container {
            display: none !important;
        }
    </style>
</head>
<body <?php body_class(); ?> style="background-color:#0e4c47; color:#ffffff; min-height:100vh;">
<?php wp_body_open(); ?>

<a class="sta-skiplink" href="#main-content"><?php echo esc_html(sta_t('Hopp til hovedinnhold', 'Skip to main content', 'Hopp til hovudinnhald')); ?></a>

<header class="sta-header" role="banner">
    <div class="sta-container">
        <div class="sta-header-inner">
            <!-- Brand Logo -->
            <a href="<?php echo esc_url(sta_get_localized_url('/')); ?>" class="sta-logo-link" rel="home" aria-label="<?php echo esc_attr(sta_t('Studentorganisasjonen i Agder - Til forsiden', 'Student Organisation of Agder - Home', 'Studentorganisasjonen i Agder - Til heimesida')); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/sta-logo.svg'); ?>" 
                     alt="<?php echo esc_attr(sta_t('Studentorganisasjonen i Agder (STA)', 'Student Organisation of Agder (STA)', 'Studentorganisasjonen i Agder (STA)')); ?>" 
                     class="sta-header-logo" 
                     height="42" 
                     width="204">
            </a>

            <!-- Desktop Navigation -->
            <nav class="sta-desktop-nav" role="navigation" aria-label="<?php echo esc_attr(sta_t('Hovedmeny', 'Main navigation', 'Hovudmeny')); ?>">
                <a href="<?php echo esc_url(sta_get_localized_url('/')); ?>" class="sta-nav-link <?php echo (is_front_page() && empty($_GET['sta_page'])) ? 'is-active' : ''; ?>">
                    <?php echo esc_html(sta_t('Hjem', 'Home', 'Heim')); ?>
                </a>

                <a href="<?php echo esc_url(sta_get_localized_url('/rad-styrer-og-utvalg')); ?>" class="sta-nav-link <?php echo strpos($current_url, 'rad-styrer-og-utvalg') !== false ? 'is-active' : ''; ?>">
                    <?php echo esc_html(sta_t('Verv', 'Get involved', 'Verv')); ?>
                </a>

                <a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" class="sta-nav-link <?php echo strpos($current_url, 'dokumenter') !== false ? 'is-active' : ''; ?>">
                    <?php echo esc_html(sta_t('Dokumenter', 'Documents', 'Dokument')); ?>
                </a>

                <a href="<?php echo esc_url(sta_get_localized_url('/for-tillitsvalgte')); ?>" class="sta-nav-link <?php echo strpos($current_url, 'for-tillitsvalgte') !== false ? 'is-active' : ''; ?>">
                    <?php echo esc_html(sta_t('Tillitsvalgt', 'Student representatives', 'Tillitsvald')); ?>
                </a>

                <!-- Om Oss Dropdown with Fixed Hover Buffer -->
                <div class="sta-dropdown-container">
                    <button type="button" class="sta-nav-link sta-dropdown-toggle" aria-expanded="false" aria-haspopup="true">
                        <span><?php echo esc_html(sta_t('Om Oss', 'About', 'Om oss')); ?></span>
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="sta-dropdown-menu" role="menu">
                        <a href="<?php echo esc_url(sta_get_localized_url('/sta-styret')); ?>" class="sta-dropdown-item" role="menuitem">
                            <span class="sta-dropdown-title"><?php echo esc_html(sta_t('STA-Styret', 'The STA-board', 'STA-styret')); ?></span>
                            <span class="sta-dropdown-desc"><?php echo esc_html(sta_t('Det daglige styret i studentorganisasjonen', 'The daily board of the student organisation', 'Det daglege styret i studentorganisasjonen')); ?></span>
                        </a>
                        <a href="<?php echo esc_url(sta_get_localized_url('/studentparlamentet')); ?>" class="sta-dropdown-item" role="menuitem">
                            <span class="sta-dropdown-title"><?php echo esc_html(sta_t('Studentparlamentet', 'The Student Parliament', 'Studentparlamentet')); ?></span>
                            <span class="sta-dropdown-desc"><?php echo esc_html(sta_t('UiAs øverste studentdemokratiske organ', "UiA's highest student democratic body", 'Det øvste studentdemokratiske organet ved UiA')); ?></span>
                        </a>
                        <a href="<?php echo esc_url(sta_get_localized_url('/linjeforeninger')); ?>" class="sta-dropdown-item" role="menuitem">
                            <span class="sta-dropdown-title"><?php echo esc_html(sta_t('Linjeforeninger', 'Academic associations', 'Linjeforeiningar')); ?></span>
                            <span class="sta-dropdown-desc"><?php echo esc_html(sta_t('Fag- og linjeforeninger i Kristiansand og Grimstad', 'Student associations at UiA campuses', 'Fag- og linjeforeiningar i Kristiansand og Grimstad')); ?></span>
                        </a>
                        <a href="<?php echo esc_url(sta_get_localized_url('/studentaktiviteter')); ?>" class="sta-dropdown-item" role="menuitem">
                            <span class="sta-dropdown-title"><?php echo esc_html(sta_t('Studentaktiviteter', 'Student Activities', 'Studentaktivitetar')); ?></span>
                            <span class="sta-dropdown-desc"><?php echo esc_html(sta_t('Interesseforeninger, idrett og kultur', 'Sports, culture and student organisations', 'Interesseforeiningar, idrett og kultur')); ?></span>
                        </a>
                        <a href="<?php echo esc_url(sta_get_localized_url('/lokaler')); ?>" class="sta-dropdown-item" role="menuitem">
                            <span class="sta-dropdown-title"><?php echo esc_html(sta_t('Lokaler', 'Our premises', 'Lokale')); ?></span>
                            <span class="sta-dropdown-desc"><?php echo esc_html(sta_t('Alibiet og Bluebox – lokaler for studenter', 'Alibiet and Bluebox – student venues', 'Alibiet og Bluebox – lokale for studentar')); ?></span>
                        </a>
                        <a href="<?php echo esc_url(sta_get_localized_url('/irene-bredals-hederspris')); ?>" class="sta-dropdown-item" role="menuitem">
                            <span class="sta-dropdown-title"><?php echo esc_html(sta_t('Irene Bredals Hederspris', 'Irene Bredals Hederspris', 'Irene Bredals Heiderspris')); ?></span>
                            <span class="sta-dropdown-desc"><?php echo esc_html(sta_t('Ærespris for ekstraordinært studentengasjement', 'Award for extraordinary student engagement', 'Heiderspris for ekstraordinært studentengasjement')); ?></span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Right Controls: Search Icon, 3-Language Selector & Mobile Menu Button -->
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <!-- Search Button -->
                <button type="button" id="sta-search-trigger" class="sta-search-btn" aria-label="<?php echo esc_attr(sta_t('Åpne søk', 'Open search', 'Opna søk')); ?>" style="background:transparent; border:none; color:#fff; cursor:pointer; padding:0.5rem; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>

                <!-- Language Switcher Dropdown (Desktop: Bokmål, Nynorsk, English) -->
                <div class="sta-dropdown-container sta-lang-dropdown" style="position:relative;">
                    <button type="button" class="sta-lang-btn sta-dropdown-toggle" aria-expanded="false" aria-haspopup="true" aria-label="<?php echo esc_attr(sta_t('Bytt språk', 'Switch language', 'Byt språk')); ?>" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.35rem 0.85rem; border-radius:9999px; background:rgba(9,50,47,0.7); border:1px solid rgba(158,207,201,0.25); color:#fff; font-size:0.75rem; font-weight:700; cursor:pointer;">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                        <span><?php echo esc_html($current_lang_label); ?></span>
                        <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="sta-dropdown-menu" role="menu" style="min-width:160px; right:0; left:auto; padding:0.5rem 0;">
                        <a href="<?php echo esc_url(sta_get_language_url('no')); ?>" class="sta-dropdown-item sta-lang-option <?php echo sta_is_no() ? 'is-active' : ''; ?>" data-lang="no" role="menuitem" style="padding:0.5rem 1rem; display:flex; align-items:center; justify-content:space-between; font-size:0.85rem; text-decoration:none;">
                            <span>Norsk bokmål</span>
                            <?php if (sta_is_no()): ?><svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg><?php endif; ?>
                        </a>
                        <a href="<?php echo esc_url(sta_get_language_url('nn')); ?>" class="sta-dropdown-item sta-lang-option <?php echo sta_is_nn() ? 'is-active' : ''; ?>" data-lang="nn" role="menuitem" style="padding:0.5rem 1rem; display:flex; align-items:center; justify-content:space-between; font-size:0.85rem; text-decoration:none;">
                            <span>Norsk nynorsk</span>
                            <?php if (sta_is_nn()): ?><svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg><?php endif; ?>
                        </a>
                        <a href="<?php echo esc_url(sta_get_language_url('en')); ?>" class="sta-dropdown-item sta-lang-option <?php echo sta_is_en() ? 'is-active' : ''; ?>" data-lang="en" role="menuitem" style="padding:0.5rem 1rem; display:flex; align-items:center; justify-content:space-between; font-size:0.85rem; text-decoration:none;">
                            <span>English</span>
                            <?php if (sta_is_en()): ?><svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg><?php endif; ?>
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Toggle Button -->
                <button type="button" id="sta-mobile-menu-trigger" class="sta-mobile-menu-toggle" aria-expanded="false" aria-controls="sta-mobile-drawer" aria-label="<?php echo esc_attr(sta_t('Åpne meny', 'Open menu', 'Opna meny')); ?>">
                    <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
            </div>
        </div>
    </div>
</header>

    <!-- Search Modal -->
    <div id="sta-search-modal" class="sta-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="sta-modal-title">
        <div class="sta-modal-box">
            <div class="sta-modal-header">
                <h3 id="sta-modal-title" class="sta-modal-title">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <span><?php echo esc_html(sta_t('Søk på stastudent.no', 'Search stastudent.no', 'Søk på stastudent.no')); ?></span>
                </h3>
                <button type="button" id="sta-modal-close-btn" class="sta-modal-close" aria-label="<?php echo esc_attr(sta_t('Lukk søk', 'Close search', 'Lukk søk')); ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form action="<?php echo esc_url(home_url('/')); ?>" method="get" class="sta-modal-search-form">
                <input type="search" name="s" id="sta-modal-input" class="sta-modal-input" placeholder="<?php echo esc_attr(sta_t('Hva leter du etter? (f.eks. verv, vedtekter, linjeforening...)', 'What are you looking for? (e.g. positions, statutes, associations...)', 'Kva leitar du etter? (t.d. verv, vedtekter, linjeforeining...)')); ?>">
                <?php if ($is_en): ?><input type="hidden" name="lang" value="en"><?php elseif ($is_nn): ?><input type="hidden" name="lang" value="nn"><?php endif; ?>
                <button type="submit" class="sta-modal-submit"><?php echo esc_html(sta_t('Søk', 'Search', 'Søk')); ?></button>
            </form>
        </div>
    </div>

    <!-- Mobile Navigation Drawer & Backdrop -->
    <div id="sta-mobile-backdrop" class="sta-mobile-backdrop" aria-hidden="true"></div>
    <div id="sta-mobile-drawer" class="sta-mobile-drawer" role="dialog" aria-modal="true">
        <div class="sta-mobile-drawer-header">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/sta-logo.svg'); ?>" 
                 alt="STA" 
                 height="34" 
                 width="165"
                 style="height:34px; width:auto;">
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <!-- Mobile 3-Language Segment Control -->
                <div style="display:inline-flex; align-items:center; background:rgba(9,50,47,0.85); border:1px solid rgba(158,207,201,0.25); border-radius:9999px; padding:2px;">
                    <a href="<?php echo esc_url(sta_get_language_url('no')); ?>" class="sta-lang-option" data-lang="no" style="display:inline-flex; align-items:center; justify-content:center; padding:0.3rem 0.55rem; border-radius:9999px; font-size:0.75rem; font-weight:700; text-decoration:none; <?php echo sta_is_no() ? 'background:var(--sta-orange); color:#fff;' : 'color:var(--sta-teal-100);'; ?>" aria-label="Norsk bokmål">
                        NB
                    </a>
                    <a href="<?php echo esc_url(sta_get_language_url('nn')); ?>" class="sta-lang-option" data-lang="nn" style="display:inline-flex; align-items:center; justify-content:center; padding:0.3rem 0.55rem; border-radius:9999px; font-size:0.75rem; font-weight:700; text-decoration:none; <?php echo sta_is_nn() ? 'background:var(--sta-orange); color:#fff;' : 'color:var(--sta-teal-100);'; ?>" aria-label="Norsk nynorsk">
                        NN
                    </a>
                    <a href="<?php echo esc_url(sta_get_language_url('en')); ?>" class="sta-lang-option" data-lang="en" style="display:inline-flex; align-items:center; justify-content:center; padding:0.35rem 0.55rem; border-radius:9999px; font-size:0.75rem; font-weight:700; text-decoration:none; <?php echo sta_is_en() ? 'background:var(--sta-orange); color:#fff;' : 'color:var(--sta-teal-100);'; ?>" aria-label="English">
                        EN
                    </a>
                </div>
                <button type="button" id="sta-mobile-drawer-close" class="sta-mobile-drawer-close" aria-label="<?php echo esc_attr(sta_t('Lukk meny', 'Close menu', 'Lukk meny')); ?>">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>

        <nav class="sta-mobile-nav" role="navigation">
            <ul class="sta-mobile-nav-list">
                <li class="sta-mobile-nav-item">
                    <a href="<?php echo esc_url(sta_get_localized_url('/')); ?>" class="sta-mobile-nav-link">
                        <?php echo esc_html(sta_t('Hjem', 'Home', 'Heim')); ?>
                    </a>
                </li>
                <li class="sta-mobile-nav-item">
                    <a href="<?php echo esc_url(sta_get_localized_url('/rad-styrer-og-utvalg')); ?>" class="sta-mobile-nav-link">
                        <?php echo esc_html(sta_t('Verv', 'Get involved', 'Verv')); ?>
                    </a>
                </li>
                <li class="sta-mobile-nav-item">
                    <a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" class="sta-mobile-nav-link">
                        <?php echo esc_html(sta_t('Dokumenter', 'Documents', 'Dokument')); ?>
                    </a>
                </li>
                <li class="sta-mobile-nav-item">
                    <a href="<?php echo esc_url(sta_get_localized_url('/for-tillitsvalgte')); ?>" class="sta-mobile-nav-link">
                        <?php echo esc_html(sta_t('Tillitsvalgt', 'Student representatives', 'Tillitsvald')); ?>
                    </a>
                </li>
                <li class="sta-mobile-nav-item">
                    <button type="button" class="sta-mobile-nav-link sta-mobile-accordion-toggle" style="width:100%; background:none; border:none; text-align:left; cursor:pointer;" aria-expanded="false">
                        <span><?php echo esc_html(sta_t('Om Oss', 'About', 'Om oss')); ?></span>
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="sta-chevron" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <ul class="sta-mobile-subnav" style="display:none;">
                        <li><a href="<?php echo esc_url(sta_get_localized_url('/sta-styret')); ?>" class="sta-mobile-sublink"><?php echo esc_html(sta_t('STA-Styret', 'The STA-board', 'STA-styret')); ?></a></li>
                        <li><a href="<?php echo esc_url(sta_get_localized_url('/studentparlamentet')); ?>" class="sta-mobile-sublink"><?php echo esc_html(sta_t('Studentparlamentet', 'The Student Parliament', 'Studentparlamentet')); ?></a></li>
                        <li><a href="<?php echo esc_url(sta_get_localized_url('/linjeforeninger')); ?>" class="sta-mobile-sublink"><?php echo esc_html(sta_t('Linjeforeninger', 'Academic associations', 'Linjeforeiningar')); ?></a></li>
                        <li><a href="<?php echo esc_url(sta_get_localized_url('/studentaktiviteter')); ?>" class="sta-mobile-sublink"><?php echo esc_html(sta_t('Studentaktiviteter', 'Student Activities', 'Studentaktivitetar')); ?></a></li>
                        <li><a href="<?php echo esc_url(sta_get_localized_url('/lokaler')); ?>" class="sta-mobile-sublink"><?php echo esc_html(sta_t('Lokaler', 'Our premises', 'Lokale')); ?></a></li>
                        <li><a href="<?php echo esc_url(sta_get_localized_url('/irene-bredals-hederspris')); ?>" class="sta-mobile-sublink"><?php echo esc_html(sta_t('Irene Bredals Hederspris', 'Irene Bredals Hederspris', 'Irene Bredals Heiderspris')); ?></a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

<main id="main-content" class="sta-site-main">
