# Wemake Tag Manager - Decisions

Newest on top. Never delete an entry; mark replaced ones `Status: superseded by <date> <title>`.

## 2026-11-25 - Updates through the Wemake update server, old copies included

Decided by: Vitalii
Context: Copies updated in two ways: 1.34.1-1.35 from the latest GitHub release (their own GitHub updater), 1.31-1.34 through the old repository.wemake.co.il updater (an admin's click on the Plugins screen). Same plan as wemake-acs 1.63 and wemake-cf7-alert 2.19 the same day.
Decision: From 1.36 the plugin ships the shared Wemake update client (`inc/class-wemake-update-client.php`) with the `Update URI` header; the GitHub updater and the old updater are removed. Releases are built by `.github/workflows/release.yml` from a `v*` tag, published only after `wemake-tagman.zip` is attached. The update server offers the latest release to old copies too: 1.34.1-1.35 through their GitHub updater, 1.31-1.34 through `update.php` (`WM_UPDATE_FROM_GITHUB`). Every copy must be able to update by itself. WordPress auto-updates are not forced on.
Why: One safe update path for all copies; installs recorded on the update server.
Affects: The repository stays public (copies 1.34.1-1.35 read its releases). Every release needs a tag that matches the `Version` header and `WMTM_PLUGIN_VERSION`. Removing `wemake-tagman` from `WM_UPDATE_FROM_GITHUB` or `WM_CLIENT_UPDATES` stops updates for old copies or for 1.36+. The `wmtm_l` option checks are left as they are.
Status: active
Task: https://task.nexfield.day/t/1f244bcb-0313-55f0-8ec7-53ab75b7acfb
