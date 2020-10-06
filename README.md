# PageToAPI
Page scraping PHP App

# Author: Kevin Brosnahan

This is a PHP App that CURL's out to http://mlb.com/stats and scrapes the page for statistical data on Players. It only does the first page but can be modified simply enough to scrape additional pages. The App pulls the data into a clean array() and then outputs that data in one of four formats: SQL (optionally executing INSERT statemens putting the data in a MySQL database (SQL included to create that database and table), .CSV, XML, and/or JSON.

The idea for this app is to turn a web page into a queryable API.

Have fun!
