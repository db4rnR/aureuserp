#!/bin/bash
# Documentation Suite Maintenance Script
# Validates links, checks completeness, and generates reports

set -e

echo "🔧 R&D Documentation Suite Maintenance"
echo "======================================"

# Change to documentation directory
cd "$(dirname "$0")"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    case $2 in
        "success") echo -e "${GREEN}✅ $1${NC}" ;;
        "warning") echo -e "${YELLOW}⚠️  $1${NC}" ;;
        "error") echo -e "${RED}❌ $1${NC}" ;;
        "info") echo -e "${BLUE}ℹ️  $1${NC}" ;;
        *) echo "$1" ;;
    esac
}

# 1. File count check
print_status "Checking documentation completeness..." "info"
file_count=$(ls -1 *.md | wc -l)
if [ "$file_count" -ge 15 ]; then
    print_status "Found $file_count documentation files" "success"
else
    print_status "Only $file_count files found (expected 15+)" "warning"
fi

# 2. Link validation
print_status "Validating internal links..." "info"
if python3 validate-links.py > /dev/null 2>&1; then
    print_status "All internal links are valid" "success"
else
    print_status "Link validation failed - check the report" "error"
    exit 1
fi

# 3. Check for required files
print_status "Checking for required documentation files..." "info"
required_files=(
    "000-index.md"
    "010-executive-dashboard.md"
    "120-quick-start-guide.md"
    "110-sti-implementation-guide.md"
    "130-event-sourcing-guide.md"
    "140-admin-panel-guide.md"
)

missing_files=()
for file in "${required_files[@]}"; do
    if [ ! -f "$file" ]; then
        missing_files+=("$file")
    fi
done

if [ ${#missing_files[@]} -eq 0 ]; then
    print_status "All required files present" "success"
else
    print_status "Missing files: ${missing_files[*]}" "error"
    exit 1
fi

# 4. Check file sizes (ensure they're not empty)
print_status "Checking file content..." "info"
empty_files=()
for file in *.md; do
    if [ ! -s "$file" ]; then
        empty_files+=("$file")
    fi
done

if [ ${#empty_files[@]} -eq 0 ]; then
    print_status "All files have content" "success"
else
    print_status "Empty files found: ${empty_files[*]}" "warning"
fi

# 5. Generate summary statistics
print_status "Generating summary statistics..." "info"
total_lines=$(wc -l *.md | tail -1 | awk '{print $1}')
total_words=$(wc -w *.md | tail -1 | awk '{print $1}')

echo ""
echo "📊 Documentation Statistics:"
echo "   • Total files: $file_count"
echo "   • Total lines: $total_lines"
echo "   • Total words: $total_words"
echo "   • Average file size: $((total_lines / file_count)) lines"

# 6. Check git status (if in a git repo)
if git rev-parse --is-inside-work-tree > /dev/null 2>&1; then
    print_status "Checking git status..." "info"
    if git diff --quiet *.md; then
        print_status "No uncommitted changes in documentation" "success"
    else
        print_status "Uncommitted changes detected in documentation" "warning"
        echo "   Modified files:"
        git diff --name-only *.md | sed 's/^/   • /'
    fi
fi

# 7. Performance check
print_status "Running performance validation..." "info"
start_time=$(date +%s)
python3 validate-links.py > /dev/null 2>&1
end_time=$(date +%s)
duration=$((end_time - start_time))

if [ $duration -lt 5 ]; then
    print_status "Link validation completed in ${duration}s (Good performance)" "success"
else
    print_status "Link validation took ${duration}s (Consider optimization)" "warning"
fi

echo ""
echo "🎉 Documentation maintenance completed successfully!"
echo "   Last updated: $(date)"
echo "   Next maintenance: $(date -d '+1 week')"

# Optional: Create a maintenance log entry
echo "$(date): Maintenance completed - $file_count files, $total_lines lines, validation passed" >> .maintenance.log
