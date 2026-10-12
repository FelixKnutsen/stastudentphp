/**
 * WXR 1.2 WordPress XML Import File Generator
 * Generates an idempotent, clean WordPress XML import file for staging/test environments.
 */

const fs = require('fs');
const path = require('path');

const dataDir = path.resolve(__dirname, '../data');
const outputFile = path.join(dataDir, 'sta_content_import.xml');

console.log('=== Generating WordPress WXR Import File ===\n');

const associations = JSON.parse(fs.readFileSync(path.join(dataDir, 'associations.json'), 'utf8'));
const activities = JSON.parse(fs.readFileSync(dataDir + '/activities.json', 'utf8'));
const board = JSON.parse(fs.readFileSync(dataDir + '/board_members.json', 'utf8'));
const news = JSON.parse(fs.readFileSync(dataDir + '/news_archive.json', 'utf8'));

function escapeXml(unsafe) {
  if (typeof unsafe !== 'string') return unsafe || '';
  return unsafe.replace(/[<>&'"]/g, function (c) {
    switch (c) {
      case '<': return '&lt;';
      case '>': return '&gt;';
      case '&': return '&amp;';
      case '\'': return '&apos;';
      case '"': return '&quot;';
    }
  });
}

function cdata(content) {
  return `<![CDATA[${content || ''}]]>`;
}

let xml = `<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
    xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/"
>
<channel>
    <title>Studentorganisasjonen i Agder</title>
    <link>https://stastudent.no</link>
    <description>Studentenes stemme ved Universitetet i Agder</description>
    <pubDate>${new Date().toUTCString()}</pubDate>
    <language>nb-NO</language>
    <wp:wxr_version>1.2</wp:wxr_version>
    <wp:base_site_url>https://stastudent.no</wp:base_site_url>
    <wp:base_blog_url>https://stastudent.no</wp:base_blog_url>
`;

let postId = 10000;

// 1. Pages
const pages = [
  { slug: 'linjeforeninger', title: 'Linjeforeninger', template: 'page-linjeforeninger.php' },
  { slug: 'studentaktiviteter', title: 'Studentaktiviteter', template: 'page-studentaktiviteter.php' },
  { slug: 'sta-styret', title: 'STA-Styret', template: 'page-sta-styret.php' },
  { slug: 'studentparlamentet', title: 'Studentparlamentet', template: 'page-studentparlamentet.php' },
  { slug: 'rad-styrer-og-utvalg', title: 'Råd, Styrer og Utvalg', template: 'page-rad-styrer-og-utvalg.php' },
  { slug: 'ledige-verv', title: 'Ledige verv', template: 'page-ledige-verv.php' },
  { slug: 'lokaler', title: 'Lokaler', template: 'page-lokaler.php' },
  { slug: 'dokumenter', title: 'Dokumenter', template: 'page-dokumenter.php' },
  { slug: 'for-tillitsvalgte', title: 'Studenttillitsvalgt', template: 'page-for-tillitsvalgte.php' },
  { slug: 'trygg-organisasjon', title: 'Trygg Organisasjon', template: 'page-trygg-organisasjon.php' },
  { slug: 'irene-bredals-hederspris', title: 'Irene Bredals Hederspris', template: 'page-irene-bredals-hederspris.php' },
  { slug: 'personvernserklaering', title: 'Personvernserklæring', template: 'page-personvernserklaering.php' },
  { slug: 'om-oss-2', title: 'Om Oss', template: 'page.php' }
];

pages.forEach(p => {
  postId++;
  xml += `
    <item>
        <title>${cdata(p.title)}</title>
        <link>https://stastudent.no/${p.slug}/</link>
        <pubDate>${new Date().toUTCString()}</pubDate>
        <dc:creator><![CDATA[admin]]></dc:creator>
        <wp:post_id>${postId}</wp:post_id>
        <wp:post_date>2026-01-01 12:00:00</wp:post_date>
        <wp:post_name>${p.slug}</wp:post_name>
        <wp:status>publish</wp:status>
        <wp:post_type>page</wp:post_type>
        <wp:postmeta>
            <wp:meta_key>_wp_page_template</wp:meta_key>
            <wp:meta_value><![CDATA[${p.template}]]></wp:meta_value>
        </wp:postmeta>
    </item>
  `;
});

