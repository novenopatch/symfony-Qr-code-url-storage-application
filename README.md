# Symfony QR Code URL Storage Application

This application is designed to allow users to store URLs along with their descriptions and generate QR codes for easy access. 
The QR codes provide a convenient way to share and access links, making it an ideal tool for personal use, business promotions, and event sharing. 
This project also serves as a solid foundation for those who want to learn how Symfony works and how to build applications using this framework.

------------

## Features

- **URL Storage**: Save multiple URLs with descriptive text to enhance clarity. 🌱
- **QR Code Generation**: Automatically generate a QR code for each URL, making it easy to access the link using a smartphone or other QR code scanners. 🌱
- **Categorization**: Organize URLs into categories for better management and retrieval. 🌱
- **Tagging System**: Use tags to classify URLs, allowing for enhanced searchability and filtering. 🌱
- **Test**:  Set up and write app tests. 🌱
- **Improve the App Design**: Enhance the user interface and user experience to make the application more visually appealing and easier to navigate. 🌱
- **Multi-language Support**: The application is built to support multiple languages, ensuring accessibility for a broader audience. 🚧
- **API Integration**: Implement a RESTful API to allow external applications to interact with the QR code generation and URL storage functionalities. 🚧
- **Webhooks**: Introduce webhook support for real-time notifications on URL status changes. 🚧
- **User Authentication**: Add user authentication to manage individual user URLs securely. 🚧
- **Admin Dashboard**: Create an admin interface for managing submissions and monitoring statistics. 🚧
- **QR Code Analytics**: Track QR code usage, including scan counts and geographic locations. 🚧
- **Customizable QR Codes**: Allow users to customize QR code appearances. 🛠️
- **Import/Export Functionality**: Provide options for managing data in various formats. 🛠️
- **Unit Testing**: Implement unit tests for core functionalities using PHPUnit. 🛠️
- **Continuous Integration/Continuous Deployment (CI/CD)**: Set up a CI/CD pipeline for automated testing and deployment. 🛠️
- **Documentation**: Enhance code documentation using PHPDoc for clarity. 🛠️
- **Setup**: Set up the app in a Docker environment. 🛠️


### Feature Development Status

- 🌱 **In Progress**: Features that are currently being developed or implemented.
- 🚧 **Planned**: Features that are planned for future development based on my availability.
- 🛠️ **Proposed**: Features that are proposed for future implementation but are not yet prioritized.

### Future Features

The application aims to evolve continually, and I plan to incorporate the following features as time permits:
- **API Integration**: For enhanced interoperability with other services.
- **Webhooks**: To notify users of real-time updates.
- **User Authentication**: For better security and personalized experiences.
- **Analytics Dashboard**: To provide insights into QR code usage.
- **Customizable QR Codes**: Allowing users to modify their QR code designs.

Your contributions are welcome to expedite the development of these features!

------------

## Requirements

- PHP 8.2.0 or higher
- Composer 2.0 or higher
- PDO-SQLite PHP extension enabled
- The [usual Symfony application requirements][2].

## Getting Started

There are 3 different ways of installing this project depending on your needs:

**1. Clone the repository:**

```bash
git clone https://github.com/yourusername/your-repository.git
cd your-repository
```

**2. Install dependencies:**

```bash
composer install
```

**3. Set up the database:**

Make sure to create your database and run migrations as needed. You can configure your `.env` file for database settings.

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Tests

Execute this command to run tests:

```bash
cd my_project/
./bin/phpunit
```

## Contribution

We welcome contributions from other developers! If you have ideas for features or improvements, please feel free to open an issue or submit a pull request. Make sure to follow the code style and include tests for new features.

