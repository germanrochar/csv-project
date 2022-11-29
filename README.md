## CSV Importer | Skills Demonstration

Originally, this project was created to demonstrate my skills in an interview process for [Voxie Inc](https://www.voxie.com/). back in December 2021. I received an assignment (that I will describe in more detail on next sections) that I had to complete within 4 days and it consisted on creating a csv importer using Laravel and Vue Js. I got the job back then, and now that I'm open for new job opportunities I decided to reuse the project and use it as a skill demonstration for hiring teams. I made some improvements in the original project to show off my skills on both backend/frontend tests, devops, and more. Additionally, I refactored some of the logic that I used to import the data and strengthened the validation of the rows in the csv files.

In the next sections, I will describe the requirements set for the importer in the assignment. Also, I will show an overview of the project so it's easier for the reader to identify where are the tests located at (phpunit tests, jest, tests, cypress tests), where are the styles/assets (sass, css), where is the main logic to read and import the files, etc. Additionally, I will include in this last section, all the tools and services that I'm using to make the importer work. The end goal is to provide a complete demonstration of my skills so the hiring teams can take a decision on whether or not I'm a good fit for their company.

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
