# Inventory
A inventory app

## Functional Requirements

- **Dashboard Access**: Inventory staff need full dashboard visibility plus data-level access for the insights they manage.
- **Purchase Orders**: Only inventory staff can originate and approve purchase orders.
- **Categories**: Users can create custom item categories beyond the defaults (materials, tools, equipment, consumables); all categories share the same tracking rules (no special expiration handling required).
- **Low-Stock Alerts**: Thresholds are configurable per item and at the category level to support varied alert granularity.
- **Forecasting**: Demand forecasting is explicitly out-of-scope for this release.
- **Suppliers & Pricing**: Each item can link to multiple suppliers for comparison, and the system must capture price history for downstream cost analysis.
- **Reporting**: Stock Level, Usage Trends, Cost Analysis, and Supplier Performance reports are mandatory and must support export (e.g., CSV/PDF).
- **Mobile/Responsive Use**: Mobile users should be able to review dashboards and perform core data-entry tasks from responsive views.
