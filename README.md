# SNett Franklin
Open-Source Multi-purpose System Core
 | Supported by [SNett Systems](http://snett.net)

## Requirements
* Apache2
* PHP 5.4 + MYSQLI
* MySQL 5

## Preparation
* Enable Apache Rewrite Module
* Suggested to install [JSONView](https://addons.mozilla.org/en-Us/firefox/addon/jsonview/) or similar plug-in into your web browser for testing API output

## Installation
1. Create MySQL user and database
2. Set database settings in Config/Database.xml
3. Call the Install API Script from web browser  
http://localhost/Franklin/API?Key=afd7d5d4b34e8ed81a3c276abeba9cdc

### Example Output
```JSON
{

    "Info": [ ],
    "Code": "200",
    "Message": "Action done.",
    "Franklin.System.Install": [
        "Installation started.",
        "Box instence created.",
        "Build of Box Object was successful.",
        "Group instence created.",
        "Build of Group Object was successful.",
        "Menu instence created.",
        "Build of Menu Object was successful.",
        "Page instence created.",
        "Build of Page Object was successful.",
        "Sidebar instence created.",
        "Build of Sidebar Object was successful.",
        "Site instence created.",
        "Build of Site Object was successful.",
        "Status instence created.",
        "Build of Status Object was successful.",
        "Article instence created.",
        "Build of Article Object was successful.",
        "Topic instence created.",
        "Build of Topic Object was successful.",
        "Address instence created.",
        "Build of Address Object was successful.",
        "Alias instence created.",
        "Build of Alias Object was successful.",
        "City instence created.",
        "Build of City Object was successful.",
        "Country instence created.",
        "Build of Country Object was successful.",
        "Language instence created.",
        "Build of Language Object was successful.",
        "Ingredients instence created.",
        "Build of Ingredients Object was successful.",
        "Meta instence created.",
        "Build of Meta Object was successful.",
        "Territory instence created.",
        "Build of Territory Object was successful.",
        "Testimonials instence created.",
        "Build of Testimonials Object was successful.",
        "Comment instence created.",
        "Build of Comment Object was successful.",
        "Question instence created.",
        "Build of Question Object was successful.",
        "Term instence created.",
        "Build of Term Object was successful.",
        "Translation instence created.",
        "Build of Translation Object was successful.",
        "User instence created.",
        "Build of User Object was successful.",
        "Member instence created.",
        "Build of Member Object was successful.",
        "Service instence created.",
        "Build of Service Object was successful."
    ],
    "RequestData": {
        "Action": "Franklin.System.Install"
    }

}
```