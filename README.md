# RudWEb extras

The side material of my PHP framework RudWEb, 2008 to 2010: the notes and road map, the phpDocumentor setup, the logo
and layout drafts, and the snippets I collected and wrote while learning. A historical archive, kept read-only: PHP 4 and
5 code with `mysql_*` calls, not safe to deploy.

## Its place in the line

RudWEb → WEb is my PHP framework line, one archive per era:

1. [rudweb-2007-2009](https://github.com/webservco-legacy/rudweb-2007-2009): the first engine and RudWEb 0.1 to 0.8;
2. [rudweb-2009-2011](https://github.com/webservco-legacy/rudweb-2009-2011): RudWEb 1 to 4 in Subversion, then 5.0;
   [rudwi](https://github.com/webservco-legacy/rudwi), the web desktop, and **rudweb-extras** (this one) belong to it;
3. [web-2012-2017](https://github.com/webservco-legacy/web-2012-2017): WEb 5 to 7;
4. [web7](https://github.com/webservco-legacy/web7): WEb7 as a git archive;
5. the framework in git since 2017: [framework-v9](https://github.com/webservco-v09/framework-v9), then
   [framework](https://github.com/webservco-v13/framework) (v10 to v13) and the WebServCo components.

## History

One commit per tarball, dated by its newest file:

| Date | Commit | |
|---|---|---|
| 2009-02-27 | 9101bd9 | `stuff/`: the study material collected until 2009-02 |
| 2009-09-19 | b8dce65 | `docs/`: the notes, the road map and the phpDocumentor setup |
| 2010-03-13 | 1cc253d | `graphics/`: the logo and layout drafts |

## What is in it

- `docs/`: the road map (`road`), notes on dependencies, layout and what to remember, the phpDocumentor user settings
  (`phpdoc/user/`) and its CHM and HTML frames converters as zips, with the pages they made for RudWEb;
- `graphics/`: the ladybug logo drafts (CorelDRAW), the layout drafts (Photoshop and the work zips of 2009-03), the
  RudiSoft and hosting site drafts (`rs/`);
- `stuff/`: AJAX, Apache, CSS, JavaScript, PHP, regular expression, Smarty and SQLite snippets and notes, the notes of
  the MVC study (`mvc/`), a small MySQL page and backup tool (`mysql/st-lib/`, `mysql/ubmypg.php`), the RudWEb
  documentation as CHM (`testing/docu/`), the same site drafts as `graphics/rs/` (`tpl/rs/`).

Left out of the whole history: the downloaded templates and packages (frameworks, MySQL classes, error handlers, editors,
PDF, icon and menu libraries), the free-hosting accounts and site copies, client and employer material (site skins,
catalogue and product data, a menu built for a client), other people's posts and contact names, a binary and a database
of unknown content. Where a client's name was left in code, it reads `travel-agency` or `travel_agency`; passwords read
`REDACTED`. The messages of the `stuff/` and `graphics/` commits are shortened to what is kept.

## How it was built

From three tarballs (`rudweb.stuff`, `rudweb.docs`, `rudweb.graphics`), turned into git in 2026 (4 commits),
each one commit, dated by its files. The last commit before this README sets LF line endings (not in the third-party
code).

## Third-party code

Kept as saved, each with its author's terms where it names any: the MySQL Dump Utility 1.4 by Erik van Geene in
`stuff/mysql/st-lib/sql/` (the backup and restore scripts and their readme), the GRC pure-CSS menu stylesheet (public
domain), a bad-word filter ("free to use"), a hex-RGB converter from a free script site, an Apache directory-listing
`.htaccess` and a mod_rewrite cheat sheet; the phpDocumentor converters (LGPL).

## Licence

New BSD (see `LICENSE`), added in 2026 for my own code; these folders shipped no licence file.
