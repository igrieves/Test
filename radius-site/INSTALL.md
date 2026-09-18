# Radius Web Design · install into Divi 5

One file: `radius-divi-code-module.html`. It goes into a single Divi Code module.

## 1. Get the two assets out of Higgsfield

In your Higgsfield account, download:

- the hero video (Kling v3.0, 6 seconds, 1280x720)
- the starting frame (GPT Image 2.5, 2688x1520)

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

Media Library, upload `hero-scrub.mp4` and `hero-poster.jpg`. Copy each file URL.

WordPress blocks `.mp4` uploads on some hosts. If it refuses, upload by FTP to
`/wp-content/uploads/radius/` instead and use that path directly.

## 4. Build the page

1. Pages, Add New, title it, then Use Divi Builder.
2. Page Settings, Template, set **Blank Page**. This keeps the theme header and
   footer out of the way, which matters because the page carries its own nav.
3. Add one Section, one Row, one **Code** module.
4. Paste the entire contents of `radius-divi-code-module.html` into it.
5. Row settings, Design, Sizing: set Custom Gutter Width to 1 and both Width
   and Max Width to 100%. Padding to 0 on the Section and the Row.

## 5. Point it at your files

At the top of the pasted code, edit the CONFIG block:

```js
window.RWD_CONFIG = {
  video:  "https://radiuswebdesign.com/wp-content/uploads/2026/09/hero-scrub.mp4",
  poster: "https://radiuswebdesign.com/wp-content/uploads/2026/09/hero-poster.jpg",
  tel:    "+441242462003",
  telText:"01242 462003"
};
```

The video must be served from the same domain as the page. The loader fetches it
with `fetch()`, so a cross-domain CDN URL needs CORS headers or it will fall back
to the still hero.

## 6. Check it

- On a laptop: scroll the hero slowly, then flick it hard. The footage should
  track the scroll both ways and settle without stuttering.
- On a phone: you should get the designed still hero, not the video. That is
  deliberate.
- Tap the phone number on a real phone and confirm it dials.

## What still needs your input

- **Two portfolio cards** say "Replace with a real one". Only My Pool and Spa is
  a real project; I found it in your own blog. Give me two more and I will write
  them in.
- **No prices anywhere.** I did not invent any for a real business. If you want a
  pricing section, tell me the numbers.
- **No logo applied.** You said you have one. It has not come through yet.
- **Supporting stills** for the lower sections are not generated. The page is
  designed to hold together without them, and the ring motif does that work for
  now. Three of them would cost about 9 credits.

## Known limitation

The hero video is 1280x720, because Kling's higher modes need a Plus plan. On a
1440p or 4K display, full-bleed 720p will look slightly soft. It is fine on a
laptop. If you want it sharper, Grok Video 1.5 at 1080p costs 48 credits against
the 9 this one cost.
