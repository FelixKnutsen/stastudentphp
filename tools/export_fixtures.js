const fs = require('fs');
const path = require('path');

const srcDir = path.resolve(__dirname, '../../src');
const dataDir = path.resolve(__dirname, '../data');

// 1. Export news articles from src/content/news.ts with original dates
const themeDataDir = path.resolve(__dirname, '../sta-theme/data');
try {
  const news = extractExport(path.join(srcDir, 'content/news.ts'), 'NEWS_ARTICLES');
  fs.writeFileSync(path.join(dataDir, 'news_archive.json'), JSON.stringify(news, null, 2), 'utf8');
  if (fs.existsSync(themeDataDir)) {
    fs.writeFileSync(path.join(themeDataDir, 'news_archive.json'), JSON.stringify(news, null, 2), 'utf8');
  }
  console.log(`Exported news_archive.json (${news.length} articles with original dates)`);
} catch (e) {
  console.error('Failed to export news.ts:', e.message);
}

// Helper to extract JSON-like array/object from a TypeScript file
function extractExport(filePath, exportName) {
  const content = fs.readFileSync(filePath, 'utf8');
  // Find "export const NAME... = [ ... ]" or "{ ... }"
  const regex = new RegExp(`export const ${exportName}[^=]*=\\s*([\\s\\S]*?);\\s*(?:export|$)`, 'm');
  const match = content.match(regex);
  if (!match) {
    throw new Error(`Could not find export ${exportName} in ${filePath}`);
  }
  let raw = match[1].trim();
  // If it ends with "as const", remove it
  raw = raw.replace(/\s*as\s+const\s*$/, '');
  
  // Evaluate the JS expression safely using Function
  try {
    const fn = new Function(`return (${raw});`);
    return fn();
  } catch (err) {
    console.error(`Error parsing ${exportName} from ${filePath}:`, err.message);
    throw err;
  }
}

// 2. Export associations (30 items)
const associations = extractExport(path.join(srcDir, 'content/associations.ts'), 'STUDENT_ASSOCIATIONS');
fs.writeFileSync(path.join(dataDir, 'associations.json'), JSON.stringify(associations, null, 2), 'utf8');
console.log(`Exported associations.json (${associations.length} items)`);

// 3. Export activities (40 items)
const activities = extractExport(path.join(srcDir, 'content/activities.ts'), 'STUDENT_ACTIVITIES');
fs.writeFileSync(path.join(dataDir, 'activities.json'), JSON.stringify(activities, null, 2), 'utf8');
console.log(`Exported activities.json (${activities.length} items)`);

// 4. Export board members (6 items)
const boardMembers = extractExport(path.join(srcDir, 'content/board-members.ts'), 'BOARD_MEMBERS');
fs.writeFileSync(path.join(dataDir, 'board_members.json'), JSON.stringify(boardMembers, null, 2), 'utf8');
console.log(`Exported board_members.json (${boardMembers.length} items)`);

// 5. Export parliament faculties
const parliament = extractExport(path.join(srcDir, 'content/parliament.ts'), 'PARLIAMENT_FACULTIES');
fs.writeFileSync(path.join(dataDir, 'parliament_faculties.json'), JSON.stringify(parliament, null, 2), 'utf8');
console.log(`Exported parliament_faculties.json (${parliament.length} faculties)`);

// 6. Export positions & vacancies
const committees = extractExport(path.join(srcDir, 'content/positions.ts'), 'COMMITTEE_CATEGORIES');
fs.writeFileSync(path.join(dataDir, 'committees.json'), JSON.stringify(committees, null, 2), 'utf8');
console.log(`Exported committees.json (${committees.length} categories)`);

const vacancies = extractExport(path.join(srcDir, 'content/positions.ts'), 'VACANCIES_DATA');
fs.writeFileSync(path.join(dataDir, 'vacancies.json'), JSON.stringify(vacancies, null, 2), 'utf8');
console.log(`Exported vacancies.json (${vacancies.length} categories)`);

// 7. Export terminology
const terminology = extractExport(path.join(srcDir, 'lib/terminology.ts'), 'OFFICIAL_TERMINOLOGY');
fs.writeFileSync(path.join(dataDir, 'terminology.json'), JSON.stringify(terminology, null, 2), 'utf8');
console.log(`Exported terminology.json (${Object.keys(terminology).length} terms)`);

console.log('All fixtures exported successfully!');
