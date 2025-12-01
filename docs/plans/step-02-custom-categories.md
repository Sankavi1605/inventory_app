# Step 02 – Custom Category Enablement

## Objective
Allow operations teams to define **any category taxonomy they need** (beyond the starter set of materials/tools/equipment/consumables) while keeping the **same tracking template for every category**. This plan ensures schema, APIs, and UI all support flexible category creation, editing, and assignment without introducing category-specific logic such as expiration-only fields.

## Current Snapshot
- Database: `categories` table already exists with `id`, `name`, `description`, timestamps. Default seed rows (Electronics, Furniture, etc.) live in `dev/inventory_schema.sql`.
- Models: `app/models/Category.php` supports CRUD methods (`getAllCategories`, `addCategory`, `updateCategory`, `deleteCategory`) but there is no validation or metadata beyond name/description.
- UI: Inventory/equipment forms load existing categories via `<select>` (see `app/views/inventory_add.php`, `inventory_edit.php`). Users **cannot create or edit categories** from the UI; only developers or direct DB changes can add new entries.
- Controllers: No dedicated Category controller/settings page exists. `Pages::inventory` grabs all categories just to populate select boxes.
- Business rule gap: Nothing enforces a consistent tracking schema; however, since no per-category options exist, the rule is implicitly respected.

## Requirements Recap
1. Users must add custom categories (e.g., "Concrete Forms", "PPE", "Fleet Vehicles") without developer intervention.
2. Default set (materials, tools, equipment, consumables) remains available but behaves like any other category.
3. All categories follow the same tracking fields—no special cases for expiration dates, etc.
4. Inventory staff (and admins) can manage categories; other roles cannot.
5. Category list should support re-labeling, descriptions, archive/restore (soft delete) to keep dropdowns clean without losing history.

## Implementation Plan

### 1. Database Enhancements
- Add optional metadata columns:
  - `slug VARCHAR(120)` (unique, for search/filter URLs).
  - `is_active TINYINT(1) DEFAULT 1` to support archiving instead of hard deletes.
  - `color_hex VARCHAR(7)` (optional) if future dashboards colorize categories (placeholder for now).
- Provide migration `dev/migrations/20241121_category_metadata.sql` that:
  - Adds the new columns.
  - Back-fills `slug` using `LOWER(REPLACE(name,' ','-'))` ensuring uniqueness.
- Update seed data to specify slugs and mark `is_active=1`.

### 2. Model/Validation Layer
- Extend `Category` model:
  - Validation helpers for unique name/slug.
  - Support filtering `getAllCategories($onlyActive = true)` to keep dropdowns clean.
  - Introduce `archiveCategory($id)` / `restoreCategory($id)` toggling `is_active`.
- Ensure `addCategory` / `updateCategory` auto-generate slugs when not provided.

### 3. Controller & Routes
- Create `Categories` controller (or add module under settings) with actions:
  - `index()` list with filters (active/archived) + search.
  - `store()` for new category submissions.
  - `update($id)` for renaming & description edits.
  - `archive($id)` / `restore($id)` to toggle active flag.
- Apply `requireRole(['inventory_staff','admin','superadmin'])` guard around all actions.

### 4. UI/UX Workflows
- Add a management screen reachable from Inventory nav ("Categories"). Features:
  - Table showing name, item count, status, last updated.
  - Quick action buttons (edit, archive/restore).
  - Inline form or modal to add new category (name required, description optional).
- Inventory item forms:
  - Fetch active categories only.
  - Provide "+ Create new" affordance that opens the same modal (AJAX) so users can add categories without leaving the form.
- Communicate the "uniform tracking" rule via helper text (e.g., "All categories share the same inventory fields").

### 5. Access & Audit
- Log category CRUD actions via `ActivityLog` to maintain traceability (action types: `category_created`, `category_updated`, `category_archived`).
- Ensure only inventory staff/admins see category navigation links.

### 6. Testing
- Unit/integration tests for Category model methods (slug generation, archive filter, unique constraints).
- Feature tests (if available) or manual QA scenarios covering:
  1. Creating a new custom category and seeing it immediately in the inventory form dropdown.
  2. Editing a category name/description with existing inventory items referencing it.
  3. Archiving a category removes it from dropdowns but keeps it in reports/history.
  4. Restoring a category reintroduces it without data loss.

### 7. Acceptance Criteria
- [ ] Users can create categories from the web UI without DB access.
- [ ] Inventory forms reflect newly created categories instantly.
- [ ] No category introduces unique tracking fields; all items share the same schema.
- [ ] Archived categories disappear from selection lists but remain queryable in reports.
- [ ] Activity log entries capture who created/updated/archived categories.

This plan sets the groundwork for development of flexible category management that meets the stated requirements while aligning with the existing MVC architecture.
