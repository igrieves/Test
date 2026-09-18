# Radius Web Design · install into Divi 5

One file: `radius-divi-code-module.html`. It goes into a single Divi Code module.

## 1. Get the assets out of Higgsfield

In your Higgsfield account, download:

- the hero video (Kling v3.0, 6 seconds, 1280x720)
- the starting frame (GPT Image 2.5, 2688x1520)
- the three step stills (GPT Image 2.5, 1168x880), save them as
  `step-01.jpg`, `step-02.jpg`, `step-03.jpg` in that order: the single point
  of light, the scribed line, the dense rings

Your logo and the five portfolio screenshots are already in this repo under
`assets/`, correctly named and compressed. Upload those straight from there.

## 2. Re-encode the video before you upload it

This matters. Scroll-scrubbed video seeks to an arbitrary timestamp on every
frame of scroll. A normal web encode puts a keyframe every 2 to 10 seconds, so
the browser has to decode forward from a distant keyframe on each seek and the
scrub feels like it is lurching. Dropping the keyframe interval to 5 frames
makes every seek land almost instantly. The file gets bigger. That is the trade,
and for a 6 second clip it is worth it.

```bash
ffmpeg -i <downloaded>.mp4 \
  -c:v libx264 -crf 20 -preset slow \
  -g 5 -keyint_min 5 -sc_threshold 0 \
  -pix_fmt yuv420p -an -movflags +faststart \
  hero-scrub.mp4
```

Poster frame, used on phones and while the video loads:

```bash
ffmpeg -i hero-scrub.mp4 -vf "select=eq(n\,0)" -frames:v 1 -q:v 2 hero-poster.jpg
```

Closing frame, if you want it as a design image lower down the page:

```bash
ffmpeg -sseof -0.2 -i hero-scrub.mp4 -frames:v 1 -q:v 2 hero-end.jpg
```

## 3. Upload both to WordPress

Media Library, upload eleven files: `hero-scrub.mp4`, `hero-poster.jpg`,
`radius-logo-white.png`, `step-01.jpg`, `step-02.jpg`, `step-03.jpg`, and the
five `work-*.jpg` screenshots. Copy each file URL.

WordPress blocks `.mp4` uploads on some hosts. If it refuses, upload by FTP to
`/wp-content/uploads/radius/` instead and use that path directly.

## 4. Install the plugin

`plugin/radius-hero.zip` is a normal WordPress plugin.

1. **Plugins, Add New, Upload Plugin**, choose `radius-hero.zip`, Install, Activate.
2. Put your media in `wp-content/plugins/radius-hero/assets/`:

   ```
   hero-scrub.mp4    the re-encoded hero video
   hero-poster.jpg   its first frame
   step-01.jpg       the three how-it-goes stills
   step-02.jpg
   step-03.jpg
   ```

   The logo and the five portfolio screenshots are already in there.

### Then pick one of three ways to show it

**A. The page template. Use this one.**

New page, **Page Attributes, Template, "Radius Hero (full page)"**, Publish.
No builder, no theme header or footer, nothing to configure. To make it the
front page: Settings, Reading, A static page, choose it.

**B. The shortcode.** Put `[radius_hero]` in a Divi **Text** module, or the
block editor, or the classic editor.

> Not a Divi **Code** module. Divi's Code module does not run shortcodes, by
> design. That is the usual reason nothing appears.

**C. The preview, for diagnosis.** Visit:

```
yoursite.co.uk/?radius_hero=preview
```

That renders the page on its own, with no theme and no page involved. If the
preview works but your page does not, the plugin is fine and the page setup is
the problem.

To update the page later, replace `page.html` inside the plugin folder.

## 5. Or paste it directly (no plugin)

Same page setup, but paste the whole of `radius-divi-code-module.html` into a
Divi **Code** module, upload the media to the Media Library, and edit the CONFIG
block at the top to point at those URLs. Raw HTML in a Code module works fine.
It is only shortcodes that do not.

## 6. Check the video before you upload it

Open `scrub-test.html` by double-clicking it. No server needed, nothing is
uploaded, it all runs in your browser. Drop `hero-scrub.mp4` on it, drag the
slider, then press **Run the automatic test**.

It does 24 random seeks and reports the average. Under about 35 ms and it will
scrub smoothly. Over about 90 ms and the keyframes are too far apart, so
re-encode with the command in step 2 and test again. Those numbers are practical
rules of thumb rather than any standard.

Do this on the re-encoded file AND the original if you want to see the
difference the `-g 5` makes. It is usually large.

## 7. Check the page

- On a laptop: scroll the hero slowly, then flick it hard. The footage should
  track the scroll both ways and settle without stuttering.
- The full page needs a web server, not a double-click. Browsers block `fetch`
  on `file://` URLs, so opening `index.html` directly shows the still hero on
  purpose. Either check it on WordPress, or run `npx http-server` in the folder
  and open the localhost link it prints.
- On a phone: you should get the designed still hero, not the video. That is
  deliberate.
- Tap the phone number on a real phone and confirm it dials.

## What still needs your input

- **No prices anywhere.** I did not invent any for a real business. If you want a
  pricing section, tell me the numbers.
- **A type decision.** The logotype is a heavy condensed grotesque. The page
  headlines are Fraunces, a warm old-style serif. That pairing is deliberate and
  it is a normal editorial move, but it is a brand call rather than a technical
  one. See the note in the chat.

## Known limitation

The hero video is 1280x720, because Kling's higher modes need a Plus plan. On a
1440p or 4K display, full-bleed 720p will look slightly soft. It is fine on a
laptop. If you want it sharper, Grok Video 1.5 at 1080p costs 48 credits against
the 9 this one cost.
