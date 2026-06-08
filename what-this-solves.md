# What This Solves

For each rule in PSSST, the failure case it prevents — written as a 10–15 second video demo. Walk top-to-bottom; matches the load order in `index.css`.

---

## `styles/preset.css`

### Narrow reset (Box model)

**Zero margins / padding on flow elements** (`html, body, h1–h6, p, blockquote, pre, dl, dt, dd, ol, ul, figure, figcaption, hr`)
- Without it: every heading has a different top/bottom margin pushing siblings around. Paragraphs have vertical spacing. Lists have indent. Voice classes can't control rhythm cleanly.

**`box-sizing: border-box`** (universal)
- Without it: an element with `width: 200px; padding: 20px; border: 2px solid` renders at **244px wide** (padding + border push beyond declared width). Cramming it into a 200px parent overflows.

### Type

**Form controls inherit font + color** (`button, input, textarea, select`)
- Without it: form controls render in **system default font** (often Helvetica/Arial ~13.3px) regardless of body font. Visually disconnected from the page. Also triggers iOS auto-zoom on focus.

**`text-size-adjust: 100%`**
- Without it: rotate a phone to landscape (especially on a desktop-formatted page) → text inflates and breaks the layout.

**`<q>` / `<blockquote>` auto-quote strip**
- Without it: `<q>hello</q>` renders as `"hello"` with browser-inserted curly marks. Authors who type curly quotes inline get double quotes.

### Structure (element chrome)

**Fieldset chrome stripped** (`margin/padding/border zero`)
- Without it: beveled border around the fieldset and padding inside its legend. The classic "old Windows form" look.

**Table `border-collapse: collapse`**
- Without it: separated double borders between cells (2px default spacing). Spreadsheet bevel look.

---

## `styles/viewport.css` (imported by preset)

**`scrollbar-gutter: stable`**
- Without it: navigate from a short page (no scrollbar) to a long page (scrollbar) → entire layout jolts sideways by the scrollbar's width.
- Open a modal that locks body scroll → content shifts sideways as the scrollbar disappears.

**`html:has(dialog[open]) { overflow: hidden }`**
- Without it: open a native `<dialog>` via `showModal()` on a long page → mouse wheel and arrow keys still scroll the page behind the modal.
- iOS Safari caveat: touch scroll still works behind the modal even with this rule. Full lock on iOS needs JS.

**`:target { scroll-margin-block: var(--target-scroll-margin, 3rem) }`**
- Without it: click an anchor link → target lands jammed against the viewport edge.
- With a sticky header, the target lands *behind* the header — invisible until you scroll up manually.

---

## `styles/cursors.css` (opt-in, commented import in preset.css)

**`a, button, label, summary, [role='button']` → `cursor: pointer`**
- Without it: hover a `<button>` or `<a>` → default arrow cursor. No affordance signal that the element is clickable.

**`[disabled] → cursor: default`**
- Without it: hover a disabled button → still shows pointer — the "this is clickable" lie.

---

## `styles/setup.css`

**`<a>` defaults + prose override**
- Wrap a card or article in an `<a>` → without the default, the link's blue underline bleeds through whatever's inside. With the default, the link is invisible chrome.
- Write a paragraph with an inline `<a>` → prose override restores blue underline.
- For prose-style links outside `<p>`, add `class='link'` to opt in.

**`picture { display: block; overflow: hidden }`**
- Add `border-radius` on the picture → the img inside clips to the rounded corners (overflow:hidden makes it work).

**`img, video, svg, canvas { display: block; width: 100%; height: auto }`**
- Drop any of these into a sized parent → fills parent width, computes height from aspect ratio.
- Without `display: block`, inline media leaves a mysterious 4px baseline gap.

**`iframe, audio { display: block }`**
- Place an `<iframe>` or `<audio>` inline → 4px magic-space gap from baseline. `display: block` removes it.

**`ul[role='list'], ol[role='list'] { list-style: none; margin: 0; padding: 0 }`**
- Mark a `<ul>` as `role='list'` → bullets stripped, margin/padding zeroed, screen reader still announces "list, N items."
- Safari VoiceOver test: strip bullets via class instead → list semantics get lost. The role attribute keeps semantics intact.

**`:where(:not(:defined)) { display: block }`**
- Use a custom element like `<inner-column>` as a semantic wrapper without JS registration → without the rule, it renders inline (default for unknown elements), breaking layout. With the rule, it behaves as block.

---

## `styles/color-scales.css`

