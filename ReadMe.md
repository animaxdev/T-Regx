<p align="center">
    <a href="https://t-regx.com"><img src="t.regx.png" alt="T-Regx"></a>
</p>
<p align="center">
    <a href="https://github.com/T-Regx/T-Regx/actions/"><img src="https://github.com/T-Regx/T-Regx/workflows/build/badge.svg" alt="Build status"/></a>
    <a href="https://coveralls.io/github/T-Regx/T-Regx"><img src="https://coveralls.io/repos/github/T-Regx/T-Regx/badge.svg" alt="Unit tests"/></a>
    <a href="https://github.com/T-Regx/T-Regx/releases"><img src="https://img.shields.io/badge/latest-0.19.2-brightgreen.svg?style=popout"/></a>
    <a href="https://github.com/T-Regx/T-Regx"><img src="https://img.shields.io/badge/dependencies-0-brightgreen.svg"/></a>
</p>

# T-Regx | Regular Expressions library

PHP regular expressions brought up to modern standards.

[See documentation](https://t-regx.com/) at [t-regx.com](https://t-regx.com/).

[![last commit](https://img.shields.io/github/last-commit/T-Regx/T-Regx/develop.svg)](https://github.com/T-Regx/T-Regx/commits/develop)
[![commit activity](https://img.shields.io/github/commit-activity/y/T-Regx/T-Regx.svg)](https://github.com/T-Regx/T-Regx)
[![Unit tests](https://img.shields.io/badge/Unit%20tests-2567-brightgreen.svg)](https://github.com/T-Regx/T-Regx)
[![Repository size](https://github-size-badge.herokuapp.com/T-Regx/fiddle.svg)](https://github.com/T-Regx/T-Regx)
[![FQN](https://img.shields.io/badge/FQN-used-blue.svg)](https://github.com/kelunik/fqn-check)
[![PRs Welcome](https://img.shields.io/badge/PR-welcome-brightgreen.svg?style=popout)](http://makeapullrequest.com)
[![Gitter](https://badges.gitter.im/T-Regx/community.svg)](https://gitter.im/T-Regx/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

[![OS Arch](https://img.shields.io/badge/OS-32&hyphen;bit-brightgreen.svg)](https://github.com/T-Regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-64&hyphen;bit-brightgreen.svg)](https://github.com/T-Regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Windows-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![OS Arch](https://img.shields.io/badge/OS-Linux/Unix-blue.svg)](https://github.com/T-Regx/T-Regx/actions)

[![PHP Version](https://img.shields.io/badge/PHP-7.1-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.2-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.3-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-7.4-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.0-blue.svg)](https://github.com/T-Regx/T-Regx/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.1-blue.svg)](https://github.com/T-Regx/T-Regx/actions)

1. [Installation](#installation)
    * [Composer](#installation)
2. [API](#api)
3. [Documentation](#documentation)
4. [T-Regx fiddle - Try online](#try-it-online-in-your-browser)
5. [Overview](#why-t-regx-stands-out)
6. [Comparison](#whats-better)
7. [License](#license)

# Installation

Installation for PHP 7.1 and later (PHP 8 as well):

```bash
composer require rawr/t-regx
```

T-Regx only requires `mb-string` extension, no additional dependencies.

# API

You, choose the interface:

- I choose to **keep PHP methods** *(but protected from errors)*:

  [Scroll to see](#no-change-in-api) - `preg::match_all()`, `preg::replace_callback()`, `preg::split()`
- I choose the **modern regex API**:

  [Scroll to see](#written-with-clean-api) - `pattern()->test()`, `pattern()->match()`, `pattern()->replace()`

For legacy projects, we suggest `preg::match_all()`, for standard projects, we suggest `pattern()`.

# Current work in progress

Current development priorities, regarding release of 1.0:

- Separate SafeRegex and CleanRegex into to two packages, so users can choose what they want #103
- Add documentation to each T-Regx public method #17 \[in progress]
- Release 1.0
- Revamp of [t-regx.com](https://t-regx.com/) documentation \[in progress]

# Documentation

Full API documentation is available at [t-regx.com](https://t-regx.com/). List of changes is available
in [ChangeLog.md](https://github.com/T-Regx/T-Regx/blob/develop/ChangeLog.md).

# Try it online, in your browser!

Open [T-Regx fiddle](https://repl.it/github/T-Regx/fiddle) and start playing around.

# Why T-Regx stands out?

:bulb: [See documentation at t-regx.com](https://t-regx.com/)

* ### No change in API!
    * You can use T-Regx safe features and exception-based error handling, without changing your API.

      Simply swap `preg_match()` to `preg::match()`, and your method is safe! Arguments and return types remain the
      same.

* ### Prepared patterns

  Using user data (for example with `preg_quote()`) isn't always safe with PCRE, as well as just not being that
  convenient to use. T-Regx provides `Pattern::inject()`, designed specifically for handling potentially unsafe
  data. `Pattern::mask()` allows converting user-supplied masks into full-fledged patterns safely.

* ### Working **with** the developer
    * Errors:
        * Not even touching your error handlers **in any way**
        * Converts all PCRE notices/error/warnings to exceptions
        * Preventing fatal errors
    * Strings:
        * [Tracking offset](https://t-regx.com/docs/replace-match-details) and subjects while replacing strings
        * [Fixing error with multi-byte offset (utf-8 safe)](https://t-regx.com/docs/match-details#offsets)

* ### Automatic delimiters for your pattern
  Surrounding slashes or tildes (`/pattern/` or  `~patttern~`) are not compulsory.

* ### Converting Warnings/Errors to Exceptions
    * Detects malformed patterns in `preg_()` (which is impossible with `preg_last_error()`).
    * Notices, warnings or errors during `preg::` are converted to exceptions.
    * `preg_()` can never fail, because it throws `PregException` on warning/error.
    * In some cases, `preg_()` methods might fail, return `false`/`null` and **NOT** trigger a warning. Separate
      exception,
      `SuspectedReturnPregException` is then thrown by T-Regx.

* ### Written with clean API
    * Descriptive, chainable interface
    * SRP methods
    * UTF-8 support out-of-the-box
    * `No Reflection used`, `No (...varargs)`, `No (boolean arguments, true)`, `(No flags, 1)`
      , `[No [nested, [arrays]]]`

* ### Protects your from fatal errors
  Certain arguments cause fatal errors with `preg_()` methods. T-Regx will throw a catchable exception, instead of a
  Fatal Error.

# What's better

![Ugly api](https://t-regx.com/img/external/readme/preg.png)

or

![Pretty api](https://t-regx.com/img/external/readme/t-regx.png)

# Sponsors

- [Andreas Leathley](https://github.com/iquito) - developing [SquirrelPHP](https://github.com/squirrelphp)
- [BarxizePL](https://github.com/BarxizePL) - Thanks!

# T-Regx is developed thanks to

<a href="https://www.jetbrains.com/?from=T-Regx">
  <img src="https://t-regx.com/img/external/jetbrains-variant-4.svg" alt="JetBrains"/>
</a>

## License

T-Regx is [MIT licensed](LICENSE).
