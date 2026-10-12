const http = require('http');
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const BASE_URL = 'http://localhost:8080';
const BASE_DIR = path.resolve(__dirname, '..');
const SITE_DIR = path.join(BASE_DIR, 'site');

// Ensure clean directory
if (fs.existsSync(SITE_DIR)) {
  fs.rmSync(SITE_DIR, { recursive: true, force: true });
}
fs.mkdirSync(SITE_DIR, { recursive: true });

function fetchUrl(urlPath) {
  return new Promise((resolve, reject) => {
    http.get(BASE_URL + urlPath, (res) => {
      if (res.statusCode >= 300 && res.statusCode < 400 && res.headers.location) {
        let loc = res.headers.location;
        if (loc.startsWith(BASE_URL)) loc = loc.substring(BASE_URL.length);
        return fetchUrl(loc).then(resolve).catch(reject);
      }
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => resolve({ status: res.statusCode, data }));
    }).on('error', reject);
  });
}

function normalizeHtml(html) {
  // Replace localhost:8080 with root relative path
  let cleaned = html.replace(/http:\/\/localhost:8080\//g, '/');
  cleaned = cleaned.replace(/http:\/\/localhost:8080/g, '');
  return cleaned;
}

function savePage(urlPath, html) {
  const cleanPath = urlPath.replace(/^\//, '').replace(/\/$/, '');
  let filePath;
  if (!cleanPath) {
    filePath = path.join(SITE_DIR, 'index.html');
  } else {
    const dir = path.join(SITE_DIR, cleanPath);
    fs.mkdirSync(dir, { recursive: true });
    filePath = path.join(dir, 'index.html');
  }
  fs.writeFileSync(filePath, normalizeHtml(html), 'utf-8');
}

async function runExport() {
  console.log('🚀 Starting STA WordPress Static Website Export...');

  // 1. Get all URLs from database
  console.log('📦 Fetching published posts and pages from local WordPress...');
  let urlsOutput = '';
  try {
    urlsOutput = execSync('php tools/list_all_urls.php', { cwd: BASE_DIR }).toString();
  } catch (err) {
    console.error('Error querying WordPress DB:', err.message);
  }

  const urlsToFetch = new Set([
    '/',
    '/om-sta/',
    '/sta-styret/',
    '/studentparlamentet/',
    '/rad-styrer-og-utvalg/',
    '/trygg-organisasjon/',
    '/irene-bredals-hederspris/',
    '/linjeforeninger/',
    '/studentaktiviteter/',
    '/for-foreninger/',
    '/lokaler/',
    '/engasjer-deg/',
    '/ledige-verv/',
    '/dokumenter/',
    '/for-tillitsvalgte/',
    '/personvernserklaering/',
    '/nyheter/'
  ]);

  // Add news pagination (pages 2 to 19)
  for (let i = 2; i <= 19; i++) {
    urlsToFetch.add(`/nyheter/page/${i}/`);
  }

  // Parse list_all_urls output
  const lines = urlsOutput.split('\n');
  for (const line of lines) {
    const parts = line.split('|').map(s => s.trim());
    if (parts.length >= 3) {
      let rawUrl = parts[2];
      if (rawUrl.startsWith(BASE_URL)) {
        rawUrl = rawUrl.substring(BASE_URL.length);
      }
      if (rawUrl.startsWith('/')) {
        urlsToFetch.add(rawUrl);
      }
    }
  }

  console.log(`📑 Total routes to export: ${urlsToFetch.size}`);

  let successCount = 0;
  let failCount = 0;

  for (const route of urlsToFetch) {
    try {
      const res = await fetchUrl(route);
      if (res.status === 200 && res.data) {
        savePage(route, res.data);
        successCount++;
        if (successCount % 25 === 0) {
          console.log(`  ✓ Exported ${successCount}/${urlsToFetch.size} routes...`);
        }
      } else {
        console.warn(`  ⚠ Non-200 status (${res.status}) for ${route}`);
        failCount++;
      }
    } catch (err) {
      console.error(`  ✕ Failed to fetch ${route}: ${err.message}`);
      failCount++;
    }
  }

  console.log(`✅ Finished exporting HTML pages! (${successCount} succeeded, ${failCount} failed)`);

  // 2. Copy Theme Assets
  console.log('🎨 Copying Theme Assets (CSS, JS, Logos, Favicons)...');
  const themeAssetsSource = path.join(BASE_DIR, 'sta-theme', 'assets');
  const themeAssetsTarget = path.join(SITE_DIR, 'wp-content', 'themes', 'sta-theme', 'assets');
  fs.cpSync(themeAssetsSource, themeAssetsTarget, { recursive: true });

  // 3. Copy Root Favicons
  const imagesSource = path.join(themeAssetsSource, 'images');
  if (fs.existsSync(imagesSource)) {
    ['favicon-32x32.png', 'favicon-192x192.png', 'apple-touch-icon.png', 'favicon.svg', 'favicon.ico'].forEach(file => {
      const srcFile = path.join(imagesSource, file);
      if (fs.existsSync(srcFile)) {
        fs.copyFileSync(srcFile, path.join(SITE_DIR, file));
      }
    });
  }

  // 4. Copy Downloads (theme & plugin zip)
  const downloadsDir = path.join(SITE_DIR, 'downloads');
  fs.mkdirSync(downloadsDir, { recursive: true });
  const themeZip = path.join(BASE_DIR, 'sta-theme.zip');
  const pluginZip = path.join(BASE_DIR, 'sta-core-plugin.zip');
  if (fs.existsSync(themeZip)) fs.copyFileSync(themeZip, path.join(downloadsDir, 'sta-theme.zip'));
  if (fs.existsSync(pluginZip)) fs.copyFileSync(pluginZip, path.join(downloadsDir, 'sta-core-plugin.zip'));

  console.log('🎉 Site export complete! Saved to site/');
}

runExport();
