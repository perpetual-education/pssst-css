# Token pipeline demo

The smallest thing that shows the whole connection: a color value typed once in a
spec file arrives in a rendered component with **no human retyping it** — and
Storybook, when it arrives, is a *viewer* bolted on the end, not a step in the chain.

Built to answer a design-systems question (how do Token Studio + Storybook actually
fit together). Kept deliberately tiny: two fills, some ink, a stroke. Color only.

## The chain

```
tokens/*.json      DTCG spec — the "Token Studio" stand-in
   │  node build.js   (the Style Dictionary slot — ~40 lines, zero dependencies)
   ▼
styles/tokens.css  GENERATED — :root + one block per brand × mode
   │  consumed by
   ▼
styles/box.css     the component — reads var(--fill-primary) etc. and NOTHING raw
   ▼
index.html         box-in-a-box + brand/mode switchers — runs in any browser
```

## Run it

```
node build.js
```

Then open `index.html` (over any static server — `file://` works in a normal
browser; the in-app preview needs http).

## Two layers, two axes

**Primitives** (`tokens/brand-*.json`) — a neutral-to-saturated ramp per brand,
same shape (`50 / 100 / 200 / 800 / 900`), different hue. Ocean, forest, ember.

**Semantics** (`tokens/semantic.json`) — named by *position in the hierarchy*
(`primary` / `secondary`), never by appearance. Only what the box needs:
`fill-primary`, `fill-secondary`, `ink-primary`, `stroke-primary`. Each is an
alias like `"{ramp.50}"`.

The two axes are both color but do different jobs — that's the teaching point:

- `data-brand` → **which ramp** (the hue)
- `data-mode` → **which rung** each semantic grabs (light: fill=50, ink=900;
  dark: fill=900, ink=50)

They compose. `ember + dark` = ember's ramp read at dark's rungs. The four
semantic names mean the same thing in all six combinations, so **one `box.css`,
zero raw values, six valid renderings.**

### How dark is wired: `light-dark()`

`build.js` writes each token as a single `light-dark(lightValue, darkValue)`
pair, and the `[data-mode]` toggle only flips `color-scheme` — one line, no
separate dark block:

```css
[data-brand='ember'] {
	--fill-primary: light-dark(oklch(98% ...), oklch(40.8% ...));
}
[data-mode='dark'] { color-scheme: dark; }
```

The browser picks the branch by the used `color-scheme`, so the OS preference
*and* the toggle both flow through one mechanism, and a token's two values sit on
one readable line. (`color-scheme` also themes the browser's own controls —
scrollbars, radios — to match.) This is the model
`derek-thomas-wood-website/styles/settings.css` is migrating to (its `immersive`
pilot block), replacing restated `@media (prefers-color-scheme: dark)` + toggle
blocks. The older restated-block approach also had a bug: a generic dark block
would silently out-rank and stomp any theme that didn't restate its own dark
values. `light-dark()` has no separate block to stomp.

**Vocabulary note (open):** the real system splits these axes differently —
`data-brand` there means *form* (type pair, corners, scale rhythm), and *color*
is the `data-emphasis` axis. This demo uses `brand` for a hue-swap, which is the
opposite. The naming here is deliberately not final.

## The proofs (do these — they're the point)

1. **Change a primitive.** Edit `ramp.500`-style value in a `tokens/brand-*.json`,
   re-run `node build.js`, refresh. The box changes. Nobody opened `box.css`.

2. **The invariant.** `box.css` must never contain a raw color:

   ```
   grep -nE '#[0-9a-fA-F]{3,6}|oklch|rgb\(' styles/box.css
   ```

   (Only the comment mentions those words — no declaration does.)

3. **Storybook adds nothing upstream** (when Stage 3 lands): after wiring
   Storybook, `git diff` on `tokens/*` and `styles/box.css` is empty. Storybook
   renders what was already true.

## Why this shape

- **Token Studio is faked as JSON on purpose.** The point is the *spec* (DTCG —
  `$value`, `$type`, `{alias}` references), not the plugin. Token Studio's real
  remaining value is multi-dimensional theming; that's the brand × mode grid here.
- **`build.js` is hand-written and tiny** so nothing hides behind a tool. Style
  Dictionary would slot in exactly where it sits.
- **The build step flattens every combination to a full, self-contained block —
  and that's a feature, not just convenience.** In a real hand-authored system
  (`derek-thomas-wood-website/styles/settings.css:19`) every palette restates the
  *whole* token set on purpose: chaining `var`-of-`var` across scopes hit a
  top-layer inheritance bug. A generator that expands each combination to flat
  values dodges that class of bug by construction — the pipeline pays for itself
  before Storybook ever enters.
- **Storybook is a viewer, not a step.** Delete it and the pipeline still works.
  The common failure is teams seeing the system *in* Storybook, concluding it *is*
  the system, and putting decisions there — a hardcoded hex in a story, a one-off
  demo style. Then there are two sources of truth. The test to hand anyone auditing
  a real setup: **grep the stories for hex values.** Any hit means the viewer
  became an author and the pipeline is already leaking.

## Deliberately not built

- **Spacing / radius / density tokens.** "Brand picks form (fonts, spacing,
  corners)" is a real and better model — but it's the *next* demo. This one is
  color-only so the wire is unmistakable.
- **Nested / scoped emphasis** (a callout inside a calm page, each component
  reading the same token names and coming out right). That's the strongest
  mise-en-mode claim and it can't be shown by a global toolbar — it needs a story
  that renders the nesting itself. Also next-demo.
