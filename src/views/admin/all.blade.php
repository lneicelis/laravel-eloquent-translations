@extends('admin.layouts.master')

@section('head-css')


    @parent
@endsection

@section('page-header')
    <div class="page-header">
        <h1>
            Translations
            <small>
                <i class="icon-double-angle-right"></i>

            </small>
        </h1>

    </div><!-- /.page-header -->
@stop

@section('content')

    <p>
        <a class="btn btn-primary show-trans-form" href="{{ URL::Action('TranslationsController@postAdd') }}">
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
                                <a class="blue trans-new-from-old" href="{{ URL::action('TranslationsController@postEdit') }}" data-id="@{{ trans.id }}" >
                                    <i class="icon-plus bigger-130"></i>
                                </a>

                                <a class="green trans-edit" href="{{ URL::action('TranslationsController@postEdit') }}" data-id="@{{ trans.id }}" >
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

@section('head-js')
    @parent

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.13/angular.min.js"></script>
@stop

@section('scripts')
    <script src="{{ URL::asset('assets/js/jquery-ui-1.10.3.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/ajax-form.js') }}"></script>
    <script src="{{ URL::asset('assets/js/admin/jsonToForm-widget.js') }}"></script>

    <script>
        (function(angular){
            var app = angular.module("translationsApp", []);

            app.controller("TranslationsController", function($scope, $http) {
                $http.post('{{ URL::action("TranslationsController@postAll") }}').success(function(data){
                    $scope.translations = data;
                });
            });
        })(angular);

        (function($){
            var createBtn = ".show-trans-form";
            var editBtn = ".trans-edit";
            var createFromOld = ".trans-new-from-old";
            var form = $("#trans-form");
            var modal = $("#trans-form-modal");
            var findOneUrl = '{{ URL::action("TranslationsController@postFind") }}';

            $(document).on("click", createBtn, function(e) {
                e.preventDefault();
                var formAction = $(e.currentTarget).attr("href");
                form.attr("action", formAction);

                bindAjaxForm();

                modal.modal("show");
            });

            $(document).on("click", editBtn, function(e) {
                e.preventDefault();
                var formAction = $(e.currentTarget).attr("href");
                var findOneId = $(this).data("id");
                $(".loader-container").show();

                fillForm(form, formAction, findOneUrl, findOneId);

                bindAjaxForm();

            });

            $(document).on("click", createFromOld, function(e) {
                e.preventDefault();
                var formAction = $(e.currentTarget).attr("href");
                var findOneId = $(this).data("id");
                $(".loader-container").show();

                fillForm(form, formAction, findOneUrl, findOneId);

                $(createBtn).trigger("click");
            });

            function fillForm(form, formAction, findUrl, findOneId)
            {
                form.attr("action", formAction)
                    .jsonToForm({
                        url: findUrl,
                        data: {
                            id: findOneId
                    },
                        onSuccessCallback: function(){
                            modal.modal("show");
                            $(".loader-container").hide();
                        }
                    });
            }

            function bindAjaxForm(){
                form.ajaxForm({
                    beforeSendCallback: function(){
                        $(".loader-container").show();
                    },
                    onSuccessCallback: function(){
                        $(".loader-container").hide();
                        modal.modal("hide");
                        location.reload();
                    },
                    onErrorCallback: function(response){
                        $(".loader-container").hide();
                    }
                });
            }
        })(jQuery);
    </script>
@stop