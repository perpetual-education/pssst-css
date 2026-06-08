# Meyer Reset — Demo Script

What to film, in order, to show what Meyer's reset is actually doing in 2026 — what's still load-bearing, what's gone obsolete, and where it might be doing something you don't want.

The whole video proves whether each line earns its place.

---

## Setup

A test page with one of each element category below, served two ways:

- **With Meyer** — `@import 'reset.css'` active
- **Without Meyer** — that import commented out

Easiest filming setup: a button on the page that toggles Meyer's stylesheet via JS (`document.styleSheet.disabled = !document.styleSheet.disabled`) so you can flip back and forth on-screen. Or two browser windows side by side.

For each demo, you'll show the **before / after toggle** and call out what changed.

---

## 1. Headings stack

### HTML

```html
<h1>Heading one</h1>
<h2>Heading two</h2>
<h3>Heading three</h3>
<h4>Heading four</h4>
<p>Body paragraph here for comparison.</p>
```

### Without Meyer

Each heading is a different size (h1 ≈ 2em, h2 ≈ 1.5em, etc.), and each has top/bottom margin pushing siblings around.

### With Meyer

All headings are the same size as body text. No margin between anything.

### What it proves

`font-size: 100%` + `margin: 0`. Meyer gives the typography system a clean slate so voice classes can paint without fighting browser defaults.

---

## 2. Lists

### HTML

```html
<ul>
	<li>Item one</li>
	<li>Item two</li>
	<li>Item three</li>
</ul>
```

### Without Meyer

Bullets visible. ~40px left indent. Vertical margin above and below the list.

### With Meyer

No bullets. No indent. No margin.

### What it proves

`padding: 0` + `margin: 0` + `list-style: none`. Lists become flat blocks — useful for nav and card grids that happen to use `<ul>`, but worth noting the a11y trade in Safari (see Demo 10).

---

## 3. Paragraphs and `<q>`

### HTML

```html
<p>First paragraph.</p>
<p>Second paragraph.</p>
<p>She said <q>hello</q> and walked away.</p>
```

### Without Meyer

Paragraphs have vertical space between them. `<q>` content is wrapped in browser-inserted curly quotes (`"hello"`).

### With Meyer

Paragraphs touch each other (no margin). `<q>` content appears bare with no quote marks.

### What it proves

`margin: 0` on paragraphs. `quotes: none` + the `:before` / `:after content: ''` cancellation strip the auto-inserted quotes — which is what you want if you type curly quotes into the prose directly (they survive style stripping, RSS, Reader View).

---

## 4. Tables

### HTML

```html
<table border="1">
	<tr><th>A</th><th>B</th></tr>
	<tr><td>1</td><td>2</td></tr>
	<tr><td>3</td><td>4</td></tr>
</table>
```

### Without Meyer

Double-lined borders between cells (each cell has its own border, plus the table border, separated by 2px of spacing).

### With Meyer

Single, collapsed borders between cells. Clean grid look.

### What it proves

`border-collapse: collapse` + `border-spacing: 0`. Tables go from "spreadsheet bevel" to clean grid by default. Almost always what you want.

---

## 5. Fieldset + legend

### HTML

```html
<fieldset>
	<legend>Group label</legend>
	<input type="checkbox"> Option one
</fieldset>
```

### Without Meyer

Visible bevel border around the fieldset. The legend sits in a notch in the top border with padding around it.

### With Meyer

No border. Legend sits flush with content.

### What it proves

`border: 0` + `padding: 0`. Removes browser-default chrome on a form group.

Note: you may *want* the fieldset border back for accessibility — it visually groups related controls. Meyer's stripping it is the kind of decision PE/PSSST would re-add intentionally if needed.

---

## 6. Form controls

### HTML

```html
<p>Some body text in your project font.</p>
<input type="text" placeholder="Type here">
<button>A button</button>
<textarea>Some text</textarea>
<select><option>One</option></select>
```

### Without Meyer

Form controls render in the *system default font* — usually Helvetica/Arial at ~13.3px. They look visually unrelated to your body text.

### With Meyer

Same. **Meyer doesn't fix this.** Its `font: inherit` rule lists every element except form controls.

### What it proves

The limit of Meyer. Form-control font inheritance is one of the most-needed fixes a reset would do, and Meyer's reset doesn't actually do it. PSSST handles it in `setup.css` (or as an additional modern-reset rule) with:

```css
button, input, textarea, select {
	font: inherit;
}
```

This is **the strongest argument for adding a modern-reset module alongside Meyer** rather than trusting Meyer alone.

---

## 7. `<sub>` and `<sup>` — H₂O test

### HTML

```html
<p>Water is H<sub>2</sub>O, and Einstein wrote E=mc<sup>2</sup>.</p>
```

### Without Meyer

`<sub>` shifts the "2" down with a smaller font. `<sup>` shifts the "2" up with a smaller font. Reads correctly as chemistry / math.

### With Meyer

`<sub>` and `<sup>` are flattened — the "2" sits inline at full size, on the baseline, no shift.

### What it proves

`vertical-align: baseline` on every element overrides the intentional baseline shift of `<sub>` and `<sup>`. **This is a case where Meyer is doing something you probably don't want.**

