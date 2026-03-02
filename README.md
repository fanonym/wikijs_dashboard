# Wiki.js Dashboard – Nextcloud App

Dashboard widget for displaying recent content from Wiki.js inside Nextcloud.

## Version
2.0.48

## Compatibility
- Nextcloud 32.0.3
- PHP 8.4 compatible

## Features
- Displays recent Wiki.js content on Nextcloud Dashboard
- Inline SVG icons (no external HTTP requests)
- Main widget icon: paragraph3.svg (inline)
- Item icons inline (base64 SVG)
- Stable WidgetItem implementation for NC32
- No custom CSP modifications required

## Widget Title
Dokumentace NPŠ

## Configuration
Configured via Nextcloud admin settings:
- Wiki.js URL
- Public URL
- Locale
- Item limit
- API token

## Notes
This is the stable baseline build for future upgrades (NC 33+ migration reference).
