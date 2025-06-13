#!/bin/zsh

# Fix heading numbering in the markdown file

# Save current working directory and change to project root
ORIGINAL_CWD=$(pwd)
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/../../../../" && pwd)"

# Trap to ensure directory restoration on script exit (success or failure)
trap 'cd "$ORIGINAL_CWD" || echo "Warning: Failed to restore original directory"' EXIT

echo "Script location: $SCRIPT_DIR"
echo "Project root: $PROJECT_ROOT"
echo "Original CWD: $ORIGINAL_CWD"

# Change to project root for consistent file operations
cd "$PROJECT_ROOT" || { echo "Error: Failed to change to project root directory"; exit 1; }

# Use relative paths from project root
FILE=".ai/200-l-s-f/010-task-tracker/010-detailed-task-instructions.md"
BACKUP_FILE="${FILE}.backup.$(date +%Y%m%d_%H%M%S)"

echo "Creating backup: $BACKUP_FILE"
cp "$FILE" "$BACKUP_FILE"

# Create a temporary file for processing
TEMP_FILE=$(mktemp)

echo "Fixing heading numbering issues..."

# Step 1: Fix the #### headings in section 6.3 that should be proper subsections
sed '
# Fix the #### **Chunk X:** headings to be proper subsections
/^#### \*\*Chunk 1: Basic Tag Creation Test\*\*/ {
    s/^#### \*\*Chunk 1: Basic Tag Creation Test\*\*/#### 6.3.3. Basic Tag Creation Test/
}
/^#### \*\*Chunk 2: Typed Tag Creation Test\*\*/ {
    s/^#### \*\*Chunk 2: Typed Tag Creation Test\*\*/#### 6.3.4. Typed Tag Creation Test/
}
/^#### \*\*Chunk 3: Tag Ordering Test (Fixed swapOrder Issue)\*\*/ {
    s/^#### \*\*Chunk 3: Tag Ordering Test (Fixed swapOrder Issue)\*\*/#### 6.3.5. Tag Ordering Test (Fixed swapOrder Issue)/
}
/^#### \*\*Chunk 4: User Model Tags Test\*\*/ {
    s/^#### \*\*Chunk 4: User Model Tags Test\*\*/#### 6.3.6. User Model Tags Test/
}
/^#### \*\*Chunk 5: Model Relationships Test\*\*/ {
    s/^#### \*\*Chunk 5: Model Relationships Test\*\*/#### 6.3.7. Model Relationships Test/
}
/^#### \*\*Chunk 6: Post Creation Test (Only if Post model exists)\*\*/ {
    s/^#### \*\*Chunk 6: Post Creation Test (Only if Post model exists)\*\*/#### 6.3.8. Post Creation Test (Only if Post model exists)/
}
/^#### \*\*Chunk 7: Tag Query Test\*\*/ {
    s/^#### \*\*Chunk 7: Tag Query Test\*\*/#### 6.3.9. Tag Query Test/
}
/^#### \*\*Chunk 8: Final Summary Test\*\*/ {
    s/^#### \*\*Chunk 8: Final Summary Test\*\*/#### 6.3.10. Final Summary Test/
}
' "$FILE" > "$TEMP_FILE"

# Step 2: Ensure proper blank lines around headings
awk '
BEGIN { prev_line = ""; prev_prev_line = "" }
{
    current_line = $0

    # Check if current line is a heading (starts with #)
    if (current_line ~ /^#{1,6} /) {
        # If previous line is not empty, add a blank line before heading
        if (prev_line != "" && prev_line !~ /^#{1,6} /) {
            print ""
        }
        print current_line
        # Set flag to ensure blank line after heading
        next_should_be_blank = 1
    } else {
        # If this is the line after a heading and it is not blank, add blank line
        if (next_should_be_blank == 1 && current_line != "") {
            print ""
            next_should_be_blank = 0
        }
        print current_line
        next_should_be_blank = 0
    }

    prev_prev_line = prev_line
    prev_line = current_line
}
' "$TEMP_FILE" > "$FILE"

# Clean up
rm "$TEMP_FILE"

echo "✅ Heading numbering fixed!"
echo "📁 Backup created: $BACKUP_FILE"
echo "📋 Summary of changes:"
echo "  - Fixed #### **Chunk X:** headings to proper subsection numbering (6.3.3 - 6.3.10)"
echo "  - Ensured proper blank lines around all headings"
echo ""
echo "🔍 Verifying changes..."

# Show fixed headings in section 6.3
echo ""
echo "Fixed headings in section 6.3:"
grep -n "^#### 6\.3\." "$FILE" | head -10

echo ""
echo "Script completed successfully! ✨"

echo ""
echo "Directory restoration will be handled automatically by trap on script exit."
