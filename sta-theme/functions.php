<?php
/**
 * STA Theme Functions & Definitions
 */

if (!defined('ABSPATH')) {
    exit;
}

define('STA_THEME_VERSION', '1.2.9');
define('STA_THEME_PATH', get_template_directory());
define('STA_THEME_URL', get_template_directory_uri());

// Include helper modules
require_once STA_THEME_PATH . '/inc/translatepress-compat.php';
require_once STA_THEME_PATH . '/inc/template-tags.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function sta_theme_setup() {
    load_theme_textdomain('sta-theme', STA_THEME_PATH . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Custom image sizes for fast delivery
    add_image_size('sta-card', 600, 400, true);
    add_image_size('sta-hero', 1200, 600, true);
    add_image_size('sta-board', 1024, 683, true);

    // Register primary navigation menus
    register_nav_menus(array(
        'primary-menu'      => __('Hovedmeny (Desktop)', 'sta-theme'),
        'mobile-menu'       => __('Mobilmeny', 'sta-theme'),
        'footer-org'        => __('Bunntekst: Organisasjon', 'sta-theme'),
        'footer-resources'  => __('Bunntekst: Ressurser', 'sta-theme'),
        'footer-guidelines' => __('Bunntekst: Retningslinjer', 'sta-theme'),
    ));
}
add_action('after_setup_theme', 'sta_theme_setup');

/**
 * Enqueue scripts and styles.
 */
function sta_theme_scripts() {
    // Main compiled design stylesheet
    wp_enqueue_style('sta-main-style', STA_THEME_URL . '/assets/css/main.css', array(), STA_THEME_VERSION);

    // Accessible navigation script
    wp_enqueue_script('sta-navigation', STA_THEME_URL . '/assets/js/navigation.js', array(), STA_THEME_VERSION, true);

    // Directory live filter script (for associations and activities)
    $sta_page = isset($_GET['sta_page']) ? sanitize_title(wp_unslash($_GET['sta_page'])) : '';
    if (is_page_template(array('page-linjeforeninger.php', 'page-studentaktiviteter.php')) || is_page(array('linjeforeninger', 'studentaktiviteter')) || in_array($sta_page, array('linjeforeninger', 'studentaktiviteter'), true)) {
        wp_enqueue_script('sta-directory-filter', STA_THEME_URL . '/assets/js/directory-filter.js', array(), STA_THEME_VERSION, true);
    }
}
add_action('wp_enqueue_scripts', 'sta_theme_scripts', 999);
add_action('elementor/preview/enqueue_styles', function() {
    wp_enqueue_style('sta-main-style', STA_THEME_URL . '/assets/css/main.css', array(), STA_THEME_VERSION);
}, 999);

/**
 * Set news archive post query to 10 articles per page (matching reference 19 pages)
 */
function sta_adjust_news_archive_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_home() || $query->is_category('nyheter') || $query->is_category(3)) {
            $query->set('posts_per_page', 10);
        }
    }
}
add_action('pre_get_posts', 'sta_adjust_news_archive_query');

/**
 * Automatically ensure all STA pages and custom templates exist in WordPress
 * so the site works out-of-the-box without manual page recreation.
 */
