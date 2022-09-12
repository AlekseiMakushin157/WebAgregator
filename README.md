# WebAgregator

WebAgregator is a simple web interface for getting data from diffrent news sites. The interface impliments a simple MVC framework for demonstration and splitting code into modules. It uses RSS feeds for get open data from news sites and display it on a single page, splitting data into parts. 

## Dependencies

WebAgregator uses loaded JQuery library for requests to server via AJAX.

## Installation

Clone github repository  into webserver domain folder with command
```bash
clone https://github.com/AlekseiMakushin157/WebAgregator.git
```

 Create vendor directory and build "autoload.php" file with command
```bash
composer install
```

## Settings

You can change site title and other parameters in config/application.ini file. You can add new news sources via Mapping.xml file (in "MODEL_DIR/NewsAgregator/" folder).

## Work description

The main module of the project is the "NewsAgregator" module, which reads data from various data sources. It uses an XML file to describe the methods and URLs used for data sources, receives data as News entities, and converts it to arrays then need to send response or populate data.
