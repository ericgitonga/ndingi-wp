# Changelog

## 0.4.1 — 2026-09-15

Home and About no longer show the small navbar logo alongside their large prominent logo
(client feedback via Valerie: "remove the small logo on the home page to leave the
big/major one"). `header.php` now skips the navbar logo on `is_front_page()` and the About
Hub template, and shifts the nav to the right edge in its place (new
`.site-header--no-logo` modifier in `main.css`) so the header row doesn't look
left-anchored with nothing to balance it. Every other page is unaffected — navbar logo
only, as before.

## 0.4.0 — 2026-09-12

Fixed the live host reporting About, Our Work, and Resources as missing (500/critical
error and 404) after the theme + plugin were uploaded: the `ndingi-wp-content` plugin was
never activated alongside the theme, so every page calling one of its functions
(`ndingi_get_child_pages()`, `ndingi_get_core_values()`, `ndingi_get_roster()`,
`ndingi_publication_url()`) hit a PHP fatal error — the homepage only survived because it
happens to skip that call when Our Work doesn't exist yet. Two changes:

- Every call site now goes through a new `ndingi_safe_get_child_pages()` /
  `ndingi_safe_get_core_values()` / `ndingi_safe_get_roster()` /
  `ndingi_safe_publication_url()` wrapper (`inc/template-helpers.php`) that degrades to an
  empty result instead of a fatal "critical error" white screen if the plugin is ever
  inactive, plus a red `admin_notices` warning naming exactly which pages depend on it.
- New **Tools → Ndingi Page Setup** admin page (in the plugin) creates the full page
  hierarchy — same titles, templates, parents, and real copy as
  `local-preview/seed.php`, minus its placeholder team members/news/publication — with one
  click, so a page missing on a host with no WP-CLI/SSH access (like Our Work here) doesn't
  need creating by hand. Safe to run repeatedly: anything already found by title is left
  untouched.

Verified against `ndingifoundation.org` directly and reproduced the exact failure (and the
fix) in the local preview by deactivating the plugin. Also confirmed live that the
Contact/Donate nav modals open correctly on both desktop and mobile — no code issue found
there.

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