Raw inventory — Tailwind 4 OKLCH palette + extra neutrals. No demo (it's data, not behavior). Project picks from this in `settings.css`.

---

## `styles/font-scales.css`

Raw inventory — font primitives + scale anchor/ratio + 12 musical-interval ratios.

**`[data-type-scale='X']` scale picker**
- Build a page with multiple voices visible. Set `data-type-scale='minor-second'` on `<html>` → voices retune to a tight scale (small intervals).
- Set `data-type-scale='golden-ratio'` → voices retune to dramatic intervals (much bigger spread).
- Same markup, totally different feel from one attribute swap.

---

## `styles/settings.css`

Semantic tokens (`--fill-primary`, `--ink-primary`, `--accent`, etc.) — data, no demo.

**`[data-emphasis='focused']` scope re-paint**
- Default page: light background, dark text.
- Add `data-emphasis='focused'` to a `<section>` → that section flips to dark background + light text + adjusted accent. Children inherit. Markup is identical to a default section; only the attribute differs.

---

## `styles/structure.css` → `layouts/default-layout.css`

**`body` flex column + `main { flex-grow: 1 }` (sticky footer)**
- Short page without it: footer floats up next to the content with empty space below.
- With it: footer pinned to the bottom of the viewport on short pages, falls below content naturally on long pages.

**`inner-column { max-width: var(--inner-column-max, 1100px) }`**
- Drop a long paragraph into `<inner-column>` on a wide screen → constrained to ~1100px wide, centered horizontally. Without it, prose stretches to viewport edge (unreadable line lengths).

**`.site-header { position: sticky; top: 0 }`**
- Without it: scroll down → site header disappears off-screen.
- With it: header stays pinned at the top while you scroll.

---

## `styles/typography.css`

**Voice classes** (`.calm-voice`, `.strong-voice`, `.attention-voice`, `.loud-voice`, `.quiet-voice`, `.data-voice`, `.label-voice`)
- Apply different voice classes to a stack of similar text → each renders at a different size and rhythm, defining hierarchy. Without them, all headings/body render at browser defaults (no consistent vocabulary).
- Switch the type scale (via `data-type-scale`) → every voice retunes proportionally. Markup unchanged.

**Article scope prose rhythm** (`:where(article, text-content) { p+p, h2+p, etc. }`)
- Place sibling `<p>` elements inside `<article>` → margin between them automatically. Without it, paragraphs touch each other (margins zeroed by preset).

---

## `styles/interaction/controls.css` (opt-in, commented import in index.css)

**Control tokens** (`--control-fill`, `--control-padding`, `--control-border`, `--control-radius`, `--control-focus-ring`, `--control-focus-offset`)
- Declared at root; any scope can re-paint them (Mise-en-Mode pattern). Re-tune the whole control system without touching the rules.

**Text-style controls** (`input, textarea, select`)
- Without it: minimal browser defaults (no padding, OS-native styling, focus ring varies by browser).
- With it: consistent padding, border, background, and focus ring derived from `--accent`.

---

## `styles/interaction/actions.css` (opt-in, commented import in index.css)

**Action band tokens** (`--action-fill-primary`, `--action-ink-primary`, `--action-stroke-primary`, `--action-radius-primary`)
- Primary tier ships. Secondary and auxiliary are commented slots — fill them in when a project hits the need.
- Any ancestor scope can re-paint these tokens; all featured actions inside repaint without touching their CSS (Mise-en-Mode scope pattern).

**Baseline `<button>`**
- Without it: browser default button (varies wildly by OS — macOS native, Windows native, mobile native).
- With it: consistent font: inherit, hairline border, cursor: pointer, disabled state.

**`[data-action='featured']`** — element-agnostic loud CTA
- Apply to any clickable (`<button>`, `<a>`, `<summary>`) → consistent loud-action treatment from the action band tokens.
- Element identity preserved: link keeps underline; summary loses its disclosure marker; details auto-hides the summary once opened.
- Hover dims, active scales down slightly, focus shows the focus ring.

**`.unstyled` escape hatch** (`button.unstyled` / `a.unstyled`)
- Apply to icon buttons, close-X buttons, anything that needs to render bare without the kit's chrome.
- Strips background, border, padding, color, text-decoration — leaves the element a clickable inline thing.

---

## `styles/scratch.css`

Holding area for things in transit. No demos — by nature temporary. Walk regularly; empty is the goal.

---

*Add or update entries as PSSST changes. Each entry should be demoable as a short video.*
