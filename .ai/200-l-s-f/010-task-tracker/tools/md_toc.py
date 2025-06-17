#!/usr/bin/env python3
"""
Markdown Table of Contents Generator

Generates a hierarchical table of contents from markdown headers with configurable
options including header level ranges, custom formatting, and various output modes.

Usage:
    python md_toc.py <input_file> [options]
    python md_toc.py --help

Examples:
    # Basic usage (H2-H6 only)
    python md_toc.py document.md

    # Include all header levels
    python md_toc.py document.md --min-level 1 --max-level 6

    # Custom output file and title
    python md_toc.py document.md -o toc.md --title "Document Index"

    # Only H2 and H3 headers
    python md_toc.py document.md --min-level 2 --max-level 3

    # Custom indent and no numbering
    python md_toc.py document.md --indent 4 --no-numbers

    # Verbose output
    python md_toc.py document.md --verbose
"""

import sys
import re
import argparse
import logging
from pathlib import Path
from typing import List, Optional, TextIO, Dict, Any
import unicodedata


class MarkdownTocGenerator:
    """Generates table of contents from markdown headers with configurable options."""

    def __init__(self, min_level: int = 2, max_level: int = 6,
                 max_depth: Optional[int] = None, indent_size: int = 2,
                 include_numbers: bool = True, number_format: str = "{number}."):
        """
        Initialize the TOC generator with configuration options.

        Args:
            min_level: Minimum header level to include (1-6)
            max_level: Maximum header level to include (1-6)
            max_depth: Maximum depth of nesting (None for no limit)
            indent_size: Number of spaces per indentation level
            include_numbers: Whether to include hierarchical numbering
            number_format: Format string for numbers (use {number} placeholder)
        """
        if not (1 <= min_level <= 6) or not (1 <= max_level <= 6):
            raise ValueError("Header levels must be between 1 and 6")
        if min_level > max_level:
            raise ValueError("min_level cannot be greater than max_level")

        self.min_level = min_level
        self.max_level = max_level
        self.max_depth = max_depth
        self.indent_size = indent_size
        self.include_numbers = include_numbers
        self.number_format = number_format
        self.counters: List[int] = []
        self.toc_entries: List[str] = []

        # Build regex pattern for specified range
        level_pattern = f"{{{min_level},{max_level}}}"
        self.header_pattern = re.compile(f'^(#{level_pattern})\\s+(.+)$')

        # Statistics for reporting
        self.stats = {
            'headers_found': 0,
            'headers_included': 0,
            'lines_processed': 0,
            'toc_entries': 0
        }

        logging.debug(f"Initialized TOC generator: levels {min_level}-{max_level}, "
                     f"numbers={include_numbers}, indent={indent_size}")

    def _adjust_counters(self, level: int) -> None:
        """Adjust counter array for the current header level."""
        # Convert absolute level to relative level (based on min_level)
        relative_level = level - self.min_level + 1

        # Remove counters for deeper levels
        while len(self.counters) > relative_level:
            self.counters.pop()

        # Add counters for missing levels
        while len(self.counters) < relative_level:
            self.counters.append(0)

        # Increment counter for current level
        if self.counters:
            self.counters[-1] += 1
        else:
            self.counters = [1]

    def _generate_number(self) -> str:
        """Generate the hierarchical number string."""
        if not self.include_numbers:
            return ""
        number = '.'.join(str(x) for x in self.counters)
        return self.number_format.format(number=number)

    def _slugify(self, text: str) -> str:
        """
        Convert header text to GitHub-compatible anchor slug.

        This follows GitHub's anchor generation rules:
        - Convert to lowercase
        - Remove punctuation except hyphens
        - Replace spaces with hyphens
        - Remove duplicate hyphens
        """
        # Remove markdown syntax and normalize
        text = re.sub(r'[`*_\[\](){}]', '', text)
        text = unicodedata.normalize('NFKD', text)

        # Convert to lowercase and replace spaces/special chars with hyphens
        text = re.sub(r'[^\w\s-]', '', text.lower())
        text = re.sub(r'[\s_-]+', '-', text)

        # Remove leading/trailing hyphens
        return text.strip('-')

    def _clean_header_title(self, title: str) -> str:
        """Clean header title by removing existing numbers if present."""
        # Remove existing numbering patterns like "1.", "1.1.", etc.
        cleaned = re.sub(r'^\d+(\.\d+)*\.\s*', '', title)
        return cleaned.strip()

    def process_line(self, line: str) -> None:
        """
        Process a single line, extracting header information for TOC.

        Args:
            line: Input line from markdown file
        """
        self.stats['lines_processed'] += 1
        match = self.header_pattern.match(line.rstrip())

        if not match:
            return

        header_md, header_title = match.groups()
        level = len(header_md)

        self.stats['headers_found'] += 1

        # Clean title and calculate relative level for depth check
        clean_title = self._clean_header_title(header_title)
        relative_level = level - self.min_level + 1

        # Skip if exceeds max depth
        if self.max_depth and relative_level > self.max_depth:
            logging.debug(f"Skipping header beyond max depth: {clean_title}")
            return

        self.stats['headers_included'] += 1
        self._adjust_counters(level)

        # Generate title with or without numbers
        if self.include_numbers:
            number = self._generate_number()
            display_title = f"{number} {clean_title}"
        else:
            display_title = clean_title

        # Create anchor (always use numbered version for uniqueness)
        number_for_anchor = '.'.join(str(x) for x in self.counters) + "."
        anchor_title = f"{number_for_anchor} {clean_title}"
        anchor = self._slugify(anchor_title)

        # Calculate indentation
        indent = " " * (self.indent_size * (relative_level - 1))

        # Add TOC entry
        toc_entry = f"{indent}- [{display_title}](#{anchor})"
        self.toc_entries.append(toc_entry)
        self.stats['toc_entries'] += 1

        logging.debug(f"Added TOC entry: {display_title} (level {level})")

    def process_file(self, input_file: TextIO) -> List[str]:
        """
        Process an entire markdown file and return TOC entries.

        Args:
            input_file: Input file handle

        Returns:
            List of TOC entry strings
        """
        self.toc_entries = []
        self.stats = {key: 0 for key in self.stats}

        for line in input_file:
            self.process_line(line)

        return self.toc_entries

    def generate_toc(self, input_file: TextIO, output_file: TextIO,
                    title: str = "Table of Contents",
                    title_level: int = 2) -> None:
        """
        Generate complete TOC with title and write to output.

        Args:
            input_file: Input file handle
            output_file: Output file handle
            title: Title for the TOC section
            title_level: Header level for the TOC title (1-6)
        """
        entries = self.process_file(input_file)

        if not entries:
            output_file.write("No headers found to generate TOC.\n")
            logging.warning("No headers found matching criteria")
            return

        # Write TOC with title
        title_prefix = "#" * title_level
        output_file.write(f"{title_prefix} {title}\n\n")

        for entry in entries:
            output_file.write(f"{entry}\n")

        output_file.write("\n")
        logging.info(f"Generated TOC with {len(entries)} entries")

    def get_stats(self) -> Dict[str, Any]:
        """Return processing statistics."""
        return self.stats.copy()


