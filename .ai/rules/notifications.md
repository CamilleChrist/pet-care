---
paths:
  - 'app/Console/**'
  - 'app/Notifications/**'
---

# Notifications

## Vaccine reminders come from Pet::reminders() and speak of a deadline, not an appointment
Pick the records to remind through `Pet::reminders()` (latest injection of each vaccine), never `VaccinationRecord::whereDate('next_due_at', …)` alone: a renewed injection keeps its old `next_due_at` and would trigger a reminder for a vaccine already done. `next_due_at` is the date by which the owner must book the vet, not a booked appointment: word the notification as "arrive à échéance / pensez à prendre rendez-vous", never "rappel prévu le". Reuse `status_label` for relative wording, placing it at the start of the sentence rather than lower-casing it.

## One digest per owner and per day
The command sends each owner a single `VaccineNotification` holding all the boosters of the day across their pets, never one message per pet or per vaccine. Pass it an Eloquent `Collection` of records (so the queue stores ids), and sort inside the notification, because a queued collection comes back in id order. A new reminder day is one more `orWhereDate()` in `notificationsToRemindToday()`, and shows up as one more line of the digest — not as a new notification class.
