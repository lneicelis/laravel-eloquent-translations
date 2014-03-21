<div id="trans-form-modal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="trans-form" method="post" action="{{ URL::action('TranslationsController@postAdd') }}" enctype="multipart/form-data">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">Translation</h4>
                </div>

                <div class="modal-body overflow-visible">
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">

                            <div class="space-4"></div>

                            {{ Form::token() }}

                            <input id="id" name="id" value="" type="hidden" />

                            <div class="form-group">
                                <label for="locale" class="control-label bolder">Locale</label>
                                <div>
                                    <input name="locale"  class="input-xxlarge" type="text" placeholder="Locale"  />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="namespace" class="control-label bolder">Namespace</label>
                                <div>
                                    <input name="namespace"  class="input-xxlarge" type="text" placeholder="Namespace"  />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="group" class="control-label bolder">Group</label>
                                <div>
                                    <input name="group"  class="input-xxlarge" type="text" placeholder="Group"  />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="key" class="control-label bolder">Key</label>
                                <div>
                                    <input name="key"  class="input-xxlarge" type="text" placeholder="Group"  />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="value" class="control-label bolder">Value</label>
                                <div>
                                    <textarea name="value" class="form-control limited" placeholder="Value" ></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm" data-dismiss="modal">
                        <i class="icon-remove"></i>
                        {{ trans('base.cancel') }}
                    </button>

                    <button class="btn btn-sm btn-primary">
                        <i class="icon-ok"></i>
                        {{ trans('base.save') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>