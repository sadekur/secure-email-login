# AGENTS.md

## Build/Lint/Test Commands
- No specific build/lint/test commands defined in composer.json or package.json.
- For PHP: Consider using `composer test` if PHPUnit is added later.
- For JS: No package.json; use browser dev tools for debugging.
- To run a single test: Not applicable; no test suite present.

## Code Style Guidelines
### PHP
- Namespaces: Use `PasswordLess\Login` for includes/.
- Classes: PascalCase (e.g., `Admin`, `RestAPI`).
- Constants: UPPER_CASE (e.g., `SECURE_EMAIL_LOGIN_VERSION`).
- Methods/Properties: camelCase.
- Docblocks: Required for classes and public methods.
- Indentation: 4 spaces.
- Imports: Use `use` statements; no type hints enforced.
- Error Handling: Use try-catch for exceptions; WordPress functions for notices.

### JavaScript
- Variables: camelCase.
- Constants: PascalCase (e.g., `PASSWORDLESSLOGIN`).
- jQuery: Preferred for DOM manipulation.
- Indentation: Tabs.
- Error Handling: Use console.error and try-catch.

### CSS
- Classes/IDs: kebab-case (e.g., `password-less-login-form`).
- Indentation: 4 spaces.
- Comments: Use /* */ for blocks.

No Cursor or Copilot rules found.