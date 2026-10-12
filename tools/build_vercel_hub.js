/**
 * Vercel Hub Builder Script
 * Generates public_html/ deployment for Vercel
 */
const fs = require('fs');
const path = require('path');

const baseDir = path.resolve(__dirname, '..');
const outDir = path.join(baseDir, 'public_html');
const downloadsDir = path.join(outDir, 'downloads');
const assetsDir = path.join(outDir, 'assets', 'images');

// Ensure clean directories
if (fs.existsSync(outDir)) fs.rmSync(outDir, { recursive: true, force: true });
fs.mkdirSync(downloadsDir, { recursive: true });
fs.mkdirSync(assetsDir, { recursive: true });

// Copy ZIP archives
const themeZip = path.join(baseDir, 'sta-theme.zip');
const pluginZip = path.join(baseDir, 'sta-core-plugin.zip');
if (fs.existsSync(themeZip)) fs.copyFileSync(themeZip, path.join(downloadsDir, 'sta-theme.zip'));
if (fs.existsSync(pluginZip)) fs.copyFileSync(pluginZip, path.join(downloadsDir, 'sta-core-plugin.zip'));

// Copy images & favicons
const themeImages = path.join(baseDir, 'sta-theme', 'assets', 'images');
if (fs.existsSync(themeImages)) {
  fs.readdirSync(themeImages).forEach(file => {
    fs.copyFileSync(path.join(themeImages, file), path.join(assetsDir, file));
  });
}

// Generate index.html
const indexHtml = `<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STA WordPress Theme & Core Plugin — Distribution Hub</title>
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png">
  <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
  <style>
    :root {
      --sta-teal-900: #072a27;
      --sta-teal-800: #0E4C47;
      --sta-teal-700: #145e58;
      --sta-teal-200: #9ecfc9;
      --sta-teal-100: #cbe7e3;
      --sta-orange: #EF7330;
      --sta-orange-hover: #d95716;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      background: #041817;
      color: #ffffff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    header {
      background: rgba(14, 76, 71, 0.95);
      border-bottom: 1px solid rgba(158, 207, 201, 0.2);
      padding: 1.25rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .logo-img { height: 40px; width: auto; }
    .badge {
      background: rgba(239, 115, 48, 0.15);
      border: 1px solid var(--sta-orange);
      color: var(--sta-orange);
      padding: 0.35rem 0.85rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    main {
      flex: 1;
      max-width: 1000px;
      width: 100%;
      margin: 0 auto;
      padding: 3.5rem 1.5rem;
    }
    .hero {
      text-align: center;
      margin-bottom: 3.5rem;
    }
    h1 {
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 1rem;
      line-height: 1.2;
    }
    h1 span { color: var(--sta-orange); }
    .subtitle {
      font-size: 1.15rem;
      color: var(--sta-teal-100);
      max-width: 650px;
      margin: 0 auto 2rem auto;
      line-height: 1.6;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
      margin-bottom: 3rem;
    }
    .card {
      background: rgba(14, 76, 71, 0.6);
      border: 1px solid rgba(158, 207, 201, 0.25);
      border-radius: 14px;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      backdrop-filter: blur(8px);
    }
    .card h2 {
      font-size: 1.35rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
      color: #ffffff;
    }
    .card p {
      font-size: 0.9rem;
      color: var(--sta-teal-100);
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      background: var(--sta-orange);
      color: #ffffff;
      padding: 0.75rem 1.25rem;
      border-radius: 8px;
      font-weight: 700;
      text-decoration: none;
      transition: background 0.2s;
    }
    .btn:hover { background: var(--sta-orange-hover); }
    .btn-secondary {
      background: transparent;
      border: 1px solid var(--sta-teal-200);
      color: var(--sta-teal-100);
    }
    .btn-secondary:hover {
      background: rgba(158, 207, 201, 0.1);
      color: #ffffff;
    }
    .instructions {
      background: rgba(7, 42, 39, 0.7);
      border: 1px solid rgba(158, 207, 201, 0.2);
      border-radius: 14px;
      padding: 2rem;
    }
    .instructions h3 {
      font-size: 1.2rem;
      margin-bottom: 1rem;
      color: var(--sta-orange);
    }
    ol {
      margin-left: 1.5rem;
      line-height: 1.8;
      font-size: 0.95rem;
      color: var(--sta-teal-100);
    }
    footer {
      border-top: 1px solid rgba(14, 76, 71, 0.8);
      padding: 1.5rem 2rem;
      text-align: center;
      font-size: 0.8rem;
      color: var(--sta-teal-200);
    }
  </style>
</head>
<body>
  <header>
    <img src="assets/images/sta-logo.svg" alt="STA" class="logo-img">
    <span class="badge">Production Build</span>
  </header>
  <main>
    <div class="hero">
      <h1>STA <span>WordPress Theme</span> & Plugin</h1>
      <p class="subtitle">Complete, production-ready WordPress recreation of <strong>stastudent.no</strong>. Installable directly into STA's live WordPress hosting.</p>
    </div>

    <div class="grid">
      <div class="card">
        <div>
          <h2>🎨 STA Theme</h2>
          <p>Native WordPress theme reproducing the complete design, responsive navigation, search modal, centered headers, and page templates.</p>
        </div>
        <a href="downloads/sta-theme.zip" class="btn" download>
          <span>Last ned sta-theme.zip</span>
          <span>↓</span>
        </a>
      </div>

      <div class="card">
        <div>
          <h2>⚙️ STA Core Plugin</h2>
          <p>Custom Post Types for 30 Academic Associations, 40 Student Activities, Board Members, Student Parliament, and Vacancies.</p>
        </div>
        <a href="downloads/sta-core-plugin.zip" class="btn" download>
          <span>Last ned sta-core-plugin.zip</span>
          <span>↓</span>
        </a>
      </div>

      <div class="card">
        <div>
          <h2>🌐 Live Referanse</h2>
          <p>Sammenlign mot originalen på stastudent.no eller sjekk den fullstendige innholdsbeholdningen med 190 artikler og rådsstrukturer.</p>
        </div>
        <a href="https://stastudent.no" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">
          <span>Besøk stastudent.no</span>
          <span>↗</span>
        </a>
      </div>
    </div>

    <div class="instructions">
      <h3>🚀 Slik installerer du i WordPress Admin</h3>
      <ol>
        <li>Last ned <code>sta-core-plugin.zip</code> og <code>sta-theme.zip</code> ovenfor.</li>
        <li>Gå til <strong>Utvidelser (Plugins) → Legg til ny → Last opp utvidelse</strong>, velg <code>sta-core-plugin.zip</code> og aktiver den.</li>
        <li>Gå til <strong>Utseende (Appearance) → Temaer → Legg til nytt → Last opp tema</strong>, velg <code>sta-theme.zip</code> og aktiver det.</li>
        <li>(Valgfritt) Importer eksisterende innhold under <strong>Verktøy → Importer → WordPress</strong> med <code>sta_content_import.xml</code>.</li>
      </ol>
    </div>
  </main>
  <footer>
    Studentorganisasjonen i Agder (STA) • Universitetet i Agder
  </footer>
</body>
</html>
`;

fs.writeFileSync(path.join(outDir, 'index.html'), indexHtml);
console.log('✅ Successfully built Vercel deployment directory (public_html/)');
