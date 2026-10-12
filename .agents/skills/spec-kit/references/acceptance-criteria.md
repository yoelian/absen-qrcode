# Acceptance Criteria & Gherkin Guide

Writing clear, unambiguous acceptance criteria ensures that requirements are testable and zero misunderstandings occur between user intent and code execution.

---

## 1. The Gherkin Syntax (Given-When-Then)

```gherkin
Scenario: [Title of scenario]
  Given [Precondition / initial state]
  And [Additional precondition]
  When [User action or event triggered]
  Then [Expected outcome / state change]
  And [Secondary observable result]
```

---

## 2. Real-World Practical Examples

### Example A: RFID / QR Attendance Scanning
```gherkin
Scenario: Scan registered and active student card
  Given a student with QR code "SISWA-101" exists and has status "active"
  And the student has not checked in today
  When the card "SISWA-101" is scanned on the scan page
  Then an attendance record with type "in" should be created with current timestamp
  And a green audio feedback and toast notification should appear
  And the input field should automatically refocus and clear within 100ms

Scenario: Scan unregistered QR code
  Given no student or employee exists with QR code "UNKNOWN-999"
  When the card "UNKNOWN-999" is scanned
  Then no attendance record should be created
  And a red error alert stating "Kartu tidak terdaftar" should be displayed
  And the system should log a warning with the attempted barcode
```

---

## 3. Checklist for Robust Criteria
- [ ] Is the scenario independent and self-contained?
- [ ] Does it specify exact error messages or HTTP codes?
- [ ] Does it cover edge cases (e.g. duplicate scan within 3 seconds, offline state, empty input)?
- [ ] Can this scenario be directly translated into an automated test (PHPUnit/Pest/Cypress)?
