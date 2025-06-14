# 4. Workflow Guidelines

## 4.1. Git Workflow Standards

### 4.1.1. Commit Message Format

#### 4.1.1.1. Summary Line Requirements

- Maximum 50 characters
- Use imperative mood (e.g., "Fix bug," "Add feature")
- Be clear and descriptive
- Keep subject lines under 51 characters

#### 4.1.1.2. Body Structure

- Separate from summary with blank line
- Wrap at 72 characters (keep lines under 73 characters)
- Provide detailed explanation
- Include blank second line after subject line

#### 4.1.1.3. Content Organization

- Include all relevant changes in each commit
- Group related changes logically
- List multiple changes using consistent bullet style
- Be specific and concise in descriptions

#### 4.1.1.4. References and Tracking

- Include related issue numbers
- Link to pull requests
- Reference related tickets

#### 4.1.1.5. Multi-Line CLI Format

- Use multiple `-m` flags
- One flag per line
- Use line continuation with `\`
- Show git commit messages as shell commands

#### 4.1.1.6. Version Tagging

- Include version tag suggestions
- Follow semantic versioning
- Consider impact level

### 4.1.2. Conventional Commits Specification

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](https://www.ietf.org/rfc/rfc2119.txt).

1. Commits MUST be prefixed with a type, which consists of a noun, feat, fix, etc., followed by the OPTIONAL scope, OPTIONAL !, and REQUIRED terminal colon and space.
2. The type feat MUST be used when a commit adds a new feature to your application or library.
3. The type fix MUST be used when a commit represents a bug fix for your application.
4. A scope MAY be provided after a type. A scope MUST consist of a noun describing a section of the codebase surrounded by parenthesis, e.g., fix(parser):
5. A description MUST immediately follow the colon and space after the type/scope prefix. The description is a short summary of the code changes, e.g., fix: array parsing issue when multiple spaces were contained in string.
6. A longer commit body MAY be provided after the short description, providing additional contextual information about the code changes. The body MUST begin one blank line after the description.
7. A commit body is free-form and MAY consist of any number of newline separated paragraphs.
8. One or more footers MAY be provided one blank line after the body. Each footer MUST consist of a word token, followed by either a :<space> or <space># separator, followed by a string value.
9. A footer's token MUST use - in place of whitespace characters, e.g., Acked-by (this helps differentiate the footer section from a multi-paragraph body). An exception is made for BREAKING CHANGE, which MAY also be used as a token.
10. A footer's value MAY contain spaces and newlines, and parsing MUST terminate when the next valid footer token/separator pair is observed.
11. Breaking changes MUST be indicated in the type/scope prefix of a commit, or as an entry in the footer.
12. If included as a footer, a breaking change MUST consist of the uppercase text BREAKING CHANGE, followed by a colon, space, and description, e.g., BREAKING CHANGE: environment variables now take precedence over config files.
13. If included in the type/scope prefix, breaking changes MUST be indicated by a ! immediately before the :. If ! is used, BREAKING CHANGE: MAY be omitted from the footer section, and the commit description SHALL be used to describe the breaking change.
14. Types other than feat and fix MAY be used in your commit messages, e.g., docs: update ref docs.
15. The units of information that make up Conventional Commits MUST NOT be treated as case sensitive by implementors, with the exception of BREAKING CHANGE which MUST be uppercase.
16. BREAKING-CHANGE MUST be synonymous with BREAKING CHANGE, when used as a token in a footer.

### 4.1.3. Example Implementation

```shell
git commit -m "Fix: Prevent crash on null input" \
    -m "" \
    -m "Addresses issue #123." \
    -m "The application was crashing when processing null input." \
    -m "This commit adds a check for null values and handles them gracefully." \
    -m "* Added null check in process_input function" \
    -m "* Updated unit tests to cover null input scenarios" \
    -m "" \
    -m "Recommended tag: v1.0.1"
