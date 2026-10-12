# STA Studentorganisasjonen i Agder — WordPress Theme & Core Plugin

> Production-ready, native WordPress theme and structured content management system for [Studentorganisasjonen i Agder (STA)](https://stastudent.no/).

---

## 📌 Overview

This project provides a clean, maintainable, native WordPress theme (`sta-theme`) and companion core plugin (`sta-core-plugin`) built to faithfully recreate the modern design, structured navigation, and full functionality of the STA student website.

Future STA boards and editors can manage website content directly from the standard WordPress admin panel without requiring programming knowledge.

---

## 📦 Project Structure

```text
├── sta-theme/              # Native WordPress theme source code
│   ├── assets/             # CSS, JavaScript, brand SVGs, favicons
│   ├── inc/                # Helpers, template tags, TranslatePress integration
│   ├── page-*.php          # Dedicated custom page templates (Parliament, RSU, etc.)
│   ├── front-page.php      # Homepage template
│   ├── header.php          # Accessible navigation header with modal search
│   └── footer.php          # Structured footer with contacts & links
│
├── sta-core-plugin/        # Core plugin source code
│   ├── inc/                # Custom Post Types, taxonomies, meta fields, options
│   └── sta-core-plugin.php # Plugin entry point
│
├── sta-theme.zip           # Ready-to-install Theme ZIP archive
├── sta-core-plugin.zip     # Ready-to-install Core Plugin ZIP archive
│
├── data/                   # Verified content inventory and WXR migration files
│   ├── fixtures/           # JSON data fixtures (associations, activities, board, parliament)
│   └── sta-export.xml      # WordPress WXR XML import file (all 190 news posts & pages)
│
├── docs/                   # Architectural blueprints & deployment guides
│   ├── ARCHITECTURE.md     # Technical specification & system architecture
│   ├── DEPLOYMENT_GUIDE.md # Production staging and migration instructions
│   └── USER_GUIDE.md       # Board handbook for managing content in WP Admin
│
├── tools/                  # Verification test suites & build scripts
│   ├── audit_all_pages.js  # Automated route health auditor
│   ├── verify_ui.js        # Automated UI/UX regression checker
│   └── package_build.js    # ZIP package builder
│
└── .gitignore              # Repository exclusion rules
```

---

## 🚀 Quick Installation Guide (Production)

### 1. Install `sta-core-plugin`
1. Log in to the WordPress dashboard (`wp-admin`).
2. Go to **Plugins → Add New → Upload Plugin**.
3. Choose `sta-core-plugin.zip` and click **Install Now**.
4. Click **Activate Plugin**.

### 2. Install `sta-theme`
1. Go to **Appearance → Themes → Add New → Upload Theme**.
2. Choose `sta-theme.zip` and click **Install Now**.
3. Click **Activate**.

### 3. Import Verified Content (Optional / Staging)
1. Go to **Tools → Import → WordPress**.
2. Select `data/sta-export.xml`.
3. Map authors and click **Submit**.

---

## 🎨 Brand Identity & Typography

- **STA Orange**: `#EF7330`
- **STA Dark Pine / Teal**: `#0E4C47`
- **STA Mint / Light Teal**: `#9ECFC9` / `#CBE7E3`
- **Backgrounds**: `#072a27` / `#0E4C47` / `#031816`
- **Favicons**: Multi-resolution icons (`favicon-32x32.png`, `apple-touch-icon.png`, `favicon-192x192.png`, `favicon.svg`) directly matched with `stastudent.no`.

---

## 🌐 Multilingual Support (Norwegian & English)

- **Compatibility**: Fully compatible with TranslatePress (`translatepress-multilingual`).
- **Language Switcher**: Built-in header and mobile drawer toggle preserving language state across page transitions via persistent cookie.
- **Terminology**: Reconciled against official Norwegian and English institutional terminology.

---

## 🧪 Testing & Quality Assurance

Run the automated verification suite locally:
```bash
# Verify route responses across all pages
node tools/audit_all_pages.js

# Verify UI layout, header centering, and parliament cards
node tools/verify_ui.js

# Build updated installable ZIP packages
node tools/package_build.js
```

---

## 📄 License
Custom theme and plugin developed for Studentorganisasjonen i Agder (STA).
