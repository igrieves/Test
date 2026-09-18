=== Radius Hero ===
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.1.0
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

== Three ways to show the page ==

1. PAGE TEMPLATE (recommended). New page, Page Attributes, Template,
   "Radius Hero (full page)". Publish. No builder, no theme chrome, nothing
   to configure. If you want it as the front page, Settings, Reading, set it
   as a static homepage.

2. SHORTCODE. Put [radius_hero] in a Divi TEXT module, or the block editor,
   or the classic editor.

   NOT a Divi Code module. Divi's Code module does not run shortcodes, by
   design. This is the usual reason nothing appears.

3. PREVIEW, for checking the plugin works at all:
     yoursite.co.uk/?radius_hero=preview
   That renders the page on its own with no theme and no page involved. If
   the preview works but your page does not, the plugin is fine and the page
   setup is the problem.

== Notes ==

Asset URLs resolve to this plugin's folder at render time, so nothing needs
editing and the page survives a domain change or a move to staging.

Without hero-scrub.mp4 the page shows its designed still hero rather than
breaking. Every asset degrades on its own.