function sta_auto_ensure_pages() {
    if (get_option('sta_theme_pages_seeded_v7') === 'yes') {
        return;
    }

    $pages_to_ensure = array(
        'forside'                  => array('title' => 'Forside',                  'template' => 'front-page.php',                  'is_front' => true),
        'sta-styret'               => array('title' => 'STA-styret',               'template' => 'page-sta-styret.php'),
        'studentparlamentet'       => array('title' => 'Studentparlamentet',       'template' => 'page-studentparlamentet.php'),
        'ledige-verv'              => array('title' => 'Ledige verv',              'template' => 'page-ledige-verv.php'),
        'linjeforeninger'          => array('title' => 'Linjeforeninger',          'template' => 'page-linjeforeninger.php'),
        'studentaktiviteter'       => array('title' => 'Studentaktiviteter',       'template' => 'page-studentaktiviteter.php'),
        'lokaler'                  => array('title' => 'Lokaler',                  'template' => 'page-lokaler.php'),
        'dokumenter'               => array('title' => 'Dokumenter',               'template' => 'page-dokumenter.php'),
        'sakspapirer'              => array('title' => 'Sakspapirer til møter i studentparlamentet', 'template' => 'page-sakspapirer.php'),
        'referat-fra-moter-i-studentparlamentet' => array('title' => 'Protokoller fra møter i Studentparlamentet', 'template' => 'page-referat-fra-moter-i-studentparlamentet.php'),
        'for-tillitsvalgte'        => array('title' => 'For tillitsvalgte',        'template' => 'page-for-tillitsvalgte.php'),
        'rad-styrer-og-utvalg'     => array('title' => 'Råd, styrer og utvalg',    'template' => 'page-rad-styrer-og-utvalg.php'),
        'valgkomiteen'             => array('title' => 'Valgkomitéen',             'template' => 'page-valgkomiteen.php'),
        'trygg-organisasjon'       => array('title' => 'Trygg Organisasjon',       'template' => 'page-trygg-organisasjon.php'),
        'irene-bredals-hederspris' => array('title' => 'Irene Bredals Hederspris', 'template' => 'page-irene-bredals-hederspris.php'),
        'prinsipprogram'           => array('title' => 'Prinsipprogram',           'template' => 'page-prinsipprogram.php'),
        'personvernserklaering'    => array('title' => 'Personvernserklæring',     'template' => 'page-personvernserklaering.php'),
        'om-sta'                   => array('title' => 'Om STA',                   'template' => 'page.php'),
        'engasjer-deg'             => array('title' => 'Engasjer deg',             'template' => 'page.php'),
        'for-foreninger'           => array('title' => 'For foreninger',           'template' => 'page.php'),
    );

    foreach ($pages_to_ensure as $slug => $data) {
        $existing = get_page_by_path($slug, OBJECT, 'page');
        if (!$existing) {
            $posts = get_posts(array(
                'name'        => $slug,
                'post_type'   => 'page',
                'post_status' => 'publish',
                'numberposts' => 1,
            ));
            if (!empty($posts)) {
                $existing = $posts[0];
                if ($existing->post_parent != 0) {
                    wp_update_post(array('ID' => $existing->ID, 'post_parent' => 0));
                }
            }
        }

        if ($existing) {
            $page_id = $existing->ID;
        } else {
            $page_id = wp_insert_post(array(
                'post_name'    => $slug,
                'post_title'   => $data['title'],
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_content' => '',
                'post_parent'  => 0,
            ));
        }

        if ($page_id && !is_wp_error($page_id) && !empty($data['template'])) {
            update_post_meta($page_id, '_wp_page_template', $data['template']);
        }

        if ($page_id && !is_wp_error($page_id) && !empty($data['is_front'])) {
            if (get_option('show_on_front') !== 'page' || !get_option('page_on_front')) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
        }
    }

    // Remove the square OpenGraph logo (11946 / 11945) from page _thumbnail_id so it never renders as a giant banner in Elementor
    $all_pages = get_pages(array('post_status' => 'any'));
    foreach ($all_pages as $p) {
        $thumb_id = (int) get_post_thumbnail_id($p->ID);
        if ($thumb_id === 11946 || $thumb_id === 11945) {
            delete_post_thumbnail($p->ID);
        }
    }

    update_option('sta_theme_pages_seeded_v7', 'yes');
    flush_rewrite_rules();
}
add_action('init', 'sta_auto_ensure_pages', 20);
add_action('after_switch_theme', function () {
    delete_option('sta_theme_pages_seeded_v6');
    sta_auto_ensure_pages();
});

/**
 * Force STA Theme templates at highest priority (9999999) so page builders
 * (such as Elementor's header-footer template) on existing sites never replace
 * our custom PHP templates with old page-builder content.
 *
 * Ensures full compatibility with Elementor:
 * - If Elementor editor/preview is active, loads front-page.php or page.php.
 * - If a subpage has been built or edited with Elementor, loads page.php so the_content() renders.
 * - Otherwise, loads our custom PHP templates.
 */
