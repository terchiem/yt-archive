# YT Archive

A project that I used to learn PHP, MySQL and practice full-stack development.

This site allows a user to search for videos published to YouTube in 2005 using the YouTube Data API. There is a quota usage limit (10,000 units per day) that needs to be considered when a single search will use up around 100-110 units. 

To mitigate that, the site first searches its own database for videos and then turns to the API when there aren't enough results to display. The videos retrieved from the API are then saved to the database for future searches.

Live version: https://yt-archive.herokuapp.com/
