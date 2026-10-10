# 🏋️ ForgetFit — WordPress Gym & Fitness Theme

**A Modern, Responsive, and Customizable WordPress Theme for Gyms & Fitness Centers**

ForgetFit is a custom WordPress theme designed to provide a modern and engaging digital experience for gyms, fitness centers, personal trainers, and fitness businesses.

Built with PHP, WordPress, HTML, CSS, and JavaScript, the theme focuses on responsive layouts, visual appeal, straightforward navigation, and flexible content management.

The project demonstrates hands-on experience in WordPress theme development, PHP templating, frontend development, UI customization, and content management system integration.

**Version:** 3.1.2  
**WordPress Compatibility:** 6.6+  
**Tested Up To:** WordPress 6.8  
**PHP Requirement:** 7.4+  
**License:** GPLv2 or later

---

## 📸 Theme Preview

![ForgetFit WordPress Theme Screenshot](./screenshot.png)

*ForgetFit homepage showcasing its bold black-and-yellow aesthetic, fitness programs, and gym-focused interface.*

---

## 🚀 Key Features

### Modern & Responsive Design
- Bold fitness-inspired visual design.
- Responsive layouts for desktop, tablet, and mobile devices.
- Clear navigation and structured content sections.
- Distinctive black, yellow, and white branding.

### Customizable Homepage
- Customizable gym offers and promotional content.
- Editable contact details and social media links.
- Configurable follower counts and theme colors.
- Homepage settings accessible through the WordPress Customizer.

### Fitness & Membership Sections
- Dedicated membership page template.
- Training program presentation.
- Club showcase page template.
- Member reviews and testimonials section.
- Contact page template.

### WordPress Integration
- Custom PHP theme templates.
- WordPress Customizer integration.
- Primary navigation menu support.
- Standard WordPress content and page management.
- Reusable theme template components.

### Lead Generation
- Free-pass promotional call-to-action.
- Free-pass form using WordPress `wp_mail()`.
- Contact and membership-related content for prospective customers.

---

## 🛠️ Technology Stack

| Technology | Purpose |
|---|---|
| WordPress | Content Management System |
| PHP | Theme logic and template rendering |
| HTML5 | Website structure |
| CSS3 | Styling and responsive layout |
| JavaScript | Frontend interaction |
| MySQL / MariaDB | WordPress database |
| XAMPP | Local development environment |
| Git & GitHub | Version control and source code hosting |

---

## 📂 Project Structure

```text
ForgetFit/
├── assets/                    # Theme assets
├── template-parts/            # Reusable template components
│
├── 404.php                    # Error page
├── archive.php                # Archive template
├── comments.php               # Comments template
├── footer.php                 # Footer
├── front-page.php             # Homepage
├── functions.php              # Theme setup and functionality
├── header.php                 # Header and navigation
├── home.php                   # Posts index template
├── index.php                  # Fallback template
├── page.php                   # Standard page template
├── search.php                 # Search results
├── single.php                 # Single post template
│
├── template-contact.php       # Contact page template
├── template-membership.php    # Membership page template
├── template-reviews.php       # Member reviews template
├── template-showcase.php      # Club showcase template
│
├── screenshot.png             # WordPress theme preview
├── style.css                  # Theme metadata and styles
├── README.txt                 # WordPress theme documentation
└── LICENSE.txt                # License information
```

---

## ⚙️ Installation Guide

### Prerequisites

Before installing the theme, ensure that your environment meets these requirements:

- WordPress 6.6 or higher.
- PHP 7.4 or higher.
- A compatible MySQL or MariaDB database.
- A local or production WordPress installation.

### Method 1: Install Through WordPress

1. Download the repository from GitHub.
2. Extract the downloaded ZIP if necessary.
3. Prepare a ZIP containing the WordPress theme files.
4. Log in to your WordPress dashboard.
5. Navigate to **Appearance → Themes → Add New Theme**.
6. Click **Upload Theme**.
7. Select the ForgetFit theme ZIP.
8. Click **Install Now**.
9. Activate the theme.

