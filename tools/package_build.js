/**
 * Package Builder Script for STA Theme and STA Core Plugin
 * Produces POSIX-compliant WordPress installable ZIP archives (forward slashes /)
 * so Linux WordPress servers unpack style.css and plugin files properly.
 */

const fs = require('fs');
const path = require('path');
const zlib = require('zlib');

const baseDir = path.resolve(__dirname, '..');
const themeDir = path.join(baseDir, 'sta-theme');
const pluginDir = path.join(baseDir, 'sta-core-plugin');
const themeZip = path.join(baseDir, 'sta-theme.zip');
const pluginZip = path.join(baseDir, 'sta-core-plugin.zip');

// CRC32 Lookup Table
const crcTable = new Uint32Array(256);
for (let n = 0; n < 256; n++) {
  let c = n;
  for (let k = 0; k < 8; k++) {
    c = (c & 1) ? (0xedb88320 ^ (c >>> 1)) : (c >>> 1);
  }
  crcTable[n] = c >>> 0;
}

function crc32(buf) {
  let crc = 0xffffffff;
  for (let i = 0; i < buf.length; i++) {
    crc = crcTable[(crc ^ buf[i]) & 0xff] ^ (crc >>> 8);
  }
  return (crc ^ 0xffffffff) >>> 0;
}

function walkDir(dir, rootPrefix, entries = []) {
  // Add directory entry itself with trailing forward slash
  entries.push({ isDir: true, zipPath: `${rootPrefix}/`, fullPath: dir });

  const list = fs.readdirSync(dir, { withFileTypes: true });
  for (const item of list) {
    const fullPath = path.join(dir, item.name);
    const zipPath = `${rootPrefix}/${item.name}`; // Always POSIX forward slash
    if (item.isDirectory()) {
      walkDir(fullPath, zipPath, entries);
    } else if (item.isFile()) {
      entries.push({ isDir: false, zipPath, fullPath });
    }
  }
  return entries;
}

function createPosixZip(sourceDir, rootFolderName, outZipPath) {
  const entries = walkDir(sourceDir, rootFolderName);
  const localFileParts = [];
  const centralDirParts = [];
  let offset = 0;

  for (const entry of entries) {
    const nameBuf = Buffer.from(entry.zipPath, 'utf8');
    let rawData = Buffer.alloc(0);
    let compressedData = Buffer.alloc(0);
    let compressionMethod = 0; // 0 = store, 8 = deflate
    let crc = 0;

    if (!entry.isDir) {
      rawData = fs.readFileSync(entry.fullPath);
      crc = crc32(rawData);
      compressedData = zlib.deflateRawSync(rawData, { level: 9 });
      compressionMethod = 8;
    }

    // Local file header (30 bytes + name + compressed data)
    const localHeader = Buffer.alloc(30);
    localHeader.writeUInt32LE(0x04034b50, 0); // signature
    localHeader.writeUInt16LE(20, 4);         // version needed (2.0)
    localHeader.writeUInt16LE(0x0800, 6);     // general purpose bit flag (UTF-8)
    localHeader.writeUInt16LE(compressionMethod, 8);
    localHeader.writeUInt16LE(0, 10);         // mod time
    localHeader.writeUInt16LE(0x5421, 12);    // mod date
    localHeader.writeUInt32LE(crc, 14);
    localHeader.writeUInt32LE(compressedData.length, 18);
    localHeader.writeUInt32LE(rawData.length, 22);
    localHeader.writeUInt16LE(nameBuf.length, 26);
    localHeader.writeUInt16LE(0, 28);         // extra field length

    localFileParts.push(localHeader, nameBuf, compressedData);

    // Central directory file header (46 bytes + name)
    const centralHeader = Buffer.alloc(46);
    centralHeader.writeUInt32LE(0x02014b50, 0); // signature
    centralHeader.writeUInt16LE(0x0314, 4);     // version made by (UNIX + 2.0)
    centralHeader.writeUInt16LE(20, 6);         // version needed
    centralHeader.writeUInt16LE(0x0800, 8);     // UTF-8 flag
    centralHeader.writeUInt16LE(compressionMethod, 10);
    centralHeader.writeUInt16LE(0, 12);         // mod time
    centralHeader.writeUInt16LE(0x5421, 14);    // mod date
    centralHeader.writeUInt32LE(crc, 16);
    centralHeader.writeUInt32LE(compressedData.length, 20);
    centralHeader.writeUInt32LE(rawData.length, 24);
    centralHeader.writeUInt16LE(nameBuf.length, 28);
    centralHeader.writeUInt16LE(0, 30);         // extra length
    centralHeader.writeUInt16LE(0, 32);         // comment length
    centralHeader.writeUInt16LE(0, 34);         // disk number
    centralHeader.writeUInt16LE(0, 36);         // internal attr
    // UNIX permissions: drwxr-xr-x (040755) for dirs, -rw-r--r-- (0100644) for files
    const unixMode = entry.isDir ? (0o040755 << 16) | 0x10 : (0o100644 << 16);
    centralHeader.writeUInt32LE(unixMode >>> 0, 38);
    centralHeader.writeUInt32LE(offset, 42);    // local header offset

    centralDirParts.push(centralHeader, nameBuf);
    offset += 30 + nameBuf.length + compressedData.length;
  }

  const centralDirBuf = Buffer.concat(centralDirParts);
  const eocd = Buffer.alloc(22);
  eocd.writeUInt32LE(0x06054b50, 0);            // EOCD signature
  eocd.writeUInt16LE(0, 4);                     // disk number
  eocd.writeUInt16LE(0, 6);                     // central dir start disk
  eocd.writeUInt16LE(entries.length, 8);        // entries on this disk
  eocd.writeUInt16LE(entries.length, 10);       // total entries
  eocd.writeUInt32LE(centralDirBuf.length, 12); // central dir size
  eocd.writeUInt32LE(offset, 16);               // central dir offset
  eocd.writeUInt16LE(0, 20);                    // comment length

  const finalZip = Buffer.concat([...localFileParts, centralDirBuf, eocd]);
  fs.writeFileSync(outZipPath, finalZip);
}

console.log('=== Packaging STA WordPress Theme & Plugin (POSIX ZIP) ===\n');

try {
  if (fs.existsSync(themeZip)) fs.unlinkSync(themeZip);
  if (fs.existsSync(pluginZip)) fs.unlinkSync(pluginZip);

  console.log('Zipping sta-theme...');
  createPosixZip(themeDir, 'sta-theme', themeZip);
  const themeStat = fs.statSync(themeZip);
  console.log(`✅ Created sta-theme.zip (${(themeStat.size / 1024).toFixed(1)} KB)`);

  console.log('Zipping sta-core-plugin...');
  createPosixZip(pluginDir, 'sta-core-plugin', pluginZip);
  const pluginStat = fs.statSync(pluginZip);
  console.log(`✅ Created sta-core-plugin.zip (${(pluginStat.size / 1024).toFixed(1)} KB)`);

  // Also copy to site/downloads and public_html/downloads if present
  for (const sub of ['site', 'public_html']) {
    const dlDir = path.join(baseDir, sub, 'downloads');
    if (fs.existsSync(dlDir)) {
      fs.copyFileSync(themeZip, path.join(dlDir, 'sta-theme.zip'));
      fs.copyFileSync(pluginZip, path.join(downloadsDir = dlDir, 'sta-core-plugin.zip'));
    }
  }

  console.log('\n🎉 Both packages built with POSIX forward-slash paths and ready for WordPress!');
} catch (err) {
  console.error('❌ Failed to package ZIP archives:', err.message);
  process.exit(1);
}
