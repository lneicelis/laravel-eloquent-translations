<?php

Item::setMenu('adminSideMenu')->setName('translations')
    ->setIcon('icon-font')->setTitle(trans('trans::base.translations'))
    ->setUrl('#')->addItem();

Item::setMenu('adminSideMenu')->setName('translations')
    ->setTitle(trans('all'))
    ->setUrl(URL::action('TranslationsController@getAll', array('namespace' => 'all')))->addSubItem();
