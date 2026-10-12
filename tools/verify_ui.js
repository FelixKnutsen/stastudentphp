const http = require('http');

async function fetchUrl(urlPath, headers = {}) {
  return new Promise((resolve) => {
    const req = http.get({
      hostname: 'localhost',
      port: 8080,
      path: urlPath,
      headers: headers
    }, (res) => {
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
      resolve({ path: urlPath, statusCode: 0, error: err.message, body: '' });
    });
  });
}

async function run() {
  console.log('=== VERIFYING UI FIXES & REGRESSION CHECKS ===\n');
  let passCount = 0;
  let failCount = 0;

  function assert(condition, message) {
    if (condition) {
      console.log(`✅ PASS: ${message}`);
      passCount++;
    } else {
      console.log(`❌ FAIL: ${message}`);
      failCount++;
    }
  }

  // 1. Language Cookie persistence
  const enRes = await fetchUrl('/?lang=en');
  const setCookie = enRes.headers['set-cookie'] || [];
  const hasStaLang = setCookie.some(c => c.includes('sta_lang=en'));
  assert(hasStaLang, 'Language switcher sets sta_lang=en cookie');

  // 2. Mobile Backdrop
  const homeRes = await fetchUrl('/');
  assert(homeRes.body.includes('id="sta-mobile-backdrop"'), 'Mobile backdrop element present in header.php');
  assert(homeRes.body.includes('sta-mobile-backdrop'), 'CSS class sta-mobile-backdrop present in header.php');

  // 3. Header centering across subpages
  const subpages = [
    '/rad-styrer-og-utvalg/',
    '/studentparlamentet/',
    '/linjeforeninger/',
    '/studentaktiviteter/',
    '/ledige-verv/',
    '/trygg-organisasjon/',
    '/irene-bredals-hederspris/',
    '/dokumenter/'
  ];

  for (const page of subpages) {
    const res = await fetchUrl(page);
    assert(res.body.includes('class="sta-page-header"') || res.body.includes('class="sta-page-header '), `Page ${page} uses standardized .sta-page-header`);
  }

  // 4. Get Involved (RSU) 6 Reasons
  const rsuRes = await fetchUrl('/rad-styrer-og-utvalg/');
  assert(rsuRes.body.includes('sta-reasons-grid'), 'RSU page has .sta-reasons-grid');
  assert(rsuRes.body.includes('sta-reason-title'), 'RSU page has .sta-reason-title');
  assert(rsuRes.body.includes('1. Representere studentene') || rsuRes.body.includes('1. Represent studentene'), 'RSU page contains reason 1 title');

  // Check English variant of RSU
  const rsuEnRes = await fetchUrl('/rad-styrer-og-utvalg/?lang=en');
  assert(rsuEnRes.body.includes('sta-reasons-grid'), 'RSU English has .sta-reasons-grid');
  assert(rsuEnRes.body.includes('1. Representing students'), 'RSU English has "1. Representing students"');

  // 5. Student Parliament faculty cards layout
  const spRes = await fetchUrl('/studentparlamentet/');
  assert(spRes.body.includes('sta-parliament-faculties-grid'), 'Student Parliament has .sta-parliament-faculties-grid');
  assert(spRes.body.includes('sta-parliament-faculty-card'), 'Student Parliament has .sta-parliament-faculty-card');
  assert(spRes.body.includes('sta-parliament-quick-link'), 'Student Parliament has .sta-parliament-quick-link for agenda/minutes');

  // 6. Check single partner logos section on homepage
  const partnerMatches = homeRes.body.match(/id="samarbeidspartnere"/g) || [];
  assert(partnerMatches.length === 1, `Homepage has exactly 1 #samarbeidspartnere section (found ${partnerMatches.length})`);

  // 7. Check latest news from STA dates and no uncategorized
  assert(!homeRes.body.includes('Uncategorized'), 'Homepage does not contain "Uncategorized"');
  assert(!homeRes.body.includes('Hello world!'), 'Homepage does not contain "Hello world!"');

  console.log(`\nVerification Finished: ${passCount} Passed, ${failCount} Failed.`);
  process.exit(failCount === 0 ? 0 : 1);
}

run();