// 2. Associations (30 items)
associations.forEach(a => {
  postId++;
  xml += `
    <item>
        <title>${cdata(a.name)}</title>
        <pubDate>${new Date().toUTCString()}</pubDate>
        <dc:creator><![CDATA[admin]]></dc:creator>
        <wp:post_id>${postId}</wp:post_id>
        <wp:post_name>${a.id}</wp:post_name>
        <wp:status>publish</wp:status>
        <wp:post_type>sta_association</wp:post_type>
        <category domain="sta_faculty" nicename="${sanitizeSlug(a.faculty)}">${cdata(a.faculty)}</category>
        <wp:postmeta>
            <wp:meta_key>_sta_desc_no</wp:meta_key>
            <wp:meta_value>${cdata(a.description)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_desc_en</wp:meta_key>
            <wp:meta_value>${cdata(a.descriptionEn)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_logo_url</wp:meta_key>
            <wp:meta_value>${cdata(a.logoUrl)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_email</wp:meta_key>
            <wp:meta_value>${cdata(a.email)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_website</wp:meta_key>
            <wp:meta_value>${cdata(a.websiteUrl)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_facebook</wp:meta_key>
            <wp:meta_value>${cdata(a.facebookUrl)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_instagram</wp:meta_key>
            <wp:meta_value>${cdata(a.instagramUrl)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_discord</wp:meta_key>
            <wp:meta_value>${cdata(a.discordUrl)}</wp:meta_value>
        </wp:postmeta>
    </item>
  `;
});

// 3. Activities (40 items)
activities.forEach(act => {
  postId++;
  xml += `
    <item>
        <title>${cdata(act.name)}</title>
        <pubDate>${new Date().toUTCString()}</pubDate>
        <dc:creator><![CDATA[admin]]></dc:creator>
        <wp:post_id>${postId}</wp:post_id>
        <wp:post_name>${act.id}</wp:post_name>
        <wp:status>publish</wp:status>
        <wp:post_type>sta_activity</wp:post_type>
        <category domain="sta_activity_category" nicename="${sanitizeSlug(act.category)}">${cdata(act.category)}</category>
        <wp:postmeta>
            <wp:meta_key>_sta_desc_no</wp:meta_key>
            <wp:meta_value>${cdata(act.description)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_desc_en</wp:meta_key>
            <wp:meta_value>${cdata(act.descriptionEn)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_image_url</wp:meta_key>
            <wp:meta_value>${cdata(act.imageUrl)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_email</wp:meta_key>
            <wp:meta_value>${cdata(act.email)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_website</wp:meta_key>
            <wp:meta_value>${cdata(act.websiteUrl)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_facebook</wp:meta_key>
            <wp:meta_value>${cdata(act.facebookUrl)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_instagram</wp:meta_key>
            <wp:meta_value>${cdata(act.instagramUrl)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_discord</wp:meta_key>
            <wp:meta_value>${cdata(act.discordUrl)}</wp:meta_value>
        </wp:postmeta>
    </item>
  `;
});

// 4. Board Members (6 items)
board.forEach((b, idx) => {
  postId++;
  xml += `
    <item>
        <title>${cdata(b.name)}</title>
        <pubDate>${new Date().toUTCString()}</pubDate>
        <dc:creator><![CDATA[admin]]></dc:creator>
        <wp:post_id>${postId}</wp:post_id>
        <wp:post_name>${b.id}</wp:post_name>
        <wp:status>publish</wp:status>
        <wp:post_type>sta_board_member</wp:post_type>
        <wp:menu_order>${idx}</wp:menu_order>
        <wp:postmeta>
            <wp:meta_key>_sta_role_no</wp:meta_key>
            <wp:meta_value>${cdata(b.role)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_role_en</wp:meta_key>
            <wp:meta_value>${cdata(b.roleEn)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_email</wp:meta_key>
            <wp:meta_value>${cdata(b.email)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_phone</wp:meta_key>
            <wp:meta_value>${cdata(b.phone)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_campus</wp:meta_key>
            <wp:meta_value>${cdata(b.campus)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_image_url</wp:meta_key>
            <wp:meta_value>${cdata(b.imageUrl)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_responsibilities_no</wp:meta_key>
            <wp:meta_value>${cdata(Array.isArray(b.responsibilities) ? b.responsibilities.join(', ') : b.responsibilities)}</wp:meta_value>
        </wp:postmeta>
        <wp:postmeta>
            <wp:meta_key>_sta_responsibilities_en</wp:meta_key>
            <wp:meta_value>${cdata(Array.isArray(b.responsibilitiesEn) ? b.responsibilitiesEn.join(', ') : b.responsibilitiesEn)}</wp:meta_value>
        </wp:postmeta>
    </item>
  `;
});

function sanitizeSlug(str) {
  if (!str) return 'annet';
  return str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
}

xml += `
</channel>
</rss>`;

fs.writeFileSync(outputFile, xml, 'utf8');
const sizeKb = (fs.statSync(outputFile).size / 1024).toFixed(1);
console.log(`✅ Generated ${outputFile} (${sizeKb} KB)`);
console.log('This XML file can be imported into any WordPress instance via Tools -> Import -> WordPress');
