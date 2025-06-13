# Restoring the Last Git Commit While Stashing Current Changes

This guide demonstrates how to restore your repository to the last commit while safely preserving your current working tree changes using Git's stash feature.

## When to Use This Workflow

This workflow is useful when:
- You need to temporarily revert to a clean state
- You want to check out how the code worked before your current changes
- You need to pull the latest changes but have uncommitted work
- You want to switch branches without committing your current work

## Step-by-Step Process

### 1. Stash Your Current Changes

First, save your current working directory changes to the stash:

```bash
git stash save "Work in progress that I want to save temporarily"
```

This command:
- Saves all tracked files with changes
- Provides a descriptive message for easy identification
- Creates a clean working directory

For including untracked files too:

```bash
git stash save -u "Work in progress including untracked files"
```

### 2. Verify Your Working Directory is Clean

```bash
git status
```

Your working directory should now be clean, showing "nothing to commit, working tree clean".

### 3. Reset to the Last Commit

Now that your changes are safely stashed, you can reset to the last commit:

```bash
git reset --hard HEAD
```

This command:
- Resets your working directory to match the last commit (HEAD)
- Discards all changes since the last commit
- Returns your repository to a clean state

### 4. Retrieving Your Stashed Changes Later

When you're ready to retrieve your stashed changes:

#### View Your Stash List

```bash
git stash list
```

This shows all stashed changes with their stash IDs (like `stash@{0}`).

#### Apply the Most Recent Stash

```bash
git stash apply
```

This applies the most recent stash but keeps it in your stash list.

#### Apply a Specific Stash

```bash
git stash apply stash@{n}
```

Replace `n` with the stash number from the stash list.

#### Apply and Remove the Stash

```bash
git stash pop
```

This applies the most recent stash and removes it from your stash list.

## Common Scenarios

### Pulling Latest Changes Without Committing Current Work

```bash
git stash
git pull
git stash pop
```

### Switching Branches With Uncommitted Changes

```bash
git stash
git checkout other-branch
# Do work on other branch
git checkout original-branch
git stash pop
```

## Important Notes

- Stashing only works with tracked files by default (use `-u` to include untracked files)
- Stashes are stored locally and aren't pushed to remote repositories
- You can have multiple stashes at once
- Use descriptive stash messages to keep track of your stashed changes
