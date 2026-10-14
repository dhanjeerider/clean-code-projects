# Clean Code Projects

A lightweight WordPress plugin for publishing HTML, CSS and JavaScript projects as standalone pages, without the active theme layout or WordPress front-end assets. It also provides a responsive project grid and an iframe-based single-project embed shortcode.

**Current release:** 1.1.0  
**Requirements:** WordPress 5.9+, PHP 7.4+  
**Plugin folder:** clean-code-projects  
**Main file:** clean-code-projects.php

## Features

- Projects custom post type managed under **Clean Projects** in WordPress admin.
- Standalone project URLs without the active theme, wp_head output, or WordPress front-end CSS/JS.
- Four output modes: Auto, External link, Code verbatim, and Code skeleton.
- Raw HTML/CSS/JS project source storage.
- External live project URL rendered in a full-viewport iframe.
- Responsive project grid with optional client-side search and live iframe previews.
- Single-project iframe embed shortcode.
- Syntax-highlighted admin code editor, project list shortcuts, and settings page.
- Automatic Projects page creation on activation.
- Uninstall cleanup that removes plugin options and metadata while retaining project posts.
- Developer filters for rewrite slug and clean-page output.

## Installation

1. Download the repository as a ZIP.
2. Copy the plugin directory into `wp-content/plugins/clean-code-projects/`.
3. Confirm the directory contains `clean-code-projects.php` and `uninstall.php`.
4. Activate **Clean Code Projects** in WordPress → Plugins.
5. Open **Clean Projects → Add New Project**.

## Quick start

1. Add a project title and optional featured image.
2. Paste the complete HTML/CSS/JS source into **Project Code**, or enter an external project URL.
3. Select an output mode. Auto is suitable for most projects.
4. Publish and use **Open clean page** to view the standalone URL.
5. Add `[ccp_projects]` to any page to display the project library.

## Output modes

| Mode | Behaviour |
|---|---|
| `auto` | Uses pasted code when available; detects full HTML documents or wraps fragments. If code is empty and a URL exists, uses the external iframe mode. |
| `link` | Displays the configured external URL in a full-viewport, borderless iframe. |
| `full` | Prints saved code as-is, without adding a document skeleton. |
| `fragment` | Wraps a snippet in a minimal document with doctype, charset, viewport, title, and body. |

The clean-page handler removes queued WordPress/theme assets before sending the document. Password-protected projects retain the normal WordPress password form.

## Shortcodes

### Project grid

```text
[ccp_projects]
```

| Attribute | Default | Description |
|---|---|---|
| `columns` | Settings value (3) | Number of columns, clamped from 1 to 6. |
| `limit` | Settings value (-1) | Maximum projects; non-positive displays all. |
| `per_page` | Settings value (-1) | Fallback limit when limit is empty. |
| `orderby` | `date` | WordPress query ordering field. |
| `order` | `DESC` | `DESC` or `ASC`. |
| `ids` | empty | Comma-separated project IDs, preserving supplied order. |
| `search` | Settings value | `1` shows client-side search; `0` hides it. |
| `preview` | Settings value | `1` shows iframe covers if no featured image exists. |
| `new_tab` | Settings value | `1` opens links in a new tab. |
| `title` | empty | Optional heading above the grid. |

Examples:

```text
[ccp_projects columns="4" limit="12" orderby="title" order="ASC"]
[ccp_projects ids="12,15,18"]
[ccp_projects search="0" preview="1" title="My Web Projects"]
```

### Embed a single project

```text
[ccp_project id="123" height="600"]
```

- `id`: published project ID; defaults to the current project on a project singular page.
- `height`: iframe height in pixels (default 520).
- `title`: optional toolbar label.

The embed includes an **Open full page** link.

## Settings

Open **Clean Projects → Settings** to configure:

- Grid columns (1–6).
- Projects per page (-1 for all, or a positive limit up to 200).
- Live iframe cover when a featured image is missing.
- Search box visibility.
- Whether project links open in a new tab.

Shortcode attributes override saved defaults for an individual grid.

## Permalinks

The custom post type key is `ccp_project`. The default public rewrite slug is `/p/`, for example `/p/my-project/`. After deploying or changing rewrite rules, visit **Settings → Permalinks** and click **Save Changes** if project URLs return 404.

Change the slug with the `ccp_rewrite_slug` filter.

## Developer hooks

| Hook | Type | Purpose |
|---|---|---|
| `ccp_rewrite_slug` | Filter | Customize the public project URL slug. |
| `ccp_clean_page_enabled` | Filter | Return false to let the active theme render a project. Receives enabled flag, post, and code. |
| `ccp_clean_page_code` | Filter | Modify source code before document generation. |
| `ccp_clean_page_document` | Filter | Modify the generated document. |

Change the slug:

```php
add_filter( 'ccp_rewrite_slug', function ( $slug ) {
    return 'demos';
} );
```

Disable clean rendering for one project:

```php
add_filter( 'ccp_clean_page_enabled', function ( $enabled, $post, $code ) {
    return 123 === (int) $post->ID ? false : $enabled;
}, 10, 3 );
```

## Security

Project source is intentionally stored and emitted as raw HTML/CSS/JS. Only trusted users should be allowed to edit project code. The metadata save flow uses a nonce, autosave/revision checks, and the `edit_post` capability. External URLs are normalized and escaped. Embedded preview iframes are sandboxed; standalone output is deliberately unsandboxed so project code can run normally. Never publish untrusted code.

## Uninstall behaviour

The uninstall routine removes plugin options, the stored Projects page ID, the activation notice transient, project metadata, and mirrored HTML files under `uploads/clean-code-projects/`. Project posts are retained to avoid accidental content loss. The routine exits without cleanup on multisite.

## Repository layout

```text
clean-code-projects/
├── clean-code-projects.php
├── uninstall.php
├── README.md
├── LICENSE
└── .gitignore
```

## Development

No Composer, npm, or build step is required. Validate PHP syntax with:

```bash
php -l clean-code-projects.php
php -l uninstall.php
```

Before release, test activation, all output modes, project grid options, embeds, password protection, permalink refresh, and uninstall behaviour on a staging site.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE) and the [GNU GPL](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).

## Author

mr-Dhanjee-kumar
