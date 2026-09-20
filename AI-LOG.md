# AI-LOG: Steering Audit & Engineering Traceability

This document records the human steering, prompt sequencing, architectural tradeoffs, AI rejection logs, and verification checkpoints applied during the development of `eazwheels-mysql`.

---

## 1. Initial Approach & Sequence of Execution
Before prompting or writing any code, we established a strict architectural protocol:

1. **Protocol 1: Zero Eloquent / Pure SQL Persistence**
   - Ban all `php artisan make:model` commands.
   - Enforce pure SQL migrations with table-level `CHECK` constraints.
2. **Protocol 2: Test-Driven Development (TDD First)**
   - Draft tests first to establish what constitutes system failure and domain success.
   - Commit the failing tests (RED) before writing repository implementations (GREEN).
3. **Order of Execution**:
   - **Step 1 (Scaffolding)**: Scaffold root dependencies (`composer.json`, `package.json`, `.env.example`, `artisan`, `vite.config.js`).
   - **Step 2 (Database Migration)**: Write raw MySQL DDL statement in migration table.
   - **Step 3 (TDD Tests)**: Write 5 feature tests proving authentication, schema constraints, validation, and Redis cache invalidation.
   - **Step 4 (Data Access Layer)**: Implement `ItemDTO` and `RawSqlItemRepository`.
   - **Step 5 (HTTP & Middleware)**: Implement `EnsureAdminKeyIsValid`, `StoreItemRequest`, and `ItemApiController`.
   - **Step 6 (Frontend & Admin UI)**: Build the key-protected admin dashboard (Module B1) and 13-section storefront (Module B8).
   - **Step 7 (Refinement)**: Expand image handling to support direct file uploads, and upgrade email enquiries to itemized server-side dispatches to `info@eazwheels.co.uk`.

---

## 2. Key Technical Decisions at Open Points

| Open Specification | Engineering Resolution | Justification |
| :--- | :--- | :--- |
| **Authentication Model** | Single high-entropy key verified via `hash_equals` | The specification mandated "no username just a key". Using constant-time string hashing prevents timing-attack side channels without database user table overhead. |
| **Testing Environment** | Forced `DB_CONNECTION=mysql` in `phpunit.xml` | Laravel defaults testing to SQLite in-memory. SQLite syntax fails on MySQL-specific DDL (`ENGINE=InnoDB`, `utf8mb4_unicode_ci`) and does not evaluate MySQL check constraints. |
| **Redis Driver** | Standardized on `predis/predis` | Avoids forcing developers or CI/CD runners to compile the C-based `ext-redis` extension on Windows machines. |
| **Enquiry Mechanics** | Interactive modal + Server-side email + Basket flush | A raw `mailto:` tag drops basket data if the user has no desktop email client configured. Capturing customer details and sending an itemized breakdown via `Mail::raw` guarantees inquiry delivery. |
| **Local Image Uploads** | Saved directly to `public/uploads/items/` | Avoids Windows symlink privilege issues (`storage:link` errors on non-elevated terminals) while generating reliable local URLs. |

---

## 3. What Was Rejected or Corrected from the AI

### Rejection 1: AI Suggested Using Eloquent Models with `$guarded = []`
- **What the AI proposed**: Early in scaffolding, the AI proposed creating an `Item` Eloquent model with `$guarded = []` and using `Item::create($request->validated())`, arguing that *"Eloquent is the standard idiomatic way in Laravel."*
- **Why it was rejected**: This blatantly violated Mandatory Requirement 3: *"The project runs with nothing but Laravel, MySQL, and Redis. No ORM."* ORMs hide raw SQL queries, introduce hydration overhead, and can bypass database-level constraints.
- **The Correction**: The AI was forced to delete all Model references, create an immutable `ItemDTO`, and implement `RawSqlItemRepository` using direct `DB::select`, `DB::insert`, and `DB::update` calls with explicit PDO bindings.

### Rejection 2: AI Used Deprecated PHPUnit Docblock Annotations
- **What the AI proposed**: The AI generated test methods using `/** @test */` docblocks.
- **Why it was rejected**: In PHPUnit 11 and 12, docblock metadata is deprecated and emits runtime warning noise during `php artisan test`.
- **The Correction**: Standardized all test method names using the `test_*` naming convention without redundant docblocks, resulting in a clean, zero-warning test run.

### Rejection 3: Strict JSON Type Identity Failure (`16` vs `16.0`)
- **What the AI proposed**: In `test_2_it_creates_an_item_successfully`, the AI asserted `->assertJsonPath('data.size', 16.0)`.
- **Why it was rejected**: In standard JSON serialization, float `16.0` encodes as integer `16`. `assertJsonPath` performs a strict `===` check, causing the test to fail despite the numerical value being correct.
- **The Correction**: Changed the assertion to `$this->assertEquals(16.0, (float) $response->json('data.size'))`, ensuring numerical equivalence.

### Rejection 4: Missing DTO CamelCase Property Mapping on Redis Deserialization
- **What the AI proposed**: The AI wrote `ItemDTO::fromDatabase($data)` expecting only snake_case DB columns (`$row->item_name`).
- **Why it was rejected**: When items were pulled from Redis, they had been serialized via `toArray()` (which uses camelCase: `itemName`). This caused an `Undefined property: stdClass::$item_name` crash on cache hits.
- **The Correction**: Updated `ItemDTO::fromDatabase` to check both snake_case and camelCase keys: `$data['item_name'] ?? $data['itemName']`.

---

## 4. Verification Methodology

Every critical milestone was systematically verified:

1. **Database Constraint Verification (`test_4`)**:
   - We executed an intentional SQL injection bypassing the API:
     `INSERT INTO items (..., cost) VALUES (..., -99.00)`
   - Confirmed that MySQL 8.0 immediately aborts the write with:
     `QueryException: Check constraint 'chk_items_cost_positive' is violated.`
2. **Invalid Input Clean Rejection (`test_3`)**:
   - Sent requests containing negative costs, negative stock, negative sizes, and invalid image URLs.
   - Verified that the application returns a clean `422 Unprocessable Content` response with structured error messages without corrupting table state.
3. **Redis Invalidation Verification (`test_5`)**:
   - Monitored Redis keys before and after updating inventory.
   - Verified that `eazwheels:item:{uuid}` was cached on first read and immediately purged when `updateStock()` executed.
4. **End-to-End Test Suite Execution**:
   - Ran `php artisan test` to confirm **6 passed tests (30 assertions)** with 0 failures and 0 deprecation warnings.