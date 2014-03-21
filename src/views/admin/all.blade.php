@extends('admin.layouts.master')

@section('head-css')

    @parent
@stop

@section('head-js')
    @parent

    {{ HTML::script('https://ajax.googleapis.com/ajax/libs/angularjs/1.2.13/angular.min.js') }}
@stop

@section('page-header')
    <div class="page-header">
        <h1>
            {{ trans('trans::base.translations') }}
            <small>
                <i class="icon-double-angle-right"></i>

            </small>
        </h1>

    </div><!-- /.page-header -->
@stop

@section('content')

    <p>
        <a class="btn btn-primary form-new" href="{{ URL::Action('TranslationsController@postAdd') }}">
            <i class="icon-plus align-top bigger-125"></i>
            {{ trans('base.create') }}
        </a>
    </p>


    <div class="row" ng-app="translationsApp">
        <div class="col-xs-12">
            <table class="table table-striped table-bordered table-hover" ng-controller="TranslationsController">
                <thead>
                    <tr>
                        <td><input type="text" ng-model="search.locale" placeholder="Locale"></td>
                        <td><input type="text" ng-model="search.namespace" placeholder="Namespace"></td>
                        <td><input type="text" ng-model="search.group" placeholder="Group"></td>
                        <td><input type="text" ng-model="search.key" placeholder="Key"></td>
                        <td class="width-40"><input class="width-100" type="text" ng-model="search.value" placeholder="Value"></td>
                        <td></td>
                    </tr>
                </thead>

                <tbody>
                    <tr ng-repeat="trans in translations | filter:search">
                        <td>@{{ trans.locale }}</td>
                        <td>@{{ trans.namespace }}</td>
                        <td>@{{ trans.group }}</td>
                        <td>@{{ trans.key }}</td>
                        <td>@{{ trans.value }}</td>
                        <td>
                            <div class="hidden-phone visible-desktop action-buttons">
                                <a class="blue form-new-from-old" href="{{ URL::action('TranslationsController@postEdit') }}" data-id="@{{ trans.id }}" >
                                    <i class="icon-plus bigger-130"></i>
                                </a>

                                <a class="green form-edit" href="{{ URL::action('TranslationsController@postEdit') }}" data-id="@{{ trans.id }}" >
                                    <i class="icon-pencil bigger-130"></i>
                                </a>

                                <a class="red ajax" href="{{ URL::action('TranslationsController@postDelete') }}" data-id="@{{ trans.id }}">
                                    <i class="icon-trash bigger-130"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @include('trans::admin.modals.trans-form')

@stop

@section('scripts')
    {{ HTML::script('assets/js/jquery-ui-1.10.3.full.min.js') }}
    {{ HTML::script('assets/js/admin/ajax-form.js') }}
    {{ HTML::script('assets/js/admin/jsonToForm-widget.js') }}
    {{ HTML::script('packages/luknei/translations/js/angular/modules/translationsApp.js') }}


    <script>
//        var app = angular.module("translationsApp", []);
        (function(angular){
            app.controller("TranslationsController", function($scope, $http) {
                $http.post(
                        '{{ URL::action("TranslationsController@postAll") }}',
                        {namespace:"{{ $namespace }}"}
                    ).success(function(data){
                    $scope.translations = data;
                });
            });
        })(angular);

        var form = $("#trans-form");
        var modal = $("#trans-form-modal");
        var findOneUrl = '{{ URL::action("TranslationsController@postFind") }}';

    </script>

    {{ HTML::script('assets/js/admin/form-actions.js') }}
@stop