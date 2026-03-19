---
name: Frontend Guidelines
description: Frontend UI/UX rules for vCargo
---

# Skill: Frontend Implementation Guidelines

When scaffolding or modifying the UI (HTML/CSS/JS) for vCargo, adhere to the following constraints strictly to maintain branding and enterprise feel.

## Visual Branding and Aesthetic
- **Template Context**: Sneat Bootstrap 5 Horizontal Menu layout is the default foundation.
- **Sharp Edges**: Enforce absolute sharpness in all UI components like Modals, Cards, Buttons, and Inputs. Use `border-radius: 0;` or appropriate Bootstrap utilities to override rounded defaults.
- **Color Palette**:
  - **Dark Mode**: `#0f172a` (Primary BG), `#1e293b` (Secondary BG).
  - **Light Mode**: `#ffffff` (Primary BG), `#f8f9fa` (Secondary BG).
  - **Accent**: `#2563eb` (Blue - Active/Hover states), `#64748b` (Gray inactive).

## Interactivity & Structure
- **Responsiveness**: Build mobile-first. Ensure DataTables and grid layouts collapse gracefully on smaller viewports.
- **Interaction**: Add subtle hover states on interactive table rows and buttons (using `#2563eb` accents). Always provide loading indicators for form submissions.

## Language Requirements
- All user-facing text (buttons, labels, placeholders, errors, toast notifications, modals) **must be in Turkish**.
- Example: "Onayla" (Approve), "İptal" (Cancel), "Kayıt Başarılı" (Record Successful).
