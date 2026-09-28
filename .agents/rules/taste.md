## UI/UX Design Taste (Anti-Slop Framework)

Always follow the `taste` design system when building or modifying frontend interfaces:

Rules:
- **Typography & Hierarchy:** Use modern paired fonts (`Outfit` / `Plus Jakarta Sans` for headers, `Inter` for body). Maintain strict scale, descender clearance, and high contrast.
- **Color Consistency:** Stick to 1 locked brand accent per interface (e.g. `#0284c7` Sky Blue or `#10b981` Emerald). Saturation < 85%. Never use clashing neon or generic AI-purple glow.
- **Materiality & Cards:** Use refined border styling (`1px solid rgba(226,232,240,0.8)`), soft layered shadows, and unified corner radii (`12px` buttons/inputs, `20px-24px` cards).
- **Tactile Feedback:** Include `:hover` lift (`-2px`), `:active` press (`scale(0.98)`), and high-contrast `:focus-visible` rings on all interactive elements.
- **Form & Table Polish:** Place labels above inputs, provide informative placeholder/helper texts, use sticky table headers, rounded status badge pills, and polished pagination.
- **Pre-Flight Check:** Verify mobile responsiveness (`< 768px`), WCAG AA contrast (4.5:1), and zero layout jumping before completing any UI task.
