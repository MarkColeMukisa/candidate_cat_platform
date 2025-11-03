# Filament v4 – Candidate Dashboard Tweaks (Playbook)

This short playbook explains how the admin dashboard was wired for the Candidate Categorization Platform and how to tweak it for future projects. It is specific to Filament v4 + Laravel 12.

## Key Files
- Panel provider: `app/Providers/Filament/AdminPanelProvider.php`
- Resource: `app/Filament/Resources/Candidates/CandidateResource.php`
- Form schema: `app/Filament/Resources/Candidates/Schemas/CandidateForm.php`
- Table: `app/Filament/Resources/Candidates/Tables/CandidatesTable.php`
- Pages (hooks):
  - Create: `app/Filament/Resources/Candidates/Pages/CreateCandidate.php`
  - Edit: `app/Filament/Resources/Candidates/Pages/EditCandidate.php`

## What We Changed
- Replaced the manual `tier` input with a questionnaire (toggles/selects) that mirrors the public form at `/candidates/create`.
- Auto-calculate `tier` on create/edit by aggregating questionnaire answers into a JSON `assessment` and computing a tier from it.
- Added a Tier filter to the table so admins can quickly segment records.

## How to Tweak the Form
Edit `CandidateForm::configure()`. Common tweaks:
- Add/remove questions using `Toggle::make()` or `Select::make()`.
- Change labels/options to match your leveling rubric.
- The form fields for assessment are not DB columns — they are transient UI fields. The page hook persists them (see below).

Example snippet:
```php
Toggle::make('can_auth_password_google')
    ->label('Can implement authentication (password + Google)?')
    ->required();

Select::make('knows_express_hono_or_laravel')
    ->label('Backend frameworks (Express/Hono/Laravel)')
    ->options(['none' => 'None', 'basic' => 'Basic', 'proficient' => 'Proficient'])
    ->required();
```

## How Tier Is Computed
The page classes handle transforming the UI fields into `assessment` JSON and setting `tier`.
- Create: `CreateCandidate::mutateFormDataBeforeCreate()`
- Edit: `EditCandidate::mutateFormDataBeforeSave()`

Both call a small `determineTier()` helper. Update this function to change the categorization logic.

Important: Because we store questionnaire answers in the `assessment` JSON column, we must unset the transient form fields before save.

## Table Filters & Columns
Update `CandidatesTable::configure()` to change listing UI.
- Add/modify columns using `TextColumn::make()`.
- Add filters like `SelectFilter::make('tier')` to quickly slice data.

## Navigation & Access
- Navigation icon/title/order live in `CandidateResource` and the panel provider.
- To gate access, use Filament’s auth/authorization features or Laravel policies.

## Styling & Layout
- Filament v4 is server-driven. For per-field layout, wrap fields with `Filament\Schemas\Components\Grid` / `Section` / `Fieldset` if needed.
- Remember v4 moved layout components into `Filament\Schemas\Components`.

## Common Tasks
- Add a new assessment question: add the component in `CandidateForm`, hydrate/set default in `EditCandidate::mutateFormDataBeforeFill()`, include it in the `assessment` array inside the page hooks, and extend `determineTier()`.
- Show computed tier but keep it read-only: add a `TextInput::make('tier')->disabled()` or use an `Infolist` on the view page.

## After Tweaks
- Run: `php artisan optimize:clear`
- If new columns were added, create & run a migration: `php artisan make:migration ...` then `php artisan migrate`.
- Optional: run Pint to fix style: `vendor/bin/pint --dirty`.

## Notes (Filament v4 specifics)
- File visibility is `private` by default.
- Filters are deferred by default; call `deferFilters(false)` if you want immediate filtering.
- Action classes extend `Filament\Actions\Action` in v4.

