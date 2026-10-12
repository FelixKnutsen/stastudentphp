/**
 * Inventory & Template Verification Test Script
 * Validates complete content inventory, template existence, and data integrity.
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

console.log('=== STA WordPress Rebuild: Automated Verification ===\n');

let failed = false;

function assert(condition, message) {
  if (!condition) {
    console.error(`❌ FAIL: ${message}`);
    failed = true;
  } else {
    console.log(`✅ PASS: ${message}`);
  }
}

// 1. Verify Data Fixtures
const dataDir = path.resolve(__dirname, '../data');

// Associations
const assocFile = path.join(dataDir, 'associations.json');
assert(fs.existsSync(assocFile), 'associations.json exists');
const associations = JSON.parse(fs.readFileSync(assocFile, 'utf8'));
assert(associations.length === 30, `Exactly 30 academic associations verified (got ${associations.length})`);

// Activities
const actFile = path.join(dataDir, 'activities.json');
assert(fs.existsSync(actFile), 'activities.json exists');
const activities = JSON.parse(fs.readFileSync(actFile, 'utf8'));
assert(activities.length === 40, `Exactly 40 student activities verified (got ${activities.length})`);

// News
const newsFile = path.join(dataDir, 'news_archive.json');
assert(fs.existsSync(newsFile), 'news_archive.json exists');
const news = JSON.parse(fs.readFileSync(newsFile, 'utf8'));
assert(news.length === 190, `Exactly 190 news articles verified across 19 archive pages (got ${news.length})`);

// Board Members
const boardFile = path.join(dataDir, 'board_members.json');
assert(fs.existsSync(boardFile), 'board_members.json exists');
const board = JSON.parse(fs.readFileSync(boardFile, 'utf8'));
assert(board.length === 6, `Exactly 6 board members verified (got ${board.length})`);

// Parliament Faculties
const parlFile = path.join(dataDir, 'parliament_faculties.json');
assert(fs.existsSync(parlFile), 'parliament_faculties.json exists');
const parl = JSON.parse(fs.readFileSync(parlFile, 'utf8'));
assert(parl.length === 7, `Exactly 7 parliament faculties verified (got ${parl.length})`);

// Terminology
const termFile = path.join(dataDir, 'terminology.json');
assert(fs.existsSync(termFile), 'terminology.json exists');
const terms = JSON.parse(fs.readFileSync(termFile, 'utf8'));
assert(Object.keys(terms).length > 50, `Authoritative terminology dictionary verified (${Object.keys(terms).length} terms)`);

// 2. Verify Theme Templates
const themeDir = path.resolve(__dirname, '../sta-theme');
const requiredTemplates = [
  'style.css',
  'functions.php',
  'header.php',
  'footer.php',
  'front-page.php',
  'index.php',
  'single.php',
  'archive.php',
  'page.php',
  'search.php',
  '404.php',
  'page-linjeforeninger.php',
  'page-studentaktiviteter.php',
  'page-sta-styret.php',
  'page-studentparlamentet.php',
  'page-rad-styrer-og-utvalg.php',
  'page-ledige-verv.php',
  'page-lokaler.php',
  'page-dokumenter.php',
  'page-for-tillitsvalgte.php',
  'page-trygg-organisasjon.php',
  'page-irene-bredals-hederspris.php',
  'page-personvernserklaering.php',
  'assets/css/main.css',
  'assets/js/navigation.js',
  'assets/js/directory-filter.js',
  'assets/images/sta-logo.svg',
  'inc/translatepress-compat.php',
  'inc/template-tags.php',
  'template-parts/news-card.php'
];

console.log('\n--- Checking Theme Files ---');
requiredTemplates.forEach(t => {
  const p = path.join(themeDir, t);
  assert(fs.existsSync(p), `Template file exists: ${t}`);
});

// 3. Verify Companion Plugin Files
const pluginDir = path.resolve(__dirname, '../sta-core-plugin');
const requiredPluginFiles = [
  'sta-core.php',
  'inc/taxonomies.php',
  'inc/cpt-associations.php',
  'inc/cpt-activities.php',
  'inc/cpt-board.php',
  'inc/cpt-parliament.php',
  'inc/cpt-positions.php',
  'inc/meta-boxes.php',
  'inc/admin-settings.php'
];

console.log('\n--- Checking Plugin Files ---');
requiredPluginFiles.forEach(f => {
  const p = path.join(pluginDir, f);
  assert(fs.existsSync(p), `Plugin file exists: ${f}`);
});

// 4. PHP Syntax Linting (if php binary is present on system)
console.log('\n--- Checking PHP Syntax ---');
let hasPhp = false;
try {
  execSync('php -v', { stdio: 'ignore' });
  hasPhp = true;
} catch (e) {
  console.log('ℹ️ PHP CLI not found in PATH; skipping local syntax linting.');
}

if (hasPhp) {
  const allPhpFiles = [
    ...requiredTemplates.filter(f => f.endsWith('.php')).map(f => path.join(themeDir, f)),
    ...requiredPluginFiles.filter(f => f.endsWith('.php')).map(f => path.join(pluginDir, f))
  ];

  allPhpFiles.forEach(phpFile => {
    try {
      execSync(`php -l "${phpFile}"`, { stdio: 'pipe' });
      console.log(`✅ PHP LINT: ${path.basename(phpFile)} syntax OK`);
    } catch (err) {
      console.error(`❌ PHP LINT ERROR in ${phpFile}:`, err.message);
      failed = true;
    }
  });
}

console.log('\n======================================================');
if (failed) {
  console.error('❌ Verification failed with one or more errors.');
  process.exit(1);
} else {
  console.log('🎉 ALL INVENTORY AND TEMPLATE CHECKS PASSED PERFECTLY!');
  process.exit(0);
}
