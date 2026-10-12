const fs = require('fs');
const path = require('path');

const base = path.resolve(__dirname, '..', 'public_html');

const tests = [
  { path: 'index.html', lang: 'nb-NO', matches: ['Norsk bokmål', 'Norsk nynorsk', 'English', 'Engasjer deg - still til verv!'] },
  { path: 'nn/index.html', lang: 'nn-NO', matches: ['Norsk nynorsk', 'Heim', 'Verv', 'Dokument', 'Tillitsvald', 'Om oss', 'gjer ein skilnad', 'Sjå alle nyheiter'] },
  { path: 'en/index.html', lang: 'en', matches: ['English', 'Home', 'Get involved', 'Documents', 'About', 'make a difference', 'View all news'] },
  { path: 'nn/sta-styret/index.html', lang: 'nn-NO', matches: ['STA-styret', 'Leiing', 'Hovudansvar'] },
  { path: 'nn/studentparlamentet/index.html', lang: 'nn-NO', matches: ['Studentdemokrati', 'Studentparlamentet'] },
  { path: 'nn/linjeforeninger/index.html', lang: 'nn-NO', matches: ['Linjeforeiningar ved UiA'] },
  { path: 'nn/studentaktiviteter/index.html', lang: 'nn-NO', matches: ['Studentaktivitetar ved UiA'] },
  { path: 'nn/lokaler/index.html', lang: 'nn-NO', matches: ['Lokale', 'Studentlokale'] },
  { path: 'nn/dokumenter/index.html', lang: 'nn-NO', matches: ['Dokument', 'Styringsdokument'] },
  { path: 'nn/for-tillitsvalgte/index.html', lang: 'nn-NO', matches: ['Studenttillitsvald', 'Rolla di som studenttillitsvald'] },
  { path: 'nn/trygg-organisasjon/index.html', lang: 'nn-NO', matches: ['Trygg organisasjon'] },
  { path: 'nn/personvernserklaering/index.html', lang: 'nn-NO', matches: ['Personvernerklæring'] },
  { path: 'nn/irene-bredals-hederspris/index.html', lang: 'nn-NO', matches: ['Irene Bredals Heiderspris'] },
  { path: 'nn/om-sta/index.html', lang: 'nn-NO', matches: ['Om Studentorganisasjonen i Agder'] }
];

let failed = 0;
for (const t of tests) {
  const fullPath = path.join(base, t.path);
  if (!fs.existsSync(fullPath)) {
    console.error('FAIL (file missing):', t.path);
    failed++;
    continue;
  }
  const html = fs.readFileSync(fullPath, 'utf8');
  if (t.lang && !html.includes(`lang="${t.lang}"`)) {
    console.error('FAIL (wrong lang attr):', t.path, 'expected:', t.lang);
    failed++;
  }
  for (const m of t.matches) {
    if (!html.includes(m)) {
      console.error('FAIL (missing match):', t.path, '->', m);
      failed++;
    }
  }
  console.log('PASS:', t.path);
}

if (failed > 0) {
  console.error('Total failures:', failed);
  process.exit(1);
} else {
  console.log('🎉 ALL ' + tests.length + ' MULTILINGUAL VERIFICATION TESTS PASSED!');
}
