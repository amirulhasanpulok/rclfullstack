# New Team Member Onboarding Guide

**Welcome to the Nicola Tetcholdiwsconsole Team!** 🎉

This guide will help you get up to speed with our modernized e-commerce platform.

---

## 📋 Onboarding Checklist (First Day)

- [ ] **Read this document completely**
- [ ] **Review project status** (see [EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md))
- [ ] **Clone the repository** (see instructions below)
- [ ] **Setup your development environment** (see [QUICK_START.md](QUICK_START.md))
- [ ] **Run local tests** to verify setup
- [ ] **Introduce yourself** to the team
- [ ] **Ask questions!** No question is too small

---

## 🚀 First Steps (30 Minutes)

### 1. Clone the Repository

```bash
# Via SSH (recommended)
git clone git@github.com:amirulhasanpulok/rclfullstack.git

# Via HTTPS (if SSH not available)
git clone https://github.com/amirulhasanpulok/rclfullstack.git

cd rclfullstack
```

### 2. Install Dependencies

```bash
# Install all project dependencies
pnpm install

# If you don't have pnpm installed
npm install -g pnpm
```

### 3. Setup the API

```bash
cd apps/api

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# (Optional) Seed demo data
php artisan db:seed --class=ProductionSeeder
```

### 4. Start Development Servers

**Terminal 1 - API Server:**
```bash
cd apps/api
php artisan serve
# Runs on http://localhost:8000
```

**Terminal 2 - Admin Dashboard:**
```bash
cd apps/admin
npm run dev
# Runs on http://localhost:5173
```

**Terminal 3 - Shop Storefront:**
```bash
cd apps/shop
npm run dev
# Runs on http://localhost:5174
```

### 5. Verify Everything Works

```bash
# In a new terminal, run tests
pnpm run test

# Should see all tests passing ✅
```

---

## 📚 Essential Reading (1-2 Hours)

Read these documents in order to understand the project:

### For All Team Members
1. **[EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md)** (15 min)
   - Project overview
   - Key achievements
   - Current status

2. **[GITHUB_COLLABORATION.md](GITHUB_COLLABORATION.md)** (20 min)
   - How we work together
   - Git workflow
   - Code review process

3. **[TEAM_HANDOFF.md](TEAM_HANDOFF.md)** (20 min)
   - Deployment procedures
   - Team roles
   - Success metrics

### For Backend Developers
4. **[PHASE_2_SETUP.md](PHASE_2_SETUP.md)** (30 min)
   - API architecture
   - Database schema
   - Available endpoints

### For Frontend Developers
4. **[PHASE_3_SETUP.md](PHASE_3_SETUP.md)** (30 min)
   - Frontend architecture
   - Component structure
   - State management

### For DevOps/Infrastructure
4. **[PHASES_4-7_COMPLETE_GUIDE.md](PHASES_4-7_COMPLETE_GUIDE.md)** (30 min)
   - Testing setup
   - Deployment procedures
   - Monitoring

---

## 🏗️ Project Structure Explained

```
rclfullstack/
│
├── apps/                          # Three separate applications
│   ├── api/                       # Laravel 11 REST API
│   │   ├── app/Http/Controllers/  # API endpoints
│   │   ├── app/Models/            # Database models
│   │   ├── app/Console/Commands/  # CLI commands
│   │   ├── database/              # Migrations & seeders
│   │   └── tests/                 # Test files
│   │
│   ├── admin/                     # Vue 3 Admin Dashboard
│   │   ├── src/pages/             # Page components
│   │   ├── src/components/        # Reusable components
│   │   ├── src/stores/            # Pinia state
│   │   └── src/api/               # API client
│   │
│   └── shop/                      # Vue 3 Shop Storefront
│       ├── src/pages/             # Page components
│       ├── src/components/        # Reusable components
│       ├── src/stores/            # Pinia state
│       └── src/api/               # API client
│
├── packages/                      # Shared code
│   └── types/                     # Shared TypeScript types
│
├── .github/                       # GitHub configuration
│   └── workflows/                 # CI/CD pipelines
│
└── docs/                          # Documentation files
    ├── QUICK_START.md
    ├── TEAM_HANDOFF.md
    └── GITHUB_COLLABORATION.md
```

