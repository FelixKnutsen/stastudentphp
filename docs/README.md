# STA WordPress Theme & Core Plugin

> Production-ready, native WordPress theme and companion plugin for **Studentorganisasjonen i Agder (STA)** ([stastudent.no](https://stastudent.no/)).

---

## 1. Project Overview

This project is a native WordPress recreation of the STA student democracy and interest organization website. It replaces legacy Elementor-based page builder lock-in with a fast, accessible, structured, and easily maintainable WordPress architecture designed for non-technical student boards.

### Key Deliverables
* **`sta-theme/`** (`sta-theme.zip`): Native classic WordPress theme with custom responsive templates, accessible navigation, and design tokens (`#0e4c47` teal, `#ef7330` orange).
* **`sta-core-plugin/`** (`sta-core-plugin.zip`): Companion plugin registering Custom Post Types (`sta_association`, `sta_activity`, `sta_board_member`, `sta_parliament_rep`, `sta_position`) and an administrative settings screen for partner logos and contact information.
* **`data/sta_content_import.xml`**: Standard WordPress WXR 1.2 XML file for one-click import of the full verified content inventory.
* **`tools/`**: Automated verification test suite and ZIP package builder.
* **`docs/`**: Board handover manual, deployment checklist, and inventory reconciliation report.

---

## 2. Requirements & Compatibility

* **WordPress**: 6.0+ (Tested up to 6.7)
* **PHP**: 7.4+ (Tested up to 8.3)
* **Multilingual**: 100% compatible with existing **TranslatePress Multilingual** installation.
* **SEO**: Native integration with **Yoast SEO** (utilizes standard `wp_head()` and `wp_footer()` action hooks).
* **Analytics**: Seamless compatibility with **Koko Analytics** and local font caching (**OMGF**).
* **Security**: Nonce verification, role capability checks, and input/output sanitization compliant with **Solid Security**.

---

## 3. Installation Guide

### Step 1: Upload Companion Plugin
1. Go to **WordPress Admin &rarr; Plugins &rarr; Add New &rarr; Upload Plugin**.
2. Select **`sta-core-plugin.zip`** and click **Install Now**.
3. Click **Activate Plugin**.

### Step 2: Upload STA Theme
1. Go to **WordPress Admin &rarr; Appearance &rarr; Themes &rarr; Add New &rarr; Upload Theme**.
2. Select **`sta-theme.zip`** and click **Install Now**.
3. Click **Activate**.

### Step 3: Import Content (For Staging / Local Development)
1. Go to **WordPress Admin &rarr; Tools &rarr; Import**.
2. Run the **WordPress** importer.
3. Select `data/sta_content_import.xml` and complete the import.

*(Note: On the live production site, existing news articles in category `nyheter` are automatically rendered by the theme without re-importing).*

---

## 4. Automated Testing & Verification

Run the automated verification suite:

```bash
node tools/verify_inventory.js
```

This verifies:
* All 30 academic associations across 7 faculties.
* All 40 student activities across 7 categories.
* All 190 news articles across 19 archive pages.
* All 6 board members and 7 parliament faculties.
* 100% of PHP templates pass syntax checks with zero lint errors.

To rebuild the installable ZIP archives:

```bash
node tools/package_build.js
```
