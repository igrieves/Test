=== Radius Hero ===
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later

The cinematic scroll hero and landing page for Radius Web Design.

== Installation ==

1. Plugins, Add New, Upload Plugin, choose radius-hero.zip, Install, Activate.
2. Add your media to wp-content/plugins/radius-hero/assets/:
     hero-scrub.mp4   the re-encoded hero video
     hero-poster.jpg  its first frame
     step-01.jpg      the three how-it-goes stills
     step-02.jpg
     step-03.jpg
   The logo and the five portfolio screenshots are already there.
3. Make a page, set Divi's Blank Page template, add one Code module,
   put [radius_hero] in it. Nothing else.

Asset URLs resolve to this plugin's own folder automatically, so there is
nothing to edit and the page survives a domain change or a move to staging.

== Notes ==

Without hero-scrub.mp4 the page shows its designed still hero rather than
breaking. Every asset degrades on its own.