---

## 👥 Team Structure & Who to Ask

### Backend Issues?
**Ask:** Backend Lead or Backend Development Team
**Files:** `apps/api/` directory
**Reference:** [PHASE_2_SETUP.md](PHASE_2_SETUP.md)

### Admin Dashboard Issues?
**Ask:** Admin Frontend Lead or Frontend Team
**Files:** `apps/admin/` directory
**Reference:** [PHASE_3_SETUP.md](PHASE_3_SETUP.md)

### Shop Storefront Issues?
**Ask:** Shop Frontend Lead or Frontend Team
**Files:** `apps/shop/` directory
**Reference:** [PHASE_3_SETUP.md](PHASE_3_SETUP.md)

### Deployment/Infrastructure Issues?
**Ask:** DevOps Lead or Infrastructure Team
**Reference:** [PHASES_4-7_COMPLETE_GUIDE.md](PHASES_4-7_COMPLETE_GUIDE.md)

### General Questions?
**Ask:** Project Lead or Team Slack Channel
**Resources:** Refer to relevant documentation

---

## 🔧 Development Workflow

### 1. Starting Work on a New Feature

```bash
# Update develop branch
git checkout develop
git pull origin develop

# Create feature branch
git checkout -b feature/your-feature-name

# Make your changes
# ...

# Commit with meaningful message
git add .
git commit -m "feat: Add your feature description"

# Push to GitHub
git push -u origin feature/your-feature-name
```

### 2. Creating a Pull Request

1. Go to https://github.com/amirulhasanpulok/rclfullstack
2. Click "Compare & pull request" for your branch
3. Fill in the PR description:
   ```markdown
   ## Description
   What does this PR do?
   
   ## Changes
   - Change 1
   - Change 2
   
   ## Testing
   How was this tested?
   
   ## Checklist
   - [ ] Tests pass locally
   - [ ] Code follows style guide
   - [ ] Documentation updated
   ```

4. Request reviewers from your team
5. Address any feedback from code review

### 3. After PR Approval

```bash
# Pull latest develop
git checkout develop
git pull origin develop

# Merge via GitHub (Squash and merge recommended)
# Or merge locally and push:
git merge --squash feature/your-feature-name
git commit -m "feat: Your feature description"
git push origin develop
```

---

## 🧪 Testing & Quality Checks

### Run Tests Locally

```bash
# Backend tests
cd apps/api
php artisan test

# Frontend tests
cd apps/admin
npm run test
# and
cd apps/shop
npm run test

# Or run all tests at once from root
pnpm run test
```

### Code Style & Linting

```bash
# Check code style
pnpm run lint

# Type checking
pnpm run type-check
```

### Before Committing
- [ ] Run tests: `pnpm run test`
- [ ] Check style: `pnpm run lint`
- [ ] Type check: `pnpm run type-check`
- [ ] Review your changes: `git diff`

---

## 🚀 Useful Commands

### Project Management
```bash
# Install dependencies
pnpm install

# Run all tests
pnpm run test

# Generate coverage reports
pnpm run test:coverage

# Type checking
pnpm run type-check

# Linting
pnpm run lint

# Build for production
pnpm run build
```

### Database
```bash
# Run migrations
php artisan migrate

# Create new migration
php artisan make:migration create_table_name

# Seed demo data
php artisan db:seed --class=ProductionSeeder

# Reset database (development only!)
php artisan migrate:reset
```

### Git
```bash
# View commit history
git log --oneline -10

# See what changed
git diff

# Stash work in progress
git stash

# View branches
git branch -a

# Delete local branch
git branch -d feature-name
```

### Docker (if using)
```bash
# Start services
docker-compose up -d

# View logs
docker-compose logs -f api

# Stop services
docker-compose down

# Rebuild images
docker-compose build
```

---

## 📞 Getting Help

### Problem-Solving Steps

