```
TASK: Refine MediFind Medicine Map UI/UX (02.3_MedicineMap.php)
```

# `ROLE AND DESIGN STANDARD` 

```
Act as a senior UI/UX designer and frontend engineer. Refine the existing
MediFind Medicine Map interface into a cohesive, modern, production-quality
healthcare web app.
```

```
Core Principles:
```

```
- Map-First Focus: The interactive map is the primary workspace. Popups, route
panels, and result cards must never unnecessarily obstruct or compete with it.
- Progressive Disclosure: Keep essential info visible. Make secondary panels
(like turn-by-turn routes) contextual, dismissible, or collapsible with an
intuitive way to reopen them.
```

```
- Strict Visual Hierarchy: Use visual emphasis sparingly. When every button is
solid green, nothing stands out. Clearly separate primary, secondary, and
tertiary actions.
```

- `Anti-Clutter: Avoid generic AI UI patterns. No nested cards, excessive borders, heavy drop shadows, or redundant labels.` 

```
- Accessible by Default: Keyboard-navigable, visible focus states, touch-
friendly targets, prefers-reduced-motion support, and never rely on color alone
for status indicators.
```

# `TECH STACK AND HARD CONSTRAINTS` 

- `Stack: PHP, HTML, CSS, JavaScript, Bootstrap 5.3.8, Material Symbols, Leaflet and OpenStreetMap.` 

- `No Schema Changes: Do not alter the database schema. Preserve all existing data flow, API endpoints, and routing.` 

- `Open-Access Guest Mode: Keep medicine search, map viewing, and directions completely free and accessible without forcing login.` 

- `Stock and Price Privacy (PHA-042 / NRF-019): Display only basic availability (Available / Unavailable) by default. Never expose exact stock counts, prices (unless explicitly enabled by the pharmacy), or full pharmacy inventories.` 

# `SPECIFIC UI/UX REFINEMENTS TO IMPLEMENT` 

`1. Top Search and Navigation Bar` 

- `Keep the Back button cleanly aligned with the search input.` 

- `Add a quick clear (x) button inside the search input and a compact primary Search button so users can run new queries effortlessly.` 

- `If the user is a guest, render the clean public header. If logged in, preserve the Patient navigation.` 

`2. Seamless Leaflet Map and Controls` 

- `Fix the faint white tile grid seams on the Leaflet map (for example: .leaflettile-container img { width: 256.5px !important; height: 256.5px !important; }). - Keep zoom controls and a "Locate Me" button cleanly positioned without overlapping popups or the search bar.` 

`3. Pharmacy Marker Popups` 

- `Style the active marker popup with a sleek dark slate card (#1e293b with white text) so it contrasts cleanly against light OpenStreetMap streets. - Display Pharmacy Name, short address, and a soft-tinted Available pill badge.` 

`4. Contextual Floating Route Card` 

- `Only display the route card when a user requests directions.` 

- `Add a Close (x) button to dismiss it and return to the full map.` 

- `Include a compact travel summary pill (for example: 1 min walk / 0.07 km) above the turn-by-turn steps.` 

- `On mobile (360px to 768px), make the route card collapsible/minimizable so it does not cover the map.` 

```
5. Bottom Results Tray and Pharmacy Cards
```

```
- Header: Show "X of Y participating pharmacies have Medicine - Strength" on the
left, and FDA generic/form metadata (Ibuprofen / Softgel Capsule / Over-the-
Counter) on the right.
```

```
- Filters: Keep Nearest, Available Only, and Open Now as clean, interactive
filter pills. Remove any unscoped filters (for example, Top Rated).
```

```
- Button Hierarchy: Highlight the selected pharmacy card with a subtle green
border and a solid orange Get Directions button (with navigation icon). Give
unselected cards a subtle gray border and a light outlined green Get Directions
button.
```

```
- Accessible Status Pills: Pair text with icons. Use a soft green pill with a
check icon for Available, a soft green pill with a clock icon for Open, and a
soft red pill with a clock icon for Closed so users do not accidentally route to
a closed pharmacy.
```

# `EXECUTION AND QUALITY REVIEW` 

```
1. Analyze first: Inspect the existing 02.3_MedicineMap.php layout, CSS, and JS
before editing. Refactor existing components rather than stacking new wrappers
on top of broken ones.
```

```
2. Implement directly: Apply all changes directly to the project files with
real, working interactions (smooth transitions, click-outside/Escape dismissal,
responsive mobile layout down to 360px).
```

```
3. Final Check: Verify that all existing features work, no panels clip or
overlap on mobile/desktop, and the interface feels intentional, fast, and
effortless.
```

