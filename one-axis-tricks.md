# One-axis tricks pitched as systems

A catalog of CSS techniques that get framed as "theming systems" or "design systems"
but are actually narrow tricks addressing one axis of a multi-axis problem. Useful
to know about, useful to extract from, but not architectural advice.

The pattern: someone publishes a clever use of one or two new CSS features,
demos it on a card or a button or a heading, and the framing inflates from
"a useful color trick" to "a theming system."

The real multi-axis problem PSSST and PE care about: color × typography × spacing
× density × layout × content × scope × scheme × motion. A one-axis answer
to a multi-axis question is interesting; it isn't the answer.

---

## Modern CSS theming with light-dark(), contrast-color(), and style queries

**Source:** Una Kravets — https://una.im/modern-css-theming (June 2026)
**Pitched as:** A theming system
**Actually:** A color trick for cards with brand colors that need readable text

### What's real and useful
- `light-dark()` — handy shorthand for scheme-based color swaps; saves writing media queries
- `contrast-color()` reaching Baseline (May 2026); auto-pick black or white text based on WCAG contrast
- `@container style()` for branching on a custom-property value (genuinely clever pattern)
- Relative color syntax (`oklch(from var(--bg) ...)`) for deriving palette tones from a brand color

### Where the framing oversells

1. **Color-only.** No typography, no spacing, no density, no layout, no content
   scoping. Calling this a "theming system" without those is using the word "theming"
   to mean what most production systems call "color tokens."

2. **"Macro vs micro" is two axes.** Macro = page-level light/dark. Micro = element-level
   color. Real production systems (Polaris, Material, PE) navigate 4–8 independent axes.
   The framing makes a deeply multi-axis problem sound like a two-axis one.

3. **`contrast-color()` is binary.** Returns black OR white. The "branch into richer
   palettes via style queries" trick is clever but gated by a yes/no input. No nuance
   for borderline cases. Mise-en-Mode-style explicit tokens give full control;
   `contrast-color()` gives an algorithm's call.

4. **Shadow-with-transparent-layers is a workaround, not an elegant pattern.** Six
   shadow layers always declared, three always transparent. That's CSS bloat masking
   the actual limitation: `light-dark()` only accepts color values, not full shadow
   definitions.

5. **`@function` is Chrome 139+ only.** The article calls this "limited" — it's
   actually "doesn't ship to most users." Burying that downplays a hard constraint.

6. **The whole technique bakes opinions as defaults:** light = shadow, dark = neon
   glow, text on brand colors should be tinted from the brand. These are stylistic
   choices presented as architectural truths.

7. **Nothing about the actual hard problems:** typography weights in dark mode, image
   treatments, spacing changes, WCAG 2.2, reduced-transparency, high-contrast mode.

### What's worth taking
- `light-dark()` for compact dark-mode token swaps when settings.css gets a scheme layer
- Continued awareness of `contrast-color()` as a fallback tool (already parked in scratch)

### What's not worth taking
- The "macro vs micro" framing (PSSST already has more axes than that)
- The shadow-layer transparent-out pattern (smell)
- `@function` (browser support)
- The framing of this as a "system"

---

## SOLID Principles And CSS (Part 1)

**Source:** Ansu Jain — https://medium.com/@ansujain/solid-principles-and-css-part-1-e73aeeb4bdc5 (Nov 2017)
**Pitched as:** Applying SOLID principles to CSS architecture
**Actually:** Utility CSS, with extra philosophical packaging

### What the article proposes
Every CSS class should do one thing. Example: a `blockquote` rule that sets margin,
color, and font-style violates SRP because it does three things. Should be split
into `.quote-spacing`, `.quote-color`, `.quote-type` — three classes on the element.

### What's real and useful
- Atomic / utility CSS has real benefits — bounded vocabulary, no specificity wars,
  easy to swap, predictable
- The instinct that "one rule doing too much" can be hard to maintain isn't wrong
- Decomposition into reusable chunks is a legitimate strategy

### Where the framing oversells

1. **SOLID is OOP. CSS isn't OOP.** SOLID was designed for objects that encapsulate
   state and behavior. CSS classes are style declarations applied to elements via
   the cascade. The mapping is forced from the start.

