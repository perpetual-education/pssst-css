# One file vs. many — how to ship PSSST

PSSST is authored as partials (`preset`, `setup`, `settings`, `structure`, `typography`, `components`, `modules`, etc.) and wired together in [`styles/index.css`](styles/index.css) via native `@import`. That's the **source** layout — it's optimized for reading and teaching, not for delivery.

This doc is about the delivery side: should you ship one big file, or many small ones? Modern browsers changed the answer, but not as much as people think.

---

## The short version

- **Default: ship one concatenated, minified file.** Simple, fast, one cache entry, one `<link>`.
- **Keep the partials in the repo** as the readable source and as the menu for pick-your-parts downloads.
- **If you want granular caching, use `<link rel="stylesheet">` per partial in HTML** — never chain `@import`s in production CSS.

---

## One big file

**Pros**
- One HTTP request, one cache entry, one cache-bust.
- No `@import` waterfall. Native CSS `@import` is *serial* — the browser can't discover `setup.css` until it's parsed `index.css`, can't discover `settings.css` until it's parsed `setup.css`, and so on. Each hop is another round trip. This is the real perf footgun in the default source setup.
- Easier for a beginner to open one file and read top-to-bottom.
- Minifies and compresses better — cross-file dedup, more repetition for Brotli/gzip to chew on.

**Cons**
- Edit one selector → the whole file's cache busts → the user re-downloads everything.
- Bigger initial payload even on pages that only use a slice.
- Harder to author (but that's what the source partials are for — ship one, author many).

## Many files

**Pros**
- Granular caching: tweak `components.css`, the user keeps `reset.css`, `typography.css`, etc. from cache.
- HTTP/2 and HTTP/3 multiplex — parallel requests are cheap *if the browser knows about them up front* (i.e. real `<link>` tags in the HTML).
- Mix-and-match per page: a landing page skips `forms.css`; an app page loads it.
- Aligns with how PSSST is already organized as layers (P-S-S-S-T-C-S).

**Cons**
- Only fast if loaded via parallel `<link>` tags. The moment you chain them through `@import`, you have the worst of both worlds: many files *and* serial loading.
- More cache entries to revalidate.
- Beginners have to understand the load order.

---

## The modern-browser reality

- HTTP/2+ killed the old "always concatenate" rule. 5–15 small files over one connection is fine.
- But `@import` chains never got faster. They are still serial. If you stay multi-file, load partials with `<link rel="stylesheet">` per partial in the HTML, not with `@import` in CSS.
- Compression (Brotli/gzip) loves repetition, so one big file usually wins on wire size by a meaningful margin.
- For a site under ~50KB of CSS total (PSSST sites usually are), the difference is mostly academic. Pick the model that's nicer to author and teach.

---

## Recommendation for PSSST

Given the audience (students, one-off downloaders, "scaffold your ideas") and the typical size:

1. **Default download → one built file.** Fast, simple, one `<link>`. Good for 95% of users.
2. **Keep the partials in the repo.** They are the readable source and the menu for a future pick-your-parts download tool.
3. **Document the multi-`<link>` pattern** for people who want granular caching.
4. **Warn against `@import` chains in production HTML** — they undo the benefits of either model.

The source `styles/index.css` uses `@import` because it's the simplest way to express "here is the load order." That's fine for local dev and for teaching. It is not what you want to ship to users.
