# ForgetFit Core

ForgetFit Core is the operational companion plugin for the ForgetFit WordPress theme. It keeps business data and permissions independent from presentation so changing themes cannot remove gym records.

## Current capabilities

- Versioned custom-table schema for leads, plans, memberships, sessions, bookings, check-ins, progress, notes, and audit events.
- Member, trainer, and manager roles with scoped capabilities.
- Three idempotently seeded membership plans.
- A native WordPress admin foundation screen with data and installation health.
- Persistent, duplicate-safe free-pass leads with consent and visit preferences.
- Searchable lead inbox with pipeline statuses, assignment, internal notes, and CSV export.
- Editable membership plans that feed the existing public theme design.
- Append-only audit events for lead and plan changes.
- Safe uninstall behavior that retains operational data by default.
- WordPress PHPUnit tests for the schema, roles, and plan seeder.

## Installation

1. Copy this directory to `wp-content/plugins/forgetfit-core`.
2. Activate **ForgetFit Core** in **Plugins**.
3. Open **ForgetFit** in the WordPress admin menu.

For local theme development, the repository can be linked into the plugins directory so plugin source remains version-controlled beside the theme.

## Data removal

Deactivating or uninstalling the plugin does not delete gym data. To intentionally remove all ForgetFit Core tables, roles, and options, define `FORGETFIT_CORE_REMOVE_DATA` as `true` before uninstalling. Back up the database first.

## Running tests

Install development dependencies with Composer, install the standard WordPress test library, set `WP_TESTS_DIR`, then run:

```sh
composer test
```
