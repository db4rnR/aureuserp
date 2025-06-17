# How to Change a GitHub Repository from Public to Private

## Overview
This guide explains how to change the visibility of a GitHub repository from public to private. This process is straightforward but has some important implications to consider.

## Prerequisites
- You must be the owner of the repository or have admin permissions
- For organization repositories, you must have the appropriate permissions

## Step-by-Step Instructions

### Method 1: Using the GitHub Web Interface
1. Navigate to your repository on GitHub.com
2. Click on the "Settings" tab near the top of the repository page
3. Scroll down to the "Danger Zone" section
4. Find the "Change repository visibility" option and click the "Change visibility" button
5. Select "Make private" from the options
6. Read the warnings about the implications of making a repository private
7. Type the repository name to confirm
8. Click "I understand, change repository visibility"

### Method 2: Using GitHub CLI
If you prefer using the command line:

```bash
# Install GitHub CLI if you haven't already
# macOS: brew install gh
# Windows: winget install --id GitHub.cli
# Linux: Follow instructions at https://github.com/cli/cli/blob/trunk/docs/install_linux.md

# Login to GitHub
gh auth login

# Change repository visibility
gh repo edit OWNER/REPO --visibility private
```

Replace `OWNER/REPO` with your repository name (e.g., `username/repository-name`).

## Important Considerations

### What Happens When You Make a Repository Private
- The repository and its resources will only be accessible to you and people you explicitly share it with
- All public forks of the repository will be detached and become independent repositories
- All public issues, pull requests, and pages become hidden
- Existing clones and references to the repository will continue to work

### Limitations
- If you're using GitHub Free, you can only create private repositories with up to 3 collaborators
- GitHub Pro, Team, and Enterprise Cloud allow unlimited collaborators on private repositories
- Some GitHub features may behave differently for private repositories

### Billing Implications
- For personal accounts, private repositories are free with limitations
- For organizations, private repositories may affect your billing depending on your plan

## Reverting Back to Public
If you need to make your repository public again:
1. Follow the same steps above
2. Select "Make public" instead of "Make private"

## Additional Resources
- [GitHub Documentation on Changing Repository Visibility](https://docs.github.com/en/repositories/managing-your-repositorys-settings-and-features/managing-repository-settings/setting-repository-visibility)
- [GitHub Plans and Pricing](https://github.com/pricing)
