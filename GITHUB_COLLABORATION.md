# GitHub Repository Setup & Team Collaboration Guide

**Repository:** git@github.com:amirulhasanpulok/rclfullstack.git  
**Status:** ✅ Ready for Team Collaboration  
**Date:** January 21, 2026

---

## 🔐 GitHub Access & Setup

### Initial Repository Access

#### For Team Members (SSH Setup Recommended)

1. **Generate SSH Key** (if not already done)
   ```bash
   ssh-keygen -t ed25519 -C "your-email@example.com"
   # Press Enter for default location
   # Enter passphrase (optional but recommended)
   ```

2. **Add Public Key to GitHub**
   ```bash
   # Copy your public key
   cat ~/.ssh/id_ed25519.pub
   
   # Go to GitHub Settings > SSH and GPG Keys > New SSH Key
   # Paste the key and save
   ```

3. **Test SSH Connection**
   ```bash
   ssh -T git@github.com
   # Should output: "Hi [username]! You've successfully authenticated..."
   ```

4. **Clone Repository**
   ```bash
   git clone git@github.com:amirulhasanpulok/rclfullstack.git
   cd rclfullstack
   ```

#### For HTTPS (if SSH not available)
```bash
git clone https://github.com/amirulhasanpulok/rclfullstack.git
cd rclfullstack
# Will prompt for GitHub credentials
```

---

## 🌿 Branch Strategy (Git Flow)

### Branch Structure
```
main (production-ready, tagged releases)
  ↓ (PR with code review)
develop (integration branch)
  ↓ (feature branches)
feature/* (feature development)
bugfix/* (bug fixes)
hotfix/* (production fixes)
```

### Branch Naming Conventions
- **Features:** `feature/product-search` or `feature/add-wishlist`
- **Bugfixes:** `bugfix/fix-product-filter` or `bugfix/cors-error`
- **Hotfixes:** `hotfix/critical-security-patch`
- **Releases:** `release/v1.0.0`

### Creating Feature Branches
```bash
# Update local main/develop first
git checkout develop
git pull origin develop

# Create feature branch
git checkout -b feature/your-feature-name

# Make changes and commit
git add .
git commit -m "feat: Add your feature description"

# Push to GitHub
git push -u origin feature/your-feature-name
```

---

## 📝 Commit Message Convention

Follow Conventional Commits format:

```
<type>(<scope>): <subject>
<blank line>
<body>
<blank line>
<footer>
```

### Types
- **feat:** New feature
- **fix:** Bug fix
- **docs:** Documentation changes
- **style:** Code style (formatting, missing semicolons, etc.)
- **refactor:** Code refactoring without feature/fix
- **perf:** Performance improvements
- **test:** Adding or updating tests
- **chore:** Build, dependencies, etc.

### Examples
```bash
# Feature
git commit -m "feat(products): Add product filtering by category"

# Bug fix
git commit -m "fix(auth): Resolve token expiration issue"

# Documentation
git commit -m "docs: Update API endpoint documentation"

# Multiple commits with detailed message
git commit -m "feat(cart): Implement shopping cart persistence
- Add localStorage support
- Sync cart state with Pinia store
- Add cart recovery on page refresh
- Fixes #123"
```

---

## 🔄 Pull Request Workflow

### Creating a Pull Request

1. **Ensure Your Branch is Up to Date**
   ```bash
   git fetch origin
   git rebase origin/develop
   ```

2. **Push Your Changes**
   ```bash
   git push origin feature/your-feature-name
   ```

3. **Create PR on GitHub**
   - Go to https://github.com/amirulhasanpulok/rclfullstack
   - Click "Compare & pull request"
   - Fill in PR template:
     ```markdown
     ## Description
     Brief description of what this PR does
     
     ## Type of Change
     - [ ] Feature
     - [ ] Bug fix
     - [ ] Documentation
     
     ## Checklist
     - [ ] Tests pass locally
     - [ ] Code follows style guidelines
     - [ ] No new warnings generated
     - [ ] Documentation updated
     
     ## Related Issues
     Fixes #123
     ```

4. **Request Review**
   - Assign reviewers
   - Link to related issues

### Code Review Process

**Reviewer Responsibilities:**
- [ ] Verify code logic and correctness
- [ ] Check test coverage
- [ ] Ensure style consistency
- [ ] Review security implications
- [ ] Verify documentation updates

**Comment Categories:**
- 🟢 **Approve:** Code is ready to merge
- 🟡 **Request Changes:** Issues that must be fixed
- 🔵 **Comment:** Suggestions (can be addressed in future PRs)

### Merging PR
```bash
# After approval, squash and merge for clean history
# GitHub UI: Click "Squash and merge"

# Or from command line:
git checkout develop
git pull origin develop
git merge --squash feature/your-feature-name
git commit -m "feat: Your feature description"
git push origin develop
```