2. **SRP is about "reasons to change," not number of properties.** A blockquote's
   visual treatment IS one responsibility: being a blockquote. The fact that being
   a blockquote needs margin AND color AND font-style doesn't violate SRP — they
   together express one design. The design changes for one reason: the design
   changes.

3. **The recommended naming is the worst of both worlds.** `.quote-spacing` /
   `.quote-color` are still semantic to "quote." Not generic enough for real
   utility CSS (Tailwind's `.m-4`, `.text-gray-500`); not specific enough to be
   cohesive. More classes per element AND less reusability.

4. **Ignores the cascade.** CSS's superpowers are the cascade, specificity,
   inheritance, adjacent siblings, scope. Adding three classes to every
   `<blockquote>` fights against `blockquote { ... }` working directly — which
   is the whole point of the cascade.

5. **Ignores tokens + scope.** PSSST/PE's approach (voice classes that bundle
   related typography, semantic tokens that bundle color decisions per role,
   scope re-painting) all "violate" SRP as described in the article. That
   bundling is exactly what makes them work.

6. **2017 vintage.** Pre-custom-properties, pre-grid, pre-container-queries.
   The CSS field has largely moved past projecting OOP frameworks onto CSS.

### What's worth taking
- Awareness that decomposition into reusable chunks is sometimes useful (utility CSS)
- The "what changes together stays together" instinct — but interpreted as bundling
  related concerns, not splitting them apart

### What's not worth taking
- The SOLID framing (CSS isn't OOP)
- The blockquote-split-three-ways recommendation (poor design advice)
- The implication that bundling related properties is "wrong" — it's how CSS
  actually works best when paired with tokens + scope

### Does this apply to PSSST / PE methodologies?

Mostly no, with one real consideration worth naming.

**Where the article's critique doesn't land:**
Voice classes bundle font-family + font-size + line-height + max-width. By the
article's logic, multiple "responsibilities" violated. By SRP's actual definition
("reasons to change"), one responsibility: the voice's rendering. The voice
changes for one reason — the voice's design changes. Same for
`[data-action='featured']` bundling display/background/border/padding/cursor
(one responsibility: featured-action treatment). Same for article scope sibling
rules bundling prose rhythm. These bundle related concerns under a single named
role, which is exactly what cohesion looks like in CSS.

**Where the real version of the question lives:**
The instinct "what changes together should stay together; what changes
independently should be separable" IS legitimate. We answer it at a different
layer than the article suggests:
  - At the rule / class layer: bundle (voices, action treatments, prose rhythm)
    — the rule expresses one stance
  - At the token layer: decompose (`--ink-primary`, `--accent`, per-voice tokens
    where they exist) — each token is one swappable decision

Bundling at class level + decomposition at token level. That's how the cascade
gets to work for us rather than against us.

**Where it could mislead a PSSST user:**
A reader who tried to "apply SOLID" to PSSST would:
  - Split voice classes into `.calm-spacing` / `.calm-color` / `.calm-type` —
    loses the voice as a role, bloats markup
  - Extract margin and padding from layout rules into separate utility classes
    — fights the cascade
  - Disaggregate token bundles — makes settings.css unmaintainable

The advice would actively break the architecture. Useful to note when this
article gets shared in a "should we adopt this?" context.

**`.calm-color` is a category error for PSSST.** Voices in our system
explicitly DON'T carry color — that's a core methodology rule: voices carry
font / size / rhythm; color comes from scope/tone (`data-emphasis`,
`data-tone`). The article is pattern-matching against a problem we don't have
because we made a different upstream design choice. There's nothing to
"separate out" because we never coupled it in the first place. The split it
recommends would not just be unnecessary — it'd require us to first DO the
coupling (put color on voices) so we'd have something to decompose.

**The three-pattern voice demo IS our answer to "different projects need
different levels of decomposition."** A single-brand project takes static
voice values; a multi-brand project takes per-voice tokens. The project picks
how decomposed it needs each voice to be. That's a more honest answer to the
underlying question than blanket "every class should do one thing."

---

*Add entries as you encounter more. Same shape: source, what it's pitched as,
what it actually is, what's useful, what's narrow, what to take.*