def setup_logging(verbose: bool = False) -> None:
    """Configure logging based on verbosity level."""
    level = logging.DEBUG if verbose else logging.INFO
    format_str = '%(levelname)s: %(message)s' if not verbose else '%(asctime)s - %(levelname)s - %(message)s'

    logging.basicConfig(
        level=level,
        format=format_str,
        datefmt='%H:%M:%S'
    )


def parse_arguments() -> argparse.Namespace:
    """Parse command line arguments."""
    parser = argparse.ArgumentParser(
        description='Generate table of contents from markdown headers',
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
Examples:
  %(prog)s document.md                           # Basic usage (H2-H6)
  %(prog)s document.md -o toc.md                 # Custom output file
  %(prog)s document.md --min-level 1 --max-level 3  # H1-H3 only
  %(prog)s document.md --title "Document Index" # Custom TOC title
  %(prog)s document.md --no-numbers             # No hierarchical numbering
  %(prog)s document.md --indent 4               # 4-space indentation
  %(prog)s document.md --max-depth 3            # Limit nesting depth
  %(prog)s document.md --verbose                # Detailed logging
        """
    )

    parser.add_argument('input_file', type=Path,
                       help='Input markdown file to process')

    parser.add_argument('-o', '--output', type=Path,
                       help='Output file (default: stdout)')

    parser.add_argument('--min-level', type=int, default=2, choices=range(1, 7),
                       help='Minimum header level to include (default: 2)')

    parser.add_argument('--max-level', type=int, default=6, choices=range(1, 7),
                       help='Maximum header level to include (default: 6)')

    parser.add_argument('--max-depth', type=int,
                       help='Maximum nesting depth (default: no limit)')

    parser.add_argument('--title', type=str, default='Table of Contents',
                       help='Title for the TOC section (default: "Table of Contents")')

    parser.add_argument('--title-level', type=int, default=2, choices=range(1, 7),
                       help='Header level for TOC title (default: 2)')

    parser.add_argument('--indent', type=int, default=2,
                       help='Number of spaces per indentation level (default: 2)')

    parser.add_argument('--no-numbers', action='store_true',
                       help='Disable hierarchical numbering in TOC entries')

    parser.add_argument('--number-format', type=str, default='{number}.',
                       help='Number format pattern (use {number} placeholder, default: "{number}.")')

    parser.add_argument('--verbose', '-v', action='store_true',
                       help='Enable verbose logging')

    return parser.parse_args()


def validate_arguments(args: argparse.Namespace) -> None:
    """Validate parsed arguments."""
    if args.min_level > args.max_level:
        raise ValueError(f"min-level ({args.min_level}) cannot be greater than max-level ({args.max_level})")

    if not args.input_file.exists():
        raise FileNotFoundError(f"Input file '{args.input_file}' does not exist")

    if not args.input_file.is_file():
        raise ValueError(f"'{args.input_file}' is not a file")

    if args.output and args.output.exists() and not args.output.is_file():
        raise ValueError(f"Output path '{args.output}' exists but is not a file")

    if args.max_depth and args.max_depth < 1:
        raise ValueError("max-depth must be at least 1")

    if args.indent < 0:
        raise ValueError("indent must be non-negative")

    if not args.no_numbers and '{number}' not in args.number_format:
        raise ValueError("Number format string must contain '{number}' placeholder")


def main() -> None:
    """Main entry point with comprehensive argument handling."""
    try:
        args = parse_arguments()
        setup_logging(args.verbose)
        validate_arguments(args)

        logging.info(f"Processing: {args.input_file}")
        logging.debug(f"Header levels: {args.min_level}-{args.max_level}")
        logging.debug(f"Max depth: {args.max_depth}")
        logging.debug(f"Include numbers: {not args.no_numbers}")

        # Initialize generator
        generator = MarkdownTocGenerator(
            min_level=args.min_level,
            max_level=args.max_level,
            max_depth=args.max_depth,
            indent_size=args.indent,
            include_numbers=not args.no_numbers,
            number_format=args.number_format
        )

        # Process file
        with open(args.input_file, 'r', encoding='utf-8') as input_file:
            if args.output:
                with open(args.output, 'w', encoding='utf-8') as output_file:
                    generator.generate_toc(input_file, output_file,
                                         args.title, args.title_level)
                logging.info(f"✅ TOC written to: {args.output}")
            else:
                generator.generate_toc(input_file, sys.stdout,
                                     args.title, args.title_level)

        # Report results
        stats = generator.get_stats()
        logging.info(f"Processed {stats['headers_found']} headers, "
                    f"included {stats['headers_included']} in TOC")
        logging.debug(f"Statistics: {stats}")

    except KeyboardInterrupt:
        logging.error("Operation cancelled by user")
        sys.exit(1)
    except Exception as e:
        logging.error(f"Error: {e}")
        sys.exit(1)


if __name__ == "__main__":
    main()
