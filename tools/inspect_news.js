const fs = require('fs');
const path = require('path');

const content = fs.readFileSync(path.resolve(__dirname, '../../src/content/news.ts'), 'utf8');
const match = content.match(/export const NEWS_ARTICLES[^=]*=\s*([\s\S]*?);\s*$/m);
if (!match) {
  console.error("Match failed");
  process.exit(1);
}

let raw = match[1].trim().replace(/\s*as\s+const\s*$/, '');
const articles = eval(raw);
console.log('Total articles in news.ts:', articles.length);
articles.slice(0, 10).forEach((a, i) => {
  console.log(`#${i+1} [${a.publishedAt}] (${a.slug}) ${a.title}`);
});
