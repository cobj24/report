
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cobj24/report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/cobj24/report/)
[![Code Coverage](https://scrutinizer-ci.com/g/cobj24/report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/cobj24/report/)
[![Build Status](https://scrutinizer-ci.com/g/cobj24/report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/cobj24/report/)

# MVC-kurs: Webbapplikation i Symfony

Det här är en webbplats byggd i PHP med Symfony-ramverket som en del av kursen **"oophp/mvc"** vid Blekinge Tekniska Högskola.

Syftet är att lära sig objektorienterad PHP-programmering, routing, templates med Twig, JSON-API:er och databasanslutningar med Doctrine.

---

## 📦 Innehåll

- [x] Routing med Symfony
- [x] Templating med Twig
- [x] JSON-endpoints (API)
- [x] Responsiv layout
- [x] Rapport- och om-sidor
- [x] Lucky number och citat-API
- [x] **Poker Square-spel**
  - 5x5 kortspel med poängräkning
  - Automatisk tips-funktion
  - Highscore med namn
  - Visualisering av statistik
  - Dynamiskt gränssnitt med kortbilder

---

## 🃏 Poker Square – Kortspel

Detta är ett interaktivt spel där du placerar kort i ett 5x5-rutnät för att skapa bästa möjliga pokerhänder i rader och kolumner.

Spelfunktioner:
- Automatisk poängräkning för varje rad/kolumn
- Tips om bästa placering för varje kort
- Highscore med namn
- Statistik över bästa hand, drag kvar m.m.
- Visuella kortbilder från `.svg`-filer

Spelstart: [http://localhost:8000/proj](http://localhost:8000/proj)

---

## 🚀 Kom igång

### 1. Klona repot

```bash
git clone https://github.com/cobj24/report.git
cd report