---

## 🛡️ Branch Protection Rules

**Main Branch (`main`)**
- Require pull request reviews: ✅
- Dismiss stale pull request approvals: ✅
- Require branches to be up to date: ✅
- Require status checks to pass: ✅
  - `api-tests`
  - `frontend-tests`
  - Code coverage > 70%

**Develop Branch (`develop`)**
- Require pull request reviews: ✅
- Dismiss stale pull request approvals: ✅
- Require branches to be up to date: ✅

---

## ✅ CI/CD Pipeline

### Automated Tests (GitHub Actions)

**When Tests Run:**
- On every push to `main` or `develop`
- On every pull request

**API Tests** (`.github/workflows/api-tests.yml`)
```bash
✅ Composer dependencies
✅ PHPUnit tests
✅ Code coverage report
✅ Database migrations
```

**Frontend Tests** (`.github/workflows/frontend-tests.yml`)
```bash
✅ npm/pnpm dependencies
✅ TypeScript type checking
✅ ESLint linting
✅ Vitest unit tests
✅ Code coverage report
✅ Production build
```

### Viewing CI/CD Status
- **In PR:** Status checks appear below PR description
- **In Branch:** Green checkmark = all tests passing
- **Actions Tab:** See detailed logs at https://github.com/amirulhasanpulok/rclfullstack/actions

---

## 📦 Monorepo Workspace Management

### Project Structure
```
rclfullstack/
├── apps/
│   ├── api/              # Laravel 11 REST API
│   ├── admin/            # Vue 3 Admin Dashboard
│   └── shop/             # Vue 3 Shop Storefront
├── packages/
│   └── types/            # Shared TypeScript types
└── turbo.json            # Build orchestration
```

### pnpm Workspaces

**Install All Dependencies**
```bash
pnpm install
```

**Run Scripts Across Workspaces**
```bash
# Run tests in all apps
pnpm run test

# Run build in all apps
pnpm run build

# Run specific workspace
pnpm -F @rcl/api run migrate
pnpm -F @rcl/admin run dev
```

**Add Dependency to Specific Workspace**
```bash
# Add to API only
pnpm add -D @types/node -F @rcl/api

# Add to Admin
pnpm add vue-router -F @rcl/admin
```

---

## 🔍 Code Review Checklist

### Backend (PHP/Laravel)

- [ ] **Code Quality**
  - [ ] Follows PSR-12 standard
  - [ ] No code duplication
  - [ ] Proper error handling
  - [ ] Proper logging

- [ ] **Security**
  - [ ] Input validation on all endpoints
  - [ ] Authorization checks in place
  - [ ] No hardcoded secrets
  - [ ] SQL injection protected (using Eloquent)

- [ ] **Database**
  - [ ] Migration created if schema changed
  - [ ] Backward compatible (for zero-downtime deploy)
  - [ ] Proper indexes for queries
  - [ ] No N+1 queries

- [ ] **Testing**
  - [ ] Feature tests written
  - [ ] Edge cases covered
  - [ ] Mocking used appropriately
  - [ ] Tests pass locally

- [ ] **Documentation**
  - [ ] PHPDoc comments on public methods
  - [ ] README updated if needed
  - [ ] API docs updated

### Frontend (Vue 3/TypeScript)

- [ ] **Code Quality**
  - [ ] Follows ESLint rules
  - [ ] Type-safe (proper TypeScript types)
  - [ ] Component reusability
  - [ ] No console logs in production

- [ ] **Performance**
  - [ ] Lazy loading implemented where needed
  - [ ] No unnecessary re-renders
  - [ ] Proper use of `computed` vs `ref`
  - [ ] Image optimization

- [ ] **Accessibility**
  - [ ] Proper ARIA labels
  - [ ] Keyboard navigation support
  - [ ] Semantic HTML
  - [ ] Color contrast adequate

- [ ] **Testing**
  - [ ] Unit tests written
  - [ ] Component tests included
  - [ ] Edge cases covered
  - [ ] Tests pass locally

- [ ] **Documentation**
  - [ ] Component props documented
  - [ ] Complex logic explained
  - [ ] README updated if needed

---

## 🐛 Issue Management

### Issue Tracking

**Creating an Issue:**
1. Go to Issues tab
2. Click "New Issue"
3. Use template:
   ```markdown
   ## Bug Description
   Clear description of the bug
   
   ## Steps to Reproduce
   1. Step 1
   2. Step 2
   3. Step 3
   
   ## Expected Behavior
   What should happen
   
   ## Actual Behavior
   What actually happens
   
   ## Environment
   - OS: macOS/Windows/Linux
   - Node version: 18.x
   - PHP version: 8.2
   
   ## Screenshots
   If applicable, add screenshots
   ```