function sta_force_theme_templates($template) {
    $is_elementor_active = !empty($_GET['elementor-preview']) 
        || !empty($_GET['preview']) 
        || (isset($_GET['action']) && $_GET['action'] === 'elementor')
        || (class_exists('\Elementor\Plugin') && (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()));

    $front_id = (int) get_option('page_on_front');

    // 0. If Elementor editor/preview is active
    if ($is_elementor_active) {
        $preview_id = !empty($_GET['elementor-preview']) ? intval($_GET['elementor-preview']) : (!empty($_GET['page_id']) ? intval($_GET['page_id']) : (!empty($_GET['p']) ? intval($_GET['p']) : get_queried_object_id()));
        if ($preview_id === $front_id) {
            $front = STA_THEME_PATH . '/front-page.php';
            if (file_exists($front)) {
                return $front;
            }
        }
        $default_page = STA_THEME_PATH . '/page.php';
        if (file_exists($default_page)) {
            return $default_page;
        }
    }

    // 1. Handle ?sta_page=... routing (used on WP Staging subdirectories without Nginx/Apache rewrite rules)
    if (!empty($_GET['sta_page'])) {
        $raw_sta_page = trim(wp_unslash($_GET['sta_page']), '/');
        $slug = sanitize_title(basename($raw_sta_page));
        status_header(200);
        global $wp_query;
        if ($wp_query) {
            $wp_query->is_404 = false;
        }

        if ($slug === 'forside' || $slug === 'studentorganisasjonen-i-agder-2') {
            $front = STA_THEME_PATH . '/front-page.php';
            if (file_exists($front)) {
                return $front;
            }
        }

        if ($slug === 'nyheter' || $slug === 'siste-nytt' || $slug === 'aktuelt') {
            $index_tpl = STA_THEME_PATH . '/index.php';
            if (file_exists($index_tpl)) {
                return $index_tpl;
            }
        }

        if ($slug === 'resolusjoner') {
            $docs_tpl = STA_THEME_PATH . '/page-dokumenter.php';
            if (file_exists($docs_tpl)) {
                return $docs_tpl;
            }
        }

        // If this page was built or edited in Elementor, route to page.php so the_content() is rendered!
        $page_obj = get_page_by_path($slug, OBJECT, 'page');
        $is_built_with_elementor = false;
        if ($page_obj) {
            if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->db->is_built_with_elementor($page_obj->ID)) {
                $is_built_with_elementor = true;
            } elseif (get_post_meta($page_obj->ID, '_elementor_edit_mode', true) === 'builder') {
                $is_built_with_elementor = true;
            }
        }

        if ($is_built_with_elementor) {
            $default_page = STA_THEME_PATH . '/page.php';
            if (file_exists($default_page)) {
                return $default_page;
            }
        }

        $slug_template = STA_THEME_PATH . '/page-' . $slug . '.php';
        if (file_exists($slug_template)) {
            return $slug_template;
        }

        $default_page = STA_THEME_PATH . '/page.php';
        if (file_exists($default_page)) {
            return $default_page;
        }
    }

    if (is_singular('post')) {
        $single = STA_THEME_PATH . '/single.php';
        if (file_exists($single)) {
            return $single;
        }
    }

    if (is_search()) {
        $search = STA_THEME_PATH . '/search.php';
        if (file_exists($search)) {
            return $search;
        }
    }

    if (is_front_page()) {
        $post_id = get_queried_object_id();
        if (!$post_id || $post_id === $front_id) {
            $front = STA_THEME_PATH . '/front-page.php';
            if (file_exists($front)) {
                return $front;
            }
        }
    }

    if (is_page()) {
        $post_id = get_queried_object_id();
        
        // If this page has been built or edited in Elementor, route to page.php so the_content() is rendered!
        $is_built_with_elementor = false;
        if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->db->is_built_with_elementor($post_id)) {
            $is_built_with_elementor = true;
        } elseif (get_post_meta($post_id, '_elementor_edit_mode', true) === 'builder') {
            $is_built_with_elementor = true;
        }

        if ($is_built_with_elementor) {
            $default_page = STA_THEME_PATH . '/page.php';
            if (file_exists($default_page)) {
                return $default_page;
            }
        }

        $post_slug = $post_id ? get_post_field('post_name', $post_id) : '';
        if ($post_slug) {
            $slug_template = STA_THEME_PATH . '/page-' . $post_slug . '.php';
            if (file_exists($slug_template)) {
                return $slug_template;
            }
        }
        $default_page = STA_THEME_PATH . '/page.php';
        if (file_exists($default_page)) {
            return $default_page;
        }
    }

    return $template;
}
add_filter('template_include', 'sta_force_theme_templates', 9999999);

