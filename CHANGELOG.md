# 6.5.3
- Removed unused files.

# 6.5.2
- Fixed default entities configs filenames.

# 6.5.1
- Up to dotenv v5.5

# 6.5.0
- Added defining configurations filenames options.
- Fixed installing test packages.

# 6.4.1
- Moved `extra` command to the separeted bin-launcher `extas-extra`.
- Added `env` command to the `extas` bin-launcher.

# 6.4.0
- Added `env` command.

# 6.3.0
- Added bin `extas`

# 6.2.0
- Allow to pass path for db drop in tests.

# 6.1.2
- Remove deprecated code.
- Up to php 8.1

# 6.1.1
- Refactoring.

# 6.1.0
- Added stage `equal` for `IItem`, see `extas\interfaces\stages\IStageItemEqual` for details.
- Refactoring DriverFileJson.

# 6.0.0
- Added `install` command (instead of separeted package).
- Added `extra` command (instead of separeted package).
- Fully rebuilt storage logic.
  - Removed clients, databases interfaces and classes. 
- `DriverFileJson` added.
- Repository getting by their aliases is now available by `Item` by default.
- `IItem::__select(...$attributes)` interfaced has changed, 
  - `$item->__select('arg1', 'arg2')` is possible now, instead of 
  - `$item->__select(['arg1', 'arg2'])`.
- Removed all flags for triggers in the `Item`. All triggers are running by default.

# 5.14.1

- Composite names for `Replace` are allowed. Possible delimiters are:
  - `.` (dot)
  - `-` (hyphen)
  - `_` (underscore)

# 5.14.0

- Added `hash` and `install on` for the `Plugin`.

# 5.13.0

- Added `IItem::__select(array $attributes)` method, which allows filtering item attributes (see tests for details).
- Added changelog.