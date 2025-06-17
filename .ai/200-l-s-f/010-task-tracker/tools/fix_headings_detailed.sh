#!/bin/bash

# Fix heading numbering in markdown file
# Addresses issues found in the heading structure analysis

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

# Create backup
echo "Creating backup: $BACKUP_FILE"
cp "$FILE" "$BACKUP_FILE"

# Fix the specific issues identified:

echo "Fixing heading numbering issues..."

# 1. Fix the #### headings in section 6.3.3-6.3.10 that should be proper subsections
sed -i '' '
/^#### \*\*Chunk 1: Basic Tag Creation Test\*\*/ {
    s/^#### \*\*Chunk 1: Basic Tag Creation Test\*\*$/#### 6.3.3. Basic Tag Creation Test/
}
/^#### \*\*Chunk 2: Typed Tag Creation Test\*\*/ {
    s/^#### \*\*Chunk 2: Typed Tag Creation Test\*\*$/#### 6.3.4. Typed Tag Creation Test/
}
/^#### \*\*Chunk 3: Tag Ordering Test (Fixed swapOrder Issue)\*\*/ {
    s/^#### \*\*Chunk 3: Tag Ordering Test (Fixed swapOrder Issue)\*\*$/#### 6.3.5. Tag Ordering Test (Fixed swapOrder Issue)/
}
/^#### \*\*Chunk 4: User Model Tags Test\*\*/ {
    s/^#### \*\*Chunk 4: User Model Tags Test\*\*$/#### 6.3.6. User Model Tags Test/
}
/^#### \*\*Chunk 5: Model Relationships Test\*\*/ {
    s/^#### \*\*Chunk 5: Model Relationships Test\*\*$/#### 6.3.7. Model Relationships Test/
}
/^#### \*\*Chunk 6: Post Creation Test (Only if Post model exists)\*\*/ {
    s/^#### \*\*Chunk 6: Post Creation Test (Only if Post model exists)\*\*$/#### 6.3.8. Post Creation Test (Only if Post model exists)/
}
/^#### \*\*Chunk 7: Tag Query Test\*\*/ {
    s/^#### \*\*Chunk 7: Tag Query Test\*\*$/#### 6.3.9. Tag Query Test/
}
/^#### \*\*Chunk 8: Final Summary Test\*\*/ {
    s/^#### \*\*Chunk 8: Final Summary Test\*\*$/#### 6.3.10. Final Summary Test/
}
' "$FILE"

# 2. Fix the Install Laravel Translatable heading that should be numbered
sed -i '' '
/^    Sub-subsection 6.3.11: Install Laravel Translatable/ {
    N
    s/    Sub-subsection 6.3.11: Install Laravel Translatable (Line [0-9]*)\n### Install Laravel Translatable/    Sub-subsection 6.3.11: Install Laravel Translatable (Line 922)\n### 6.3.11. Install Laravel Translatable/
}
' "$FILE"

# 3. Ensure proper blank lines around headings
# Add blank line before headings if missing
awk '
BEGIN { prev_line = "" }
{
    # Check if current line is a heading (starts with #)
    if ($0 ~ /^#+/) {
        # If previous line is not empty, add blank line
        if (prev_line != "") {
            print ""
        }
    }
    print $0
    prev_line = $0
}
' "$FILE" > "${FILE}.tmp" && mv "${FILE}.tmp" "$FILE"

# Add blank line after headings if missing
awk '
{
    print $0
    # If current line is a heading and next line exists and is not empty
    if ($0 ~ /^#+/) {
        # Look ahead to next line
        if ((getline next_line) > 0) {
            if (next_line != "") {
                print ""
            }
            print next_line
        }
    }
}
' "$FILE" > "${FILE}.tmp" && mv "${FILE}.tmp" "$FILE"

echo "Heading numbering fixes completed!"
echo "Backup created at: $BACKUP_FILE"

# Verify the changes
echo ""
echo "Verifying changes in section 6.3..."
grep -n "^#### 6\.3\." "$FILE" | head -10

echo ""
echo "Checking for proper blank lines around headings..."
grep -n -A1 -B1 "^#" "$FILE" | head -20

echo ""
echo "Summary: Fixed heading numbering issues including:"
echo "- Converted #### **Chunk X:** format to proper #### 6.3.X. format"
echo "- Added missing subsection number to Install Laravel Translatable"
echo "- Ensured proper blank lines around headings"
echo ""
echo "Script completed successfully! ✨"

echo ""
echo "Directory restoration will be handled automatically by trap on script exit."
