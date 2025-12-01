# UI Refresh Plan – Auth + Operational Dashboards

## Goals
1. Deliver a cohesive visual language across login, signup, dashboard, inventory, and equipment screens before continuing functional roadmap work.
2. Improve usability on both desktop and tablet widths, with clean typography, branded colors, and accessible contrast.
3. Introduce reusable layout tokens so future pages inherit the new style with minimal duplication.

---

## 1. Authentication Screens (Login & Signup)
### Current Pain Points
- Inline `<style>` blocks make maintenance hard.
- Generic grey palette lacks brand presence.
- Forms are centered cards with no context (no hero content, no supporting copy).

### Design Direction
- Split-screen layout: left column features background illustration/brand messaging, right column hosts form card.
- Create dedicated stylesheet `public/css/auth.css` shared by login + signup.
- Use CSS variables for brand palette (navy background, amber accent from requirements doc).
- Include helpful elements: Remember me checkbox, "Need access?" link, trust badges.

### Implementation Steps
1. Build `auth.css` with:
   - `:root` palette tokens (primary navy `#0b1422`, accent `#f5b301`, neutrals).
   - Responsive grid (stack columns under 900px).
   - Form field styles, focus rings, error states.
2. Refactor `app/views/users/login.php`:
   - Remove inline styles, reference `auth.css` + Font Awesome.
   - Add left hero section with brand description, key metrics/testimonial.
   - Enhance form (username/email, password, remember me, CTA button, link to signup/forgot).
3. Apply same structure to `app/views/users/signup.php` with multi-column form groups, password strength meter placeholder, etc.

### QA Checklist
- Test on 320px, 768px, 1024px widths.
- Run aXe/lighthouse for color contrast.
- Confirm flash messages still render.

---

## 2. Dashboard Shell (Global Layout)
### Scope
Affects `app/views/index.php`, `inventory.php`, `equipment.php`, shared components in `views/inc/components/` along with `public/css/dashboard.css`, `inventory.css`, `equipment.css`, etc.

### Design Direction
- Adopt a `grid-template` main layout: collapsible sidebar, persistent top bar, content area with cards.
- Sidebar: vertical nav with icon buttons, active state pill, compact view for tablet.
- Top bar: search field, notifications, user avatar dropdown.
- Cards: soft gradient backgrounds, subtle borders, consistent padding.

### Implementation Steps
1. Create `public/css/layout.css` for shared shell (sidebar/topbar/responsive breakpoints) and update existing dashboard CSS to consume tokens.
2. Update sidebar partials (e.g., `views/inc/components/side_panel_admin.php`) to new markup (logo, nav sections, collapse button).
3. Revamp `views/index.php` stats cards + tables to new components (use CSS utility classes, maybe lighten color scheme described in README: deep blue base + warm accent).
4. Confirm inventory/equipment pages reference same layout wrapper to avoid duplicated structural HTML.

### Enhancements
- Add quick filters row to inventory/equipment list pages with pill buttons.
- Introduce modals styled via new design system (update `public/css/components/form-styles.css`).

### QA Checklist
- Verify sticky sidebar on desktop, collapsible on tablet.
- Confirm table responsiveness (horizontal scroll or card view on small screens).
- Smoke test navigation for all roles once RBAC is implemented.

---

## 3. Component Library Alignment
- Document tokens (`docs/styleguide.md` later) covering typography, spacing scale, color palette, button variants.
- Refactor existing CSS files to import shared tokens using `@import` or build pipeline (for now, include via `<link>` order).
- Provide utility classes (e.g., `.text-muted`, `.card`, `.pill`) to speed future development.

---

## Timeline & Dependencies
1. **Auth UI (2 dev days)** – unaffected by RBAC schema, can proceed immediately.
2. **Dashboard shell (3 dev days)** – coordinate with upcoming Step 3 to ensure new alert widgets fit.
3. **Inventory/Equipment content polish (2 dev days)** – once shell is ready, restyle content tables/forms.

Testing & deployment should follow after each slice to avoid large regressions.
