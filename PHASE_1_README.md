# Phase 1: Foundation - Quick Reference

## ✅ What's Ready

Your monorepo foundation is now set up with:

1. **Directory Structure** - `apps/{api,admin,shop}` and `packages/types`
2. **pnpm Workspaces** - Root `package.json` with workspace definitions
3. **Docker Environment** - MySQL, Redis, PHP Apache, Node, Mailhog
4. **Build Orchestration** - Turbo for coordinating builds across apps
5. **Configuration Files** - All needed for monorepo development

---

## 🚀 Quick Start

### Option 1: Using Docker (Recommended)
```bash
# Start all services
pnpm docker:up

# Check services are running
docker-compose ps

# View logs
pnpm docker:logs

# Stop
pnpm docker:down
```

### Option 2: Local Development
```bash
# Install dependencies
pnpm install

# Start development servers (all at once)
pnpm dev

# Or individually:
cd apps/admin && pnpm dev    # Admin dashboard on :5173
cd apps/shop && pnpm dev     # Shop frontend on :5174
```

---

## 📁 What Each Directory Contains

| Directory | Purpose | Status |
|-----------|---------|--------|
| `apps/api` | Laravel 11 REST API | 📋 Ready for code |
| `apps/admin` | Vue 3 admin dashboard | 📋 Ready for code |
| `apps/shop` | Vue 3 customer storefront | 📋 Ready for code |
| `packages/types` | Shared TypeScript types | 📋 Ready for code |
| `docker/` | Docker configuration | ✅ Complete |

---

## 📝 Environment Setup

1. **Create `.env` file** (or copy from `.env.example`):
```env
APP_ENV=local
DB_HOST=db
DB_DATABASE=nicolatetcholdiwsconsole
DB_USERNAME=nicolatetch
DB_PASSWORD=password
```

2. **Docker services will connect automatically**

---

## 🔧 Common Commands

```bash
# Root level (affects all apps)
pnpm dev              # Start all in parallel
pnpm build            # Build all
pnpm test             # Test all
pnpm lint             # Lint all
pnpm type-check       # TypeScript check all

# Docker
pnpm docker:up        # Start containers
pnpm docker:down      # Stop containers
pnpm docker:logs      # View logs

# Individual apps
cd apps/api && pnpm dev
cd apps/admin && pnpm dev
cd apps/shop && pnpm dev
```

---

## 📚 Documentation

- **Phase 1 Details** → `PHASE_1_SETUP.md`
- **Full Roadmap** → `MIGRATION_ROADMAP.md`
- **AI Conventions** → `.github/copilot-instructions.md`
- **Implementation Guide** → `IMPLEMENTATION_GUIDE.md`

---

## ⚠️ Important Notes

- **Database**: MariaDB/MySQL will auto-initialize on first `docker-compose up`
- **Redis**: Used for caching and sessions (automatic with Docker)
- **Mailhog**: Email testing on http://localhost:8025
- **API**: Running on http://localhost:8000
- **Admin**: Running on http://localhost:5173
- **Shop**: Running on http://localhost:5174

---

## 🎯 Next Phase (Phase 2)

After this foundation is working, Phase 2 involves:
1. Migrating existing Laravel code to `apps/api`
2. Setting up API resources and routes
3. Database migration and seeding
4. Initial testing setup

See `PHASE_1_SETUP.md` for detailed Phase 2 plan.

---

## ❓ Troubleshooting

**Docker won't start?**
```bash
docker-compose down -v  # Clean up volumes
pnpm docker:up          # Try again
```

**Port already in use?**
```bash
# Change ports in .env:
API_PORT=8001
VITE_ADMIN_PORT=5175
VITE_SHOP_PORT=5176
```

**pnpm workspaces issue?**
```bash
pnpm install --force
```

---

**Status:** Phase 1 ✅ Complete  
**Next:** Begin Phase 2 or read `PHASE_1_SETUP.md` for details
