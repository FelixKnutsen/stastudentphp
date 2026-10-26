# stastudent.no – WordPress-nettsiden til Studentorganisasjonen i Agder (STA)

Dette repoet inneholder temaet (`sta-theme`), kjerneutvidelsen (`sta-core-plugin`) og verktøyene som brukes på **stastudent.no**. Denne guiden forklarer hvordan nettsiden er bygget, hvordan du redigerer innhold, skriver nyheter, håndterer språk og tar sikkerhetskopi.

> **Kort fortalt:** Sidene redigeres i **Elementor**. Nyheter skrives som vanlige **Innlegg**. Engelsk oversettes i **TranslatePress**, nynorsk under **Innstillinger → Nynorsk-tekst**. Etter endringer: trykk **Purge cache** i toppmenyen.

---

## Innhold

1. [Slik er nettsiden bygget](#1-slik-er-nettsiden-bygget)
2. [Innlogging, testside og produksjon](#2-innlogging-testside-og-produksjon)
3. [Redigere en side i Elementor](#3-redigere-en-side-i-elementor)
4. [Legge til, endre og fjerne innhold](#4-legge-til-endre-og-fjerne-innhold)
5. [Skrive og publisere nyheter](#5-skrive-og-publisere-nyheter)
6. [Linjeforeninger og studentaktiviteter](#6-linjeforeninger-og-studentaktiviteter)
7. [Dokumenter, sakspapirer og protokoller](#7-dokumenter-sakspapirer-og-protokoller)
8. [Språk: bokmål, nynorsk og engelsk](#8-språk-bokmål-nynorsk-og-engelsk)
9. [Hurtigbuffer (cache)](#9-hurtigbuffer-cache)
10. [Sikkerhetskopi og gjenoppretting](#10-sikkerhetskopi-og-gjenoppretting)
11. [Tilgjengelighet – huskeliste for redaktører](#11-tilgjengelighet--huskeliste-for-redaktører)
12. [For utviklere: tema, kode og publisering](#12-for-utviklere-tema-kode-og-publisering)
13. [Feilsøking](#13-feilsøking)
14. [Viktige fakta og kontakter](#14-viktige-fakta-og-kontakter)

---

## 1. Slik er nettsiden bygget

| Del | Hva den gjør | Hvor du endrer den |
|---|---|---|
| **WordPress** | Selve publiseringssystemet | `stastudent.no/wp-admin` |
| **Elementor** (gratis) + **Happy Addons** | Bygger innholdet på alle vanlige sider (tekst, bilder, kort, knapper) | «Rediger med Elementor» på hver side |
| **sta-theme** (dette repoet) | Topp (meny, språkvelger, søk), bunntekst, «Tilbake»-lenke, farger, nyhetsarkiv, nynorsk-systemet, katalogene | Kode i `sta-theme/` (krever utvikler) |
| **STA Core Plugin** | Innholdstyper: Linjeforeninger, Studentaktiviteter, STA-Styret, Studentparlamentet, Verv og utvalg + menyen **STA Innstillinger** (kontaktinfo i bunnteksten) | Venstremenyen i wp-admin |
| **TranslatePress** (gratis) | Engelsk versjon av sidene | «Oversett side» i toppmenyen |
| **Nynorsk-tekst** (i temaet) | Nynorsk versjon av sidene | **Innstillinger → Nynorsk-tekst** |
| **Yoast SEO** | Sidetitler, beskrivelser, sitemap | Panelet «Yoast SEO» under hver side |
| **WP-Optimize** | Hurtigbuffer og komprimering | «Purge cache» i toppmenyen |
| **Solid Security** | Sikkerhet og innloggingsvern | wp-admin → Security |
| **WP STAGING** | Testside og sikkerhetskopier | wp-admin → WP Staging |

**Sider og hva de inneholder**

| Side | Innhold redigeres i |
|---|---|
| Forside, STA-styret, Studentparlamentet, Ledige verv, Råd/styrer/utvalg (= «Verv» i menyen), Dokumenter, Sakspapirer, Protokoller, Resolusjoner, For tillitsvalgte, Lokaler, Irene Bredals hederspris, Trygg organisasjon, Personvernerklæring, Om oss, Valgkomiteen | **Elementor** |
| Linjeforeninger, Studentaktiviteter | Toppen i Elementor, selve katalogen fra **Linjeforeninger** / **Studentaktiviteter** i venstremenyen |
| Nyheter (Siste nytt) | **Innlegg** |
| Menyen øverst og bunnteksten | Temaet (`header.php`, `footer.php`) – kontaktinfo i bunnteksten fra **STA Innstillinger** |

Prinsipprogrammet er historisk og ligger som **utkast** (ikke synlig for besøkende). Det kan publiseres igjen under Sider om det trengs.

---

## 2. Innlogging, testside og produksjon

- **Produksjon (den ekte nettsiden):** `https://stastudent.no/wp-admin`
- **Testside (staging):** `https://stastudent.no/reliant/wp-admin` – en kopi laget med WP STAGING. Bare innloggede brukere ser den. Prøv store endringer her først.

Slik flytter du ferdig arbeid fra testsiden til produksjon: **WP Staging → velg testsiden → Push changes**. Ta alltid en sikkerhetskopi av produksjon først (se [kapittel 10](#10-sikkerhetskopi-og-gjenoppretting)).

> ⚠️ Testsiden er satt til «ikke indekser i søkemotorer». Sjekk etter en push at **Innstillinger → Lesing → «Be søkemotorer om ikke å indeksere»** *ikke* er huket av på produksjon.

**Lenker på testsiden:** testsiden har ikke «pene» adresser slått på. Temaet sørger likevel for at lenker som `/reliant/sta-styret/` virker. WP STAGING bytter `/reliant/` til `/` ved push, så du trenger ikke endre lenker.

---

## 3. Redigere en side i Elementor

1. Gå til siden mens du er innlogget, og klikk **Rediger med Elementor** i den svarte/oransje toppmenyen (eller **Sider → hold musen over siden → Rediger med Elementor**).
2. Klikk på teksten, bildet eller knappen du vil endre. Panelet til venstre viser innstillingene:
   - **Innhold** – tekst, lenke, bilde
   - **Stil** – farge, størrelse, skrift
   - **Avansert** – marger, avstand, synlighet på mobil
3. Klikk **Publiser** (grønn/rosa knapp øverst til høyre).
4. Trykk **Purge cache** i toppmenyen og se på siden.

**Angre:** `Ctrl+Z` i Elementor, eller **Historikk**-ikonet (klokke) → **Revisjoner** for å hente en tidligere lagret versjon.

**Se mobilvisning:** ikonene for skjerm / nettbrett / mobil øverst i Elementor. Sjekk alltid mobil før du publiserer.

**Sidelayout:** alle redigerte sider bruker sidelayouten **Elementor Full Width** (Side-innstillinger, tannhjulet). Ikke bytt den – da forsvinner STA-designet på siden.

---

## 4. Legge til, endre og fjerne innhold

### Endre tekst, navn, roller, datoer
Klikk på teksten i Elementor og skriv. Gjelder f.eks. nye styremedlemmer, nye representanter i Studentparlamentet eller «per [dato]» på Ledige verv.

> Etter at du endrer bokmålstekst må den også oversettes: se [kapittel 8](#8-språk-bokmål-nynorsk-og-engelsk).

### Legge til et nytt kort / en ny boks
Den enkleste måten å holde designet likt på er å **duplisere** et eksisterende kort:
1. Hold musen over kortet → høyreklikk → **Dupliser** (eller i Navigator: høyreklikk på containeren → Dupliser).
2. Endre teksten/bildet i kopien.
3. Flytt den ved å dra (Navigator: hamburger-ikonet øverst til venstre gir oversikt over alle elementer).

### Fjerne noe
Høyreklikk på elementet → **Slett**. Er du usikker, sett heller **Avansert → Responsiv → Skjul på skrivebord/nettbrett/mobil**, så kan du hente det tilbake senere.

### Bilder
- Klikk på bildet → **Velg bilde** → last opp i Mediebiblioteket.
- Fyll alltid inn **Alternativ tekst** i mediebiblioteket (hva bildet viser). Rene pyntebilder kan stå uten.
- Bruk JPG for bilder, PNG/SVG for logoer. Store bilder (over ca. 2500 px brede) blir forminsket automatisk av WordPress.

### Knapper og lenker
- Interne lenker: skriv adressen uten domenet, f.eks. `/reliant/studentparlamentet/` på testsiden eller `/studentparlamentet/` på produksjon. Velg gjerne siden fra søkefeltet i lenkefeltet.
- Eksterne lenker: full adresse (`https://…`). Huk av **Åpne i ny fane** bare når det er nødvendig.
- Skriv lenketekst som sier hvor man havner («Les mer om Studentparlamentet»), ikke bare «Klikk her».

### Samarbeidspartnere på forsiden
Logoene er vanlige bilde-kort på forsiden i Elementor. Dupliser et partnerkort, bytt logo, navn og lenke.

### Ny side
1. **Sider → Legg til ny**, skriv tittel, publiser.
2. Klikk **Rediger med Elementor**. Sett **Sidelayout = Elementor Full Width** i side-innstillingene.
3. Kopier gjerne seksjoner fra en lignende side (høyreklikk → Kopier, så Lim inn på den nye siden).
4. **Menyen øverst er laget i temaet** (`sta-theme/header.php`). Nye menypunkter må legges inn av en utvikler (se [kapittel 12](#12-for-utviklere-tema-kode-og-publisering)). Du kan alltid lenke til en ny side fra andre sider i Elementor.

### Skjule eller fjerne en side
Sett siden til **Utkast** (Sider → Hurtigredigering → Status: Utkast). Den forsvinner for besøkende, men innholdet beholdes. Slett bare om du er sikker – slettede sider ligger i **Papirkurven** i 30 dager.

### Kontaktinfo i bunnteksten
**STA Innstillinger** i venstremenyen: adresser, telefon, e-post, org.nr. og lenker til Facebook, Instagram og LinkedIn.

---

## 5. Skrive og publisere nyheter

1. **Innlegg → Legg til nytt**.
2. Skriv **tittel** og **tekst**. Bruk overskriftsnivåene i rekkefølge (Overskrift 2, deretter 3).
3. I høyre panel:
   - **Kategori:** «Nyheter» (eller annen relevant kategori). Siste nytt viser kategorien *Nyheter*.
   - **Fremhevet bilde:** bildet som vises på nyhetskortet (liggende format, f.eks. 1200 × 800). Fyll inn alternativ tekst.
   - **Utdrag:** 1–2 setninger som vises på kortet og i søkeresultater.
4. **Publiser** (eller **Planlegg** for et senere tidspunkt).
5. **Purge cache**. De tre nyeste nyhetene vises automatisk på forsiden under «Siste nytt».

Tips:
- Lim inn tekst fra Word med `Ctrl+Shift+V` (uten formatering).
- Engelsk tekst for internasjonale studenter kan skrives under den norske i samme innlegg («English below»), slik STA gjør i dag. Nyheter oversettes ikke automatisk til nynorsk/engelsk.
- Lenk til PDF-er ved å laste dem opp i Mediebiblioteket og lime inn lenken.

---

## 6. Linjeforeninger og studentaktiviteter

Katalogene på sidene Linjeforeninger og Studentaktiviteter hentes fra **Linjeforeninger** og **Studentaktiviteter** i venstremenyen (STA Core Plugin).

- **Ny forening:** Linjeforeninger → Legg til ny. Fyll inn navn, beskrivelse, logo (fremhevet bilde), studieprogram, campus, e-post, nettside og sosiale medier i feltene under teksten, og velg **Fakultet** (eller **Aktivitetskategori** for studentaktiviteter).
- **Fjerne en forening:** sett den til Utkast eller flytt den til papirkurven.
- Filtrene og «Viser X av Y» oppdateres automatisk.
- Nye beskrivelser må oversettes til engelsk (TranslatePress) og nynorsk (Nynorsk-tekst).

---

## 7. Dokumenter, sakspapirer og protokoller

1. **Media → Legg til ny** → last opp PDF/DOCX.
2. Kopier **Fil-URL** fra mediebiblioteket.
3. Åpne riktig side i Elementor (Sakspapirer, Protokoller, Resolusjoner eller Dokumenter) og legg til en ny linje øverst i listen for riktig år (kopier en eksisterende linje og bytt tekst og lenke).
4. Gi lenken et beskrivende navn, f.eks. «SP-møte 21.10.26» eller «Resolusjon … (PDF)».

PDF-er bør være tilgjengelige (tekst som kan markeres, ikke bare skannede bilder, og med tittel i dokumentegenskapene).

---

## 8. Språk: bokmål, nynorsk og engelsk

Alt innhold skrives på **bokmål** i Elementor. Språkvelgeren øverst gir besøkende NB / NN / EN.

| Språk | Hvordan det oversettes |
|---|---|
| **Bokmål** | Originalteksten i Elementor |
| **Engelsk** | **TranslatePress**: gå til siden, klikk **Oversett side** i toppmenyen, velg en tekst i listen til venstre, skriv engelsk, **Lagre**. |
| **Nynorsk** | **Innstillinger → Nynorsk-tekst** (laget for STA, siden gratisversjonen av TranslatePress bare har ett ekstra språk). |
| Meny, bunntekst, knapper i temaet | Ligger i temaet på alle tre språk (`sta_t('bokmål', 'English', 'nynorsk')`). |

**Nynorsk-tekst steg for steg**
1. Gjør endringen på bokmål i Elementor og publiser.
2. Gå til **Innstillinger → Nynorsk-tekst** og trykk **Finn ny tekst på sidene**. Nye tekster vises øverst («Vis berre tekst utan omsetjing»).
3. Skriv nynorsk i feltet til høyre. Er teksten lik på nynorsk (navn, titler), skriv den samme teksten.
4. **Lagre** og **Purge cache**. Sjekk siden med språkvelgeren (NN).

Tekster uten oversettelse vises på bokmål. Endrer du en bokmålstekst, må nynorsk og engelsk oversettes på nytt for den teksten (de er knyttet til den eksakte bokmålsteksten).

Nyheter skrives på bokmål og oversettes ikke automatisk.

---

## 9. Hurtigbuffer (cache)

Siden lagres ferdig hos WP-Optimize for å gå raskt. Etter endringer:
- **Purge cache → Purge cache for all pages** i toppmenyen.
- Ser du fortsatt gammel design/farge: **Purge minify cache** i samme meny.
- Elementor: **Elementor → Verktøy → Regenerer filer og data** hvis en side mangler stil.

---

## 10. Sikkerhetskopi og gjenoppretting

Ta sikkerhetskopi **før** store endringer, oppdateringer av utvidelser, push fra testside eller sletting.

| Hva | Hvordan |
|---|---|
| **Hele nettsiden** (database + filer) | **WP Staging → Backup & Migration → Create Backup**. Last ned backupfilen og lagre den et trygt sted (f.eks. STAs delte disk). Gjenopprett fra samme meny (**Restore**). |
| **Én side i Elementor** | Elementor → Historikk (klokke) → **Revisjoner** → velg versjon → **Bruk**. Eller wp-admin → Sider → Rediger → **Revisjoner**. |
| **Nyheter** | Innlegg → Rediger → **Revisjoner**. Slettede innlegg ligger i **Papirkurven** i 30 dager. |
| **Nynorsk-oversettelser** | Innstillinger → Nynorsk-tekst → kopier boksen under **Eksport / import** til en fil. Lim inn og trykk **Lagre** for å gjenopprette. |
| **Engelske oversettelser** | Ligger i databasen (tas med i WP Staging-backup). |
| **Tema og kode** | Dette Git-repoet. Alle tidligere versjoner ligger i historikken, og `sta-theme.zip` er siste byggede versjon. |
| **Webhotellet** | Spør leverandøren av webhotellet hvilke automatiske kopier de tar og hvor lenge de lagres. |

Gjenopprett aldri en backup på produksjon uten å ta en ny backup av dagens versjon først.

---

## 11. Tilgjengelighet – huskeliste for redaktører

Nettsiden er sjekket mot WCAG 2.2 AA (kontrast, tastatur, overskrifter, alternativ tekst). Hold den slik:

- **Overskrifter i rekkefølge:** én H1 per side (sidetittelen), deretter H2, H3 … Ikke hopp over nivåer. Velg nivå i Elementor under *HTML-tag*.
- **Alternativ tekst** på alle bilder som viser noe viktig (personer, logoer, steder).
- **Lenketekst** som forklarer målet. Unngå «Klikk her» og «Les mer» alene der det er mulig.
- **Farger:** bruk STA-fargene under. Ikke hvit tekst på lys oransje (`#EF7330`) – bruk mørk oransje bak hvit tekst.
- **Ikke bare farge** for å vise noe viktig – bruk også tekst eller ikon.
- **PDF-er:** last opp tekstbaserte PDF-er, ikke skannede bilder.
- **Video:** legg ved teksting.

**Fargepalett (oppfyller kontrastkrav på den mørke bakgrunnen)**

| Bruk | Farge |
|---|---|
| Bakgrunn | `#0E4C47` (mørk grønn) |
| Brødtekst | `#CDE7E4` / hvit |
| Dempet tekst | `#9ED2CC` |
| Oransje tekst/lenker på mørk bakgrunn | `#FFAB78` |
| Oransje knapper/merkelapper med hvit tekst | `#C44F14` (hover `#A8420F`) |
| Ikoner og pynt | `#EF7330` (STA-oransje) |

---

## 12. For utviklere: tema, kode og publisering

### Mappestruktur
```text
sta-theme/                 WordPress-temaet
  header.php, footer.php   Toppmeny, språkvelger, søk, bunntekst
  functions.php            Oppsett, «Tilbake»-lenke, meta-beskrivelse, katalog-kortkode
  inc/translatepress-*.php Språkruting og TranslatePress-kobling (+ REST for oversettelser)
  inc/nynorsk.php          Nynorsk-ordbok, utskifting av tekst og adminsiden Nynorsk-tekst
  assets/css/main.css      All CSS (fargevariabler øverst)
  assets/js/               Navigasjon (meny, søk, tilbake) og katalogfilter
sta-core-plugin/           Innholdstyper og STA Innstillinger
tools/build_theme_zip.py   Bygger sta-theme.zip
tools/elementor-converter/ Skript brukt til å bygge Elementor-sidene (referanse)
sta-theme.zip              Siste ferdige temapakke
```

### Endre temaet
1. Gjør endringen i `sta-theme/`.
2. Øk versjonsnummeret både i `sta-theme/style.css` (`Version:`) og `sta-theme/functions.php` (`STA_THEME_VERSION`). WP-Optimize bruker versjonen til å hente ny CSS/JS.
3. Bygg pakken:
   ```bash
   python tools/build_theme_zip.py
   ```
4. Last opp: **Utseende → Temaer → Legg til nytt → Last opp tema** → velg `sta-theme.zip` → **Erstatt gjeldende med opplastet**. (Får du «mangler style.css», prøv samme fil én gang til.)
5. **Purge cache** og **Purge minify cache**. Test siden på mobil og med tastatur.
6. Commit og push endringen til dette repoet.

### Legge til et menypunkt
Menyen ligger i `sta-theme/header.php` (både skrivebordsmenyen og mobilmenyen). Kopier et eksisterende `<li>`/lenke, bruk `sta_get_localized_url('/slug')` og `sta_t('Bokmål', 'English', 'Nynorsk')`.

### Oversettelser via kode (bare administrator)
- `POST /wp-json/sta/v1/translations` – lese og lagre engelske TranslatePress-oversettelser (`mode`: `list`, `save`, `recent`, `maxid`).
- `POST /wp-json/sta/v1/nynorsk` – lese og lagre nynorsk-ordboken (`mode`: `list`, `save`).

### Oppdateringer
Oppdater WordPress og utvidelser jevnlig, helst på testsiden først. Ta backup før. Elementor-oppdateringer kan endre utseendet – sjekk forsiden, STA-styret og Råd/styrer/utvalg etterpå.

---

## 13. Feilsøking

| Problem | Løsning |
|---|---|
| Endringen vises ikke | Purge cache (alle sider) og last siden på nytt (`Ctrl+F5`). |
| Siden mangler design / ser «rå» ut | Purge minify cache. Elementor → Verktøy → Regenerer filer. Sjekk at sidelayout er *Elementor Full Width*. |
| Engelsk/nynorsk viser bokmål | Teksten er ny eller endret – oversett den (kapittel 8). |
| Rar kode i titler (`trp-…`, `#!trpst#…`) | Purge cache. Skjer det igjen, kontakt utvikler (gjelder TranslatePress-koblingen i temaet). |
| Lenke fører til forsiden på testsiden | Bruk adressen fra lenke-søket i Elementor, eller `/reliant/<side>/`. |
| Elementor laster ikke | Prøv en annen nettleser, slå av nettleserutvidelser, eller Elementor → Verktøy → «Safe Mode». |
| Glemt passord | «Glemt passord?» på innloggingssiden, eller be en administrator i STA. |

---

## 14. Viktige fakta og kontakter

- Studentorganisasjonen i Agder, org.nr. 981 596 412
- Universitetsveien 24, 4630 Kristiansand S · Jon Lilletuns vei 9, 4879 Grimstad
- sta@uia.no · +47 38 14 20 10
- Sosiale medier: Facebook, Instagram, LinkedIn (STA bruker ikke X/Twitter)

Mer dokumentasjon: `SITE_COMPLETENESS_AUDIT.md` og `SITE_RESTORATION_PROGRESS.md` i hovedrepoet (sjekklister og historikk), og `docs/` her.