If your site uses chemistry, math, footnotes, or trademarks (™, ®), Meyer's reset breaks them silently. You either re-add `<sub>`/`<sup>` styling explicitly, or trim Meyer's `vertical-align` line.

---

## 8. HTML5 landmarks — the obsolete block

### HTML

```html
<article>An article.</article>
<aside>An aside.</aside>
<nav>A nav.</nav>
<section>A section.</section>
<header>A header.</header>
<footer>A footer.</footer>
```

### Without Meyer

Each renders on its own line (`display: block`). Looks like a stack of blocks.

### With Meyer

Identical. **No visible difference.**

### What it proves

Meyer's "HTML5 display-role reset for older browsers" block does nothing in modern browsers. All current browsers ship these landmark elements as `display: block` by default. The block was needed for IE9 and below.

**This is the clearest "we could trim this" case.** Cost of leaving it: a few lines of dead CSS. Cost of removing it: nothing on real users in 2026.

---

## 9. HTML4 dead element list

### HTML

Nothing to render — these elements either don't exist or are obsolete:

- `<applet>` — removed; embedded Java applets, doesn't render
- `<center>` — deprecated since HTML4; centering content
- `<big>` — removed in HTML5
- `<tt>` — obsolete; teletype font
- `<strike>` — removed; use `<s>` or `<del>`
- `<acronym>` — removed; use `<abbr>`

### What it proves

Meyer's element list includes these names. The rules apply to them. But the elements either don't exist in the DOM or render as plain inline boxes. The CSS is dead-letter — fires on nothing.

Cost: a few names in the selector list. Worth showing in the video because it makes vivid how much of "reset" content is *historical sediment* rather than work the browser still needs help with.

---

## 10. `body { line-height: 1 }` — paragraph cramp test

### HTML

```html
<p>Long enough paragraph that it wraps onto multiple lines when the viewport is narrow. The point of this test is to show how the lines stack against each other depending on the line-height value.</p>
```

### Without Meyer

`line-height: normal` (≈ 1.2 in most browsers). Lines have comfortable space.

### With Meyer

`line-height: 1`. Lines literally touch each other — descenders of one line bump into ascenders of the next. Cramped.

### What it proves

Meyer ships `line-height: 1` on body. **This is actively bad for prose readability.** Modern resets (Andy Bell, Josh Comeau) ship `line-height: 1.5` because cramped lines are harder to read, especially for users with dyslexia.

The voice classes in `typography.css` override this everywhere they're applied — but any unstyled content (a stray `<div>` with text, a third-party widget) inherits the cramped default.

This is a strong case for either:
- Trimming Meyer's `body { line-height: 1 }` line
- Adding a `body { line-height: 1.5 }` after Meyer's import (modern-reset additions)

---

## 11. VoiceOver list semantics (Safari)

### HTML

```html
<ul>
	<li>First</li>
	<li>Second</li>
	<li>Third</li>
</ul>
```

### Setup

Mac with VoiceOver on. Safari. Read the list aloud.

### Without Meyer

VoiceOver announces: *"List, three items. First. Second. Third. End of list."*

### With Meyer

VoiceOver announces just the items: *"First. Second. Third."* No list context. The Safari heuristic removes list semantics when `list-style: none` is applied universally.

### What it proves

Meyer's universal `ol, ul { list-style: none }` silently breaks list semantics for VoiceOver users in Safari. The fix (from Andy Bell's modern reset): only strip when the author explicitly opts in via `role='list'`:

```css
ul[role='list'],
ol[role='list'] {
	list-style: none;
}
```

This is **an audio demo** — film with screen reader running, capture the announcement difference. Genuinely powerful proof for an accessibility-aware audience.

---

## The takeaway

Walking the demos lands on three honest categories:

### Meyer still earns its place on

- Heading margins / sizes (Demo 1)
- List padding / bullets (Demo 2)
- Paragraph margins (Demo 3)
- `<q>` auto-quote stripping (Demo 3)
- Table border-collapse (Demo 4)
- Fieldset chrome (Demo 5)

### Meyer doesn't actually solve

- **Form-control font inheritance** (Demo 6) — biggest hole; needs a modern-reset addition

### Meyer is reaching too far

- `<sub>` / `<sup>` flattened (Demo 7) — over-reach on `vertical-align`
- `body { line-height: 1 }` (Demo 10) — actively bad prose default
- `ol, ul { list-style: none }` (Demo 11) — silent a11y cost in Safari

### Meyer is doing nothing

- HTML5 `display: block` block (Demo 8) — pure 2010s residue
- HTML4 obsolete element names (Demo 9) — fires on nothing

---

## Conclusion options

After the demos, the natural three forks:

**A.** Keep Meyer as-is. It works. Dead-weight rules cost nothing. Stack the known thing. Add a small modern-reset block for the holes (form-control fonts, line-height, `[role='list']`, `<sub>`/`<sup>` restore).

**B.** Author a "Meyer 2026" — same philosophy, trim the obsolete HTML5 block, drop the HTML4 element names, restore `<sub>`/`<sup>`, ship a sensible body `line-height`. ~30 lines instead of ~55.

**C.** Keep Meyer + add a `meyer-overrides.css` next to it that re-fixes the things Meyer over-reaches on, plus the modern-reset additions.

Which to recommend at the end of the video is the editorial choice. The demos themselves don't decide — they make the trade visible.
