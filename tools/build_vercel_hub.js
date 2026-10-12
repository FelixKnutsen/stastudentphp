/**
 * Vercel Live Website Deployment Builder
 * Prepares public_html/ with the complete authentic STA website for Vercel
 */
const fs = require('fs');
const path = require('path');

const baseDir = path.resolve(__dirname, '..');
const siteDir = path.join(baseDir, 'site');
const outDir = path.join(baseDir, 'public_html');
const downloadsDir = path.join(outDir, 'downloads');

console.log('🚀 Preparing Vercel live website deployment...');

// Clean and recreate public_html
if (fs.existsSync(outDir)) {
  fs.rmSync(outDir, { recursive: true, force: true });
}
fs.mkdirSync(outDir, { recursive: true });

// Copy all static site pages, stylesheets, scripts, and assets
if (fs.existsSync(siteDir)) {
  fs.cpSync(siteDir, outDir, { recursive: true });
  console.log('✓ Successfully copied complete static site pages and assets from site/ to public_html/');
} else {
  console.error('⚠ Warning: site/ directory not found! Run "node tools/export_site.js" first.');
}

// Ensure downloads directory has zip files
fs.mkdirSync(downloadsDir, { recursive: true });
const themeZip = path.join(baseDir, 'sta-theme.zip');
const pluginZip = path.join(baseDir, 'sta-core-plugin.zip');
if (fs.existsSync(themeZip)) {
  fs.copyFileSync(themeZip, path.join(downloadsDir, 'sta-theme.zip'));
}
if (fs.existsSync(pluginZip)) {
  fs.copyFileSync(pluginZip, path.join(downloadsDir, 'sta-core-plugin.zip'));
}

console.log('✅ Successfully built Vercel deployment directory (public_html/) showcasing the actual live STA website!');
