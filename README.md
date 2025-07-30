<a name="readme-top">

<br/>

<br />
<div align="center">
  <a href="https://github.com/zyx-0314/">
  <!-- TODO: If you want to add logo or banner you can add it here -->
    <img src="./assets/img/nyebe_white.png" alt="Nyebe" width="130" height="100">
  </a>
<!-- TODO: Change Title to the name of the title of your Project -->
  <h3 align="center">Steampunck Contruction Website</h3>
</div>
<!-- TODO: Make a short description -->
<div align="center">
  A simple multipaged website that is steampunked themed in the construction space. Where you can buy materials, create accounts & products, edit accounts & products, and change order status.,
</div>

<br />

<!-- TODO: Change the zyx-0314 into your github username  -->
<!-- TODO: Change the WD-Template-Project into the same name of your folder -->

![](https://visit-counter.vercel.app/counter.png?page=JuanCarlosCruz1920/steampunk-contruction)

[![wakatime](https://wakatime.com/badge/user/018dd99a-4985-4f98-8216-6ca6fe2ce0f8/project/63501637-9a31-42f0-960d-4d0ab47977f8.svg)](https://wakatime.com/badge/user/018dd99a-4985-4f98-8216-6ca6fe2ce0f8/project/63501637-9a31-42f0-960d-4d0ab47977f8)

---

<br />
<br />

<!-- TODO: If you want to add more layers for your readme -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#overview">Overview</a>
      <ol>
        <li>
          <a href="#key-components">Key Components</a>
        </li>
        <li>
          <a href="#technology">Technology</a>
        </li>
      </ol>
    </li>
    <li>
      <a href="#rule,-practices-and-principles">Rules, Practices and Principles</a>
    </li>
    <li>
      <a href="#resources">Resources</a>
    </li>
  </ol>
</details>

---

## Overview

<!-- TODO: To be changed -->
<!-- The following are just sample -->

This is a multiple paged website where you can create an account and login in either as an user or an admin. After loging in as a user you can either check your orders or create one. As for an admin you can create new products and update them you can also update the users accounts and orders.

### Key Components

<!-- TODO: List of Key Components -->
<!-- The following are just sample -->

- Product Search Bar
- Admin order status Filters
- Admin crud system
- Multi-paged website
- Working Database

### Technology

<!-- TODO: List of Technology Used -->

#### Language

![HTML](https://img.shields.io/badge/HTML-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)

#### Framework/Library

![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

## Rules, Practices and Principles

<!-- Do not Change this -->

1. Always use `AD-` in the front of the Title of the Project for the Subject followed by your custom naming.
2. Do not rename `.php` files if they are pages; always use `index.php` as the filename.
3. Add `.component` to the `.php` files if they are components code; example: `footer.component.php`.
4. Add `.util` to the `.php` files if they are utility codes; example: `account.util.php`.
5. Place Files in their respective folders.
6. Different file naming Cases
   | Naming Case | Type of code | Example |
   | ----------- | -------------------- | --------------------------------- |
   | Pascal | Utility | Accoun.util.php |
   | Camel | Components and Pages | index.php or footer.component.php |
7. Renaming of Pages folder names are a must, and relates to what it is doing or data it holding.
8. Use proper label in your github commits: `feat`, `fix`, `refactor` and `docs`
9. File Structure to follow below.

```
steampunk-contruction
└──  docker/
│  └──  mysql/
│    ├──  init.sql
│  └──  php/
│    ├──  Dockerfile
└──  products/
└──  public/
│  ├──  about.php
│  ├──  add-to-cart.php
│  └──  admin/
│    └──  customers/
│    ├──  customers.php
│      ├──  edit.php
│    ├──  dashboard.php
│    ├──  orders.php
│    ├──  products.php
│    ├──  reports.php
│  ├──  cart.php
│  └──  css/
│    ├──  style.css
│  ├──  dashboard.php
│  ├──  forgot-password.php
│  └──  images/
│    ├──  gear-icon.png
│    ├──  gears-pattern.png
│    └──  products/
│      ├──  Autosofted_Auto_Keyboard_Presser_1.9.exe
│      ├──  brass-bolts.jpg
│      ├──  brass-plates.avif
│      ├──  clay-bricks.webp
│      ├──  clockwork-rivets.webp
│      ├──  copper-pipes.jpg
│      ├──  steam-engine.jpg
│      ├──  steam-hammer.jpg
│      ├──  steel-hammer.webp
│    └──  team/
│      ├──  jebson.jpg
│      ├──  jefferson.jpg
│      ├──  juan.jpg
│  ├──  index.php
│  └──  js/
│    ├──  mains.js
│  ├──  login.php
│  ├──  logout.php
│  ├──  orders.php
│  ├──  products.php
│  └──  public/
│    └──  image/
│      └──  products/
│    └──  images/
│      └──  products/
│  ├──  register.php
│  ├──  reset-password.php
└── src/
│  └── config/
│    ├──  database.php
│  └──  controllers/
│    ├──  AdminController.php
│    ├──  AuthController.php
│    ├──  CartController.php
│    ├──  ProductController.php
│  └──  helpers/
│    ├──  functions.php
│  └──  models/
│    ├──  Orders.php
│    ├──  Product.php
│    ├──  Users.php
│  └──  views/
│    └──  partials/
│      ├──  footer.php
│      ├──  header.php
│    └──  products/
│      └──  list.php
└── docker-compose.yml
└── readme.md
```

> The following should be renamed: name.css, name.js, name.jpeg/.jpg/.webp/.png, name.component.php(but not the part of the `component.php`), Name.utils.php(but not the part of the `utils.php`)

## Resources

<!-- TODO: Add References -->

| Title          | Purpose                                         | Link                                                                      |
| -------------- | ----------------------------------------------- | ------------------------------------------------------------------------- |
| JavaScript MDN | Comprehensive JavaScript reference and guide.   | [MDN JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript) |
| Bootstrap Docs | Official documentation for Bootstrap framework. | [Bootstrap](https://getbootstrap.com/)                                    |
| PHP Docs       | PHP manual and resources.                       | [PHP Docs](https://www.php.net/docs.php)                                  |
| CSS Tricks     | Tutorials and guides for CSS techniques.        | [CSS Tricks](https://css-tricks.com/)                                     |
| Docker         | To understand how dockers is used.              | [Docer](https://docker-curriculum.com/)                                   |
| Google Images  | To give images for our products.                | [Images](https://images.google.com)                                       |