/**
 * Safeguard: Prevent WP-Optimize, Autoptimize, or other minification tools from
 * deferring jQuery and Elementor core scripts, which causes the Elementor editor preview
 * handshake to fail or hang indefinitely.
 */
add_filter('script_loader_tag', function($tag, $handle, $src) {
    if (in_array($handle, array('jquery', 'jquery-core', 'jquery-migrate'), true) || strpos($handle, 'elementor') !== false) {
        // Strip defer and async attributes so critical scripts execute synchronously
        $tag = preg_replace('/\s+(defer|async)(="[^"]*"|=\'[^\']*\'|(?=[\s>]))/i', '', $tag);
    }
    return $tag;
}, 99999, 3);

/**
 * Automatically ensure WP-Optimize has defer_jquery turned off and .htaccess is configured for staging.
 */
add_action('admin_init', function() {
    $wpo_minify = get_option('wp_optimize_minify_settings', null);
    if (is_array($wpo_minify) && !empty($wpo_minify['defer_jquery'])) {
        $wpo_minify['defer_jquery'] = false;
        update_option('wp_optimize_minify_settings', $wpo_minify);
    }

    // Try to ensure .htaccess has proper RewriteBase /reliant/ if writable
    $htaccess_file = ABSPATH . '.htaccess';
    if (is_writable($htaccess_file) && function_exists('insert_with_markers')) {
        $marker = 'WordPress';
        $rules = array(
            'RewriteEngine On',
            'RewriteBase /reliant/',
            'RewriteRule ^index\.php$ - [L]',
            'RewriteCond %{REQUEST_FILENAME} !-f',
            'RewriteCond %{REQUEST_FILENAME} !-d',
            'RewriteRule . /reliant/index.php [L]',
        );
        insert_with_markers($htaccess_file, $marker, $rules);
    }
});

/**
 * Shortcode to render 3 latest news articles matching the approved homepage design
 * Usage: [sta_frontpage_news count="3"]
 */
