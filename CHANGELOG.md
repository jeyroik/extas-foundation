# 6.0.0
- `IItem::__select(...$attributes)` interfaced has changed, 
  - `$item->__select('arg1', 'arg2')` is possible now, instead of 
  - `$item->__select(['arg1', 'arg2'])`.

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