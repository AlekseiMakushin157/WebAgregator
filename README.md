# WebAgregator

WebAgregator is a simple web interface to get data from diffrent news sites. It's using RSS feeds for geting open data from sites and show them on one page, spliting data to parts. 

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
