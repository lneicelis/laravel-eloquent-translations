<?php

Item::setMenu('adminSideMenu')->setName('translations')
    ->setIcon('icon-font')->setTitle('Translations')->setUrl(URL::action('TranslationsController@getAll'))->addItem();