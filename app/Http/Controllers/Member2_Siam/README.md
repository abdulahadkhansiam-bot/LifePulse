# Member 2: Md Abdul Ahad Khan Siam (23101011) - Controllers

**Domain:** Emergency Coordination & Donor Matching  
**Assigned Features (SRS):**
1. **F-03: Blood Request Lifecycle** (`BloodRequestController.php`)
   - Manages urgent blood requests, state machine transitions, urgency tiers, expiration timers.
2. **F-04: Compatibility Matching** (`CompatibilityController.php`)
   - Biological ABO/Rh compatibility engine and donor ranking logic.
3. **F-05: Outreach & Batch Tracking** (`OutreachBatchController.php`)
   - Staged batch notification engine and donor response escalation queues.
4. **F-06: Emergency (SOS) Dispatch** (`EmergencySosController.php`)
   - Priority broadcast dispatcher for critical life-or-death requests.
5. **F-15: Custom Haversine Search** (`ProximitySearchController.php`)
   - Custom spherical trigonometry (Haversine formula in raw SQL) for coordinate distance matching.
