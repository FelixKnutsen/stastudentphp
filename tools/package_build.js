/**
 * Package Builder Script for STA Theme and STA Core Plugin
 * Produces valid WordPress installable ZIP archives: sta-theme.zip & sta-core-plugin.zip
 */

const { execSync } = require('child_process');
const path = require('path');
const fs = require('fs');

const baseDir = path.resolve(__dirname, '..');
const themeDir = path.join(baseDir, 'sta-theme');
const pluginDir = path.join(baseDir, 'sta-core-plugin');
const themeZip = path.join(baseDir, 'sta-theme.zip');
const pluginZip = path.join(baseDir, 'sta-core-plugin.zip');

console.log('=== Packaging STA WordPress Theme & Plugin ===\n');

// Remove existing zips if any
if (fs.existsSync(themeZip)) fs.unlinkSync(themeZip);
if (fs.existsSync(pluginZip)) fs.unlinkSync(pluginZip);

try {
  // Compress theme
  console.log('Zipping sta-theme...');
  execSync(`powershell.exe -Command "Compress-Archive -Path '${themeDir}' -DestinationPath '${themeZip}' -Force"`, { stdio: 'inherit' });
  const themeStat = fs.statSync(themeZip);
  console.log(`✅ Created sta-theme.zip (${(themeStat.size / 1024).toFixed(1)} KB)`);

  // Compress plugin
  console.log('Zipping sta-core-plugin...');
  execSync(`powershell.exe -Command "Compress-Archive -Path '${pluginDir}' -DestinationPath '${pluginZip}' -Force"`, { stdio: 'inherit' });
  const pluginStat = fs.statSync(pluginZip);
  console.log(`✅ Created sta-core-plugin.zip (${(pluginStat.size / 1024).toFixed(1)} KB)`);

  console.log('\n🎉 Both packages built successfully and are ready for WordPress installation!');
} catch (err) {
  console.error('❌ Failed to package ZIP archives:', err.message);
  process.exit(1);
}
