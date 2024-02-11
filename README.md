# EEC-group Invoive project

## Run the project
1. Clone repository

    ```
        1.1- git clone https://github.com/ahmednour005/invoice.git
        1.2- cd project-directory 
        1.3- composer install
        1.4- npm install
        1.5- cp .env.example .env
        1.6- php artisan key:generate
    ```
2. Database 
    2.1 Entity Relationship Diagram (ERD)
    ![Figure 1-1](/invoice-DB-ERD.PNG "Figure 1-1")

    2.2 Create database in DBMS via this query
    ``` sql
        create database `invoice`;
    ```
    2.3 Database Configuration in .env file in application root
    ``` 
        DB_DATABASE=invoice
        DB_USERNAME=
        DB_PASSWORD=
        Put your database user after DB_USERNAME, and your user password after DB_PASSWORD
    ```
    2.4 Migrate & seed
    ``` 
        php artisan migrate
        php artisan db:seed
        
        or
        
        php artisan migrate --seed
    ```
    2.5 Run the project
    ```
        php artisan serve
    ```
---

3. `.env file`
    
     This variable `sub_Folder_URL` will add because we publish this system as subfolder for our domain https://eecgroup.online
    as https://eecgroup.online/invoices
    for that this variable contain folder name Preceded by forward slash (`/`)
    finally we must add this line in our `.env`

    `sub_Folder_URL = 'invoices'`
---
## Contributing

* [Ahmed Fawzy Metwally](https://github.com/ahmed-fawzy-metwally)
* Ahmed Nour
* Mostafa Medhat
* [Mahmoud Ahmed Shalma](https://github.com/MahmoudShalma)

When contributing to this repository, please first discuss the change you wish to make via issue.
---
## Contributing Guidelines

1. **Create** a new issue discussing what changes you are going to make.
2. **Fork** the repository to your own Github account.
3. **Clone** the project to your own machine.
4. **Create** a branch locally with a succinct but descriptive name.
5. **Commit** Changes to the branch.
6. **Push** changes to your fork.
7. **Open** a Pull Request in 
---
## License

 Invoice project Copyright © 2021 Ahmed Fawzy. It is a closed software and redistributed under under the [EEC group license](https://eecegypt.com/).

