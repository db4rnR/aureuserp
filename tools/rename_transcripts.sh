#!/bin/bash

# Script to rename transcript files with date-time stamp prefix
# Format: YYYY-MM-DD-HHMMSS-original-filename.md

cd .junie/transcripts

for file in *.md; do
  # Get creation timestamp
  timestamp=$(stat -f "%Sm" -t "%Y-%m-%d-%H%M%S" "$file")

  # Create new filename with timestamp prefix
  new_filename="${timestamp}-${file}"

  # Rename the file
  mv "$file" "$new_filename"

  echo "Renamed: $file -> $new_filename"
done

echo "All transcript files have been renamed."