1. **Check the Documentation**
   - Start with [QUICK_START.md](QUICK_START.md)
   - Review relevant phase guide
   - Search project README

2. **Check GitHub Issues**
   - Search for similar issues
   - Comment on existing issues

3. **Check the Code**
   - Browse similar implementations
   - Read code comments
   - Check git history: `git log -p`

4. **Ask Your Team**
   - Post in team Slack
   - Ask in standup meeting
   - Direct message team lead

5. **Ask the Team Lead**
   - If no one else knows
   - For architectural decisions
   - For urgent blockers

### Common Issues & Solutions

**Issue: `Command not found: pnpm`**
```bash
npm install -g pnpm
```

**Issue: `Cannot connect to database`**
```bash
# Check .env file has correct credentials
cd apps/api
cat .env | grep DB_

# Run migrations
php artisan migrate
```

**Issue: `Port already in use`**
```bash
# Find process using port 8000
lsof -i :8000

# Kill process
kill -9 <PID>

# Or use different port
php artisan serve --port=8001
```

**Issue: `Tests failing locally but passing in CI`**
```bash
# Clear caches
php artisan cache:clear
rm -rf node_modules/.vite

# Reinstall
pnpm install
php artisan migrate
```

---

## 🎓 Learning Resources

### Backend (Laravel/PHP)
- [Laravel 11 Documentation](https://laravel.com/docs/11.x)
- [PHP Docs](https://www.php.net/docs.php)
- [PHPUnit Testing](https://phpunit.de/documentation.html)

### Frontend (Vue 3/TypeScript)
- [Vue 3 Guide](https://vuejs.org/)
- [TypeScript Handbook](https://www.typescriptlang.org/docs/)
- [Pinia Store Docs](https://pinia.vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)

### Git & GitHub
- [Git Book](https://git-scm.com/book/en/v2)
- [GitHub Guides](https://guides.github.com/)
- [Conventional Commits](https://www.conventionalcommits.org/)

### Database
- [MySQL 8.0 Docs](https://dev.mysql.com/doc/refman/8.0/en/)
- [Eloquent ORM](https://laravel.com/docs/11.x/eloquent)

---

## ✅ First Week Milestones

### Day 1-2
- [x] Setup local environment
- [x] Run project locally
- [x] Read project overview
- [x] Understand team structure

### Day 3-4
- [x] Read your specialty area (backend/frontend/devops)
- [x] Make first small contribution
- [x] Participate in code review
- [x] Attend team standup

### Day 5
- [x] Complete first feature/bugfix
- [x] Create first pull request
- [x] Work with team on code review
- [x] Understand deployment process

### Week 2-4
- [x] Contribute regularly
- [x] Learn codebase patterns
- [x] Build confidence with workflow
- [x] Take on more complex tasks

---

## 🎉 Welcome!

You're now part of a modern, well-organized development team working on a production-ready e-commerce platform!

**Quick Wins to Build Confidence:**
1. Fix a small bug from the issue tracker
2. Improve documentation
3. Add tests for existing code
4. Optimize a slow query or component

**Remember:**
- ✅ Ask questions - we're here to help!
- ✅ Share knowledge - explain what you learn
- ✅ Follow processes - helps the whole team
- ✅ Have fun - we love what we do!

---

## 📞 Quick Reference

| Topic | Resource |
|-------|----------|
| Getting Started | [QUICK_START.md](QUICK_START.md) |
| Project Overview | [EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md) |
| Git Workflow | [GITHUB_COLLABORATION.md](GITHUB_COLLABORATION.md) |
| Team Info | [TEAM_HANDOFF.md](TEAM_HANDOFF.md) |
| API Details | [PHASE_2_SETUP.md](PHASE_2_SETUP.md) |
| Frontend Details | [PHASE_3_SETUP.md](PHASE_3_SETUP.md) |
| Deployment | [PHASES_4-7_COMPLETE_GUIDE.md](PHASES_4-7_COMPLETE_GUIDE.md) |

---

**Questions? Contact your team lead or post in the team Slack! Welcome aboard! 🚀**

*Last Updated: January 21, 2026*
