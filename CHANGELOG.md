# 6.22.1

- Fixed `DriverFileJson` work with parallel tables modifications.

# 6.22.0

- Added method `IHaveParams::setParamValue(string $paramName, mixed $value): static`.

# 6.21.3

- Added output for already existing records on `updateAndThrowIfExist(...)`.

# 6.21.2

- Detailed install output.

# 6.21.1

- Fixed `shout` args.

# 6.21.0

- Added output for repositories.

# 6.20.0

- Added `IRepoItem::updateAndThrowIfExist(IRepository $repo, IItem &$item, array $fields): void`.

# 6.19.0

- Added `IHaveOutput`.

# 6.18.4

- Updated install output.

# 6.18.3

- Fixed `THasParams::setParams()`.
- Continue updating to avoid direct setting in classes.

# 6.18.2

- Fixed [#66](https://github.com/jeyroik/extas-foundation/issues/66).

# 6.18.1

- Fixed `ExtasTestCase`.
- Fixed `TBuildRepository`.

# 6.18.0

- Allow miss `libName` in the `TBuildRepository` to build repositories of the current package.

# 6.17.0

- Added `ExtasTestCase`.
- Added `buildLibsRepos()` and `installLibItems` in `TBuildRepository`.

# 6.16.1

- Fixed [#61](https://github.com/jeyroik/extas-foundation/issues/61).

# 6.16.0

- Added `RepoItem::addNameToAliases()`.

# 6.15.1

- Added tests for `setParams(...)`.

# 6.15.0

- Added `setParams(array $params)`, `getParamsValues()` and `addParam(IParam $param)` to `IHaveParams`.

# 6.14.0

- Added `IHasValue` to `IParam` and `IParametred`.

# 6.13.0

- Added `ICollection`, `TCollection`.
- Added `IParam`, `IParams`, `IHaveParams`, `IParametered`, `IParametredCollection`.
- Added `TBuildItems`.
- Marked `ISampleParameter` deprecated. Be aware: in the next major version this one and associated interfaces, traits and classes will be deleted.

# 6.12.0

- Added nested fields support for `DriverFileJson` (see #51).

# 6.11.2

- Improve interface compatability.

# 6.11.1

- Fixed composer `bin` section.

# 6.11.0

- Added support for `\JsonSerializible`.

# 6.10.0

- Added `RepoItem` (you do not need to require `jeyroik\extas-repo-toolkit` anymore).
- Added `extas-config-php g` command (you do not need to require `jeyroik\extas-config-php` anymore).
- Added checks for plugins and extensions for existing before creating.

# 6.9.1

- Fixed critical mistype in the extas.storage.json.

# 6.9.0

- Up deps versions.

# 6.8.1

- Fixed https://github.com/jeyroik/extas-foundation/issues/38

# 6.8.0
- Allow to use file for repositories `code`. See #34 (https://github.com/jeyroik/extas-foundation/issues/34)

# 6.7.0
- Added repository methods `allAsArray()` and `oneAsArray()`.

# 6.6.0
- Added stage `extas.is.install.entity`.

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