All fields are explained below.


```
| country_id      | int(11)      |
| city_id         | int(11)      |
| username        | varchar(255) |
| password        | varchar(255) |
| token           | varchar(255) |
| apiKey          | varchar(255) |
Every user has an apiKey generated with Ojs/UserBundle/Entity/User:generateApiKey

| email           | varchar(255) |
Emails are like usernames. They are unique for every user

| title           | varchar(255) |
Will be written as standart formats and English. Dr. Prof. 

| first_name      | varchar(255) |
| last_name       | varchar(255) |
Full name can get as concatted with model

| gender          | varchar(2)   |
| initials        | varchar(255) |
| url             | varchar(255) |
| phone           | varchar(255) |
| fax             | varchar(255) |
| address         | longtext     |
| billing_address | longtext     |
Billing address will be imported after subscription feature released.

| locales         | varchar(255) |
| isActive        | tinyint(1)   |
show that user is mail activated or not

| disable_reason  | longtext     |
| status          | int(11)      |
User record status

| privacy         | tinyint(1)   |
| settings        | longtext     |
User key-value settings stored s serialized

| lastlogin       | datetime     |
| avatar          | varchar(255) |
| avatar_options  | varchar(255) |
| header          | varchar(255) |
| header_options  | varchar(255) |
| created         | datetime     |
| updated         | datetime     |
| deletedAt       | date         |
| tags            | varchar(255) |
```