### Method 2: Manual Installation Using XAMPP

1. Install and start XAMPP.
2. Start Apache and MySQL.
3. Install WordPress in your local development environment.
4. Locate the WordPress themes directory:

   `C:\xampp\htdocs\wordpress\wp-content\themes\`

5. Copy the `ForgetFit` folder into the themes directory.
6. Open your local WordPress dashboard.
7. Navigate to **Appearance → Themes**.
8. Activate ForgetFit.

---

## 🎨 Theme Customization

After activating the theme, navigate to:

**WordPress Dashboard → Appearance → Customize**

### Site Identity

Customize the website's branding by changing:

- Site logo.
- Website title.
- Tagline.
- Browser favicon.

### ForgetFit Homepage

Navigate to:

**Appearance → Customize → ForgetFit homepage**

Available settings include:

- Promotional offers.
- Contact information.
- Social media links.
- Follower counts.
- Theme colors.

### Navigation Menu

To configure navigation:

1. Open **Appearance → Menus**.
2. Create a menu or select an existing one.
3. Add relevant pages.
4. Assign the menu to the **Primary** location.
5. Save your changes.

### Custom Page Templates

Create new pages through the WordPress dashboard and assign the appropriate included templates:

- Membership
- Club Showcase
- Member Reviews
- Contact

These templates allow different types of gym-related information to be presented through dedicated page layouts.

---

## 💡 Development Highlights

This project demonstrates practical understanding of several aspects of WordPress development.

### PHP & WordPress Theme Architecture

Organizing a custom theme using WordPress template files, theme functions, and reusable components.

### Frontend Development

Creating an engaging user interface with responsive layouts, typography, fitness imagery, and consistent visual branding.

### Content Management

Integrating WordPress functionality to support editable website content and configuration through the admin interface.

### Website Customization

Implementing customizable sections that allow website administrators to manage brand-specific information without directly editing source files.

### Local Development

Working with XAMPP, Apache, PHP, and a database-backed WordPress environment during development and testing.

---

## ⚠️ Important Configuration Notes

**Demo Content**

The theme contains demonstration information that should be replaced before launching a production website, including:

- Membership prices and offers.
- Customer ratings and reviews.
- Social media follower counts.
- Business addresses.
- Phone numbers and email addresses.

**Email Configuration**

The free-pass form uses WordPress `wp_mail()`.

Email delivery may not work automatically in local XAMPP installations. Production websites should use properly configured transactional email delivery.

**Security & Maintenance**

For production deployment, use supported WordPress and PHP versions, maintain regular backups, and keep themes and plugins updated.

---

## 🔮 Future Improvements

Potential enhancements for future development include:

- Online membership registration.
- Membership plan comparison.
- Online booking for training sessions.
- Payment gateway integration.
- Class schedules and trainer profiles.
- Enhanced form validation and spam protection.
- Accessibility improvements.
- Additional performance optimization.

These are planned improvement ideas, not features claimed to be implemented in the current version.

---

## 👨‍💻 Developer

**Safkat Khan**  
*Software Developer | Full-Stack Development*

Computer Science and Engineering Graduate  
American International University-Bangladesh (AIUB)

**GitHub:** [0xSafkat20](https://github.com/0xSafkat20)

**LinkedIn:** [Safkat Khan](https://linkedin.com/in/0xsafkat20/)

**Portfolio:** [Personal Portfolio](https://safkat-portfolio-zeta.vercel.app/)

---

## 📄 License

This project is distributed under the **GNU General Public License v2 or later (GPLv2+)**.

See [LICENSE.txt](./LICENSE.txt) for license information.

---

## ⭐ Support

If you find this project useful, consider starring the repository.

Contributions, suggestions, and constructive feedback are welcome.

**Built with WordPress, PHP, and a passion for modern web development.**
