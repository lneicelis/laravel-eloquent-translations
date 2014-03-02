<?php

use Luknei\Translations\Translation;

class TranslationsController extends \BaseController {

    public function getAll()
    {
        /* Breadcrumb */
        Item::setMenu('crumb')->setTitle('Translations')->setUrl(URL::current())->addItem();

        return View::make('trans::admin.all');
    }

    public function postAll()
    {
        $translations = Translation::all();

        return Response::json($translations, 200);
    }

    public function postAdd()
    {
        $trans = new Translation();
        $trans->fill(Input::except('_token'));
        if ($trans->save()) {
            $response = array('type' => 'success', 'title' => 'Success', 'message' => 'Page was successfully created');

            return Response::json($response, 200);
        } else {
            return Response::json($trans->errors(), 500);
        }
    }

    public function postEdit()
    {
        $trans = Translation::find(Input::get('id'));
        $trans->fill(Input::except('id', '_token'));
        if ($trans->save()) {
            $response = array('type' => 'success', 'title' => 'Success', 'message' => 'Page was successfully created');

            return Response::json($response, 200);
        } else {
            return Response::json($trans->errors(), 500);
        }
    }

    public function postFind()
    {
        if (Input::has('id')) {
            $response = Translation::find(Input::get('id'));

            return Response::json($response, 200);
        }
    }

    public function postDelete()
    {
        if (Input::has('id')) {
            Translation::find(Input::get('id'))->delete();
            $response = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Translations successfully deleted.'
            );

            return Response::json($response, 200);
        }
    }

} 