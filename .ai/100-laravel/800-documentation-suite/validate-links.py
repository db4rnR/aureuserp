#!/usr/bin/env python3
"""
Documentation Link Validator

Validates all internal markdown links in the 800-documentation-suite
to ensure documentation integrity and navigation accuracy.

Usage: python3 validate-links.py
"""

import os
import re
import sys
from pathlib import Path
from typing import Dict, List, Set, Tuple

class LinkValidator:
    def __init__(self, docs_dir: Path):
        self.docs_dir = docs_dir
        self.all_files: Set[str] = set()
        self.broken_links: List[Tuple[str, str, str]] = []
        self.external_links: List[Tuple[str, str]] = []
        self.internal_links: List[Tuple[str, str, str]] = []

    def scan_files(self) -> None:
        """Scan directory for all markdown files."""
        for file_path in self.docs_dir.glob("*.md"):
            self.all_files.add(file_path.name)

    def extract_links(self, file_path: Path) -> List[Tuple[str, str]]:
        """Extract all markdown links from a file."""
        links = []
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()

            # Match [text](url) patterns
            link_pattern = r'\[([^\]]+)\]\(([^\)]+)\)'
            matches = re.findall(link_pattern, content)

            for text, url in matches:
                links.append((text, url))

        except Exception as e:
            print(f"❌ Error reading {file_path}: {e}")

        return links

    def categorize_links(self) -> None:
        """Categorize all links as internal or external."""
        for file_path in self.docs_dir.glob("*.md"):
            links = self.extract_links(file_path)

            for text, url in links:
                if url.startswith(('http://', 'https://', 'mailto:')):
                    self.external_links.append((file_path.name, text, url))
                elif url.startswith(('#', '../')):
                    # Skip anchors and parent directory links for now
                    continue
                else:
                    self.internal_links.append((file_path.name, text, url))

    def validate_internal_links(self) -> None:
        """Check if internal links point to existing files."""
        for source_file, text, url in self.internal_links:
            # Handle relative paths and anchors
            target_file = url.split('#')[0]  # Remove anchor

            if target_file and target_file not in self.all_files:
                self.broken_links.append((source_file, text, url))

    def generate_report(self) -> str:
        """Generate a comprehensive validation report."""
        report = []
        report.append("# 📋 Documentation Link Validation Report")
        report.append(f"**Generated:** {os.popen('date').read().strip()}")
        report.append(f"**Documentation Directory:** `{self.docs_dir}`")
        report.append("")

        # Summary
        report.append("## 📊 Summary")
        report.append("")
        report.append(f"- **Total Files:** {len(self.all_files)}")
        report.append(f"- **Internal Links:** {len(self.internal_links)}")
        report.append(f"- **External Links:** {len(self.external_links)}")
        report.append(f"- **Broken Links:** {len(self.broken_links)}")
        report.append("")

        # Health Score
        if len(self.internal_links) > 0:
            health_score = (len(self.internal_links) - len(self.broken_links)) / len(self.internal_links) * 100
            report.append(f"**Link Health Score:** {health_score:.1f}%")
        else:
            report.append("**Link Health Score:** N/A (no internal links)")
        report.append("")

        # Broken Links
        if self.broken_links:
            report.append("## ❌ Broken Internal Links")
            report.append("")
            report.append("| Source File | Link Text | Target | Issue |")
            report.append("|-------------|-----------|--------|-------|")

            for source, text, url in self.broken_links:
                target = url.split('#')[0]
                issue = "File not found" if target else "Empty target"
                report.append(f"| {source} | {text} | {url} | {issue} |")
            report.append("")

        else:
            report.append("## ✅ All Internal Links Valid")
            report.append("")
            report.append("No broken internal links found!")
            report.append("")

        # File Inventory
        report.append("## 📁 File Inventory")
        report.append("")
        sorted_files = sorted(self.all_files)
        for i, filename in enumerate(sorted_files, 1):
            report.append(f"{i:2d}. {filename}")
        report.append("")

        # Internal Links by File
        if self.internal_links:
            report.append("## 🔗 Internal Links by File")
            report.append("")

            links_by_file = {}
            for source, text, url in self.internal_links:
                if source not in links_by_file:
                    links_by_file[source] = []
                links_by_file[source].append((text, url))

            for source_file in sorted(links_by_file.keys()):
                report.append(f"### {source_file}")
                report.append("")
                for text, url in links_by_file[source_file]:
                    status = "✅" if (source_file, text, url) not in self.broken_links else "❌"
                    report.append(f"- {status} [{text}]({url})")
                report.append("")

        # External Links Summary
        if self.external_links:
            report.append("## 🌐 External Links Summary")
            report.append("")
            report.append(f"Found {len(self.external_links)} external links across documentation.")
            report.append("*Note: External link validation requires network access and is not performed by this script.*")
            report.append("")

            # Group by domain
            domains = {}
            for source, text, url in self.external_links:
                domain = url.split('/')[2] if len(url.split('/')) > 2 else url
                if domain not in domains:
                    domains[domain] = 0
                domains[domain] += 1

            report.append("**Domains referenced:**")
            for domain, count in sorted(domains.items()):
                report.append(f"- {domain}: {count} links")
            report.append("")

        # Recommendations
        report.append("## 🎯 Recommendations")
        report.append("")

        if self.broken_links:
            report.append("### Immediate Actions Required")
            report.append("")
            report.append("1. **Fix broken links** listed above")
            report.append("2. **Verify file naming** follows the numbering convention")
            report.append("3. **Update index references** to match actual filenames")
            report.append("")

        report.append("### Maintenance Suggestions")
        report.append("")
        report.append("1. **Run this validator** before committing documentation changes")
        report.append("2. **Consider automation** in CI/CD pipeline")
        report.append("3. **Add anchor validation** for internal section links")
        report.append("4. **Implement external link checking** for comprehensive validation")
        report.append("")

        return '\n'.join(report)

    def run_validation(self) -> bool:
        """Run complete validation process."""
        print("🔍 Scanning documentation directory...")
        self.scan_files()
        print(f"   Found {len(self.all_files)} markdown files")

        print("🔗 Extracting and categorizing links...")
        self.categorize_links()
        print(f"   Found {len(self.internal_links)} internal links")
        print(f"   Found {len(self.external_links)} external links")

        print("✅ Validating internal links...")
        self.validate_internal_links()

        if self.broken_links:
            print(f"❌ Found {len(self.broken_links)} broken links")
            return False
        else:
            print("✅ All internal links are valid!")
            return True

def main():
    """Main execution function."""
    script_dir = Path(__file__).parent
    docs_dir = script_dir

    print("📋 Documentation Link Validator")
    print("=" * 50)

    if not docs_dir.exists():
        print(f"❌ Documentation directory not found: {docs_dir}")
        sys.exit(1)

    validator = LinkValidator(docs_dir)
    is_valid = validator.run_validation()

    # Generate report
    report_content = validator.generate_report()
    report_path = docs_dir / "999-link-validation-report.md"

    try:
        with open(report_path, 'w', encoding='utf-8') as f:
            f.write(report_content)
        print(f"📄 Report saved to: {report_path}")
    except Exception as e:
        print(f"❌ Failed to save report: {e}")
        sys.exit(1)

    print("\n" + "=" * 50)
    if is_valid:
        print("🎉 Documentation validation PASSED!")
        sys.exit(0)
    else:
        print("💥 Documentation validation FAILED!")
        print(f"   Check the report at {report_path} for details")
        sys.exit(1)

if __name__ == "__main__":
    main()
