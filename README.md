# Brukerveiledning for stastudent.no

**Nettsiden til Studentorganisasjonen i Agder (STA)**

Denne veiledningen er skrevet for deg som skal holde nettsiden oppdatert, også om du aldri har brukt WordPress eller Elementor før og ikke kan koding. Den er delt i to:

- **Del 1 – For redaktører:** alt du trenger for å endre tekst og bilder, skrive nyheter, oversette og ta sikkerhetskopi. Ingen koding.
- **Del 2 – Teknisk del:** for den som skal endre selve koden (temaet). De fleste trenger ikke denne delen.

---

## Hurtigoversikt: «Hvor gjør jeg …?»

| Jeg vil … | Gå hit |
|---|---|
| Logge inn | [stastudent.no/wp-admin](https://stastudent.no/wp-admin) |
| Endre tekst eller bilder på en side | [Alle sider](https://stastudent.no/wp-admin/edit.php?post_type=page) → hold musen over siden → **Rediger med Elementor** ([oppskrift](#4-endre-tekst-på-en-side)) |
| Skrive en nyhet | [Nytt innlegg](https://stastudent.no/wp-admin/post-new.php) ([oppskrift](#6-skrive-en-nyhet)) |
| Se eller endre nyheter | [Alle innlegg](https://stastudent.no/wp-admin/edit.php) |
| Laste opp en PDF eller et bilde | [Last opp fil](https://stastudent.no/wp-admin/media-new.php) |
| Endre linjeforeninger | [Linjeforeninger](https://stastudent.no/wp-admin/edit.php?post_type=sta_association) |
| Endre studentaktiviteter | [Studentaktiviteter](https://stastudent.no/wp-admin/edit.php?post_type=sta_activity) |
| Endre adresse, telefon, e-post eller sosiale medier i bunnteksten | [STA Innstillinger](https://stastudent.no/wp-admin/admin.php?page=sta-settings) |
| Oversette til nynorsk | [Nynorsk-tekst](https://stastudent.no/wp-admin/options-general.php?page=sta-nynorsk) ([oppskrift](#72-nynorsk)) |
| Oversette til engelsk | Åpne siden og klikk **Oversett side** i den svarte linja øverst ([oppskrift](#71-engelsk)) |
| Ta sikkerhetskopi | [Backup & Migration](https://stastudent.no/wp-admin/admin.php?page=wpstg_backup) ([oppskrift](#93-ta-sikkerhetskopi-av-hele-nettsiden)) |
| Gi en ny person tilgang | [Legg til bruker](https://stastudent.no/wp-admin/user-new.php) |
| Bytte mitt passord | [Min profil](https://stastudent.no/wp-admin/profile.php) |
| Få endringene mine til å vises | Klikk **Purge cache** i den svarte linja øverst ([forklaring](#8-få-endringene-til-å-vises-tømme-hurtigbufferen)) |
| Se hva som må oppdateres hvert år (nytt styre, valg, dokumenter) | [Årshjul og årlig sjekkliste](ARSHJUL.md) |

> **Testsiden:** Alle lenkene over går til den ekte nettsiden. På testsiden setter du inn `/reliant` etter `stastudent.no`, for eksempel [stastudent.no/reliant/wp-admin](https://stastudent.no/reliant/wp-admin). Les mer i [kapittel 3](#3-testsiden-og-den-ekte-nettsiden).

---

# Del 1 – For redaktører

> 📅 **Hva må gjøres når?** Se [Årshjul og årlig sjekkliste](ARSHJUL.md): alt som må oppdateres hvert år (nytt styre 1. juli, valg om våren, dokumenter etter hvert møte), med lenker og fremgangsmåte.

## 1. Ord du bør kjenne

| Ord | Betyr |
|---|---|
| **WordPress** | Programmet nettsiden er laget i. Her logger du inn for å endre innhold. |
| **Kontrollpanelet / wp-admin** | «Baksiden» av nettsiden der du styrer alt. Åpnes på [stastudent.no/wp-admin](https://stastudent.no/wp-admin). Menyen står til venstre. |
| **Elementor** | Verktøyet du bruker for å endre utseendet og teksten på en side. Du klikker rett på teksten og skriver. |
| **Side** | En fast side, for eksempel «STA-styret» eller «Lokaler». |
| **Innlegg** | En nyhet. Vises under «Siste nytt». |
| **Publiser** | Gjør endringen synlig for alle. Før du publiserer ser bare du endringen. |
| **Utkast** | Lagret, men ikke synlig for besøkende. |
| **Mediebiblioteket** | Lageret for alle bilder og PDF-er på nettsiden. |
| **Den svarte linja øverst** | Når du er innlogget og ser på nettsiden, har du en svart (på testsiden oransje) linje helt øverst med snarveier: **Rediger med Elementor**, **Oversett side** og **Purge cache**. |
| **Cache / hurtigbuffer** | En lagret kopi av sidene som gjør nettsiden rask. Må «tømmes» (**Purge cache**) etter endringer. |
| **Revisjon** | En tidligere lagret versjon. Brukes til å angre. |

---

## 2. Logge inn og komme i gang

### 2.1 Logge inn
1. Gå til [stastudent.no/wp-admin](https://stastudent.no/wp-admin).
2. Skriv inn brukernavn (eller e-post) og passord. Klikk **Logg inn**.
3. Du kommer til kontrollpanelet. Menyen til venstre har alt du trenger.

**Glemt passord?** Klikk **Mistet passordet?** under innloggingsboksen og følg e-posten du får.

### 2.2 Bytte passord
1. Gå til [Min profil](https://stastudent.no/wp-admin/profile.php).
2. Bla ned til **Ny passord** → **Sett nytt passord**.
3. Klikk **Oppdater profil** nederst.

### 2.3 Gi nye styremedlemmer tilgang (krever administrator)
1. Gå til [Legg til bruker](https://stastudent.no/wp-admin/user-new.php).
2. Fyll inn brukernavn og e-post.
3. Velg **Rolle**:
   - **Redaktør** – kan endre sider og nyheter (det vanlige).
   - **Administrator** – kan alt, også brukere og innstillinger. Gi dette til få personer.
4. Klikk **Legg til ny bruker**. Personen får en e-post med innlogging.

Når noen slutter i styret: gå til [Alle brukere](https://stastudent.no/wp-admin/users.php), hold musen over personen og klikk **Slett** (du får spørsmål om hvem som skal «arve» innholdet deres – velg en annen i styret).

### 2.4 Den svarte linja øverst
Når du er innlogget og åpner nettsiden, ser du en linje øverst. De tre knappene du bruker mest:

- **Rediger med Elementor** – endre siden du ser på.
- **Oversett side** – oversette siden til engelsk.
- **Purge cache** – få endringene dine til å vises for alle (hold musen over og velg **Purge cache for all pages**).

---

## 3. Testsiden og den ekte nettsiden

STA har to versjoner av nettsiden:

| | Adresse | Hvem ser den |
|---|---|---|
| **Den ekte nettsiden** | [stastudent.no](https://stastudent.no) · innlogging: [stastudent.no/wp-admin](https://stastudent.no/wp-admin) | Alle |
| **Testsiden** | [stastudent.no/reliant](https://stastudent.no/reliant) · innlogging: [stastudent.no/reliant/wp-admin](https://stastudent.no/reliant/wp-admin) | Bare innloggede |

- Små endringer (en skrivefeil, en ny nyhet) kan du gjøre rett på den ekte nettsiden.
- Store endringer (ny side, ny layout) bør du prøve på testsiden først.
- Hvordan testsiden kopieres over til den ekte nettsiden står i [kapittel 9.5](#95-flytte-testsiden-over-til-den-ekte-nettsiden).

---

## 4. Endre tekst på en side

### 4.1 Oppskrift
1. Gå til [Alle sider](https://stastudent.no/wp-admin/edit.php?post_type=page).
2. Hold musen over siden du vil endre, og klikk **Rediger med Elementor**.
   *(Alternativ: åpne siden på nettsiden mens du er innlogget og klikk **Rediger med Elementor** i den svarte linja.)*
3. Vent til siden er lastet. Du ser siden til høyre og et panel til venstre.
4. **Klikk på teksten** du vil endre. Panelet til venstre viser nå teksten.
5. Endre teksten i panelet (eller skriv rett i siden).
6. Klikk **Publiser** (knappen øverst til høyre).
7. Gå tilbake til nettsiden og [tøm hurtigbufferen](#8-få-endringene-til-å-vises-tømme-hurtigbufferen).
8. Har du endret tekst, må den også oversettes: se [kapittel 7](#7-oversette-til-nynorsk-og-engelsk).

**Panelet til venstre har tre faner:**
- **Innhold** – teksten, lenken eller bildet.
- **Stil** – farge, størrelse og skrift. *Du trenger sjelden å endre noe her.*
- **Avansert** – avstander og om noe skal skjules på mobil.

**Se hvordan det ser ut på mobil:** klikk på skjerm-ikonet øverst i Elementor og velg mobil. Sjekk alltid mobil før du publiserer.

**Angre:** trykk `Ctrl` + `Z` (Mac: `Cmd` + `Z`) så lenge du ikke har lukket Elementor. Mer om å angre i [kapittel 9](#9-angre-og-ta-sikkerhetskopi).

### 4.2 Sidene og hvor du endrer dem

Lenkene i kolonnen «Rediger» åpner siden rett i Elementor.

| Side på nettsiden | Se siden | Rediger |
|---|---|---|
| Forsiden | [Se](https://stastudent.no/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=11503&action=elementor) |
| STA-styret | [Se](https://stastudent.no/sta-styret/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=30&action=elementor) |
| Studentparlamentet | [Se](https://stastudent.no/studentparlamentet/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=161&action=elementor) |
| Ledige verv | [Se](https://stastudent.no/ledige-verv/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=13129&action=elementor) |
| Råd, styrer og utvalg («Verv» i menyen) | [Se](https://stastudent.no/rad-styrer-og-utvalg/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=325&action=elementor) |
| Dokumenter | [Se](https://stastudent.no/dokumenter/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=163&action=elementor) |
| Sakspapirer | [Se](https://stastudent.no/dokumenter/sakspapirer/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=6443&action=elementor) |
| Protokoller | [Se](https://stastudent.no/dokumenter/referat-fra-moter-i-studentparlamentet/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=6445&action=elementor) |
| Resolusjoner | [Se](https://stastudent.no/dokumenter/resolusjoner/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=6450&action=elementor) |
| For tillitsvalgte | [Se](https://stastudent.no/for-tillitsvalgte/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=127&action=elementor) |
| Lokaler | [Se](https://stastudent.no/lokaler/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=8218&action=elementor) |
| Linjeforeninger (toppen av siden) | [Se](https://stastudent.no/linjeforeninger/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=6302&action=elementor) – selve listen: [kapittel 5.10](#510-linjeforeninger-og-studentaktiviteter) |
| Studentaktiviteter (toppen av siden) | [Se](https://stastudent.no/studentaktiviteter/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=6304&action=elementor) – selve listen: [kapittel 5.10](#510-linjeforeninger-og-studentaktiviteter) |
| Irene Bredals hederspris | [Se](https://stastudent.no/irene-bredals-hederspris/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=16160&action=elementor) |
| Trygg organisasjon | [Se](https://stastudent.no/trygg-organisasjon/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=13219&action=elementor) |
| Personvernerklæring | [Se](https://stastudent.no/personvernserklaering/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=3&action=elementor) |
| Om oss («Hvem er STA?») | [Se](https://stastudent.no/om-oss-2/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=13186&action=elementor) |
| Valgkomiteen | [Se](https://stastudent.no/valgkomiteen/) | [Rediger](https://stastudent.no/wp-admin/post.php?post=14071&action=elementor) |

> Virker en «Rediger»-lenke ikke? Finn siden i [Alle sider](https://stastudent.no/wp-admin/edit.php?post_type=page) i stedet. På testsiden: sett inn `/reliant` etter `stastudent.no` i lenken.

**Dette endrer du ikke i Elementor:**
- **Menyen øverst** og **oppsettet av bunnteksten** er en del av designet (koden). Kontaktinfo i bunnteksten endrer du i [STA Innstillinger](#59-kontaktinfo-i-bunnteksten). Nye menypunkter må legges inn av en med kodekunnskap ([Del 2](#legge-til-et-menypunkt)).
- **«Tilbake»-lenken** øverst på hver side kommer automatisk.
- **Nyhetene** under «Siste nytt» kommer automatisk fra [innleggene](#6-skrive-en-nyhet).

---

## 5. Vanlige oppgaver – steg for steg

### 5.1 Bytte et bilde
1. Åpne siden i Elementor ([kapittel 4.2](#42-sidene-og-hvor-du-endrer-dem)).
2. Klikk på bildet.
3. I panelet til venstre: klikk på bildet under **Velg bilde**.
4. Velg et bilde fra **Mediebiblioteket**, eller velg fanen **Last opp filer** og hent et bilde fra datamaskinen.
5. Til høyre i vinduet, under **Alternativ tekst**: skriv kort hva bildet viser (f.eks. «STA-styret 2026 utenfor Alibiet»). Dette leses opp for blinde og svaksynte.
6. Klikk **Velg** (eller **Sett inn medie**) og så **Publiser**.

Tips: bruk liggende bilder i god kvalitet (minst 1200 piksler brede). Bilder av personer krever at personene har sagt ja.

### 5.2 Endre en lenke eller knapp
1. Klikk på knappen eller teksten med lenken i Elementor.
2. Under **Lenke** i panelet:
   - Lenke til en side på stastudent.no: begynn å skrive navnet på siden og velg den fra listen som dukker opp.
   - Lenke til en annen nettside: lim inn hele adressen, med `https://`.
   - E-post: skriv `mailto:sta@uia.no`.
3. Skriv en knappetekst som sier hvor man havner, f.eks. «Les mer om Studentparlamentet» (ikke bare «Klikk her»).
4. **Publiser**.

### 5.3 Legge til en ny boks (et «kort») – kopier en som finnes
Den enkleste måten å holde designet likt på er å kopiere et kort som allerede finnes.
1. Åpne siden i Elementor.
2. **Høyreklikk** på kanten av kortet du vil kopiere (eller på et tomt område inni kortet).
3. Velg **Dupliser**. En kopi legges rett etter.
4. Klikk på teksten i kopien og skriv ny tekst.
5. Vil du flytte kortet: klikk på ikonet med tre streker øverst til venstre (**Navigator**). Der ser du alle delene av siden i en liste, og du kan dra dem opp og ned.
6. **Publiser**.

### 5.4 Fjerne eller skjule noe
- **Fjerne:** høyreklikk på det du vil fjerne → **Slett** → **Publiser**.
- **Skjule midlertidig (anbefalt hvis du er usikker):** klikk på delen → fanen **Avansert** → **Responsiv** → slå på **Skjul på skrivebord**, **Skjul på nettbrett** og **Skjul på mobil** → **Publiser**. Slå av igjen for å vise det.

### 5.5 Nytt styre (STA-styret)
1. Åpne [STA-styret i Elementor](https://stastudent.no/wp-admin/post.php?post=30&action=elementor).
2. Endre årstallet i overskriften (f.eks. «2027-2028»).
3. For hvert medlem: klikk på navnet, rollen, e-posten og telefonen og skriv nytt innhold. Bytt bildet ([5.1](#51-bytte-et-bilde)).
4. Bytt gruppebildet øverst.
5. **Publiser** og [oversett endringene](#7-oversette-til-nynorsk-og-engelsk) (navn trenger ikke oversettes).

### 5.6 Nye representanter i Studentparlamentet
1. Åpne [Studentparlamentet i Elementor](https://stastudent.no/wp-admin/post.php?post=161&action=elementor).
2. Under hvert fakultet: klikk på navnene og skriv de nye. Trenger du flere navn, dupliser en linje ([5.3](#53-legge-til-en-ny-boks-et-kort--kopier-en-som-finnes)).
3. Endre årstallet i overskriften.
4. **Publiser**.

### 5.7 Ledige verv
1. Åpne [Ledige verv i Elementor](https://stastudent.no/wp-admin/post.php?post=13129&action=elementor).
2. Oppdater datoen i teksten «Oversikten viser ledige verv per …».
3. Fjern verv som er besatt, og dupliser et kort for nye ledige verv.
4. **Publiser** og oversett endringene.

### 5.8 Legge ut et dokument (sakspapirer, protokoll, resolusjon)
1. Gå til [Last opp fil](https://stastudent.no/wp-admin/media-new.php) og last opp PDF-en.
2. Når den er lastet opp, klikk **Kopier URL til utklippstavle**.
3. Åpne riktig side i Elementor: [Sakspapirer](https://stastudent.no/wp-admin/post.php?post=6443&action=elementor), [Protokoller](https://stastudent.no/wp-admin/post.php?post=6445&action=elementor) eller [Resolusjoner](https://stastudent.no/wp-admin/post.php?post=6450&action=elementor).
4. Klikk på listen under riktig år. I tekstfeltet til venstre: sett markøren øverst i listen og skriv navnet på dokumentet (f.eks. «SP-møte 21.10.26»).
5. Merk navnet, klikk på **lenke-ikonet** (kjettingen) og lim inn adressen fra punkt 2. Trykk `Enter`.
6. **Publiser**.

Gi dokumenter et tydelig navn med dato. Last helst opp PDF-er der teksten kan markeres (ikke skannede bilder), så de kan leses av skjermlesere.

### 5.9 Kontaktinfo i bunnteksten
1. Gå til [STA Innstillinger](https://stastudent.no/wp-admin/admin.php?page=sta-settings).
2. Endre adresse, telefon, e-post, org.nr. eller lenkene til Facebook, Instagram og LinkedIn.
3. Klikk **Lagre endringer** og [tøm hurtigbufferen](#8-få-endringene-til-å-vises-tømme-hurtigbufferen).

### 5.10 Linjeforeninger og studentaktiviteter
Listene med foreninger hentes fra egne registre, ikke fra Elementor.

**Legge til en forening**
1. Gå til [Legg til linjeforening](https://stastudent.no/wp-admin/post-new.php?post_type=sta_association) (eller [Legg til studentaktivitet](https://stastudent.no/wp-admin/post-new.php?post_type=sta_activity)).
2. Skriv navnet øverst og beskrivelsen i tekstfeltet.
3. Fyll ut feltene under (studieprogram, campus, e-post, nettside, Facebook, Instagram).
4. Til høyre: velg **Fakultet** (eller **Aktivitetskategori**) og legg inn logo under **Fremhevet bilde**.
5. Klikk **Publiser**.

**Endre eller fjerne en forening:** gå til [alle linjeforeninger](https://stastudent.no/wp-admin/edit.php?post_type=sta_association) eller [alle studentaktiviteter](https://stastudent.no/wp-admin/edit.php?post_type=sta_activity), klikk på navnet for å endre, eller hold musen over og velg **Flytt til papirkurven** for å fjerne.

Filtrene og telleren «Viser X av Y» oppdaterer seg selv.

### 5.11 Samarbeidspartnere på forsiden
1. Åpne [forsiden i Elementor](https://stastudent.no/wp-admin/post.php?post=11503&action=elementor) og bla ned til **Samarbeidspartnere**.
2. Dupliser et partnerkort ([5.3](#53-legge-til-en-ny-boks-et-kort--kopier-en-som-finnes)).
3. Bytt logo ([5.1](#51-bytte-et-bilde)), navn og lenke ([5.2](#52-endre-en-lenke-eller-knapp)).
4. **Publiser**.

### 5.12 Lage en ny side
1. Gå til [Legg til side](https://stastudent.no/wp-admin/post-new.php?post_type=page).
2. Skriv tittelen og klikk **Publiser** (eller **Lagre utkast** til den er ferdig).
3. Klikk **Rediger med Elementor**.
4. Klikk på **tannhjulet** nederst til venstre (Side-innstillinger) og sett **Sidelayout** til **Elementor Full Width**. *Dette gir siden STA-designet.*
5. Det enkleste er å kopiere deler fra en lignende side: åpne den andre siden i Elementor, høyreklikk på en del → **Kopier**. Gå til den nye siden, høyreklikk → **Lim inn**.
6. **Publiser**.
7. Lenk til den nye siden fra en annen side ([5.2](#52-endre-en-lenke-eller-knapp)). Skal den inn i menyen øverst, må det gjøres i koden ([Del 2](#legge-til-et-menypunkt)).

### 5.13 Skjule eller slette en side
- **Skjule (anbefalt):** gå til [Alle sider](https://stastudent.no/wp-admin/edit.php?post_type=page), hold musen over siden → **Hurtigredigering** → **Status: Utkast** → **Oppdater**. Siden forsvinner for besøkende, men ingenting slettes. Slik er Prinsipprogrammet skjult i dag.
- **Slette:** hold musen over siden → **Flytt til papirkurven**. Den kan hentes tilbake fra papirkurven i 30 dager.

Husk å fjerne lenker til siden fra andre sider.

---

## 6. Skrive en nyhet

1. Gå til [Nytt innlegg](https://stastudent.no/wp-admin/post-new.php).
2. Skriv **overskriften** øverst.
3. Klikk under overskriften og skriv teksten. Trykk `Enter` for nytt avsnitt.
   - **Mellomtitler:** klikk på `+`, velg **Overskrift** (velg nivå **H2**, og **H3** under den).
   - **Bilde i teksten:** `+` → **Bilde**.
   - **Lenke:** merk teksten og klikk på kjettingen.
   - **Liming fra Word:** bruk `Ctrl` + `Shift` + `V` for å lime inn uten rar formatering.
4. I panelet til høyre (fanen **Innlegg**):
   - **Kategorier:** huk av **Nyheter**. *Bare nyheter med denne kategorien vises under «Siste nytt».*
   - **Fremhevet bilde:** bildet som vises på nyhetskortet. Bruk et liggende bilde og fyll inn alternativ tekst.
   - **Utdrag:** 1–2 setninger som oppsummerer saken. Vises på forsiden.
5. Klikk **Publiser** (to ganger – WordPress spør om du er sikker).
   - Vil du at nyheten skal komme ut senere: klikk på **Umiddelbart** ved siden av «Publiser», velg dato og tid, og klikk **Planlegg**.
6. [Tøm hurtigbufferen](#8-få-endringene-til-å-vises-tømme-hurtigbufferen). De tre nyeste nyhetene vises automatisk på forsiden.

**Endre eller slette en nyhet:** [Alle innlegg](https://stastudent.no/wp-admin/edit.php) → klikk på tittelen for å endre, eller hold musen over → **Flytt til papirkurven**.

Nyheter skrives på bokmål. Vil du nå internasjonale studenter, skriv en engelsk versjon under den norske i samme innlegg («English below»). Nyheter oversettes ikke automatisk.

---

## 7. Oversette til nynorsk og engelsk

Nettsiden finnes på **bokmål**, **nynorsk** og **engelsk** (velges øverst til høyre på nettsiden). Du skriver alltid på bokmål i Elementor, og oversetter etterpå.

**Når du har endret eller lagt til tekst på en side, må den oversettes på nytt.** Tekst som ikke er oversatt, vises på bokmål.

Menyen, bunnteksten og knappene i designet er allerede oversatt.

### 7.1 Engelsk
1. Gå til siden på nettsiden (mens du er innlogget).
2. Klikk **Oversett side** i den svarte linja øverst.
3. Til venstre: velg **English** hvis det ikke er valgt.
4. Klikk på blyanten ved teksten du vil oversette (eller velg den fra listen til venstre).
5. Skriv den engelske teksten i feltet under **English**.
6. Klikk **Lagre oversettelse** (Save translation).
7. Lukk oversetteren (krysset øverst til venstre) og [tøm hurtigbufferen](#8-få-endringene-til-å-vises-tømme-hurtigbufferen).

Se resultatet ved å velge **English** i språkvelgeren på nettsiden.

### 7.2 Nynorsk
1. Gå til [Nynorsk-tekst](https://stastudent.no/wp-admin/options-general.php?page=sta-nynorsk).
2. Klikk **Finn ny tekst på sidene**. Vent til det står hvor mange nye tekster som ble funnet.
3. Nå vises bare tekstene som mangler nynorsk. Venstre kolonne er bokmål, høyre kolonne er der du skriver nynorsk.
4. Skriv nynorsk i feltet til høyre. Er teksten lik på nynorsk (et navn, en tittel), skriv den samme teksten.
5. Klikk **Lagre** nederst.
6. [Tøm hurtigbufferen](#8-få-endringene-til-å-vises-tømme-hurtigbufferen) og sjekk siden med **Norsk nynorsk** i språkvelgeren.

Du kan søke etter en tekst i søkefeltet øverst på siden for å rette en oversettelse.

---

## 8. Få endringene til å vises (tømme hurtigbufferen)

Nettsiden lagrer en ferdig kopi av hver side for å være rask. Etter en endring:

1. Gå til nettsiden mens du er innlogget.
2. Hold musen over **Purge cache** i den svarte linja øverst.
3. Klikk **Purge cache for all pages**.
4. Last siden på nytt (`Ctrl` + `F5`, Mac: `Cmd` + `Shift` + `R`).

Ser siden fortsatt gammel ut (feil farger eller mangler design): velg også **Purge minify cache** i samme meny.

---

## 9. Angre og ta sikkerhetskopi

### 9.1 Angre en endring på en side
1. Åpne siden i Elementor.
2. Klikk på **klokke-ikonet** (Historikk) øverst til venstre i panelet.
3. Velg fanen **Revisjoner**. Du ser en liste over tidligere lagrede versjoner med dato og hvem som lagret.
4. Klikk på en versjon for å se den, og klikk **Bruk** (Apply).
5. **Publiser**.

### 9.2 Angre en endring på en nyhet
Åpne nyheten fra [Alle innlegg](https://stastudent.no/wp-admin/edit.php). I panelet til høyre: klikk **Revisjoner**, velg en tidligere versjon og **Gjenopprett denne revisjonen**.

**Slettet noe ved en feil?** Gå til papirkurven for [innlegg](https://stastudent.no/wp-admin/edit.php?post_status=trash&post_type=post) eller [sider](https://stastudent.no/wp-admin/edit.php?post_status=trash&post_type=page), hold musen over og klikk **Gjenopprett**. Ting ligger der i 30 dager.

### 9.3 Ta sikkerhetskopi av hele nettsiden
Gjør dette **før** store endringer, før oppdateringer og minst én gang i semesteret.
1. Gå til [Backup & Migration](https://stastudent.no/wp-admin/admin.php?page=wpstg_backup).
2. Klikk **Create Backup** (Lag sikkerhetskopi). Behold standardvalgene (alt huket av) og start.
3. Vent til den er ferdig (kan ta noen minutter).
4. Klikk på **Actions** / menyen ved backupen → **Download**, og lagre filen på et trygt sted, for eksempel STAs felles lagringsområde. Gi filen et navn med dato.

### 9.4 Hente tilbake en sikkerhetskopi
1. Ta en ny sikkerhetskopi av slik nettsiden er nå (i tilfelle du må tilbake).
2. Gå til [Backup & Migration](https://stastudent.no/wp-admin/admin.php?page=wpstg_backup).
3. Finn backupen i listen (eller **Upload Backup** hvis du har den som fil) → **Restore**.
4. Følg stegene. Du må kanskje logge inn på nytt etterpå.

Er du usikker, spør en administrator eller den som har ansvar for nettsiden før du gjenoppretter.

### 9.5 Flytte testsiden over til den ekte nettsiden
1. Ta en sikkerhetskopi av den ekte nettsiden ([9.3](#93-ta-sikkerhetskopi-av-hele-nettsiden)).
2. Gå til [WP Staging](https://stastudent.no/wp-admin/admin.php?page=wpstg_clone) på den ekte nettsiden.
3. Ved testsiden: klikk **Actions** → **Push changes** og følg stegene.
4. Etterpå: gå til [Innstillinger → Lesing](https://stastudent.no/wp-admin/options-reading.php) og sjekk at **«Be søkemotorer om ikke å indeksere dette nettstedet»** ***ikke*** er huket av. Ellers forsvinner STA fra Google.
5. [Tøm hurtigbufferen](#8-få-endringene-til-å-vises-tømme-hurtigbufferen) og se gjennom nettsiden.

### 9.6 Ta vare på nynorsk-oversettelsene
1. Gå til [Nynorsk-tekst](https://stastudent.no/wp-admin/options-general.php?page=sta-nynorsk) og bla ned til **Eksport / import**.
2. Merk all teksten i boksen (`Ctrl` + `A` inne i boksen), kopier og lim den inn i en tekstfil som du lagrer.
3. For å hente tilbake: lim inn teksten i boksen, klikk **Bruk teksten over i tabellen** og så **Lagre**.

---

## 10. Gode vaner (så alle kan bruke nettsiden)

Nettsiden er laget for å kunne brukes av alle, også de som bruker skjermleser, bare tastatur eller ser dårlig. Hold den slik:

- **Alternativ tekst på bilder** som viser noe viktig (personer, logoer, steder). Skriv kort hva bildet viser.
- **Overskrifter i riktig rekkefølge.** Sidetittelen er det største nivået (H1). Under den bruker du H2, og H3 under H2. Ikke velg nivå bare fordi du vil ha stor eller liten skrift. (I Elementor: klikk på overskriften → **HTML-tag**.)
- **Tydelig lenketekst:** «Les mer om Studentparlamentet» i stedet for «Klikk her».
- **Bruk fargene som allerede finnes.** Ikke legg hvit tekst på lys oransje – den blir vanskelig å lese.
- **Ikke bare farge** for å vise noe viktig. Skriv det også i tekst.
- **PDF-er med tekst**, ikke skannede bilder.
- **Videoer med teksting.**
- **Kort og enkelt språk.** Forklar forkortelser første gang (f.eks. «Råd, styrer og utvalg (RSU)»).

---

## 11. Hvis noe går galt

| Problem | Gjør dette |
|---|---|
| Endringen min vises ikke | [Tøm hurtigbufferen](#8-få-endringene-til-å-vises-tømme-hurtigbufferen) og last siden på nytt med `Ctrl` + `F5`. |
| Siden ser «rar» ut / mangler farger | Velg **Purge minify cache** i den svarte linja. Hjelper ikke det: [Elementor → Verktøy](https://stastudent.no/wp-admin/admin.php?page=elementor-tools) → **Regenerer filer og data**. Sjekk også at **Sidelayout** er **Elementor Full Width** (tannhjulet i Elementor). |
| Teksten vises på bokmål når jeg har valgt nynorsk/engelsk | Teksten er ny eller endret. [Oversett den](#7-oversette-til-nynorsk-og-engelsk). |
| Elementor laster ikke | Prøv en annen nettleser (Chrome eller Firefox). Slå av reklameblokkering. Hjelper ikke det: [Elementor → Verktøy](https://stastudent.no/wp-admin/admin.php?page=elementor-tools) → **Safe Mode**. |
| Jeg har ødelagt en side | [Gå tilbake til en tidligere revisjon](#91-angre-en-endring-på-en-side). |
| Jeg har slettet noe | Se i papirkurven: [sider](https://stastudent.no/wp-admin/edit.php?post_status=trash&post_type=page) eller [innlegg](https://stastudent.no/wp-admin/edit.php?post_status=trash&post_type=post). |
| Rar kode i titler (f.eks. `trp-…` eller `#!trpst#`) | Tøm hurtigbufferen. Kommer det tilbake, kontakt den som har ansvar for koden. |
| Jeg kommer ikke inn | Bruk **Mistet passordet?** på [innloggingssiden](https://stastudent.no/wp-admin), eller be en administrator i STA. |

---

## 12. Fakta om STA (brukes på nettsiden)

- Studentorganisasjonen i Agder, org.nr. 981 596 412
- Universitetsveien 24, 4630 Kristiansand S · Jon Lilletuns vei 9, 4879 Grimstad
- [sta@uia.no](mailto:sta@uia.no) · +47 38 14 20 10
- Sosiale medier: Facebook, Instagram og LinkedIn. STA bruker ikke X/Twitter.

---
---

# Del 2 – Teknisk del

*For den som skal endre koden. Redaktører trenger ikke denne delen.*

## Hvordan nettsiden er satt sammen

| Del | Rolle |
|---|---|
| **WordPress** | Publiseringssystemet. |
| **sta-theme** (`sta-theme/` i dette repoet) | Eget tema: toppmeny, språkvelger, søk, bunntekst, «Tilbake»-lenke, CSS/farger, nyhetsarkiv og enkeltnyhet, katalogene for linjeforeninger/studentaktiviteter, språkruting og nynorsk-systemet. |
| **Elementor 4 (gratis) + Happy Addons** | Innholdet på alle vanlige sider. Sidene bruker sidelayout **Elementor Full Width** (`elementor_header_footer`), som gir `body`-klassen `sta-native-layout`. |
| **STA Core Plugin** (`sta-core-plugin/`) | Innholdstyper `sta_association`, `sta_activity`, `sta_board_member`, `sta_parliament_rep`, `sta_position`, taksonomier og [STA Innstillinger](https://stastudent.no/wp-admin/admin.php?page=sta-settings) (`sta_contact_info`). |
| **TranslatePress 3 (gratis)** | Engelsk (`en_GB`). |
| **Nynorsk** (`sta-theme/inc/nynorsk.php`) | Egen ordbok (bokmål → nynorsk) i valget `sta_nn_translations`. Byttes inn i ferdig HTML når besøkende har valgt nynorsk. Redigeres på [Nynorsk-tekst](https://stastudent.no/wp-admin/options-general.php?page=sta-nynorsk). |
| **Yoast SEO** | Titler, meta-beskrivelser, sitemap. Temaet lager en meta-beskrivelse fra sideteksten når Yoast-feltet er tomt. |
| **WP-Optimize** | Sidebuffer og minifisering. Minifiseringen bruker temaversjonen som nøkkel. |
| **WP STAGING** | Testside (`/reliant/`) og sikkerhetskopier. |
| **Solid Security**, **Koko Analytics**, **OMGF** | Sikkerhet, besøksstatistikk uten informasjonskapsler, lokale Google-fonter. |

## Mappestruktur
```text
sta-theme/
  header.php                 Toppmeny (skrivebord + mobil), språkvelger, søk, «Tilbake»-lenke
  footer.php                 Bunntekst (kontaktinfo fra sta_contact_info)
  front-page.php             Forsiden (Elementor-innhold + skjult H1)
  index.php, single.php      Nyhetsarkiv og enkeltnyhet
  page-*.php                 Reservemaler (brukes bare der siden ikke er bygget i Elementor)
  functions.php              Oppsett, sidelayout-hjelper, [sta_directory], tilbake-lenke, meta-beskrivelse
  inc/translatepress-compat.php  Språkvalg (?lang=, /en/, informasjonskapsel), URL-hjelpere, ruting av «pene» lenker på testsiden
  inc/translatepress-bridge.php  Kobling mot TranslatePress + REST /sta/v1/translations
  inc/nynorsk.php            Nynorsk-ordbok, utbytting, adminside, REST /sta/v1/nynorsk
  assets/css/main.css        All CSS. Fargevariabler øverst i :root
  assets/js/navigation.js    Meny, nedtrekk, mobilmeny, søk, «Tilbake»
  assets/js/directory-filter.js  Filter/søk i katalogene
sta-core-plugin/             Innholdstyper og STA Innstillinger
tools/build_theme_zip.py     Bygger sta-theme.zip
tools/elementor-converter/   Skript brukt til å bygge og endre Elementor-sidene (referanse)
docs/                        Eldre dokumentasjon
```

## Endre og publisere temaet
1. Gjør endringen i `sta-theme/`.
2. Øk versjonen **begge** steder: `Version:` i `sta-theme/style.css` og `STA_THEME_VERSION` i `sta-theme/functions.php`.
3. Bygg pakken:
   ```bash
   python tools/build_theme_zip.py
   ```
4. Last opp: [Utseende → Temaer → Last opp tema](https://stastudent.no/wp-admin/theme-install.php?upload) → velg `sta-theme.zip` → **Installer nå** → **Erstatt gjeldende med opplastet**. Får du «The theme is missing the style.css stylesheet», last opp samme fil én gang til.
5. Purge cache + Purge minify cache. Test på mobil, med tastatur og på alle tre språk.
6. Commit og push.

Gjør gjerne endringen på testsiden først ([stastudent.no/reliant/wp-admin/theme-install.php?upload](https://stastudent.no/reliant/wp-admin/theme-install.php?upload)).

## Legge til et menypunkt
Menyen er skrevet i `sta-theme/header.php`, én gang for skrivebord (`.sta-desktop-nav`) og én gang for mobil (`.sta-mobile-nav`). Kopier et eksisterende punkt i begge og bruk:
```php
<a href="<?php echo esc_url(sta_get_localized_url('/min-side')); ?>" class="sta-nav-link">
    <?php echo esc_html(sta_t('Bokmål', 'English', 'Nynorsk')); ?>
</a>
```
`sta_get_localized_url()` lager riktig lenke for språk og for testsiden. `sta_t()` velger tekst etter språk.

## Språk
- Språk velges med `?lang=no|nn|en`, prefiks `/en/` eller `/nn/`, eller informasjonskapselen `sta_lang`.
- Engelsk: TranslatePress oversetter når språket er engelsk. Ellers holdes TranslatePress på standardspråket, slik at markørene (`trp-post-container`, `#!trpst#`) ikke lekker inn på norske sider.
- TranslatePress lagrer originaltekst nøyaktig, også `&nbsp;`. Får en tekst ikke oversettelse, sjekk om originalen har `&nbsp;`.
- Nynorsk: ordboknøkkelen er bokmålsteksten med mellomrom slått sammen og HTML-entiteter dekodet. Tekst mellom tagger og attributtene `alt`, `title`, `aria-label`, `placeholder` og meta-beskrivelser byttes. Skript og stiler røres ikke.
- REST (bare administrator, krever `X-WP-Nonce`):
  - `POST /wp-json/sta/v1/translations` – `mode`: `list` (`originals`), `save` (`pairs`), `recent` (`after_id`), `maxid`
  - `POST /wp-json/sta/v1/nynorsk` – `mode`: `list`, `save` (`pairs`)

## Testsiden og lenker
Testsiden ligger i `/reliant/` uten «pene» adresser (permalenker). Et `request`-filter i `inc/translatepress-compat.php` gjør at `/reliant/<slug>/` likevel åpner riktig side, innlegg eller vedlegg. Filteret gjør ingenting når permalenker er slått på (produksjon). WP STAGING bytter `/reliant/` til `/` ved push.

## Tilgjengelighet og farger
Nettsiden er sjekket mot WCAG 2.2 AA (kontrast på alle sider, tastaturnavigasjon, overskriftsnivåer, lenkenavn, alternativ tekst, skjemaetiketter, redusert bevegelse). Fargevariabler i `assets/css/main.css`:

| Variabel | Verdi | Bruk |
|---|---|---|
| `--sta-teal` | `#0e4c47` | Bakgrunn |
| `--sta-orange` | `#ef7330` | Ikoner, rammer, pynt (ikke tekst) |
| `--sta-orange-bg` | `#c44f14` | Fyll bak hvit tekst (4,7:1) |
| `--sta-orange-hover` | `#a8420f` | Hover på oransje knapper |
| `--sta-orange-text` | `#ffab78` | Oransje tekst/lenker på mørk bakgrunn |
| `--sta-teal-100` / `--sta-teal-200` | `#cde7e4` / `#9ecfc9` | Brødtekst / dempet tekst |

Ikke formell WCAG-sertifisering. Ikke testet med skjermleser og ikke kontrollert for PDF-tilgjengelighet.

## Oppdateringer
Oppdater WordPress og utvidelser jevnlig ([Oppdateringer](https://stastudent.no/wp-admin/update-core.php)), helst på testsiden først og alltid med sikkerhetskopi. Sjekk forsiden, STA-styret og Råd, styrer og utvalg etter Elementor-oppdateringer.

## Mer dokumentasjon
- `SITE_COMPLETENESS_AUDIT.md` og `SITE_RESTORATION_PROGRESS.md` i hovedrepoet ([FelixKnutsen/stastudent](https://github.com/FelixKnutsen/stastudent)): sjekklister og historikk.
- `docs/` i dette repoet: eldre dokumentasjon fra før Elementor-ombyggingen.
