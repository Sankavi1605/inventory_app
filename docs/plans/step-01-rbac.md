# Step 01 – RBAC & Purchase-Order Ownership

## Objective
Guarantee that **inventory staff** receive full dashboard visibility/data access while **purchase-order creation & approval** is restricted exclusively to that role. Deliverables for this step are design-level and implementation-ready instructions so development can proceed with confidence.

## Current State Snapshot
- `users` table (see `dev/inventory_schema.sql`) lacks any `role` column; only core profile data exists.
- Authentication (`app/controllers/Auth.php`) forces every session to `$_SESSION['user_role'] = 'admin'` and `user_role_id = 2`, regardless of actual responsibilities.
- Helper guards in `app/helpers/session_helper.php` simply check login status (`requireRole()` never inspects roles). Any user that authenticates is effectively an admin today.
- Dashboard controllers (`app/controllers/Pages.php`) call `requireLogin()` but enforce no role distinctions. Purchase-order functionality has not been implemented yet, so there are no hooks to limit.

**Implication:** There is no meaningful RBAC. Before later requirements (alerts, reporting, supplier data) can trust permissions, we must introduce real role metadata, enforce it, and pre-wire the purchase-order flow for inventory staff only.

## Target Role Model
| Role Key           | Purpose/Notes                                                    | Dashboard Access | Purchase Order Authoring |
|--------------------|-----------------------------------------------------------------|------------------|--------------------------|
| `superadmin`       | Platform owners; can manage tenants + configuration             | ✅ full           | 🚫 (kept supervisory)    |
| `admin`            | Site administrators (IT/ops). Manage users, settings            | ✅ full           | 🚫                        |
| `inventory_staff`  | Inventory operations team. Needs full dashboards and PO control | ✅ full           | ✅ exclusive              |
| `maintenance`      | Equipment maintenance crews                                     | 🔶 limited        | 🚫                        |
| `security`         | Security team dashboards                                        | 🔶 limited        | 🚫                        |
| `resident`/`external` | Portal-only users                                             | 🚫                | 🚫                        |

> "Dashboard access" here means the rich dashboard at `Pages::index`/`Pages::dashboard`. Other roles may receive future lightweight cards, but Step 01 assures that inventory staff joins admins in seeing the existing experience.

## Implementation Plan

### 1. Database
- **Add `role` column** to `users` (`ENUM` or `VARCHAR(50)` with default `'external'`). Document migration in `dev/migrations/20241121_add_user_role.sql`.
- **Seed data**: ensure the default admin inserted in `dev/inventory_schema.sql` gets role `superadmin`.
- Optional but recommended: add `status ENUM('pending','active','suspended') DEFAULT 'pending'` for future gating.

### 2. Model Layer
- Update `User::register()` to insert role (default `'external'` unless an admin assigns something else). Preserve compatibility by checking `isset($data['role'])`.
- Expose a helper like `User::updateRole($userId, $role)` for admin UI.

### 3. Session & Helper Utilities
- In `Auth::createUserSession()`, set `$_SESSION['user_role'] = $user->role ?? 'external';` and drop the obsolete numeric `user_role_id`.
- Revise `session_helper.php`:
  - `getCurrentUserRole()` returns string with fallback `'external'`.
  - `hasRole()` accepts string or array plus `{ 'match' => 'exact' | 'hierarchy' }` option. Default to hierarchy so `superadmin` counts as `admin` when desired.
  - `requireRole()` calls `hasRole()` and redirects to `pages/unauthorized` with flash message when unauthorized.
  - Introduce `requireExactRole('inventory_staff')` convenience wrapper or use `requireRole('inventory_staff', ['match' => 'exact'])` for purchase-order routes.

### 4. Controller Guardrails
- Dashboards (`Pages::index`, `Pages::dashboard`) should call `requireRole(['inventory_staff','admin','superadmin'])` to assert these roles only.
- Any existing admin-only sections (alerts, reports, settings) should be updated to `requireRole(['admin','superadmin'])` to keep parity.
- When the purchase-order controller/routes are created, guard them with `requireRole('inventory_staff', ['match' => 'exact'])`.

### 5. View/UI Adjustments
- Navigation: hide dashboard links for users lacking required role (check `getCurrentUserRole()` in navbar component).
- Profile sidebar selection currently uses numeric IDs. Refactor to switch on role strings to keep UI consistent once numeric IDs disappear.

### 6. Purchase-Order Stubs (Forward Prep)
- Define route placeholders (e.g., `PurchaseOrders::create`, `PurchaseOrders::store`, `PurchaseOrders::approve`) returning `501`/view until later steps, but already guarded by the strict inventory-staff check. This makes future development straightforward and documents expectations.

### 7. Testing & Verification
1. **Migration**: run new SQL on dev DB; confirm `role` column exists and defaults properly. Update seed admin row.
2. **Login Smoke Test**: log in as seeded `superadmin`, confirm dashboards load. Manually change DB role to `inventory_staff` and verify dashboards still show; change to `maintenance` and confirm redirect to `pages/unauthorized` when visiting dashboard.
3. **Helper Unit Tests** (if PHPUnit available): add coverage for `hasRole()` permutations (single role, array, strict match).
4. **Security Regression**: ensure unauthorized users receive 403/redirect and logs capture the attempt if needed.

### 8. Acceptance Criteria
- [ ] `users` table stores explicit role per user; seed admin labeled `superadmin`.
- [ ] `getCurrentUserRole()` returns correct value from session.
- [ ] `requireRole()` truly blocks unauthorized access (verified via manual test or automated test).
- [ ] Inventory staff accounts reach dashboards; other non-listed roles are redirected.
- [ ] Only inventory staff accounts (strict match) can hit purchase-order endpoints (even if stubbed for now).

### 9. Open Questions / Follow-Ups
- Who assigns roles to new registrants? (Assumption: admin UI or manual DB edit until Step 2.)
- Should admins also retain purchase-order authority as backup? Current requirement says **no**; double-check with stakeholders before cutting them off.
- Any audit logging required when access is denied? Could integrate with `ActivityLog` later.

This document completes Step 01 by capturing the RBAC gaps and the concrete implementation path. Next up: Step 02 (custom categories) can proceed referencing this foundation.
