# Bruce Class: Stitch Build Loop Setup

This folder holds the design setup for the Bruce Class website redesign, using the Google Stitch skills installed in `.claude/skills/`.

**Status: the setup is complete, and `stitch-loop` has NOT been started.** Wait for the project owner's instruction before running it.

## Files

| File | What it is | Used by |
|---|---|---|
| `SITE.md` | The project constitution: identity, existing PHP architecture, full sitemap, a spec for every page, the portal ecosystem, the roadmap, and the rules | `site-md`, `stitch-loop` |
| `DESIGN.md` | The design system: colours, typography, components, layout, motion, accessibility, anti-patterns. **Section 6 is the prompt block for every Stitch prompt.** | `design-md`, `taste-design`, `stitch-loop`, `enhance-prompt` |
| `next-prompt.md` | The baton, holding the first task (generate the Home page in Stitch). Ready, not started. | `stitch-loop` |
| `designs/index.html` | The Home page reference draft (v0), hand-built to set the direction. **Not a Stitch export.** | Reference, and can be uploaded with `stitch-upload-to-stitch` |
| `designs/index.png`, `designs/index-mobile.png` | Full-page screenshots of the draft at 1440px and 390px | Visual reference |
| `metadata.json` | *Not created yet.* The first loop iteration creates it after `create_project` (the `stitch-loop` schema). | `stitch-loop` |

Integrated prototype pages will go into `site/public/`, which the loop creates. Existing PHP, SQL and CSS files are never modified by the loop (see SITE.md §7).

## Before starting the loop

1. **Connect the Stitch MCP server.** This session had no Stitch MCP tools, so no Stitch project or screen could be created yet. Add the Stitch MCP server (with your Stitch API key) to your Claude Code MCP configuration, then confirm that its tools (`create_project`, `generate_screen_from_text`, `get_project`, …) are listed.
2. Optionally, preview the draft by opening `.stitch/designs/index.html` in a browser.

## When you say "start the loop"

The first iteration will:
1. Create the Stitch project "Bruce Class" and save it to `metadata.json`.
2. Generate the Home screen from `next-prompt.md` (or upload the local draft with `stitch-upload-to-stitch` if you prefer it as the starting point).
3. Run `design-md` against the real Stitch screen and reconcile `DESIGN.md`.
4. Replace `[pending]` project IDs in `SITE.md` and `DESIGN.md`.
5. Integrate the result into `site/public/index.html`, tick the sitemap, and write the next baton (Programs).

## Placeholders to replace with real content

`[metric]` statistics, `[Date]`/`[Time]`/`[DD]`/`[Mon]` dates, `[Campus address]`, `[Phone]`, `[Email]`, the Midwifery and Caregiving degree types and durations, and every labelled image placeholder ("Photo — …"). These are deliberately visible, so that no invented facts ship.
