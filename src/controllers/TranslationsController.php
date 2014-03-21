<?php

use Luknei\Translations\Translation;

class TranslationsController extends \BaseController {

    public function getAll($namespace = "all")
    {
        /* Breadcrumb */
        Item::setMenu('crumb')->setTitle(trans('trans::base.translations'))->setUrl(URL::current())->addItem();

        return View::make('trans::admin.all', array('namespace' => $namespace));
    }

    public function postAll()
    {
        if ( Input::has('namespace') && Input::get('namespace') !== "all") {
            $translations = Translation::where('namespace', Input::get('namespace'))->get();
        } else {
            $translations = Translation::all();
        }

        return Response::json($translations, 200);
    }

    public function postAdd()
    {
        $trans = new Translation();
        $trans->fill(Input::except('_token'));
        if ($trans->save()) {
            $response = array('type' => 'success', 'title' => trans('base.success'), 'message' => trans('trans::base.addSuccess'));

            return Response::json($response, 200);
        } else {
            return Response::json($trans->errors(), 500);
        }
    }

    public function postEdit()
    {
        $trans = Translation::findOrFail(Input::get('id'));
        $trans->fill(Input::except('id', '_token'));
        if ($trans->save()) {
            $response = array('type' => 'success', 'title' => trans('base.success'), 'message' => trans('trans::base.editSuccess'));

            return Response::json($response, 200);
        } else {
            return Response::json($trans->errors(), 500);
        }
    }

    public function postFind()
    {
        $response = Translation::findOrFail(Input::get('id'));

        return Response::json($response, 200);
    }

    public function postDelete()
    {
        Translation::findOrFail(Input::get('id'))->delete();
        $response = array(
            'type' => 'success',
            'title' => trans('base.success'),
            'message' => trans('trans::base.deleteSuccess')
        );

        return Response::json($response, 200);
    }

} 