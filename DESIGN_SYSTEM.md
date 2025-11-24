# RentMate Design System Documentation

## Overview

This design system provides a unified, consistent UI framework for the RentMate application. It replaces the previous inline styles with a maintainable, scalable system using CSS variables and utility classes.

## Getting Started

### Using the Design System

All design tokens are available as CSS variables. Simply reference them in your styles:

```css
.my-element {
    color: var(--color-primary);
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
}
```

### Building Assets

After making changes to CSS files, run:

```bash
npm run build
```

For development with hot reload:

```bash
npm run dev
```

## Design Tokens

### Colors

#### Primary Colors
- `--color-primary: #4461F2` - Main brand color
- `--color-primary-hover: #3651E2` - Hover state
- `--color-primary-light: #E8EEFF` - Light backgrounds
- `--color-primary-lighter: #F5F7FF` - Lighter backgrounds

#### Secondary Colors
- `--color-secondary: #1e3a8a` - Secondary brand color
- `--color-accent: #60a5fa` - Accent color

#### Semantic Colors
- **Success**: `--color-success`, `--color-success-hover`, `--color-success-light`
- **Warning**: `--color-warning`, `--color-warning-hover`, `--color-warning-light`
- **Danger**: `--color-danger`, `--color-danger-hover`, `--color-danger-light`
- **Info**: `--color-info`, `--color-info-hover`, `--color-info-light`
- **Purple**: `--color-purple`, `--color-purple-hover`, `--color-purple-light`
- **Teal**: `--color-teal`, `--color-teal-hover`, `--color-teal-light`

#### Neutral Colors
- `--color-gray-50` through `--color-gray-900`
- `--color-white`, `--color-black`

### Typography

#### Font Sizes
- `--text-xs: 0.75rem` (12px)
- `--text-sm: 0.875rem` (14px)
- `--text-base: 1rem` (16px)
- `--text-lg: 1.125rem` (18px)
- `--text-xl: 1.25rem` (20px)
- `--text-2xl: 1.5rem` (24px)
- `--text-3xl: 1.875rem` (30px)
- `--text-4xl: 2.25rem` (36px)

#### Font Weights
- `--font-normal: 400`
- `--font-medium: 500`
- `--font-semibold: 600`
- `--font-bold: 700`

#### Line Heights
- `--line-height-tight: 1.25`
- `--line-height-normal: 1.5`
- `--line-height-relaxed: 1.75`

### Spacing

- `--spacing-xs: 0.25rem` (4px)
- `--spacing-sm: 0.5rem` (8px)
- `--spacing-md: 1rem` (16px)
- `--spacing-lg: 1.5rem` (24px)
- `--spacing-xl: 2rem` (32px)
- `--spacing-2xl: 3rem` (48px)
- `--spacing-3xl: 4rem` (64px)

### Border Radius

- `--radius-sm: 0.25rem` (4px)
- `--radius-md: 0.375rem` (6px)
- `--radius-lg: 0.5rem` (8px)
- `--radius-xl: 0.75rem` (12px)
- `--radius-2xl: 1rem` (16px)
- `--radius-full: 9999px` (fully rounded)

### Shadows

- `--shadow-xs`: Subtle shadow
- `--shadow-sm`: Small shadow
- `--shadow-md`: Medium shadow
- `--shadow-lg`: Large shadow
- `--shadow-xl`: Extra large shadow
- `--shadow-2xl`: Maximum shadow

### Transitions

- `--transition-fast: 150ms ease-in-out`
- `--transition-base: 200ms ease-in-out`
- `--transition-slow: 300ms ease-in-out`

### Z-Index Scale

- `--z-dropdown: 1000`
- `--z-sticky: 1020`
- `--z-fixed: 1030`
- `--z-modal-backdrop: 1040`
- `--z-modal: 1050`
- `--z-popover: 1060`
- `--z-tooltip: 1070`

## Component Classes

### Buttons

```html
<!-- Primary Button -->
<button class="btn btn-primary">Click Me</button>

<!-- Secondary Button -->
<button class="btn btn-secondary">Cancel</button>

<!-- Success Button -->
<button class="btn btn-success">Confirm</button>

<!-- Danger Button -->
<button class="btn btn-danger">Delete</button>

<!-- Warning Button -->
<button class="btn btn-warning">Warning</button>

<!-- Button Sizes -->
<button class="btn btn-primary btn-sm">Small</button>
<button class="btn btn-primary">Default</button>
<button class="btn btn-primary btn-lg">Large</button>
```

### Cards

```html
<div class="card">
    <div class="card-header">
        <h3>Card Title</h3>
    </div>
    <div class="card-body">
        <p>Card content goes here...</p>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">Action</button>
    </div>
</div>
```

### Forms

```html
<div class="form-group">
    <label class="form-label">Email Address</label>
    <input type="email" class="form-input" placeholder="Enter email">
    <p class="form-help">We'll never share your email.</p>
</div>

<!-- With Error -->
<div class="form-group">
    <label class="form-label">Password</label>
    <input type="password" class="form-input">
    <p class="form-error">Password is required</p>
</div>

<!-- Select -->
<div class="form-group">
    <label class="form-label">Category</label>
    <select class="form-select">
        <option>Option 1</option>
        <option>Option 2</option>
    </select>
</div>

<!-- Textarea -->
<div class="form-group">
    <label class="form-label">Description</label>
    <textarea class="form-textarea" rows="4"></textarea>
</div>
```

