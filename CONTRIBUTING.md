# 🤝 Contributing to Apotek Puri Pasir Putih

First off, thank you for considering contributing to our pharmacy management system! It's people like you that make this project better for everyone.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Commit Guidelines](#commit-guidelines)
- [Pull Request Process](#pull-request-process)
- [Issue Guidelines](#issue-guidelines)
- [Testing](#testing)
- [Documentation](#documentation)

---

## 📜 Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

### Our Pledge

We pledge to make participation in our project a harassment-free experience for everyone, regardless of:
- Age, body size, disability, ethnicity, gender identity and expression
- Level of experience, nationality, personal appearance, race, religion
- Sexual identity and orientation

### Our Standards

**Positive behavior includes:**
- Using welcoming and inclusive language
- Being respectful of differing viewpoints and experiences
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

**Unacceptable behavior includes:**
- Trolling, insulting/derogatory comments, and personal or political attacks
- Public or private harassment
- Publishing others' private information without explicit permission
- Other conduct which could reasonably be considered inappropriate

---

## 🚀 Getting Started

### Prerequisites

Before contributing, make sure you have:
- PHP 8.1+ installed
- Composer installed
- MySQL 8.0+ installed
- Git installed
- Basic knowledge of Laravel and PHP

### Quick Start

1. **Fork the repository**
2. **Clone your fork**
   ```bash
   git clone https://github.com/yourusername/apotek-puri-pasir-putih.git
   cd apotek-puri-pasir-putih
   ```
3. **Install dependencies**
   ```bash
   composer install
   ```
4. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. **Create a branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

---

## 🛠️ How Can I Contribute?

### 🐛 Reporting Bugs

Before creating bug reports, please check existing issues to avoid duplicates.

**When filing a bug report, include:**
- **Clear title** - Descriptive summary of the issue
- **Environment details** - OS, PHP version, browser, etc.
- **Steps to reproduce** - Detailed steps to reproduce the behavior
- **Expected behavior** - What you expected to happen
- **Actual behavior** - What actually happened
- **Screenshots** - If applicable, add screenshots
- **Additional context** - Any other relevant information

**Bug Report Template:**
```markdown
## Bug Description
Brief description of the bug

## Environment
- OS: [e.g., Windows 10, Ubuntu 20.04]
- PHP Version: [e.g., 8.1.2]
- Laravel Version: [e.g., 10.x]
- Browser: [e.g., Chrome 96.0]

## Steps to Reproduce
1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

## Expected Behavior
What should happen

## Actual Behavior
What actually happens

## Screenshots
If applicable, add screenshots

## Additional Context
Any other relevant information
```

### ✨ Suggesting Features

Feature suggestions are welcome! Please provide:
- **Clear title** - Concise feature description
- **Problem statement** - What problem does this solve?
- **Proposed solution** - How should it work?
- **Alternatives considered** - Other solutions you've considered
- **Additional context** - Screenshots, mockups, etc.

### 🔧 Code Contributions

We welcome code contributions! Here are areas where help is needed:

**High Priority:**
- Bug fixes
- Performance improvements
- Security enhancements
- Test coverage improvements

**Medium Priority:**
- New features
- UI/UX improvements
- Documentation updates
- Code refactoring

**Low Priority:**
- Code style improvements
- Minor optimizations
- Additional language support

---

## 💻 Development Setup

### 1. Environment Setup

```bash
# Clone your fork
git clone https://github.com/yourusername/apotek-puri-pasir-putih.git
cd apotek-puri-pasir-putih

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set up database
mysql -u root -p -e "CREATE DATABASE apotek_test"

# Update .env for testing
DB_DATABASE=apotek_test

# Run migrations and seeders
php artisan migrate --seed
```

### 2. Development Tools

**Recommended IDE/Editor:**
- VS Code with PHP extensions
- PhpStorm
- Sublime Text with PHP packages

**Useful Extensions:**
- PHP Intelephense
- Laravel Extension Pack
- GitLens
- Prettier
- ESLint

### 3. Local Development

```bash
# Start development server
php artisan serve

# Watch for file changes (if using Node.js)
npm run dev

# Run tests
php artisan test
```

---

## 📏 Coding Standards

### PHP Standards

We follow **PSR-12** coding standards with some project-specific additions:

#### 1. Code Style
```php
<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $obats = Obat::select([
            'id',
            'nama_obat',
            'harga_jual',
            'stok'
        ])->get();

        return response()->json($obats);
    }
}
```

#### 2. Naming Conventions
- **Classes**: PascalCase (`ObatController`)
- **Methods**: camelCase (`getObatList`)
- **Variables**: camelCase (`$obatList`)
- **Constants**: UPPER_SNAKE_CASE (`MAX_STOCK_LIMIT`)
- **Database tables**: snake_case (`penjualan_details`)
- **Database columns**: snake_case (`nama_obat`)

#### 3. Documentation
```php
/**
 * Calculate total price for transaction
 *
 * @param array $items Array of items with quantity and price
 * @param float $discount Discount percentage (0-100)
 * @return float Total price after discount
 * 
 * @throws InvalidArgumentException When items array is empty
 */
public function calculateTotal(array $items, float $discount = 0): float
{
    // Implementation
}
```

### JavaScript Standards

#### 1. ES6+ Features
```javascript
// Use const/let instead of var
const apiUrl = '/api/obat';
let searchQuery = '';

// Use arrow functions
const filterProducts = (products, query) => {
    return products.filter(product => 
        product.nama_obat.toLowerCase().includes(query.toLowerCase())
    );
};

// Use template literals
const productCard = `
    <div class="product-card">
        <h3>${product.nama_obat}</h3>
        <p>Rp ${product.harga.toLocaleString()}</p>
    </div>
`;
```

#### 2. Class-based Architecture
```javascript
class ProductManager {
    constructor() {
        this.products = [];
        this.init();
    }
    
    async init() {
        await this.loadProducts();
        this.setupEventListeners();
    }
    
    async loadProducts() {
        try {
            const response = await fetch('/api/obat');
            this.products = await response.json();
        } catch (error) {
            console.error('Failed to load products:', error);
        }
    }
}
```

### CSS Standards

#### 1. CSS Custom Properties
```css
:root {
    --primary-color: #2563eb;
    --secondary-color: #10b981;
    --font-family: 'Inter', sans-serif;
    --spacing-4: 1rem;
    --radius-lg: 0.75rem;
}

.btn-primary {
    background-color: var(--primary-color);
    padding: var(--spacing-4);
    border-radius: var(--radius-lg);
    font-family: var(--font-family);
}
```

#### 2. BEM Methodology
```css
/* Block */
.product-card { }

/* Element */
.product-card__title { }
.product-card__price { }
.product-card__image { }

/* Modifier */
.product-card--featured { }
.product-card--out-of-stock { }
```

---

## 📝 Commit Guidelines

We follow [Conventional Commits](https://www.conventionalcommits.org/) specification:

### Commit Message Format
```
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]
```

### Types
- **feat**: New feature
- **fix**: Bug fix
- **docs**: Documentation changes
- **style**: Code style changes (formatting, etc.)
- **refactor**: Code refactoring
- **perf**: Performance improvements
- **test**: Adding or updating tests
- **chore**: Maintenance tasks

### Examples
```bash
# Feature
git commit -m "feat(kasir): add QRIS payment method"

# Bug fix
git commit -m "fix(pdf): resolve UTF-8 encoding issues"

# Documentation
git commit -m "docs: update installation guide"

# Breaking change
git commit -m "feat(api)!: change response format for products endpoint

BREAKING CHANGE: Product API now returns nested object structure"
```

### Commit Best Practices
- Use present tense ("add feature" not "added feature")
- Use imperative mood ("move cursor to..." not "moves cursor to...")
- Limit first line to 72 characters
- Reference issues and pull requests when applicable
- Include breaking change information in footer

---

## 🔄 Pull Request Process

### 1. Before Submitting

**Checklist:**
- [ ] Code follows project coding standards
- [ ] Tests pass locally
- [ ] Documentation updated (if needed)
- [ ] Commit messages follow guidelines
- [ ] Branch is up to date with main
- [ ] No merge conflicts

### 2. Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] Documentation update

## Testing
- [ ] Unit tests pass
- [ ] Integration tests pass
- [ ] Manual testing completed

## Screenshots (if applicable)
Add screenshots to help explain your changes

## Checklist
- [ ] My code follows the style guidelines
- [ ] I have performed a self-review
- [ ] I have commented my code, particularly in hard-to-understand areas
- [ ] I have made corresponding changes to the documentation
- [ ] My changes generate no new warnings
- [ ] I have added tests that prove my fix is effective or that my feature works
- [ ] New and existing unit tests pass locally with my changes
```

### 3. Review Process

1. **Automated checks** must pass
2. **Code review** by maintainers
3. **Testing** in development environment
4. **Approval** from at least one maintainer
5. **Merge** by maintainer

### 4. After Merge

- Delete your feature branch
- Update your local main branch
- Close related issues

---

## 🐛 Issue Guidelines

### Issue Labels

We use labels to categorize issues:

**Type:**
- `bug` - Something isn't working
- `enhancement` - New feature or request
- `documentation` - Improvements or additions to documentation
- `question` - Further information is requested

**Priority:**
- `priority: high` - Critical issues
- `priority: medium` - Important issues
- `priority: low` - Nice to have

**Status:**
- `status: needs-triage` - Needs initial review
- `status: in-progress` - Currently being worked on
- `status: blocked` - Blocked by external dependency
- `status: ready-for-review` - Ready for code review

**Difficulty:**
- `good first issue` - Good for newcomers
- `help wanted` - Extra attention is needed
- `difficulty: easy` - Easy to implement
- `difficulty: medium` - Moderate complexity
- `difficulty: hard` - High complexity

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ObatTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter testCanCreateObat
```

### Writing Tests

#### 1. Feature Tests
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Obat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObatTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_obat(): void
    {
        $admin = User::factory()->admin()->create();
        
        $response = $this->actingAs($admin)
            ->post('/admin/obat', [
                'nama_obat' => 'Paracetamol',
                'harga_jual' => 5000,
                'stok' => 100,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('obats', [
            'nama_obat' => 'Paracetamol',
        ]);
    }
}
```

#### 2. Unit Tests
```php
<?php

namespace Tests\Unit;

use App\Helpers\PenjualanHelper;
use PHPUnit\Framework\TestCase;

class PenjualanHelperTest extends TestCase
{
    public function test_calculate_total_items(): void
    {
        $details = collect([
            (object) ['jumlah' => 2],
            (object) ['jumlah' => 3],
            (object) ['jumlah' => 1],
        ]);

        $total = PenjualanHelper::calculateTotalItems($details);

        $this->assertEquals(6, $total);
    }
}
```

### Test Coverage

We aim for:
- **Unit tests**: 90%+ coverage
- **Feature tests**: 80%+ coverage
- **Critical paths**: 100% coverage

---

## 📚 Documentation

### Code Documentation

#### 1. Inline Comments
```php
// Calculate discount based on customer type and purchase amount
$discount = $this->calculateDiscount($customer, $totalAmount);

/**
 * Complex algorithm explanation:
 * 1. Check customer loyalty level
 * 2. Apply volume discounts
 * 3. Consider seasonal promotions
 */
```

#### 2. Method Documentation
```php
/**
 * Process pharmacy transaction
 *
 * This method handles the complete transaction flow including:
 * - Inventory validation
 * - Price calculation
 * - Payment processing
 * - Receipt generation
 *
 * @param array $items Array of items with id, quantity, and price
 * @param string $paymentMethod Payment method (cash, qris)
 * @param float|null $amountReceived Amount received for cash payments
 * 
 * @return array Transaction result with success status and details
 * 
 * @throws InsufficientStockException When item stock is insufficient
 * @throws InvalidPaymentException When payment amount is invalid
 * 
 * @example
 * $result = $this->processTransaction([
 *     ['id' => 1, 'quantity' => 2, 'price' => 5000],
 *     ['id' => 2, 'quantity' => 1, 'price' => 10000]
 * ], 'cash', 25000);
 */
public function processTransaction(array $items, string $paymentMethod, ?float $amountReceived = null): array
{
    // Implementation
}
```

### README Updates

When adding features, update:
- Feature list
- Installation instructions (if needed)
- Usage examples
- API documentation
- Screenshots

---

## 🏆 Recognition

Contributors will be recognized in:
- **README.md** - Contributors section
- **CHANGELOG.md** - Feature attribution
- **GitHub releases** - Contributor mentions

### Contribution Types

We recognize various types of contributions:
- 💻 **Code** - Bug fixes, features, improvements
- 📖 **Documentation** - README, guides, comments
- 🐛 **Bug Reports** - Quality issue reports
- 💡 **Ideas** - Feature suggestions and feedback
- 🎨 **Design** - UI/UX improvements
- 🧪 **Testing** - Test improvements and coverage
- 🌍 **Translation** - Internationalization support

---

## ❓ Questions?

If you have questions about contributing:

1. **Check existing documentation** - README, INSTALLATION, etc.
2. **Search existing issues** - Your question might be answered
3. **Create a new issue** - Use the question template
4. **Contact maintainers** - Email or GitHub discussions

### Getting Help

- **GitHub Discussions** - For general questions
- **GitHub Issues** - For bug reports and feature requests
- **Email** - fuad@example.com for private matters

---

## 🙏 Thank You

Thank you for taking the time to contribute! Every contribution, no matter how small, helps make this project better for everyone.

**Happy Contributing! 🚀**

---

*This contributing guide is inspired by open source best practices and is continuously updated based on community feedback.*