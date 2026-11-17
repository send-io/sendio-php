# Changelog

## 1.1.0 (2026-11-18)

- New: `$sendio->transactional` for transactional emails (`/transactional`): `create` (with an `idempotency_key` option), `list` (`search`, `template_id`), `get` (with the published version's `variables`), `update` (`expected_updated_at` for a 409 on a stale save), `remove`, `test` and `stats`. Results are `Sendio\TransactionalEmail` objects. Tracking (`track_opens`, `track_clicks`) is off by default.
- `$sendio->emails->send` and `$sendio->batch->send` take `transactional_id` with `variables` (and no `from`, `subject`, `reply_to` or body), and `track_opens` / `track_clicks` for one email. `Sendio\Email` reads `transactional_id`.
- `$sendio->broadcasts->create` and `update` take `template_id` and `template_version_id`; `update` with `'template_id' => null` detaches a broadcast from its template. `Sendio\Broadcast` reads the template link fields and `unfillable_variables`.
- `Sendio\Mailbox` reads `can_send` and `send_reason`.
- The docs list the automation template categories `appointments`, `events`, `education`, `account` and `nonprofit`.

## 1.0.0 (2026-11-11)

First public release.
