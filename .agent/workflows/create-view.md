---
description: Create a new Frontend View for vCargo
---
# Workflow: Create View

This workflow helps you scaffold a UI page in the vCargo application.

## 1. Directory Structure
- Store view files in `views/` directory. Sub-directories should group logical entities (e.g., `views/admin/`, `views/branch/`).
- Master layout should be wrapped appropriately using shared partials (Header, Sidebar, Footer).

## 2. Design Mandates
- **Template**: Sneat Bootstrap 5 Horizontal Menu.
- **Form Controls**: Check that inputs and buttons are using proper Bootstrap spacing, but enforce `border-radius: 0;` (sharp edges style) via custom CSS overrides.
- **Colors**:
  - Primary Base Dark: `#0f172a`
  - Secondary Base Dark: `#1e293b`
  - Accent Active: `#2563eb`

## 3. Localization
- **UI Content**: All labels, placeholders, and user-facing text **must be in Turkish**.
- Example validation text: "Bu alan zorunludur." NOT "This field is required."

## 4. Implementation Checklist
- [ ] Added `border-radius: 0` UI conventions.
- [ ] Tested responsive (mobile-first) layout.
- [ ] Confirmed text localization to Turkish.
- [ ] Verified Dark/Light Mode capability is intact.
