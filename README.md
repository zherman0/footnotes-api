# footnotes-api

### Collection of APIs for the FootNotes(tm) web application which tracks users, hiking locations and specific hikes that people go on and want to track.

#### **NOTE:** This needs to currently be installed on PHP7.4.x or lower. The library that powers this api does not work with PHP 8.

## APIs

USERS

- GET /user - get all users
- GET /user/1 - get a specific user with ID of 1
- POST /user - add new user
- PUT /user/1 - update user 1
- GET /searchuser/username/jsmith - search any column (userId, username, fullname, status, email) in the user table

> _Example usage_
>
> localhost/?/fnapi/user
>
> - gets all the users in the table

LOCATIONS

- GET /location - get all locations
- GET /location/2 - get a specific location with locationId of 2
- POST /location - add new location
- PUT /location/2 - update location 2
- GET /locationsearch/name/examplename - search any column (locationId, description, name, directions)

> _Example usage_
>
> localhost/?/fnapi/location
>
> - gets all the locations in the table

HIKES

- GET /hike - get all hikes
- GET /hike/2 - get a specific hike with hikeId of 2
- POST /hike - add new hike
- PUT /hike/2 - update hike 2
- GET /hikesearch/name/examplename - search any column (locationId, userId,description, hikeDate)

> _Example usage_
>
> localhost/?/fnapi/hike
>
> - gets all the hikes in the table

## Setup

1. Install some XAMP stack with apache, PHP 7.4.x, mariaDB
2. On mariaDB, create a database called 'footnotes-db'. Deploy the /inc/footnotes.sql file on your 'footnotes-db' database.
3. Clone this repo into your working html directory such as /var/www/html/
4. Using the /inc/cred.sample.txt file, make your own cred.txt file in the /inc directory and fill out the db information such as user, password, host, and db name.
5. With your server running, you should be able to type the following in your web browser and see some results:

   1. > localhost/fnapi/?/location

   2. > localhost/fnapi/?/user
   3. In the above example the **fnapi** directory is an alias defined in the httpd.conf file:

      Alias /fnapi/ /var/www/html/fn/

      > <Directory "/var/www/html/fn/"><br/>
      > Options Indexes FollowSymLinks Includes ExecCGI<br/>
      > AllowOverride All<br/>
      > Require all granted<br/>
      > <\/Directory>
