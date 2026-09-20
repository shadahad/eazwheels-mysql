# EAZWHEELS Precision Automotive Catalog (`eazwheels-mysql`)

A standalone, ultra-reliable Laravel, MySQL, and Redis vehicle accessories e-commerce catalog and administrative API engineered without Eloquent ORM.

---

## 1. System Requirements & Stack
- **PHP**: 8.2 or 8.3
- **Database**: MySQL 8.0+ (Mandatory for DDL `CHECK` constraint enforcement)
- **Cache/Queue/Session**: Redis 6.0+ (Using pure PHP `predis/predis` driver)
- **Framework**: Laravel 11.x
- **Frontend**: Blade, Tailwind CSS, Alpine/Vanilla JS
- **Dependency Management**: Composer 2.6+, Node.js 18+

---

## 2. Installation & Quickstart

### Step 1: Clone & Install Dependencies
```bash
git clone <repository-url> eazwheels-mysql
cd eazwheels-mysql
```
### Install PHP dependencies (disabling audit blocks if older framework advisories exist)
```bash
composer config audit.block-insecure false
composer install
```
### Step1: Install frontend dependencies
```bash
npm install && npm run build  
```
### Step 2: Clone & Install Dependencies
Copy .env.example to .env:
```bash
cp .env.example .env
php artisan key:generate
```
Verify your .env contains the required MySQL and Redis parameters:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=database_user
DB_PASSWORD=database_password

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

ADMIN_API_KEY=your_actual_key
```
### Step 3: Run Database Migrations
Execute the raw DDL schema migrations against MySQL:
```bash
php artisan migrate:fresh
```
### Step 4: Run the Test Suite
Ensure MySQL and Redis are running locally, then execute:
```bash
php artisan test
```
### Step 5: Start the Application Server
```bash
php artisan serve
```
- Public Storefront (Module B2): http://localhost:8000
- Admin Dashboard (Module B1): http://localhost:8000/admin?admin_key=your_actual_key
## 3. Core Design Guarantees
### Guarantee 1: Database-Level Domain Correctness (Anti-Bypass)
Data integrity is enforced at the MySQL storage engine layer (InnoDB). We do not rely exclusively on web form validation. Even if an actor writes directly to the database via raw SQL or external tools, invalid records are halted by MySQL engine CHECK constraints:

CONSTRAINT chk_items_cost_positive CHECK (cost >= 0.00),
CONSTRAINT chk_items_size_positive CHECK (size > 0.00),
CONSTRAINT chk_items_stock_non_negative CHECK (units_in_stock >= 0)

### Guarantee 2: Zero ORM & Complete Model Isolation
Eloquent Models (Illuminate\Database\Eloquent\Model) have been completely omitted from this repository.

- All reads execute via DB::select(...).
- All writes execute via DB::insert(...) and DB::update(...).
- All operations use parameterized PDO array bindings to eliminate SQL injection.
- Results are mapped directly into immutable Data Transfer Objects (App\DTO\ItemDTO).

### Guarantee 3: Key-Only Administrative Authentication
Administrative access enforces strict key-based authorization (X-Admin-Key header, query parameter, or bearer token) without requiring usernames or session cookies. Comparisons are evaluated via PHP's constant-time hash_equals function to mitigate timing attacks.

### Guarantee 4: Immediate Redis Cache Invalidation
Active items are cached in Redis under the key eazwheels:item:{id} with a 1-hour TTL. Any stock mutation or catalog update through RawSqlItemRepository::updateStock() automatically invalidates the specific item cache and master collection keys, ensuring storefront pricing and stock levels reflect warehouse reality instantly.

## 4. Open Points & Architectural Assumptions
1. **Image Storage Handling**: The REST API specification required itemImage as a string/URL in JSON payloads (POST /items), but practical administrative workflows require uploading images directly from a computer. We resolved this by building a dynamic validator (StoreItemRequest): if a file is uploaded from the web UI, it is validated, saved to public/uploads/items/, and assigned a fully-qualified local URL; if received via API JSON, it is validated as an external URL.
2. **Basket Email Enquiry Flow**: The specification stated "enquire the items via emailing". Rather than a simple, fragile mailto: link that drops basket details if an email client is unconfigured, we implemented an interactive modal that gathers the customer's Name, Email, and Vehicle Notes. The server formats an itemized specification email and dispatches it directly to info@eazwheels.co.uk with Reply-To pointing to the customer, while automatically clearing the basket.
3. **Driver Selection on Windows**: Laravel defaults to the C-extension phpredis. To allow the project to run natively without requiring custom C compilers on Windows dev environments, the project standardizes on predis/predis across both .env and phpunit.xml.

## 5. Architectural Separation & Future Growth
If this system needs to scale to enterprise throughput, its decoupled architecture ensures zero code rewrites:
Because all domain rules and SQL operations live exclusively inside ItemRepositoryInterface using immutable ItemDTOs, the persistence mechanism can transition from single-node MySQL to a horizontally sharded database cluster (such as AWS Aurora MySQL, Vitess, or PlanetScale) without altering a single line of business logic in the API or Storefront controllers. Furthermore, the presentation layer (Storefront vs Admin) can be split into micro-frontends or dedicated containerized services where storefront read traffic is served entirely from Redis edge caches and read-replicas, while admin catalog mutations route to primary database nodes.