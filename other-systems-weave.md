# Weave — other system thinking to consider

https://github.com/cris-achiardi/weave-ds-template/tree/main/docs/documentation

A design-system starter that ships **machinery and zero components** — token
pipeline, per-component "contracts," paint-policy checks, Figma wiring, CI.
The inverse bet from PSSST: we ship the styles with the least machinery we
can get away with; Weave ships the enforcement and none of the styles.

To discuss — not adopted, just noted:


## Shared convictions, independently arrived at

- "You don't fix the output, you fix the system that produced it."
- "Each fact lives in exactly one place and everything points at it" —
  their anti-drift rule is our docs-point-at-something-real rule, verbatim
  in spirit.
- Token policy names a *family*, not a value: "whatever paints this
  background must come off the fill shelf." That's our "components consume
  semantic tokens only," stated as an enforceable rule.


## The one stealable idea: the paint check

`pnpm report:paints` — walks a component's actual CSS and reports any
property whose value doesn't resolve through the allowed token family
(`background-color: #1e1e1e` fails `--ds-color-fill-`). The machine-checked
version of "raw values are a smell." Our equivalent would be a stylelint
rule: color-ish properties in components/ and modules/ may only contain
var(--ink-*/--fill-*/--stroke-*/--accent). Worth thinking about — it
converts a discipline into a structure, which is our own stated preference.


## The contract idea (the discussable one)

A small file next to each component carrying only what the code *cannot*
say — purpose, a11y promises, which token family paints which part — with a
sharp rule: anything the contract repeats that the code also knows must be
checked equal, because two copies of one fact always drift.

The tension for us: PSSST's answer to drift is smallness — the system fits
in a head, so the "what it really is" layer lives in comments and the
style-guide page. Contracts earn their keep at multi-framework, multi-team
scale (their whole pipeline assumes React + Figma + CI). Question to sit
with: does PE-scale complexity ever want a contract layer, or is the
style-guide-page-shows-every-state rule already our contract?


## What doesn't translate

The pipeline (use case → contract → prop map → generated component → docs →
Figma) assumes frameworks, build steps, and a merge tool. That's exactly
the machinery PSSST refuses on purpose. Reading it is useful the way
reading starter kits is: a reference implementation of conventions we'd
enforce by structure and size instead.
