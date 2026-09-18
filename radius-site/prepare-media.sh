#!/usr/bin/env bash
# Radius Hero: turn the Higgsfield downloads into the five files the plugin wants.
# Needs ffmpeg. Run it in the folder where you saved the downloads.
set -euo pipefail

VIDEO_IN="${1:-}"
S1_IN="${2:-}"
S2_IN="${3:-}"
S3_IN="${4:-}"

if [ -z "$VIDEO_IN" ]; then
  cat <<'USAGE'
Usage:
  ./prepare-media.sh <hero.mp4> <step1.png> <step2.png> <step3.png>

Example, using the names Higgsfield gives you:
  ./prepare-media.sh \
    hf_20260918_122446_18f683f7-82a2-4f06-9e02-ceda66d4d31b.mp4 \
    hf_20260918_123657_19fe0ffd-fc3f-4a3e-bb7e-fc47ab68cb5b.png \
    hf_20260918_123656_c926aadc-4101-482f-baff-3c35e2b83b68.png \
    hf_20260918_123656_8beade17-723b-4efe-a6e2-b57709f51df0.png

Produces, ready to drop into the plugin's assets folder:
  hero-scrub.mp4  hero-poster.jpg  step-01.jpg  step-02.jpg  step-03.jpg
USAGE
  exit 1
fi

command -v ffmpeg >/dev/null || { echo "ffmpeg is not installed."; exit 1; }
mkdir -p out

echo "1/3  Re-encoding the video with a short keyframe interval..."
ffmpeg -y -loglevel error -i "$VIDEO_IN" \
  -c:v libx264 -crf 20 -preset slow \
  -g 5 -keyint_min 5 -sc_threshold 0 \
  -pix_fmt yuv420p -an -movflags +faststart \
  out/hero-scrub.mp4

echo "2/3  Pulling the poster frame..."
ffmpeg -y -loglevel error -i out/hero-scrub.mp4 \
  -vf "select=eq(n\,0)" -frames:v 1 -q:v 2 out/hero-poster.jpg

echo "3/3  Converting the three step stills..."
i=1
for f in "$S1_IN" "$S2_IN" "$S3_IN"; do
  [ -n "${f:-}" ] || { echo "     step $i not supplied, skipping"; i=$((i+1)); continue; }
  ffmpeg -y -loglevel error -i "$f" -vf "scale=1168:-2" -q:v 4 "out/step-0$i.jpg"
  i=$((i+1))
done

echo
echo "Done. Everything is in ./out :"
ls -lh out
echo
echo "Next: copy these into wp-content/plugins/radius-hero/assets/ on the server,"
echo "then check https://radiusdemo1.co.uk/?radius_hero=check"