### Badges

```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-danger">Danger</span>
<span class="badge badge-info">Info</span>
<span class="badge badge-purple">Purple</span>
<span class="badge badge-teal">Teal</span>
```

### Alerts

```html
<div class="alert alert-success">
    Operation completed successfully!
</div>

<div class="alert alert-warning">
    Please review your information.
</div>

<div class="alert alert-danger">
    An error occurred.
</div>

<div class="alert alert-info">
    Here's some helpful information.
</div>
```

### Tables

```html
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John Doe</td>
            <td>john@example.com</td>
            <td><span class="badge badge-success">Active</span></td>
        </tr>
    </tbody>
</table>
```

### Dropdowns

```html
<div class="dropdown">
    <button class="btn btn-primary">
        Menu <i class="fas fa-chevron-down"></i>
    </button>
    <div class="dropdown-menu">
        <a href="#" class="dropdown-item">Action</a>
        <a href="#" class="dropdown-item">Another action</a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">Separated link</a>
    </div>
</div>
```

### Stat Cards (Admin Dashboard)

```html
<div class="stat-card">
    <div class="stat-card-header">
        <div class="stat-icon-wrapper blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i> 12%
        </div>
    </div>
    <div class="stat-card-body">
        <div class="stat-card-value">1,234</div>
        <div class="stat-card-label">Total Users</div>
    </div>
    <div class="stat-card-footer">
        <span class="stat-details">Updated just now</span>
        <a href="#" class="view-details">
            View Details <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>
```

Icon wrapper colors: `blue`, `green`, `orange`, `red`, `purple`, `teal`

### Modals

```html
<div class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3>Modal Title</h3>
        </div>
        <div class="modal-body">
            <p>Modal content goes here...</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary">Cancel</button>
            <button class="btn btn-primary">Confirm</button>
        </div>
    </div>
</div>
```

### Loading States

```html
<!-- Spinner -->
<span class="spinner"></span>

<!-- Skeleton Loader -->
<div class="skeleton" style="width: 200px; height: 20px;"></div>
```

## Utility Classes

### Typography

```html
<h1 class="page-title">Page Title</h1>
<h2 class="section-title">Section Title</h2>
<p class="text-muted">Muted text</p>
```

### Layout

```html
<div class="container">
    <!-- Max-width container with auto margins -->
</div>

<div class="divider"></div>
```

### Navigation

```html
<!-- Sidebar Item -->
<a href="#" class="sidebar-item active">
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
</a>
```

## Tailwind Integration

The design system extends Tailwind CSS. You can use both custom classes and Tailwind utilities:

```html
<button class="btn btn-primary flex items-center gap-2">
    <i class="fas fa-plus"></i>
    Add Item
</button>

<div class="card p-6 mb-4">
    <!-- Combines card class with Tailwind utilities -->
</div>
```

### Custom Tailwind Classes

Available through the extended config:

```html
<!-- Colors -->
<div class="bg-primary text-white">Primary background</div>
<div class="bg-primary-light">Light primary background</div>

<!-- Shadows -->
<div class="shadow-sm">Small shadow</div>
<div class="shadow-md">Medium shadow</div>

<!-- Z-index -->
<div class="z-dropdown">Dropdown content</div>
<div class="z-modal">Modal content</div>
```

## Best Practices

### Do's

1. **Use design tokens** for all colors, spacing, and typography
2. **Use component classes** instead of creating custom styles
3. **Combine with Tailwind utilities** for layout and spacing
4. **Keep styles maintainable** by following the system

### Don'ts

1. **Don't use inline styles** unless absolutely necessary
2. **Don't create custom colors** outside the design system
3. **Don't hardcode values** that exist as design tokens
4. **Don't duplicate component styles**

### Example: Good vs Bad

**Bad:**
```html
<button style="background: #4461F2; padding: 10px 20px; border-radius: 8px;">
    Click Me
</button>
```

**Good:**
```html
<button class="btn btn-primary">
    Click Me
</button>
```

## Responsive Design

All components are mobile-responsive. Key breakpoints:

- Mobile: < 768px
- Tablet: 768px - 968px
- Desktop: > 968px

Components automatically adapt to screen sizes. Test on multiple devices!

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Migration Guide

### Migrating Existing Views

1. Remove inline `<style>` blocks
2. Replace hardcoded colors with design token classes
3. Use component classes for common elements
4. Test for visual consistency

### Example Migration

**Before:**
```html
<style>
.my-button {
    background: #4461F2;
    padding: 10px 20px;
    border-radius: 8px;
    color: white;
}
</style>

<button class="my-button">Click</button>
```

**After:**
```html
<button class="btn btn-primary">Click</button>
```

## Future Enhancements

Planned improvements to the design system:

1. Dark mode support
2. Additional component variants
3. Animation utilities
4. More color themes
5. Enhanced accessibility features

## Support

For questions or issues with the design system, refer to this documentation or check:

- `resources/css/app.css` - All component definitions
- `tailwind.config.js` - Tailwind extensions
- Layout files for implementation examples

## Changelog

### Version 1.0.0 (Current)

- Initial design system implementation
- Comprehensive color palette
- Typography scale
- Spacing system
- Component library (buttons, cards, forms, badges, alerts, tables, dropdowns, modals)
- Stat cards for admin dashboard
- Unified shadow system
- Z-index scale
- Transition timing
- Responsive utilities
- Tailwind integration
