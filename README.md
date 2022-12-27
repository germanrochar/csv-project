## :bulb: CSV Importer | Skills Demonstration

Originally, this project was created to demonstrate my skills in an interview process for [Voxie Inc](https://www.voxie.com/). back in December 2021. I received an assignment (that I will describe in more detail on next sections) that I had to complete within 4 days and it consisted on creating a csv importer using Laravel and Vue Js. I got the job back then, and now that I'm open for new job opportunities I decided to reuse the project and use it as a skill demonstration for hiring teams. I made some improvements in the original project to show off my skills on both backend/frontend tests, devops, and more. Additionally, I refactored some of the logic that I used to import the data and strengthened the validation of the rows in the csv files.

In the upcoming sections, I will describe the requirements set for the importer in the assignment. The end goal of this project is to provide a complete demonstration of my skills so the hiring teams can take a decision on whether or not I'm a good fit for their company.

## Installation

To get started with the project, please run the following commands:

1. Run `make up`
2. Run `make install`. **Note:** Make sure the build is completed before running this command.

### SQS Configuration
The project uses SQS to process the jobs in the application. Please get your AWS credentials and create a new queue in your account. Then, copy and paste your credentials and queue's info in the `.env` file. 

### Pusher Configuration
The project use pusher notifications. For this, you need to get your credentials from [Pusher](https://pusher.com/) and paste them in the `.env` file. 

## Web Tools Summary
The following table shows a list of all the tools/languages/frameworks used to develop the application:

| :wrench: Tool / Language / Framework | 
|--------------------------------------|
| :white_check_mark: PHP 8.1           | 
| :white_check_mark: Laravel 8.x       |
| :white_check_mark: Vue 2             |
| :white_check_mark: MySQL 8.0         |
| :white_check_mark: PHPUnit           |
| :construction: Jest (In Progress)    |
| :construction: Cypress (In Progress) |
| :construction: Docker (In Progress)  |
| :white_check_mark: Amazon SQS        |
| :white_check_mark: Pusher            |
| :white_check_mark: SASS              |
| :white_check_mark: Bootstrap         |

## Requirements

The requirements for the importer are the following:

- Given a table that has the following fields for contacts (named fields below) - build a Laravel app that will take an uploaded CSV file, read out the columns, and allow the user to map their CSV's columns to the table's fields. Once done, import the file into the contacts table.

- Any fields that are not mapped, import into a separate `custom_attributes` table which has `key` and `value` that correlate to the CSV column and row value.

### Database Requirements

**Contacts Table**
- team_id (`unsigned integer, not null`)
- name (`string, nullable`)
- phone (`string, not null`)
- email (`string, nullable`)
- sticky_phone_number_id (`integer, nullable`)
- created_at (`timestamp, not null`)
- updated_at (`timestamp, not null`)

**Custom Attributes Table**
- contact_id (`unsigned integer, not null`)
- key (`string, not null`)
- value (`string, not null`)
- created_at (`timestamp, not null`)
- updated_at (`timestamp, not null`)

### UI Requirements

Implement a similar visual flow like this: https://support.autopilothq.com/hc/en-us/articles/203885305-Import-contacts using Vue JS.

## :envelope: Contact Info
If you are interested on my skills or if you have any questions, please contact me at: [german.rocha.ra@gmail.com](mailto:german.rocha.ra@gmail.com).
