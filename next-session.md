# PSSST CSS — session start context

I'm Derek. You're helping me with PSSST-CSS, a starter CSS kit at
/Users/sheriffderek/projects/pssst-css/. PSSST is a "one-time download, not
a dependency" kit — projects clone it and own the code. Read this whole
brief before responding.


## What PSSST is and what it isn't

PSSST is a starter kit that ships a vanilla-CSS foundation a client project
can build on. Not a framework. Not utility CSS. Not Tailwind.

The architecture is three conceptual layers:

  Foundation   preset (fixes + box model + type + structure) + viewport + cursors
  Identity     color-scales + font-scales + settings (semantic tokens + scope)
  Composition  structure (layouts) + typography (voice classes) + interaction
               (controls, actions) + components/modules (project-filled slots)

Plus a /scratch.css/ that loads last as a holding area for in-transit work.

Key methodology principles (held tight, not negotiable without discussion):

  1. Every line earns its place. No filler comments, no preemptive abstractions.
  2. Bundling at the rule/class layer; decomposition at the token layer.
     Voices bundle related concerns (font + size + rhythm + max-width). Tokens
     are the swap surface.
  3. Voices carry font/size/rhythm — NOT color. Color is a scope/tone concern
     (data-emphasis, data-tone). Never put `color:` on a voice class.
  4. The "why not" is as important as the "why." Documented decisions include
     what we rejected and why.
  5. Opt-in modular layering. Reset, cursors, controls, actions, interaction all
     opt-in via commented imports — the kit demonstrates the slot pattern.
  6. Three-pattern voice demonstration: calm-voice uses static values,
     strong-voice uses scale steps, attention-voice uses per-voice tokens.
     Projects pick how decomposed each voice needs to be.


## PE and the bidirectional flow

A second project at /Users/sheriffderek/projects/perpetual-education-website/
is PE (Perpetual Education) — a complex multi-brand WordPress theme that
serves as the "testing ground" for ideas before they get distilled into PSSST.

The flow is bidirectional:
  - PE complexifies and stress-tests; PSSST distills.
  - PSSST simplifications come back into PE.

PE has its own canonical methodology doc at
/wp-content/themes/perpetual-2019/design-system/css-methodology.md.
Reference that when discussing methodology — it's PE's internal context
surface, not a public artifact. Do not propagate names from a "people in
CSS" celebrity list externally; the list exists internally for context only.


## Communication preferences

  - Tight, story-driven, no walls of text. Sharp over thorough.
  - For each substantive move: state the rule, state the reason, surface
    trade-offs, wait for confirmation, edit + summarize.
  - Match Derek's existing voice in CSS comments. Conversational, purposeful,
    never lecturing. No filler.
  - Don't propose big restructurings without confirmation.
  - When removing things, park them somewhere visible (scratch.css) — never
    drop work silently.
  - Don't volunteer "next moves" — ask.
  - Indent with tabs. K&R braces. Single-quote HTML attrs. Native CSS nesting.