### Issue Labels
- `bug` - Something isn't working
- `enhancement` - New feature request
- `documentation` - Needs documentation
- `good first issue` - Good for new contributors
- `help wanted` - Community help needed
- `critical` - Production issue
- `wontfix` - Will not be fixed

### Linking PR to Issue
```bash
# In PR description or commit message
Fixes #123
Closes #456
Related to #789
```

---

## 📊 Versioning & Releases

### Semantic Versioning (SemVer)
Format: `MAJOR.MINOR.PATCH`

- **MAJOR:** Breaking changes (v1.0.0 → v2.0.0)
- **MINOR:** New features, backward compatible (v1.0.0 → v1.1.0)
- **PATCH:** Bug fixes (v1.0.0 → v1.0.1)

### Creating a Release

1. **Create Release Branch**
   ```bash
   git checkout main
   git pull origin main
   git checkout -b release/v1.1.0
   ```

2. **Update Version Numbers**
   - PHP: `config/app.php`
   - Node: `package.json`
   - Create `CHANGELOG.md` entry

3. **Create Release PR**
   - Merge to `main` via PR
   - Require approval before merging

4. **Create GitHub Release**
   ```bash
   git tag -a v1.1.0 -m "Release version 1.1.0"
   git push origin v1.1.0
   ```

5. **On GitHub**
   - Go to Releases
   - Click "Create release"
   - Select tag `v1.1.0`
   - Add release notes
   - Publish release

---

## 🤝 Communication Guidelines

### Slack/Discord Channels
- `#announcements` - Important updates
- `#development` - General dev discussion
- `#deployments` - Deployment notifications
- `#issues` - Bug reports and troubleshooting
- `#random` - Off-topic discussion

### Escalation
- **Minor Issue:** Post in appropriate channel
- **Urgent Issue:** Mention relevant team lead
- **Critical Issue:** Page on-call engineer

### Daily Standup Format
```
✅ Yesterday: What I completed
🎯 Today: What I'm working on
🚧 Blockers: Any issues blocking me
```

---

## 📚 Useful Links

| Resource | URL |
|----------|-----|
| Repository | https://github.com/amirulhasanpulok/rclfullstack |
| Project Board | [Add if available] |
| API Documentation | See `PHASE_2_SETUP.md` |
| Frontend Documentation | See `PHASE_3_SETUP.md` |
| Deployment Guide | See `PHASE_4-7_COMPLETE_GUIDE.md` |
| Local Setup | See `QUICK_START.md` |

---

## 🎓 Learning Resources

### Team Reference Materials
- [Conventional Commits](https://www.conventionalcommits.org/)
- [Git Flow Workflow](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow)
- [GitHub Flow](https://docs.github.com/en/get-started/quickstart/github-flow)

### Backend
- [Laravel 11 Documentation](https://laravel.com/docs/11.x)
- [RESTful API Design](https://restfulapi.net/)
- [PHP PSR Standards](https://www.php-fig.org/)

### Frontend
- [Vue 3 Documentation](https://vuejs.org/)
- [TypeScript Handbook](https://www.typescriptlang.org/docs/)
- [Pinia State Management](https://pinia.vuejs.org/)

---

## ✨ Team Best Practices

### Code Quality
✅ **Do:**
- Write self-documenting code
- Add comments for complex logic
- Use type hints (TypeScript/PHP types)
- Write tests for new features
- Keep functions small and focused

❌ **Don't:**
- Commit code with failing tests
- Use magic numbers/strings
- Leave TODO comments without issues
- Copy-paste code without refactoring
- Merge without code review

### Git Hygiene
✅ **Do:**
- Commit frequently with meaningful messages
- Pull before pushing
- Rebase feature branches before PR
- Delete merged branches
- Use `git diff` before committing

❌ **Don't:**
- Commit large files (use Git LFS)
- Push directly to `main` or `develop`
- Force push to shared branches
- Rewrite shared history
- Mix unrelated changes in one commit

### Collaboration
✅ **Do:**
- Communicate blocking issues
- Help team members when they're stuck
- Share knowledge in team meetings
- Document decisions
- Review code promptly

❌ **Don't:**
- Work in isolation without updates
- Criticize code without suggesting improvements
- Leave reviews hanging for days
- Assume context without asking
- Ignore feedback on PRs

---

## 📞 Support & Questions

- **Repository Issues:** Create GitHub issue
- **General Questions:** Ask in team chat
- **Urgent Issues:** Contact project lead
- **Documentation Gaps:** Create issue with details

---

**Last Updated:** January 21, 2026  
**Repository:** git@github.com:amirulhasanpulok/rclfullstack.git
