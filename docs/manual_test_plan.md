# Manual Test Plan

This checklist exercises the primary end-to-end flows after deploying the latest backend changes. Run it on every staging/production deploy (or after applying database migrations).

## Prerequisites

- Database has been migrated with `dev/migrations/20241111_add_user_roles.sql`
- Default `admin` account password noted (or a known superadmin exists)
- Browser session cleared between role switches

---

## 1. Signup → Admin Activation

1. Visit `/auth/signup` and register a new user (role auto-set to `resident`).
2. Verify the inline validations (blank fields, mismatched passwords) surface the new error messaging.
3. Log out and sign back in with the new credentials immediately—accounts are available right away in single-admin mode.

## 2. Inventory CRUD

1. As admin, open `/inventory`.
2. Click **Add Item** and submit the form with:
   - Name: `Test Steel Beam`
   - SKU: `SB-001`
   - Quantity Available/Total: `5 / 10`
   - Minimum Quantity: `3`
   - Unit Cost: `250.00`
3. Verify success flash and that the card appears with the correct chips.
4. Open the item, edit (change quantity + description), then view the detail page to ensure the transaction log updates.
5. Trigger a quantity adjustment through the quick action (if available) and confirm audit logging in the DB (`activity_logs`).
6. Delete the test record and verify the list updates + flash shows success.

## 3. Equipment & Maintenance

1. Navigate to `/equipment` as admin.
2. Add a new equipment item (assign to the new `resident` user created earlier, set next maintenance within 7 days).
3. Confirm the dashboard stats increment (equipment assigned + maintenance due).
4. Edit the equipment record: change status to `maintenance`, update `next_maintenance`, and confirm the maintenance log shows the insertion.
5. View the detail page to confirm assignment info and maintenance table render correctly.

## 4. Dashboard Widgets & Alerts

1. Log in as admin, hit `/index` (Dashboard).
2. Ensure the stat tiles show live counts (no placeholder numbers).
3. The “Recent Checkouts,” “Low Stock Alerts,” and “Upcoming Maintenance” cards should list the latest 3 records (or the “all good” empty states).
4. Trigger low stock by editing an item to fall below its threshold, reload dashboard, and confirm it appears in the alert card.
5. Run `GET /api/getDashboardData` (or hit the page with dev tools open) to confirm the JSON payload matches what the UI shows.

## 5. Regression Smoke

- Logout/Login flows for all roles
- CSV export endpoints under `/api`
- Alerts list + mark-as-read
- Equipment quick status update, inventory quick update, and flash messages

Document any discrepancies inside your release notes along with DB snapshots / log excerpts.