## Thinking docs at PSSST root (active surfaces)

  - what-this-solves.md        Filmable index — per-rule failure case, demoable
                                as 10–15s video
  - meyer-demo.md              Filmable script for the Meyer reset critique
  - spacing-vs-figma.md        Open architecture question about CSS sibling
                                logic vs Figma's wrapper+gap model
  - one-axis-tricks.md         Catalog of CSS techniques pitched as "systems"
                                that are actually one-axis tricks (Una's
                                theming article; Ansu Jain's SOLID-for-CSS)
  - one-file-vs-many.md        Derek's own thinking doc
  - other-systems-weave.md     Notes on weave-ds-template (contracts, paint
                                checks) — other system thinking to discuss
  - README.md                  NOT yet updated to reflect current architecture
                                (still loose)


## Parked in scratch.css (in-transit, will move to permanent home)

  - interpolate-size → settings (when motion section lands)
  - color-scheme → settings (near dark-mode setup)
  - Outbound link indicators with screen-reader-safe pattern
  - iOS tap-highlight handling
  - Selection on clickables (button / summary / .no-select / .allow-select)


## Loose threads (intentionally not addressed)

  - Craig Abbott's heading-scale article (craigabbott.co.uk/blog/2026/
    using-maths-to-balance-your-heading-styles/) — two keepers:
      1. Rewrite font-scales.css steps as pow(var(--scale-ratio), N)
         (Donnie D'Amato's suggestion — same values, each step reads
         independently off --step-0, exponent = step number; also makes
         a half-step pow(ratio, 0.5) a one-liner *if* a voice ever
         needs one — don't add the token until then).
      2. --flow-space rhythm token (open question, not adoption):
         typography.css prose rhythm uses hardcoded 1em/1.3em sibling
         margins that don't retune with the scale. His pattern — one
         property owns the gap, headings override the variable — is the
         ink/fill slot move applied to spacing. Decide first: should
         prose rhythm track the type scale at all, or is em-relative
         already correct? Relates to spacing-vs-figma.md.
    Rejected: his heading-cue hierarchy (voices dissolve that problem),
    the h6 weight-drop trick.
  - Per-typeface calibration, from PE (2026-09-22) — a candidate for
    font-scales.css, to consider as a universal tool, not to adopt yet:
      The finding: a typeface arrives with metrics the system didn't
      choose, and the system should compensate ONCE, beside the font
      primitive — never in a voice or a component. --type-anchor is the
      first member (apparent size). PE added a second: an optical left
      edge, --font-sans-inset / --font-serif-inset (the face's H-stem
      side bearing as a negative em, e.g. -0.05em for Neue Montreal),
      consumed by display voices as margin-inline-start so big headings
      sit flush with the column edge instead of indented by side bearing.
      The shape that held up in use, after two wrong tries:
        - three owners: the FONT says how much (primitive), the VOICE
          says whether it shows (only display voices consume it — at
          sub-display sizes it's sub-pixel AND those voices land on
          spans/legends/summaries), the SCOPE says whether it applies
          (a centered scope sets the pointer to 0 — the ink/fill slot
          move applied to alignment).
        - the role token (--font-heading-inset) is only a pointer to the
          active face's primitive; re-point it wherever --font-heading
          is reassigned. Font-attached, not role-attached: PE's first
          try was role-attached and a serif-overridden h1 got the sans
          number.
        - margin, not transform/text-indent: indent misses wrapped
          lines; transform lies to layout, creates a stacking context,
          and GSAP overwrites it. Checked in the browser, no rendering
          difference.
        - margins on one element never add, so a component's margin:0
          silently cancels it (write margin-block there).
        - no native CSS for this (csswg-drafts #5466, open since 2020;
          text-box-trim is block-axis only) — keep consumption in one
          place so an @supports swap is one block if it ever ships.
      Source of truth: the token comment in PE's styles/typography.css
      and docs/architecture/typography.md. Open before distilling: is
      this a kit-level concern (the "READ ME!!!" caveat applies — one-
      font projects may not want the layer), and should --type-anchor
      then move from the scale block to sit beside the primitives too,
      as one "calibration" block per face.
  - README catch-up (most public-facing artifact; needs updating)
  - Demo content cleanup (site.css, examples.html, index.html, root modules/)
  - developer-styles.css final position
  - index.css P/S/S/S/T per-letter comment markers (color, layouts, interaction
    broke the acronym — could reframe as Foundation/Identity/Composition)
  - The bare `<a>` `:where()` thought in setup.css


## Research handed to other agents in parallel windows

  - role='list' audit in PE
  - Symlinks primer (general learning)
  - Spacing-tokens-across-toolchain survey (Token Studio + code + Figma)


## To start the session

Ask Derek what he wants to work on. Don't assume from this brief. Common
starting points: continue cleanup of loose threads, walk a specific file
top-to-bottom, react to a new article/idea, work on PE-side cross-pollination.
