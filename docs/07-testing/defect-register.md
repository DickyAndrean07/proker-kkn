# Defect Register — DEV-08 Formal Testing & Release Qualification

| Attribute | Value |
| :--- | :--- |
| **Project** | Portal Informasi Desa Bendung |
| **Document** | Defect Register |
| **Version** | 1.0 |
| **Testing Specification Authority** | `docs/07-testing/testing-specification.md` (v1.1 FROZEN) |
| **Execution Environment** | PHP 8.3.26 / Laravel 13.25.0 / MariaDB 10.4.32 / Windows |
| **Status** | Active / Maintained during DEV-08 |

---

## 1. Defect Severity Definitions

| Severity | Definition & Impact Criteria | Target Resolution |
| :--- | :--- | :--- |
| **CRITICAL** | Authorization bypass, cross-Dusun leakage, unauthorized destructive action, data loss/corruption, security vulnerability. | Must be fixed before any release or qualification. Blocker for MVP. |
| **HIGH** | Core CRUD or lifecycle failure, broken public navigation/detail, major validation failure without fallback. | Must be fixed for Local MVP QA. |
| **MEDIUM** | Secondary UX/UI flaw, non-critical responsive issue, material visual inconsistency without functional loss. | Fix or document as accepted known issue. |
| **LOW** | Minor copy, spacing, or visual polish with zero functional or access impact. | Tracked for post-MVP refinement. |

---

## 2. Defect Status Lifecycle
`OPEN` $\rightarrow$ `IN_PROGRESS` $\rightarrow$ `RESOLVED` $\rightarrow$ `VERIFIED_CLOSED` (or `WONT_FIX_DOCUMENTED`).

---

## 3. Defect Log

| Defect ID | Formal TC | Severity | Summary | Root Cause | Fix / Resolution | Retest Evidence | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| *(None)* | — | — | *No implementation defects detected during formal test execution of 108 test cases.* | — | — | 300 automated regression tests passing cleanly (1253 assertions). | **CLOSED** |

---

## 4. Defect Summary Table

| Severity | Logged | Open | Resolved / Closed | Pass Rate Impact |
| :--- | :---: | :---: | :---: | :---: |
| **CRITICAL** | 0 | 0 | 0 | 0% |
| **HIGH** | 0 | 0 | 0 | 0% |
| **MEDIUM** | 0 | 0 | 0 | 0% |
| **LOW** | 0 | 0 | 0 | 0% |
| **TOTAL** | **0** | **0** | **0** | **0% Defects** |

---

## 5. Security & Integrity Defect Audit Sign-Off
- **Cross-Dusun / Cross-Role Isolation Defects:** 0
- **Unauthorized Destructive Action Defects:** 0
- **Logically Removed Account Login Defects:** 0
- **Public Data Leakage Defects:** 0
- **Database Schema / FK Violation Defects:** 0
