# Package Starter

It's a predefined laravel package scaffold that has features like:

- easy-to-use
- customizable
- dockerized
- phpunit support

## Installation

Clone latest release via cURL
```bash
curl -L https://api.github.com/repos/hans-thomas/package-starter/releases/latest | awk -F \" -v RS="," '/tarball_url/ {print $(NF-1)}' | xargs wget -O - | tar -xz && find ./ -maxdepth 1 -name 'hans-thomas-package-starter-*' -exec mv {}  ./package-starter  \;

```

then, create something amazing🔥
