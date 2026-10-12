# Deployment Checklist & Rollback Plan

> **Step-by-step procedure for safe staging testing, live activation, and instant rollback.**

---

## 1. Pre-Deployment Staging Checklist

Before activating on production (`https://stastudent.no/`):

- [ ] **Staging Environment**: Perform initial installation and smoke tests on a staging clone or local development instance.
- [ ] **Full Backup Created**:
  * Complete database export (`.sql` dump or via hosting backup tool / UpdraftPlus).
  * Archive of `wp-content/` directory.
- [ ] **Verify Plugin Prerequisites**:
  * `TranslatePress - Multilingual` (Active)
  * `Yoast SEO` (Active)
  * `Solid Security` (Active)
  * `Koko Analytics` (Active)
- [ ] **Verify Package Integrity**:
  * `sta-theme.zip` built and verified (approx. 48 KB).
  * `sta-core-plugin.zip` built and verified (approx. 11 KB).

---

## 2. Live Activation Sequence

Follow this exact order to ensure no database conflicts:

### Step 1: Install & Activate Companion Plugin
1. Navigate to **Plugins &rarr; Add New &rarr; Upload Plugin**.
2. Upload **`sta-core-plugin.zip`** and click **Activate**.
3. **Verification**: Confirm that the new admin menu items appear:
   * *Linjeforeninger*
   * *Studentaktiviteter*
   * *STA-Styret*
   * *Studentparlamentet*
   * *Verv og utvalg*
   * *STA Innstillinger*

### Step 2: Install & Activate STA Theme
1. Navigate to **Appearance &rarr; Themes &rarr; Add New &rarr; Upload Theme**.
2. Upload **`sta-theme.zip`** and click **Activate**.
3. **Live Test**: Open the front page in an incognito window:
   * Verify header navigation and dropdown behavior.
   * Verify 3:2 board image on `/sta-styret/`.
   * Verify latest news grid on front page and `/category/nyheter/`.

### Step 3: Page Template Verification
In **Pages &rarr; All Pages**, ensure the core pages have their corresponding custom templates assigned in the *Page Attributes* sidebox:
* `Linjeforeninger` &rarr; Template: *Linjeforeninger*
* `Studentaktiviteter` &rarr; Template: *Studentaktiviteter*
* `STA-Styret` &rarr; Template: *STA-Styret*
* `Studentparlamentet` &rarr; Template: *Studentparlamentet*
* `Råd, Styrer og Utvalg` &rarr; Template: *Råd, styrer og utvalg*
* `Ledige verv` &rarr; Template: *Ledige verv*
* `Lokaler` &rarr; Template: *Lokaler*
* `Dokumenter` &rarr; Template: *Dokumenter*
* `Studenttillitsvalgt` &rarr; Template: *For tillitsvalgte*
* `Trygg Organisasjon` &rarr; Template: *Trygg Organisasjon*
* `Irene Bredals Hederspris` &rarr; Template: *Irene Bredals Hederspris*
* `Personvernserklæring` &rarr; Template: *Personvernserklæring*

*(Note: The templates include built-in fallback data fixtures so that all pages render 100% complete immediately upon activation).*

---

## 3. Instant Rollback Plan

If any critical issue or incompatibility occurs during live deployment:

1. Go to **Appearance &rarr; Themes**.
2. Hover over the previous active theme (**Hestia**) and click **Activate**.
3. **Result**:
   * The website immediately reverts to its previous Elementor layout and styling within seconds.
   * No existing pages or posts are deleted or altered by the rollback.
   * Custom post types stored by `sta-core-plugin` remain safely preserved in the database for future activation.