function sta_render_frontpage_news_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 3
    ), $atts);
    $count = intval($atts['count']);
    if ($count < 1) $count = 3;

    ob_start();
    $latest_news_query = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    ));

    $news_items = array();
    if ($latest_news_query->have_posts()) {
        while ($latest_news_query->have_posts()) {
            $latest_news_query->the_post();
            if (get_post_field('post_name', get_the_ID()) === 'hello-world') {
                continue;
            }
            $cats = get_the_category();
            $fallback_cat = sta_is_en() ? 'News' : (sta_is_nn() ? 'Nyheiter' : 'Nyheter');
            $cat_name = (!empty($cats) && $cats[0]->name !== 'Uncategorized') ? $cats[0]->name : $fallback_cat;
            $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
            if (!$thumb) {
                $thumb = 'https://stastudent.no/wp-content/uploads/2025/11/IMG_4781_CROP-scaled.jpg';
            }
            $news_items[] = array(
                'title'        => get_the_title(),
                'url'          => get_permalink(),
                'date'         => sta_format_date(),
                'category'     => $cat_name,
                'excerpt'      => get_the_excerpt(),
                'thumbnail'    => $thumb,
            );
        }
        wp_reset_postdata();
    }

    if (empty($news_items)) {
        $news_file = sta_get_data_file('news_archive.json');
        if ($news_file && file_exists($news_file)) {
            $raw = json_decode(file_get_contents($news_file), true);
            if (is_array($raw)) {
                $slice = array_slice($raw, 0, $count);
                $fallback_cat = sta_is_en() ? 'News' : (sta_is_nn() ? 'Nyheiter' : 'Nyheter');
                foreach ($slice as $n) {
                    $news_items[] = array(
                        'title'     => $n['title'] ?? '',
                        'url'       => sta_get_localized_url('/nyheter/' . ($n['slug'] ?? '')),
                        'date'      => sta_format_date($n['publishedAt'] ?? 'now'),
                        'category'  => $n['category'] ?? $fallback_cat,
                        'excerpt'   => $n['excerpt'] ?? '',
                        'thumbnail' => $n['imageUrl'] ?? 'https://stastudent.no/wp-content/uploads/2025/11/IMG_4781_CROP-scaled.jpg',
                    );
                }
            }
        }
    }
    ?>
    <div class="sta-grid-3">
        <?php foreach ($news_items as $article) : ?>
            <article class="sta-card" style="display:flex; flex-direction:column; overflow:hidden; border-radius:var(--sta-radius-md); background:var(--sta-card-bg); border:1px solid rgba(158,207,201,0.15); transition:transform 0.2s ease, box-shadow 0.2s ease;">
                <a href="<?php echo esc_url($article['url']); ?>" style="display:block; position:relative; aspect-ratio:16/9; overflow:hidden; background:var(--sta-teal-900);">
                    <img src="<?php echo esc_url($article['thumbnail']); ?>" alt="<?php echo esc_attr($article['title']); ?>" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                    <span style="position:absolute; top:0.75rem; left:0.75rem; background:var(--sta-orange); color:#fff; font-size:0.7rem; font-weight:700; text-transform:uppercase; padding:0.25rem 0.6rem; border-radius:4px; letter-spacing:0.05em;">
                        <?php echo esc_html($article['category']); ?>
                    </span>
                </a>
                <div style="padding:1.5rem; display:flex; flex-direction:column; flex:1; justify-content:space-between;">
                    <div>
                        <div style="font-size:0.8rem; color:var(--sta-teal-200); margin-bottom:0.6rem; display:flex; align-items:center; gap:0.35rem;">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <span><?php echo esc_html($article['date']); ?></span>
                        </div>
                        <h3 style="font-size:1.15rem; font-weight:700; margin:0 0 0.5rem; line-height:1.4;">
                            <a href="<?php echo esc_url($article['url']); ?>" style="color:#fff; text-decoration:none;">
                                <?php echo esc_html($article['title']); ?>
                            </a>
                        </h3>
                        <p style="font-size:0.85rem; color:var(--sta-teal-100); line-height:1.6; margin:0; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
                            <?php echo esc_html($article['excerpt']); ?>
                        </p>
                    </div>
                    <div style="margin-top:1.25rem; padding-top:1rem; border-top:1px solid rgba(158,207,201,0.2);">
                        <a href="<?php echo esc_url($article['url']); ?>" style="font-size:0.85rem; font-weight:700; color:var(--sta-orange); display:inline-flex; align-items:center; gap:0.25rem; text-decoration:none;">
                            <span><?php echo esc_html(sta_t('Les hele saken', 'Read full article', 'Les heile saka')); ?> &rarr;</span>
                        </a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('sta_frontpage_news', 'sta_render_frontpage_news_shortcode');

/**
 * Admin action hook to restore clean, native Elementor structure for the front page (Post 11503).
 * Accessible via /wp-admin/?sta_restore_homepage_elementor=1
 */
