#!/bin/bash

# Script to find and remove nested .git directories
# Usage: ./remove_nested_git_dirs.sh [--dry-run]

DRY_RUN=false
if [ "$1" == "--dry-run" ]; then
    DRY_RUN=true
    echo "Running in dry-run mode. No directories will be deleted."
fi

# Find all .git directories, excluding the 010-ddl repository's .git directory
NESTED_GIT_DIRS=$(find . -name ".git" -type d | grep -v "^./.git$")

# Count the number of nested .git directories
COUNT=$(echo "$NESTED_GIT_DIRS" | wc -l)
COUNT=$(echo "$COUNT" | tr -d '[:space:]')

if [ -z "$NESTED_GIT_DIRS" ]; then
    echo "No nested .git directories found."
    exit 0
fi

echo "Found $COUNT nested .git directories:"
echo "$NESTED_GIT_DIRS" | sed 's/^/  /'
echo ""

if [ "$DRY_RUN" = true ]; then
    echo "Dry run completed. Run without --dry-run to delete these directories."
    exit 0
fi

# Ask for confirmation before deleting
read -p "Do you want to delete these nested .git directories? (y/n): " CONFIRM
if [ "$CONFIRM" != "y" ] && [ "$CONFIRM" != "Y" ]; then
    echo "Operation cancelled."
    exit 0
fi

# Delete the nested .git directories
echo "Deleting nested .git directories..."
for DIR in $NESTED_GIT_DIRS; do
    echo "Removing $DIR"
    rm -rf "$DIR"
done

echo "All nested .git directories have been removed."
