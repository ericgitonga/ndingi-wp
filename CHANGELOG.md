# Changelog

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
