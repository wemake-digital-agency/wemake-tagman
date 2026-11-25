# Wemake Tag Manager - Changelog

Releases and other changes, newest on top. Every entry: What, Why, Verify, Rollback, Task.
Versions before 1.36 have no entries here; see the git history.

## 2026-11-25 - 1.36: updates through the Wemake update server, settings page fixed

What:
- Shared update client (`inc/class-wemake-update-client.php`, started for every request in `wemake_tagman.php`) and the `Update URI` header. Removed the GitHub updater (`inc/admin_update_plugin_github.php`) and the old updater (`inc/admin_update.php`, the update code in `assets/js/admin.js`). Version 1.36.
- `assets/js/admin.js` ran only when the old updater's `wmtm_update_param` existed, so in 1.35 (old updater switched off) the settings page script never ran: no AJAX save, no tabs, no switches. It now runs on the settings page (`.wmtm-form`).
- `inc/frontend.php`: removed the old updater's maintenance mode (the whole site showed "Briefly unavailable" while `wmtm_maintenance_on` was set).
- `inc/action.php`: removed the PHP error display block (`WMTM_AJAX_DEBUG`).
- `inc/admin.php`: the "Settings" link on the Plugins screen used a wrong slug and URL; now `[data-slug='wemake-tagman']` → `admin.php?page=wm-tag-manager`.
- Release workflow `.github/workflows/release.yml` (tag-based, draft first, asset `wemake-tagman.zip`) instead of `build-release.yml`. README written, docs added.
Why: Decision 2026-11-25 "Updates through the Wemake update server, old copies included"; the settings page bug found while preparing it.
Verify: After the tag `v1.36` the GitHub release has `wemake-tagman.zip` with the top folder `wemake-tagman/`. Tested locally (WordPress 7.0, PHP 8.3, the update server code with `wemake-tagman` in both lists): the real 1.34 old updater got 1.36 from `update.php` and removed its old files, the plugin stayed active; 1.35 with its GitHub updater installed 1.36 from the release zip; 1.36 updated to 1.36.1 through the update server; the GTM snippet is printed when enabled; the settings page renders with nonce-protected AJAX URLs; with `wmtm_maintenance_on` = 1 the front end renders normally. PHP lint on 7.4 and 8.3, `admin.js` parses.
Rollback: Publish a new release with the previous code and a higher version; on the update server remove `wemake-tagman` from `WM_UPDATE_FROM_GITHUB` and `WM_CLIENT_UPDATES`.
Task: https://task.nexfield.day/t/1f244bcb-0313-55f0-8ec7-53ab75b7acfb
