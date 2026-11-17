# Member 3: Mashud Hasan (22299053) - Controllers

**Domain:** Blood Bank Operations & Clinical Safety  
**Assigned Features (SRS):**
1. **F-07: FEFO Inventory Engine** (`BloodInventoryController.php`)
   - First-Expiry-First-Out stock sorting, unit intake, storage condition tracking.
2. **F-08: Expiry & Low-Stock Alerts** (`InventoryAlertController.php`)
   - Threshold scans, component shelf-life warnings, automated replenishment alerts.
3. **F-10: Screening & Deferral Flow** (`ClinicalScreeningController.php`)
   - Donor vitals check (hemoglobin, BP, pulse) and temporary/permanent deferral logic.
4. **F-11: Blood Allocation & Issue** (`BloodIssueController.php`)
   - Atomic database locking (`lockForUpdate`), unit reservation, and collection OTP handshake.
5. **F-20: Donation Camp Coordination** (`DonationCampController.php`)
   - Community donation camp creation, volunteer role assignments, on-site unit synchronization.
