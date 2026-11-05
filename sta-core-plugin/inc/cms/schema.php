<?php
/**
 * Content model for the STA website: every content type, its fields and its taxonomies.
 *
 * Everything an editor can change in wp-admin is described here. Registration, the edit forms,
 * validation, saving and the frontend output all read this one definition, so a new field only
 * has to be added in one place.
 *
 * Field types: text, textarea, email, tel, url, number, date, checkbox, select, editor (rich text),
 * image_url (media library picker that stores a URL), file (media library picker for documents),
 * members (people with a role in a group or committee).
 */

if (!defined('ABSPATH')) {
    exit;
}

const STA_CMS_EN_HELP = 'Valgfritt. Står feltet tomt, vises den norske teksten (eller oversettelsen i TranslatePress) på engelske sider.';

function sta_cms_types() {
    static $types = null;
    if ($types !== null) {
        return $types;
    }
    $types = array(
        'sta_association' => array(
            'plural'      => 'Linjeforeninger',
            'singular'    => 'linjeforening',
            'icon'        => 'dashicons-welcome-learn-more',
            'public'      => true,
            'rewrite'     => 'linjeforening',
            'title_label' => 'Navn på linjeforeningen',
            'thumbnail'   => 'Logo (brukes hvis feltet «Logo» under er tomt)',
            'pages'       => array('linjeforeninger'),
            'intro'       => 'Linjeforeningene vises på siden Linjeforeninger, sortert etter rekkefølge og navn.',
            'taxonomies'  => array('sta_faculty'),
            'fields'      => array(
                array('key' => '_sta_desc_no', 'label' => 'Beskrivelse', 'type' => 'textarea'),
                array('key' => '_sta_desc_en', 'label' => 'Beskrivelse på engelsk', 'type' => 'textarea', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_study_program', 'label' => 'Studieprogram', 'type' => 'text'),
                array('key' => '_sta_campus', 'label' => 'Campus', 'type' => 'select', 'options' => array('' => '—', 'Kristiansand' => 'Kristiansand', 'Grimstad' => 'Grimstad', 'Kristiansand og Grimstad' => 'Kristiansand og Grimstad')),
                array('key' => '_sta_logo_url', 'label' => 'Logo', 'type' => 'image_url'),
                array('key' => '_sta_email', 'label' => 'E-post', 'type' => 'email'),
                array('key' => '_sta_website', 'label' => 'Nettside', 'type' => 'url'),
                array('key' => '_sta_instagram', 'label' => 'Instagram', 'type' => 'url'),
                array('key' => '_sta_facebook', 'label' => 'Facebook', 'type' => 'url'),
                array('key' => '_sta_discord', 'label' => 'Discord', 'type' => 'url'),
            ),
        ),
        'sta_activity' => array(
            'plural'      => 'Studentaktiviteter',
            'singular'    => 'studentaktivitet',
            'icon'        => 'dashicons-buddicons-activity',
            'public'      => true,
            'rewrite'     => 'studentaktivitet',
            'title_label' => 'Navn på studentaktiviteten',
            'thumbnail'   => 'Bilde eller logo (brukes hvis feltet «Bilde/logo» under er tomt)',
            'pages'       => array('studentaktiviteter'),
            'intro'       => 'Studentaktivitetene vises på siden Studentaktiviteter, sortert etter rekkefølge og navn.',
            'taxonomies'  => array('sta_activity_category'),
            'fields'      => array(
                array('key' => '_sta_desc_no', 'label' => 'Beskrivelse', 'type' => 'textarea'),
                array('key' => '_sta_desc_en', 'label' => 'Beskrivelse på engelsk', 'type' => 'textarea', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_meeting_time', 'label' => 'Når (valgfritt)', 'type' => 'text', 'help' => 'F.eks. «Trening tirsdager kl. 18–20». Vises på kortet hvis det er fylt ut.'),
                array('key' => '_sta_meeting_time_en', 'label' => 'Når på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_location', 'label' => 'Hvor (valgfritt)', 'type' => 'text', 'help' => 'F.eks. «Spicheren, Kristiansand». Vises på kortet hvis det er fylt ut.'),
                array('key' => '_sta_image_url', 'label' => 'Bilde/logo', 'type' => 'image_url'),
                array('key' => '_sta_email', 'label' => 'E-post', 'type' => 'email'),
                array('key' => '_sta_website', 'label' => 'Nettside', 'type' => 'url'),
                array('key' => '_sta_instagram', 'label' => 'Instagram', 'type' => 'url'),
                array('key' => '_sta_facebook', 'label' => 'Facebook', 'type' => 'url'),
                array('key' => '_sta_discord', 'label' => 'Discord', 'type' => 'url'),
            ),
        ),
        'sta_board_member' => array(
            'plural'      => 'Personer',
            'singular'    => 'person',
            'icon'        => 'dashicons-admin-users',
            'title_label' => 'Fullt navn',
            'thumbnail'   => 'Portrettbilde',
            'pages'       => array('sta-styret', 'studentparlamentet'),
            'intro'       => 'En person vises på nettsiden når hen er lagt til i en publisert gruppe (under «Grupper»), f.eks. STA-styret og Sekretariatet. Samme person kan være med i flere grupper med ulik rolle.',
            'fields'      => array(
                array('key' => '_sta_role_no', 'label' => 'Rolle', 'type' => 'text', 'required' => true, 'help' => 'F.eks. «Studentleder». Brukes når gruppen ikke angir en egen rolle.'),
                array('key' => '_sta_role_en', 'label' => 'Rolle på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_responsibilities_no', 'label' => 'Hovedansvar', 'type' => 'textarea', 'help' => 'Ett punkt per linje.'),
                array('key' => '_sta_responsibilities_en', 'label' => 'Hovedansvar på engelsk', 'type' => 'textarea', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_email', 'label' => 'E-post', 'type' => 'email'),
                array('key' => '_sta_phone', 'label' => 'Telefon', 'type' => 'tel', 'help' => 'Skriv nummeret slik det skal vises, f.eks. +47 923 51 334.'),
            ),
        ),
        'sta_group' => array(
            'plural'      => 'Grupper',
            'singular'    => 'gruppe',
            'icon'        => 'dashicons-groups',
            'title_label' => 'Navn på gruppen',
            'pages'       => array('sta-styret', 'studentparlamentet'),
            'intro'       => 'En gruppe er en samling personer som vises sammen på nettsiden, f.eks. «STA-styret og Sekretariatet» eller «Ansvarlige for Studentparlamentet». Rekkefølgen på medlemmene her er rekkefølgen på nettsiden. Er gruppen et utkast, vises den ikke.',
            'fields'      => array(
                array('key' => '_sta_title_en', 'label' => 'Navn på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_members', 'label' => 'Medlemmer', 'type' => 'members', 'help' => 'Velg personer og eventuelt en egen rolle i denne gruppen. Står rollen tom, brukes rollen som er lagret på personen. Dra i ☰ for å endre rekkefølgen.'),
            ),
        ),
        'sta_parliament_rep' => array(
            'plural'      => 'Studentparlamentet',
            'singular'    => 'representant',
            'menu'        => 'Parlamentsrepresentanter',
            'icon'        => 'dashicons-megaphone',
            'title_label' => 'Navn på representanten (skriv «Ledig plass» hvis plassen er ledig)',
            'pages'       => array('studentparlamentet'),
            'intro'       => 'Representantene vises på siden Studentparlamentet, gruppert etter fakultet. Velg perioden de er valgt for; nettsiden viser perioden som er valgt under Perioder → «Vis på nettsiden». Gamle perioder kan beholdes som historikk.',
            'taxonomies'  => array('sta_faculty', 'sta_period'),
            'required_taxonomies' => array('sta_faculty', 'sta_period'),
            'fields'      => array(
                array('key' => '_sta_rep_type', 'label' => 'Type', 'type' => 'select', 'required' => true, 'options' => array('representative' => 'Fast representant', 'substitute' => 'Vararepresentant')),
                array('key' => '_sta_vara_number', 'label' => 'Varanummer', 'type' => 'number', 'help' => 'Kun for vararepresentanter: 1 = «1. vara», 2 = «2. vara» osv. Vararepresentantene sorteres etter dette nummeret.'),
                array('key' => '_sta_is_vacant', 'label' => 'Ledig plass', 'type' => 'checkbox', 'help' => 'Plassen er ledig. Nettsiden viser «Ledig verv – kontakt sta@uia.no» i stedet for navnet.'),
            ),
        ),
        'sta_position' => array(
            'plural'      => 'Verv og utvalg',
            'singular'    => 'verv/utvalg',
            'icon'        => 'dashicons-awards',
            'public'      => true,
            'rewrite'     => 'verv',
            'title_label' => 'Navn på vervet eller utvalget',
            'pages'       => array('rad-styrer-og-utvalg'),
            'intro'       => 'Vervene og utvalgene vises på siden «Råd, styrer og utvalg», gruppert etter utvalgsgruppe (f.eks. «STAs politiske utvalg»).',
            'taxonomies'  => array('sta_committee_group'),
            'required_taxonomies' => array('sta_committee_group'),
            'fields'      => array(
                array('key' => '_sta_title_en', 'label' => 'Navn på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_desc_no', 'label' => 'Kort beskrivelse', 'type' => 'textarea', 'required' => true, 'help' => 'Ett eller to setninger som vises på kortet.'),
                array('key' => '_sta_desc_en', 'label' => 'Kort beskrivelse på engelsk', 'type' => 'textarea', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_long_no', 'label' => 'Utfyllende tekst («Les mer»)', 'type' => 'editor', 'help' => 'Vises når man klikker «Les mer». La stå tom for å skjule «Les mer».'),
                array('key' => '_sta_long_en', 'label' => 'Utfyllende tekst på engelsk', 'type' => 'editor', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_link_url', 'label' => 'Lenke (valgfritt)', 'type' => 'url', 'help' => 'F.eks. til en egen side om utvalget. Vises nederst på kortet.'),
                array('key' => '_sta_link_label_no', 'label' => 'Lenketekst', 'type' => 'text', 'help' => 'F.eks. «Les mer om Studentparlamentet →».'),
                array('key' => '_sta_link_label_en', 'label' => 'Lenketekst på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_members', 'label' => 'Medlemmer (valgfritt)', 'type' => 'members', 'help' => 'Personer som sitter i vervet/utvalget. Lagres som kobling mellom personen og vervet.'),
            ),
        ),
        'sta_vacancy' => array(
            'plural'      => 'Ledige verv',
            'singular'    => 'ledig verv',
            'icon'        => 'dashicons-megaphone',
            'title_label' => 'Navn på vervet (f.eks. «Valgkomité»)',
            'pages'       => array('ledige-verv'),
            'intro'       => 'Ledige verv vises på siden Ledige verv, gruppert etter kategori. Når et verv er besatt: flytt det til papirkurven eller gjør det om til utkast.',
            'taxonomies'  => array('sta_vacancy_category'),
            'required_taxonomies' => array('sta_vacancy_category'),
            'fields'      => array(
                array('key' => '_sta_title_en', 'label' => 'Navn på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_seats_no', 'label' => 'Ledige plasser', 'type' => 'text', 'required' => true, 'help' => 'F.eks. «1., 2. og 3. vararepresentant» eller «1 fast plass».'),
                array('key' => '_sta_seats_en', 'label' => 'Ledige plasser på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_organ_no', 'label' => 'Organ', 'type' => 'text', 'required' => true, 'help' => 'Hvem vervet hører til, f.eks. «Studentorganisasjonen i Agder (STA)».'),
                array('key' => '_sta_organ_en', 'label' => 'Organ på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_eligibility_no', 'label' => 'Kvalifikasjonskrav', 'type' => 'text', 'required' => true, 'help' => 'F.eks. «Alle semesterregistrerte studenter ved UiA».'),
                array('key' => '_sta_eligibility_en', 'label' => 'Kvalifikasjonskrav på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_link_url', 'label' => 'Lenke (valgfritt)', 'type' => 'url', 'help' => 'Lenker til stastudent.no vises som knapp, lenker til andre nettsider som ekstern lenke.'),
                array('key' => '_sta_link_label_no', 'label' => 'Lenketekst', 'type' => 'text'),
                array('key' => '_sta_link_label_en', 'label' => 'Lenketekst på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
            ),
        ),
        'sta_document' => array(
            'plural'      => 'Dokumenter',
            'singular'    => 'dokument',
            'icon'        => 'dashicons-media-document',
            'title_label' => 'Tittel (slik den skal vises på nettsiden)',
            'pages'       => array('dokumenter', 'dokumenter/sakspapirer', 'dokumenter/referat-fra-moter-i-studentparlamentet', 'dokumenter/resolusjoner'),
            'intro'       => 'Dokumentene vises på dokumentsidene etter kategori (f.eks. Sakspapirer eller Resolusjoner), gruppert etter år med nyeste først. Last opp filen med «Velg fil».',
            'taxonomies'  => array('sta_document_category'),
            'required_taxonomies' => array('sta_document_category'),
            'fields'      => array(
                array('key' => '_sta_file', 'label' => 'Fil', 'type' => 'file', 'help' => 'Velg en fil fra mediebiblioteket eller last opp en ny (PDF, Word …). Uten fil vises tittelen som en vanlig linje uten lenke (f.eks. «Møtet ble avlyst»).'),
                array('key' => '_sta_doc_date', 'label' => 'Dato', 'type' => 'date', 'required' => true, 'help' => 'Møtedato eller vedtaksdato. Bestemmer sortering og årstall.'),
                array('key' => '_sta_title_en', 'label' => 'Tittel på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_desc_no', 'label' => 'Kort beskrivelse (valgfritt)', 'type' => 'text', 'help' => 'Brukes på kortene på Dokumenter-siden, f.eks. «Organisasjonens regler».'),
                array('key' => '_sta_note_no', 'label' => 'Merknad (valgfritt)', 'type' => 'text', 'help' => 'Vises etter lenken i lister, f.eks. «(digitalt Zoom-møte)».'),
                array('key' => '_sta_note_en', 'label' => 'Merknad på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_desc_en', 'label' => 'Kort beskrivelse på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
            ),
        ),
        'sta_partner' => array(
            'plural'      => 'Samarbeidspartnere',
            'singular'    => 'samarbeidspartner',
            'icon'        => 'dashicons-heart',
            'title_label' => 'Navn på samarbeidspartneren',
            'pages'       => array(''),
            'intro'       => 'Samarbeidspartnerne vises nederst på forsiden.',
            'fields'      => array(
                array('key' => '_sta_title_en', 'label' => 'Navn på engelsk', 'type' => 'text', 'help' => STA_CMS_EN_HELP),
                array('key' => '_sta_logo_url', 'label' => 'Logo', 'type' => 'image_url', 'required' => true),
                array('key' => '_sta_website', 'label' => 'Nettside (valgfritt)', 'type' => 'url', 'help' => 'Logoen og navnet på forsiden lenker hit.'),
            ),
        ),
    );
    return $types;
}

function sta_cms_taxonomies() {
    return array(
        'sta_faculty' => array(
            'plural' => 'Fakulteter', 'singular' => 'fakultet', 'objects' => array('sta_association', 'sta_parliament_rep'),
            'public' => true, 'rewrite' => 'fakultet', 'single' => true,
            'intro'  => 'Fakultetene brukes både for linjeforeninger og for Studentparlamentet. «Rekkefølge» bestemmer rekkefølgen på Studentparlamentet-siden.',
        ),
        'sta_activity_category' => array(
            'plural' => 'Aktivitetskategorier', 'singular' => 'aktivitetskategori', 'objects' => array('sta_activity'),
            'public' => true, 'rewrite' => 'aktivitetskategori', 'single' => true,
        ),
        'sta_committee_group' => array(
            'plural' => 'Utvalgsgrupper', 'singular' => 'utvalgsgruppe', 'objects' => array('sta_position'),
            'public' => true, 'rewrite' => 'utvalgsgruppe', 'single' => true,
            'intro'  => 'Hver utvalgsgruppe blir en egen seksjon med overskrift på siden «Råd, styrer og utvalg».',
        ),
        'sta_period' => array(
            'plural' => 'Perioder', 'singular' => 'periode', 'objects' => array('sta_parliament_rep'), 'single' => true,
            'intro'  => 'F.eks. «2026/2027». Kryss av «Vis på nettsiden» på perioden som skal vises. Tidligere perioder blir liggende som historikk.',
            'current_flag' => true,
        ),
        'sta_vacancy_category' => array(
            'plural' => 'Kategorier for ledige verv', 'singular' => 'kategori', 'menu' => 'Kategorier (ledige verv)', 'objects' => array('sta_vacancy'), 'single' => true,
            'intro'  => 'Hver kategori blir en egen seksjon med overskrift på siden Ledige verv.',
        ),
        'sta_document_category' => array(
            'plural' => 'Dokumentkategorier', 'singular' => 'dokumentkategori', 'objects' => array('sta_document'), 'single' => true,
            'intro'  => 'Kategorien bestemmer hvilken liste dokumentet vises i. Endre ikke «Slug» på eksisterende kategorier, den brukes av sidene.',
        ),
    );
}

/** Field definition by meta key for a post type. */
function sta_cms_field($post_type, $key) {
    $types = sta_cms_types();
    foreach ($types[$post_type]['fields'] ?? array() as $field) {
        if ($field['key'] === $key) {
            return $field;
        }
    }
    return null;
}
