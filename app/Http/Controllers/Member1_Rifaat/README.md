# Member 1: Rifaat Nuha (23301320) - Controllers

**Domain:** Donor Experience, Eligibility & Retention  
**Assigned Features (SRS):**
1. **F-01: Donor Health Evaluation Engine** (`EligibilityController.php`)
   - Evaluates age, weight, health declaration answers, and donation interval gap to set medical eligibility and calculate next eligible date.
2. **F-02: Digital Donor Card (PDF/QR)** (`DonorCardController.php`)
   - Compiles donation history, awards milestone badges, and generates downloadable PDF donor card with dynamic QR code.
3. **F-09: Appointment Scheduling** (`AppointmentController.php`)
   - Slot booking engine with capacity concurrency handling.
4. **F-12: Reminder Scheduler Daemon** (`ReminderController.php`)
   - Automated notification & email dispatcher for upcoming appointments and eligibility expiration.
5. **F-13: Donor Reliability Scoring** (`ReliabilityController.php`)
   - Algorithmic score updates based on attendance, cancellations, and pledges.
