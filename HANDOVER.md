# Radius Web Design site: where we got to

Branch: `claude/radiuswebdesign-website-dxxpjf`

## Done and verified

The page is built and tested. Nothing about it is in doubt.

- `radius-site/radius-divi-code-module.html` — the whole page, one file
- `radius-site/plugin/radius-hero.zip` — the same page as a WordPress plugin
- `radius-site/assets/` — logo + five portfolio screenshots
- `radius-site/prepare-media.sh` — converts the Higgsfield downloads
- `radius-site/scrub-test.html` — checks an mp4 will scrub, no server needed
- `DESIGN-PACKAGE.md` — concept, palette, type, beat map, buyer research

Tested in headless Chromium at 1440 / 820 / 390 and under reduced motion:
scrub engine tracks scroll both ways, caption bands sequence, five fallback
gates fire correctly, no horizontal overflow, no console errors, copy gate
clean (no em dashes, no stock words, no AI tells).

## Not done

The install. That is the whole of what is outstanding.

The plugin IS live on radiusdemo1.co.uk and `?radius_hero=preview` renders.
What has not happened: a page using the "Radius Hero (full page)" template,
and the five media files copied into the plugin's assets folder.

Unresolved: the user reported "errors" on the preview and never said what
they were. `?radius_hero=check` was built to answer that and has not been run.

## The five missing media files

Generated, in the user's Higgsfield account, never downloadable from this
container (egress proxy blocks the CDN). Job IDs:

  hero video    18f683f7-82a2-4f06-9e02-ceda66d4d31b   kling3_0, 6s, 1280x720
  start frame   2428c88c-b879-4e83-b844-b64339ea41b5   gpt_image_2_5, 2688x1520
  step 1        19fe0ffd-fc3f-4a3e-bb7e-fc47ab68cb5b   point of light
  step 2        c926aadc-4101-482f-baff-3c35e2b83b68   scribed line
  step 3        8beade17-723b-4efe-a6e2-b57709f51df0   dense rings

Credits spent: 18 of 320. Frame 3, video 9, stills 6.

## What made this hard, for whoever picks it up

This container cannot reach radiuswebdesign.com, radiusdemo1.co.uk, the
Higgsfield CDN, or the two reference sites. Every diagnosis of the live site
has to be done by asking the user or by having the site report on itself.
That is why the plugin has a self-check route.

If the next session can run somewhere with normal network access, or on the
user's own machine, most of the friction disappears.

## Decisions already made, do not re-litigate

- Concept: The Growing Ring. Timber grain pulling back into growth rings.
- Palette: deep umber ground, warm amber, bone, verdigris for CTA only.
- Type: Fraunces display, Instrument Sans body, IBM Plex Mono labels,
  Anton for the stat numbers to echo the logotype.
- One call to action: the phone number. No form, no prices.
- Five real portfolio projects. Locations confirmed by the user.
- Imagery disclosed as AI generated, in the footer.

## Known open questions

- What the preview "errors" actually said.
- Whether the user wants the page inside Divi (Code module paste) or
  bypassing it (page template). They chose the template last.
- The mp4 has never been tested scrubbing on real footage by anyone.
