const http = require('http');

const urls = [
  '/',
  '/?lang=en',
  '/rad-styrer-og-utvalg/',
  '/rad-styrer-og-utvalg/?lang=en',
  '/studentparlamentet/',
  '/studentparlamentet/?lang=en',
  '/sta-styret/',
  '/sta-styret/?lang=en',
  '/linjeforeninger/',
  '/linjeforeninger/?lang=en',
  '/studentaktiviteter/',
  '/studentaktiviteter/?lang=en',
  '/ledige-verv/',
  '/ledige-verv/?lang=en',
  '/dokumenter/',
  '/dokumenter/?lang=en',
  '/for-tillitsvalgte/',
  '/for-tillitsvalgte/?lang=en',
  '/lokaler/',
  '/lokaler/?lang=en',
  '/trygg-organisasjon/',
  '/trygg-organisasjon/?lang=en',
  '/irene-bredals-hederspris/',
  '/irene-bredals-hederspris/?lang=en',
  '/personvernserklaering/',
  '/personvernserklaering/?lang=en',
  '/category/nyheter/',
  '/category/nyheter/?lang=en',
  '/stem-i-studentvalget-2026-na/',
  '/?s=valg',
  '/non-existent-page-404/'
];

async function fetchUrl(urlPath) {
  return new Promise((resolve) => {
    const req = http.get('http://localhost:8080' + urlPath, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => {
        resolve({
          path: urlPath,
          statusCode: res.statusCode,
          headers: res.headers,
          body: data
        });
      });
    });
    req.on('error', (err) => {
      resolve({
        path: urlPath,
        statusCode: 0,
        error: err.message,
        body: ''
      });
    });
  });
}

async function run() {
  console.log('=== RUNNING COMPREHENSIVE QA AUDIT OF ALL ROUTES ===\n');
  const results = [];
  for (const u of urls) {
    const res = await fetchUrl(u);
    const errors = [];
    if (u === '/non-existent-page-404/') {
      if (res.statusCode !== 404) errors.push(`Expected 404 status, got ${res.statusCode}`);
    } else {
      if (res.statusCode !== 200) errors.push(`Expected 200 status, got ${res.statusCode}`);
    }

    // Check PHP errors / warnings
    if (res.body.includes('Fatal error') || res.body.includes('Parse error')) {
      errors.push('CRITICAL: PHP Fatal/Parse Error detected in response');
    }
    if (res.body.includes('Warning:') || res.body.includes('Notice:') || res.body.includes('Deprecated:')) {
      errors.push('PHP Warning/Notice/Deprecated detected');
    }

    // Check favicon and header logo
    if (!res.body.includes('favicon.svg')) {
      errors.push('Favicon link missing from <head>');
    }
    if (!res.body.includes('sta-logo.svg')) {
      errors.push('sta-logo.svg missing from page');
    }

    // Check for "Uncategorized"
    if (res.body.includes('Uncategorized')) {
      errors.push('Found "Uncategorized" category label on page');
    }

    // Check for "Hello world"
    if (res.body.includes('Hello world!') || res.body.includes('hello-world')) {
      errors.push('Found default WP "Hello world" post content');
    }

    results.push({
      path: u,
      status: res.statusCode,
      errors,
      bodyLength: res.body.length
    });
  }

  let totalErrors = 0;
  for (const r of results) {
    if (r.errors.length > 0) {
      console.log(`❌ [${r.status}] ${r.path} (${r.bodyLength} bytes):`);
      for (const e of r.errors) {
        console.log(`    - ${e}`);
        totalErrors++;
      }
    } else {
      console.log(`✅ [${r.status}] ${r.path} (${r.bodyLength} bytes) - Clean`);
    }
  }

  console.log(`\nAudit complete. Found ${totalErrors} issue(s) across ${urls.length} pages.`);
}

run();
