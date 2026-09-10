# Changelog

## 0.3.2 — 2026-09-10

Reverted the logo back to the cream section above the hero — the client felt its colours
didn't sit well on the orange gradient — but trimmed the hero's top padding so "Empowering
Communities…" now sits immediately below the logo with no dead space. Matches the same
revision on the Next.js site.

## 0.3.1 — 2026-09-10

Moved Home's prominent logo into the orange hero section, directly above "Empowering
Communities…" — the hero now starts immediately after the navbar again, matching client
feedback (and the same fix on the Next.js site).

## 0.3.0 — 2026-09-10

Home and About now show a large, centred logo above their content (new
`template-parts/prominent-logo.php`), matching the same change on the Next.js site —
every other page keeps the logo in the header navbar only. About also drops its
"About" heading/text entirely: just the logo, then the Who We Are and People cards.

## 0.2.0 — 2026-09-10

Our Work's programme cards (`/our-work/` and the homepage's preview grid) now show the client's
supplied photograph for each programme, with the title and blurb underneath, instead of an
icon tile — click-to-modal behaviour is unchanged. Along the way, fixed a real bug this surfaced:
the homepage's Our Work grid was rendering zero cards, because its page lookup used a bare
slug (`get_page_by_path('education')`) that can't find a page nested under Our Work — replaced
with the same child-page lookup `page-our-work.php` already uses, removing a hardcoded slug
list in the process. Also replaced `get_page_by_title()` (fully deprecated since WP 6.2) in
`local-preview/seed.php` with a small `WP_Query`-based helper.

## 0.1.0 — 2026-09-10

Initial scaffold: full WordPress port of the ndingi Next.js/Sanity site, evaluated as a
separate repo. Custom theme (`ndingi-wp`) + content-model plugin (`ndingi-wp-content`) covering
every route from the original — homepage, About hub, Who We Are + detail grid, Mission,
Vision, Core Values, People accordion, Management Team/Trustees/Board of Directors rosters,
Our Work hub, the three programme pages, Partners, Resources hub, News list + single post,
Publications, Donate, Contact — plus a native `wp_mail()` contact form handler and a
Docker-free local preview via WordPress's SQLite database integration.
