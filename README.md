# bus-tracker

This is a quick-and-dirty tracker of buses and trams in Gdańsk that uses ZTM's public API for vehicle GPS data and OpenStreetMap for maps. Currently unmaintained, but it works to an extent which allows me to never be late to school.

## How to use

Easiest way to test it out would be to launch a test server with `php -S localhost:8080`, then just navigate to that port in your browser. PHP process needs to have permission to write to `api/cache` directory, as it will cache some of the requests to make the page work faster. Clicking on a marker will select the vehicle and show more information about it. This also allows tracking the vehicle on a map when the data in the API gets updated (usually twice a minute).

![](http://f.sakamoto.pl/9a8f9e33-3e1b-4f0b-8a86-9e8cd2fc8bc6file_71.jpg)