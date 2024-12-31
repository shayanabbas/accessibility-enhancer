# Accessibility Enhancer

![Build Status](https://img.shields.io/badge/build-passing-brightgreen) ![PHP](https://img.shields.io/badge/PHP-8.3-blue) ![Node.js](https://img.shields.io/badge/Node.js-16-brightgreen) ![License](https://img.shields.io/badge/license-GPL--3.0-blue)

The Accessibility Enhancer is a WordPress plugin designed to improve website accessibility. It provides features such as a dynamic toolbar for text adjustments and contrast changes, automated accessibility reports for site admins, and front-end tools to identify and address accessibility issues. This plugin is built to adhere to WCAG standards, ensuring your website meets global accessibility guidelines.

[![Preview WP Plugin](https://img.shields.io/badge/Preview-plugin-blue?style=for-the-badge&logo=wordpress)](https://playground.wordpress.net/#ewoiJHNjaGVtYSI6ICJodHRwczovL3BsYXlncm91bmQud29yZHByZXNzLm5ldC9ibHVlcHJpbnQtc2NoZW1hLmpzb24iLAoibG9naW4iOiB0cnVlLAoic2l0ZU9wdGlvbnMiOiB7CiJibG9nbmFtZSI6ICJBY2Nlc3NpYmlsaXR5IEVuaGFuY2VyIgp9LAoicGx1Z2lucyI6IFsKImh0dHBzOi8vZ2l0aHViLmNvbS9zaGF5YW5hYmJhcy9hY2Nlc3NpYmlsaXR5LWVuaGFuY2VyL3JlbGVhc2VzL2Rvd25sb2FkL3YxLjAuMC9hY2Nlc3NpYmlsaXR5LWVuaGFuY2VyLnppcCIKXSwKInN0ZXBzIjogWwp7CiJzdGVwIjogImltcG9ydFd4ciIsCiJmaWxlIjogewoicmVzb3VyY2UiOiAidXJsIiwKInVybCI6ICJodHRwczovL2dpc3QuZ2l0aHVidXNlcmNvbnRlbnQuY29tL3NoYXlhbmFiYmFzLzI5NTg4MTUxMzgwNzcyMDgzNmY0ODE0YzMwZTk1NTI1L3Jhdy82ZWJiZDBhNjIxYzc2NDBhNGVmNmUwOWJhMDcxOGE4Y2QzODRhNTNjL2V4cG9ydCUyNTIwY29udGVudCUyNTIwZHVtbXAueG1sIgp9Cn0KXQp9)

---

## Features

- **Dynamic Toolbar**: Front-end accessibility toolbar with text resizing and contrast adjustment options.
- **Automated Reports**: Generates accessibility reports for pages and posts.
- **WCAG Compliance**: Includes tools to check for WCAG violations such as missing alt tags, improper heading structures, and insufficient contrast.
- **REST API Integration**: Custom REST API endpoints for fetching and saving accessibility data.
- **React Integration**: Modern front-end components built with React.js.

---

## Technology Stack

- **WordPress**: Backend framework.
- **PHP**: Core language for WordPress development (version 8.3 recommended).
- **React.js**: Front-end library for the accessibility toolbar and admin dashboard.
- **Webpack**: Module bundler for managing assets.
- **Composer**: Dependency management for PHP.
- **Babel**: JavaScript compiler for ES6+ features.
- **DDEV**: Local development environment for WordPress.

---

## Requirements

- **Docker** and **DDEV** installed on your system.
- PHP version **8.3** or higher.
- Node.js (version **16** recommended) and npm/yarn for JavaScript dependencies.
- Composer for PHP dependency management.

---

## Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/shayanabbas/accessibility-enhancer.git
   cd accessibility-enhancer
   ```

2. **Start DDEV**
   ```bash
   ddev start
   ```

3. **Download and Install WordPress**
   Run the provided `setup.sh` script to download and configure WordPress.
   ```bash
   ./setup.sh
   ```

4. **Install Composer Dependencies**
   ```bash
   cd public/plugins/accessibility-enhancer
   composer install
   ```

5. **Install Node.js Dependencies**
   Navigate to the plugin directory and install JavaScript dependencies.
   ```bash
   cd public/plugins/accessibility-enhancer
   npm install
   ```

6. **Build Assets**
   Use Webpack to build the plugin's JavaScript and CSS files.
   ```bash
   npm run build
   ```

---

## Running the Project

1. **Access WordPress**
   Visit your local DDEV environment:
   [http://accessibility-enhancer.ddev.site](http://accessibility-enhancer.ddev.site)

2. **Activate the Plugin**
   - Log in to the WordPress admin panel.
   - Navigate to `Plugins > Installed Plugins`.
   - Activate the "Accessibility Enhancer" plugin.

---

## Development

1. **Frontend Development**
   - Run the Webpack development server for hot-reloading.
     ```bash
     npm start
     ```
   - Access the React components in `src/components/`.

2. **Backend Development**
   - Add or modify PHP classes in the `includes/` directory.

3. **REST API Development**
   - API endpoints are located in `class-rest-api.php`.
   - Test endpoints using tools like Postman or directly through the WordPress REST API console.

---

## Testing

- **PHP Testing**
  Run PHP CodeSniffer:
  ```bash
  composer phpcs
  composer phpcbf # For auto fix
  ```

- **JavaScript Testing**
  Lint JavaScript files:
  ```bash
  npm run lint
  npm run lint:fix # For auto fix
  ```

---

## Release

- **Build Release**
  Run the provided `release.sh` file that will build a release in `./build` folder.
  ```bash
  ./release.sh
  ```

---

## Contribution

- Fork the repository.
- Create a feature branch:
  ```bash
  git checkout -b feature/<feature-name>
  ```
- Submit a pull request with detailed descriptions of the changes.

---

## License

![License](https://img.shields.io/badge/license-GPL--3.0-blue)

This project is licensed under the **GPL-3.0** license.

---

For any issues or feature requests, please [open an issue](https://github.com/shayanabbas/accessibility-enhancer/issues).

