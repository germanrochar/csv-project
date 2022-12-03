## CSV Importer | Skills Demonstration

Originally, this project was created to demonstrate my skills in an interview process for [Voxie Inc](https://www.voxie.com/). back in December 2021. I received an assignment (that I will describe in more detail on next sections) that I had to complete within 4 days and it consisted on creating a csv importer using Laravel and Vue Js. I got the job back then, and now that I'm open for new job opportunities I decided to reuse the project and use it as a skill demonstration for hiring teams. I made some improvements in the original project to show off my skills on both backend/frontend tests, devops, and more. Additionally, I refactored some of the logic that I used to import the data and strengthened the validation of the rows in the csv files.

In the upcoming sections, I will describe the requirements set for the importer in the assignment. Also, I will show an overview of the project so it's easier for the reader to identify where are the tests located at (phpunit tests, jest, tests, cypress tests), where are the styles/assets (sass, css), where is the main logic to read and import the files, etc. Additionally, I will include in this last section, all the tools and services that I'm using to make the importer work. The end goal is to provide a complete demonstration of my skills so the hiring teams can take a decision on whether or not I'm a good fit for their company.

## Web Tools Summary
The following table shows a list of all the tools/languages/frameworks used to develop the application:

| :wrench: Tool / Language / Framework | 
|--------------------------------------|
| :white_check_mark: PHP 8.1           | 
| :white_check_mark: Laravel 8.x       |
| :white_check_mark: Vue 2             |
| :white_check_mark: MySQL 8.0         |
| :white_check_mark: PHPUnit           |
| :white_check_mark: Jest              |
| :white_check_mark: Cypress           |
| :white_check_mark: Docker            |
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


## Implementation

In the previous section I described the requirements for the importer that I received from [Voxie Inc](https://www.voxie.com/) for the assignment. Now, in this section I will provide a general explanation on how I implemented a solution that satisfies the requirements. Also, after a brief explanation, you'll see a link to the logic I'm talking about in case you want to take a deeper look.  

### :hammer: Back-end

In the backend side, there are two main tasks that I need to perform in order to import contacts. The first task is scan the csv file to fetch the headers. This is an important task because we are going to take the headers and map them with the fields in contacts table. The second task, is read the data from the csv file and store it in the database.

#### Importer
_Controller_: [ImportContactsController.php](https://github.com/germanrochar/csv-project/blob/main/app/Http/Controllers/ImportContactsController.php)

_Job_: [ImportContacts.php](https://github.com/germanrochar/csv-project/blob/main/app/Jobs/ImportContacts.php)

The [ImportContactsController.php](https://github.com/germanrochar/csv-project/blob/main/app/Http/Controllers/ImportContactsController.php) handles the request to import the data from the csv file and store it in the database.
It expects a csv file and a list of mappings from the frontend as inputs to start the import. Also, it validates the format of the input file and the mappings received. You can find the validation rules in the [ImportContactsRequest.php](https://github.com/germanrochar/csv-project/blob/main/app/Http/Requests/ImportContactsRequest.php).
Once the inputs are validated, it stores the csv file in an S3 bucket and dispatches a job to perform the import.

#### Scanner
The [ContactsCsvScannerController](https://github.com/germanrochar/csv-project/blob/main/app/Http/Controllers/ContactsCsvScannerController.php) handles the request to read the headers from the csv file and also validates the format and length of the files. Finally, it returns a list of csv headers and contact fields available.

_Controller_: [ContactsCsvScannerController](https://github.com/germanrochar/csv-project/blob/main/app/Http/Controllers/ContactsCsvScannerController.php)

#### Import Jobs
_Controller_: [ImportJobsController.php](https://github.com/germanrochar/csv-project/blob/main/app/Http/Controllers/ImportJobsController.php)


Since all the imports are done through jobs, I created a table that keeps track of every job fired and it knows if it was completed or failed. In the UI, the last step of the process shows a list of import jobs that started on that day and it shows their status (started, completed and failed). This list of jobs is updated in real time with [Pusher](https://pusher.com/) so there's no need to refresh the page to see updates on their status.

### ::high_brightness: Front-end
The frontend follows the same flow as required ([look here](https://support.autopilothq.com/hc/en-us/articles/203885305-Import-contacts)). Therefore, I divided the process to import contacts in four steps. The first step, allows users to upload their csv file. The second step shows a table where users can map csv fields with contact fields or custom attributes in the database. The third step shows a preview of the fields matched so the users can confirm their mappings and make modifications if necessary. On the fourth and last step, the users can see a list of import jobs and their status. In this last step they can see if the data was imported successfully or if there were errors while performing the import.   

#### Vue JS Components and Pages
_Pages:_ https://github.com/germanrochar/csv-project/tree/main/resources/js/pages

_Components:_ https://github.com/germanrochar/csv-project/tree/main/resources/js/components/imports


#### Core Javascript
_Path:_ https://github.com/germanrochar/csv-project/tree/main/resources/js/core

### :art: Styling
For the styling of the application I'm using Bootstrap to set the layout of the page and use it's components. Also, for custom styles I'm using SASS that's compiled into CSS using webpack (see config [here](https://github.com/germanrochar/csv-project/blob/main/webpack.mix.js)).

#### SASS
_Path:_ https://github.com/germanrochar/csv-project/tree/main/resources/sass

### :slot_machine: Tests
All features in the application are covered with automated tests. Backend tests were created using PHPUnit and Frontend tests using Jest and Cypress.

#### Back-end testing | PHPUnit
_Path:_ https://github.com/germanrochar/csv-project/tree/main/tests
#### Component testing | Jest
_Path:_ To be defined.
#### End-to-end testing | Cypress
_Path:_ To be defined.

### :hammer: DevOps
The application uses Github Actions to run the automated tests on every commit pushed to the repo and Docker to manage the environment.

#### GitHub Actions
_Path:_ https://github.com/germanrochar/csv-project/tree/main/.github/workflows

#### Docker
_Path:_ To be defined.

### :wrench: Amazon SQS
The application uses Amazon SQS to set up the queues and process the jobs. The configuration is set in the `.env` file.

### :page_facing_up: Laravel Excel
To import data from the csv files, I'm using the [Laravel Excel](https://laravel-excel.com/) package.

_Importer:_ [ContactsImport.php](https://github.com/germanrochar/csv-project/blob/main/app/Imports/ContactsImport.php)

### :loudspeaker: Broadcasting with Pusher
In the last step of the process to import data, the users can see a list of import jobs started in the day. Each import job shows if the import was completed successfully, if it's in progress or if it failed. All this info is updated in real time using broadcasting in Laravel with [Pusher](https://pusher.com/).

_Events:_ [ImportSucceeded.php](https://github.com/germanrochar/csv-project/blob/main/app/Events/ImportSucceeded.php) | [ImportFailed.php](https://github.com/germanrochar/csv-project/blob/main/app/Events/ImportFailed.php)

_UI:_ [MappinsCompletedPage.vue](https://github.com/germanrochar/csv-project/blob/main/resources/js/pages/imports/steps/MappingsCompletedPage.vue)