add_action('admin_init', function() {
    if (isset($_GET['sta_restore_homepage_elementor']) && current_user_can('manage_options')) {
        $post_id = 11503;
        $json_file = STA_THEME_PATH . '/inc/clean_elementor_homepage_data.json';
        if (!file_exists($json_file)) {
            wp_send_json_error(array('message' => 'Clean data JSON file not found at: ' . $json_file));
        }
        $raw = file_get_contents($json_file);
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            wp_send_json_error(array('message' => 'Invalid JSON in clean data file.'));
        }

        // Update postmeta
        update_post_meta($post_id, '_elementor_data', wp_slash(json_encode($data)));
        update_post_meta($post_id, '_elementor_edit_mode', 'builder');
        update_post_meta($post_id, '_elementor_template_type', 'wp-page');
        
        // Ensure post is published
        wp_update_post(array(
            'ID'          => $post_id,
            'post_status' => 'publish'
        ));

        // Trigger Elementor internal db and CSS cache update
        if (class_exists('\Elementor\Plugin')) {
            if (isset(\Elementor\Plugin::$instance->files_manager)) {
                \Elementor\Plugin::$instance->files_manager->clear_cache();
            }
            if (isset(\Elementor\Plugin::$instance->db)) {
                \Elementor\Plugin::$instance->db->save_editor($post_id, $data);
            }
        }

        // Clear WP-Optimize caches
        if (class_exists('WP_Optimize')) {
            do_action('wpo_purge_all_caches');
        }

        wp_send_json_success(array(
            'message' => 'Clean Elementor homepage structure restored successfully for Post ' . $post_id . '!',
            'sections' => count($data)
        ));
    }
});

/**
 * Admin action hook to restore clean, native Elementor structure for subpages.
 * Accessible via /wp-admin/?sta_restore_subpage_elementor=valgkomiteen (or dokumenter, prinsipprogram, all_pilot)
 */
