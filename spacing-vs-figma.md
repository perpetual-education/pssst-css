# Spacing model vs Figma — the gap

A thinking note. Not a decision. Save for later conversation.

---

## The mismatch

CSS and Figma have **fundamentally different spacing models**, and they don't reconcile cleanly.

CSS spacing lives in three places, each with its own logic:
- **Sibling relationships** (`h2 + p { margin-top: 1em }`) — the *thing in the middle* is a property of the *pair*
- **Self-margins + collapsing** (Donnie D'Ammato's approach) — each element owns its margins; the browser resolves what happens when they meet
- **Parent gap** (`.stack { gap: 16px }`) — the parent owns all the spacing; children are uniform

Figma has only the third. Auto-layout gap on the parent. No siblings, no collapsing.

The moment you wrap two things in Figma to give them shared spacing, you've moved the spacing from being **an emergent property of siblings** to being **a property of the wrapper**. CSS goes the other way — children are first-class, parents are just containers.

## Three models, irreconcilable

| Model | Where spacing lives | What you give up |
|---|---|---|
| **Sibling logic** (`h2 + p`) | In the relationship | Can't be expressed in Figma at all |
| **Self-margins + collapse** (Donnie) | On each element | Figma still can't collapse |
| **Wrapper + gap** (Polaris/Stack) | On the parent | Lose contextual / per-pair tuning |

Only the third maps 1:1 to Figma. Which is why all the production design systems (Polaris, Lightning, Carbon, Material) converged on Stack/HStack/VStack with abstract gap tokens. They paid the price: lost sibling context for Figma parity.

That trade rounds down. Every gap is the same unless wrapped in a sub-stack with a different gap. The typographic specificity that `h2 + p` gives you is gone.

## Where PSSST sits

Current PSSST keeps sibling logic — the article scope explicitly tunes per-pair:

```css
:where(article, text-content) {
	p + p { margin-top: 1em; }
	h2 + p, h3 + p { margin-top: 1em; }
	p + h2, p + h3 { margin-top: 1.3em; }
}
```

PE picks the same. The choice has consequences: **the design tool can't represent the system completely.** That's a deliberate trade, not a bug.

## The Donnie observation

Voices each declaring their own intrinsic margins and letting the browser's collapse algorithm resolve the relationships is conceptually elegant. The system maps perfectly to how the browser actually works.

```css
.attention-voice { margin-block-end: 0.75em; }
.calm-voice, p {
	margin-block-start: 1em;
	margin-block-end: 1em;
}
```

Attention (0.75 × attention size) followed by calm (1 × calm size) → collapses to whichever is larger. No `h2 + p` rule needed.

What you gain: voices are self-contained; adding a voice doesn't require new sibling rules; smaller surface.
What you give up: per-pair tuning; collapsing is "magic" to anyone who doesn't know about it.

## The lookup-table thought

If the type scale is finite (and it is), the collapse algorithm is just a lookup. With ~7 voices, you have 49 possible ordered pairs. Most never occur in practice — maybe 10 real pairs.

You could precompute every resolved spacing value at authoring time. The Figma variables get outcome-named: `--after-attention-before-calm`, not the abstract `--space-200`. The name carries the why. The Figma file becomes the **resolved snapshot** rather than the spacing system itself.

That's an interesting bridge to Figma — not "translate the algorithm," but "ship the resolved output."

## Open questions

- Is the Donnie approach worth adopting in PSSST? Simpler architecture, but a real shift.
- Is the lookup-table-as-Figma-variables approach worth building, or is it overkill?
- Is there something in Figma we're missing that solves this? (Unlikely given the community's years of requesting margins, but worth re-checking each major Figma release.)
- For PE's complex multi-brand needs vs PSSST's simpler kit needs — should they make the same choice here, or differ?

## Status

Identified gap. Documented choice. Not asking it to be resolved tonight.