```

### 4.1.4. Branching Strategy

- Use GitHub flow
- Use git to track changes, manage branches, tags, commits, pull requests, and issues

## 4.2. Terminal Management

### 4.2.1. Session Optimization

- Run commands in one terminal when possible
- Maintain session context
- Minimize window switching

### 4.2.2. Terminal Creation Guidelines

- Only launch new terminal if no active processes
- Check existing terminal availability
- Document reason for new terminal

### 4.2.3. Session Management

- Maintain terminal session persistence
- Minimize window clutter
- Optimize context switching
- Check existing sessions first
- Verify session availability
- Document reuse attempts

### 4.2.4. Process and Resource Tracking

- Close unused terminals (get confirmation first)
- Track terminal usage
- Monitor active processes
- Document session purposes
- Maintain process inventory

### 4.2.5. Command-Line Tools and Text Manipulation

#### 4.2.5.1. Preferred Text Processing Tools

- **Use command-line tools** for text manipulation tasks over programmatic solutions
- **Prefer Unix/Linux utilities** for efficiency and reliability

**Essential Tools:**

- **`awk`** - Pattern scanning and data extraction
- **`sed`** - Stream editing and text transformation
- **`grep`** - Text search and pattern matching
- **`wc`** - Word, line, and character counting
- **`cut`** - Column extraction and field processing
- **`sort`** - Text sorting operations
- **`uniq`** - Duplicate line removal
- **`head`/`tail`** - File beginning/end extraction

**Usage Examples:**

```bash
# Count code lines excluding comments and blanks
grep -v '^\s*#\|^\s*$' file.php | wc -l

# Extract specific columns from CSV
cut -d',' -f1,3 data.csv

# Find and replace text patterns
sed 's/old_pattern/new_pattern/g' file.txt

# Process structured data
awk -F',' '{print $1, $3}' data.csv

# Get file statistics
wc -l *.php | sort -nr
```

#### 4.2.5.2. Terminal Buffer Management

**Command Length Optimization:**

- **Keep individual commands short** to prevent terminal buffer overflow
- **Break long operations** into smaller, sequential commands
- **Use intermediate files** for complex multi-step operations
- **Monitor command output size** to prevent terminal hanging

**Buffer Protection Guidelines:**

- **Limit output lines** using `head`, `tail`, or `less`
- **Use pagination** for large datasets (`less`, `more`)
- **Redirect large outputs** to files instead of displaying
- **Chain commands efficiently** with pipes but avoid excessive nesting

**Examples of Proper Command Segmentation:**

```bash
# Instead of one massive command that might hang:
# find . -name "*.php" -exec grep -l "pattern" {} \; | xargs wc -l | sort -nr

# Break into manageable steps:
find . -name "*.php" > php_files.txt
grep -l "pattern" $(cat php_files.txt) > matching_files.txt
wc -l $(cat matching_files.txt) | sort -nr

# Or limit output safely:
find . -name "*.php" -exec grep -l "pattern" {} \; | head -20
```

**Terminal Safety Practices:**

- **Test commands on small datasets** before running on large files
- **Use `--dry-run` flags** when available
- **Implement early exit conditions** (`head -n 100`)
- **Monitor process resources** before executing intensive operations

## 4.3. Development Workflow

### 4.3.1. Local Development Setup

1. Start the development environment:
```bash
composer dev
```
This runs:
- Laravel development server
- Queue worker for background jobs
- Real-time log monitoring
- Asset compilation with hot reload

### 4.3.2. Building for Production

Before submitting code:
1. Run tests: `php artisan test`
2. Format code: `composer pint`
3. Build assets: `npm run build`

### 4.3.3. Contribution Guidelines

- Create feature branches from `main`
- Follow the commit message convention
- Submit pull requests with comprehensive descriptions
- Ensure all tests pass before submitting
- Address code review feedback promptly

### 4.3.4. Code Review Process

- Review code for adherence to standards
- Check for security vulnerabilities
- Verify test coverage
- Provide constructive feedback
- Approve only when all issues are addressed

### 4.3.5. Continuous Integration

- Automated tests run on each pull request
- Code quality checks are performed
- Security scans are conducted
- Performance benchmarks are evaluated
- Documentation is verified