add_action('admin_init', function() {
    if (isset($_GET['sta_restore_subpage_elementor']) && current_user_can('manage_options')) {
        $target = sanitize_text_field(wp_unslash($_GET['sta_restore_subpage_elementor']));
        
        $subpages = array(
            'valgkomiteen' => array(
                'slug'         => 'valgkomiteen',
                'file'         => 'clean_elementor_valgkomiteen_data.json',
                'title'        => 'Valgkomitéen',
                'preferred_id' => 14071,
            ),
            'dokumenter' => array(
                'slug'         => 'dokumenter',
                'file'         => 'clean_elementor_dokumenter_data.json',
                'title'        => 'Dokumenter',
                'preferred_id' => 163,
            ),
            'prinsipprogram' => array(
                'slug'         => 'prinsipprogram',
                'file'         => 'clean_elementor_prinsipprogram_data.json',
                'title'        => 'Prinsipprogram',
                'preferred_id' => 14055,
            ),
            'sta-styret' => array(
                'slug'         => 'sta-styret',
                'file'         => 'clean_elementor_sta_styret_data.json',
                'title'        => 'STA-Styret',
                'preferred_id' => 30,
            ),
            'rad-styrer-og-utvalg' => array(
                'slug'         => 'rad-styrer-og-utvalg',
                'file'         => 'clean_elementor_rad_styrer_og_utvalg_data.json',
                'title'        => 'Råd, Styrer og Utvalg',
                'preferred_id' => 325,
            ),
            'studentparlamentet' => array(
                'slug'         => 'studentparlamentet',
                'file'         => 'clean_elementor_studentparlamentet_data.json',
                'title'        => 'Studentparlamentet',
                'preferred_id' => 161,
            ),
            'ledige-verv' => array(
                'slug'         => 'ledige-verv',
                'file'         => 'clean_elementor_ledige_verv_data.json',
                'title'        => 'Ledige verv',
                'preferred_id' => 13129,
            ),
            'lokaler' => array(
                'slug'         => 'lokaler',
                'file'         => 'clean_elementor_lokaler_data.json',
                'title'        => 'Lokaler',
                'preferred_id' => 8218,
            ),
            'for-tillitsvalgte' => array(
                'slug'         => 'for-tillitsvalgte',
                'file'         => 'clean_elementor_for_tillitsvalgte_data.json',
                'title'        => 'For tillitsvalgte',
                'preferred_id' => 127,
            ),
            'trygg-organisasjon' => array(
                'slug'         => 'trygg-organisasjon',
                'file'         => 'clean_elementor_trygg_organisasjon_data.json',
                'title'        => 'Trygg Organisasjon',
                'preferred_id' => 13219,
            ),
            'irene-bredals-hederspris' => array(
                'slug'         => 'irene-bredals-hederspris',
                'file'         => 'clean_elementor_irene_bredals_hederspris_data.json',
                'title'        => 'Irene Bredals Hederspris',
                'preferred_id' => 16160,
            ),
        );

        $to_process = array();
        if ($target === 'all_pilot' || $target === 'pilot') {
            $to_process = array('valgkomiteen', 'dokumenter', 'prinsipprogram');
        } elseif ($target === 'batch2') {
            $to_process = array('sta-styret', 'rad-styrer-og-utvalg', 'studentparlamentet');
        } elseif ($target === 'batch3') {
            $to_process = array('ledige-verv', 'lokaler', 'for-tillitsvalgte', 'trygg-organisasjon', 'irene-bredals-hederspris');
        } elseif ($target === 'all') {
            $to_process = array_keys($subpages);
        } elseif (isset($subpages[$target])) {
            $to_process = array($target);
        } else {
            wp_send_json_error(array('message' => 'Invalid subpage key: ' . $target));
        }

        $results = array();
        foreach ($to_process as $key) {
            $conf = $subpages[$key];
            $json_file = STA_THEME_PATH . '/inc/' . $conf['file'];
            if (!file_exists($json_file)) {
                $results[$key] = array('status' => 'error', 'message' => 'File not found: ' . $conf['file']);
                continue;
            }
            $raw = file_get_contents($json_file);
            $data = json_decode($raw, true);
            if (!is_array($data)) {
                $results[$key] = array('status' => 'error', 'message' => 'Invalid JSON in ' . $conf['file']);
                continue;
            }

            // Find post ID
            $post_id = 0;
            if (!empty($conf['preferred_id'])) {
                $check_post = get_post($conf['preferred_id']);
                if ($check_post) {
                    $post_id = $conf['preferred_id'];
                }
            }
            if (!$post_id) {
                $page = get_page_by_path($conf['slug'], OBJECT, 'page');
                if ($page) {
                    $post_id = $page->ID;
                } else {
                    $pages = get_posts(array(
                        'name'        => $conf['slug'],
                        'post_type'   => 'page',
                        'post_status' => 'any',
                        'numberposts' => 1,
                    ));
                    if (!empty($pages)) {
                        $post_id = $pages[0]->ID;
                    }
                }
            }

            if (!$post_id) {
                $results[$key] = array('status' => 'error', 'message' => 'Post not found for slug ' . $conf['slug']);
                continue;
            }

            // Update postmeta
            update_post_meta($post_id, '_elementor_data', wp_slash(json_encode($data)));
            update_post_meta($post_id, '_elementor_edit_mode', 'builder');
            update_post_meta($post_id, '_elementor_template_type', 'wp-page');

            wp_update_post(array(
                'ID'          => $post_id,
                'post_status' => 'publish'
            ));

            if (class_exists('\Elementor\Plugin')) {
                if (isset(\Elementor\Plugin::$instance->files_manager)) {
                    \Elementor\Plugin::$instance->files_manager->clear_cache();
                }
                if (isset(\Elementor\Plugin::$instance->db)) {
                    \Elementor\Plugin::$instance->db->save_editor($post_id, $data);
                }
            }

            $results[$key] = array(
                'status'   => 'success',
                'post_id'  => $post_id,
                'sections' => count($data)
            );
        }

        if (class_exists('WP_Optimize')) {
            do_action('wpo_purge_all_caches');
        }

        wp_send_json_success(array(
            'message' => 'Clean Elementor structures restored successfully!',
            'results' => $results
        ));
    }
});

