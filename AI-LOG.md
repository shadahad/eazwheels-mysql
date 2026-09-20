# AI-LOG: Architectural Steering & Audit Trace

This document captures the engineering steering, architectural decisions, and human verification trails applied while implementing the `eazwheels-mysql` platform.

---

### 1. Pre-Code Strategy & Execution Order
Before producing or executing application logic, we defined the strict constraints:
1. **Zero ORM Enforcement**: Complete omission of Eloquent, Model classes, or ActiveRecord conventions. All database interactions execute via raw, parameterized SQL queries through `Illuminate\Support\Facades\DB` with strict PDO binding types.
2. **Database Integrity Over API Independence**: MySQL engine-level `CHECK` constraints (`chk_items_cost_positive`, `chk_items_size_positive`, `chk_items_stock_non_negative`) ensuring non-API direct SQL operations cannot corrupt data.
3. **Execution Sequence**:
   - Step 1: Raw DDL SQL Migration with Table Level Check Constraints.
   - Step 2: Red TDD Phase (Unit & Feature tests written and verified failing).
   - Step 3: DTO and Raw SQL Repository Implementation with Redis cache decorators.
   - Step 4: Admin Key Middleware and Form Requests.
   - Step 5: Green TDD Phase (All tests passing).
   - Step 6: Modules B1 (Admin) and B8 (Public Storefront) Blade views.

---

### 2. Resolution of Open Specifications

| Open Point | Decision | Engineering Justification |
| :--- | :--- | :--- |
| **Admin Authentication Format** | Single High-Entropy Key (`ADMIN_API_KEY`) verified via `hash_equals` | The specification explicitly requested "no username just a key". Timing attack-resistant string comparison ensures security without the overhead of user tables. |
| **`modified` Column Behavior** | Set to `created` value on initial insert; updated to London time on subsequent stock/item changes | The spec mandated `modified` is updated "at the time of inserting new items, updating stock and other actions". |
| **Cart Persistence** | Client-side reactive memory with immediate stock validation bounds | Prevents redundant session roundtrips while enforcing `qty <= units_in_stock`. |

---

### 3. AI Hallucination & Proposal Rejection

* **What the AI proposed**: 
  The AI suggested using Eloquent models combined with `$guarded = []` and adding the `Illuminate\Database\Eloquent\SoftDeletes` trait, arguing that "Laravel best practices encourage Eloquent for developer velocity."
* **Why it was rejected**:
  This violated Requirement 3 (*"No ORM"*). ORM abstractions obscure explicit SQL execution, introduce hydration overhead, and can bypass database-level constraints if developers rely on ORM validation hooks.
* **The Corrective Action**:
  We wiped the suggested Models, created a dedicated Data Transfer Object (`ItemDTO`), and wrote a standalone `RawSqlItemRepository` using `DB::insert(...)`, `DB::select(...)`, and `DB::update(...)` with parameterized array bindings.

---

### 4. Verification Methods

1. **Check Constraint Integrity Verification**:
   - Executed `test_4_mysql_check_constraints_prevent_bypassing_corruption` which executes a direct PDO statement `INSERT INTO items ... VALUES (-99)`.
   - Verified that MySQL 8.0 halts execution with `QueryException: Check constraint 'chk_items_cost_positive' is violated.`
2. **TDD Progression Verification**:
   - Ran `php artisan test --filter=ItemApiTest` after writing tests, confirming all 5 tests failed (RED).
   - Ran again after implementing the RawSqlItemRepository and Controllers, confirming 5/5 green assertions.
3. **Redis Cache Invalidation Verification**:
   - Interrogated Redis via `redis-cli monitor` while executing stock updates.
   - Confirmed key `eazwheels:item:{uuid}` is deleted upon executing `RawSqlItemRepository::updateStock()`.