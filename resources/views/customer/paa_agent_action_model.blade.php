<!-- Modal -->
<div class="modal fade" id="adAction" tabindex="-1" role="dialog" aria-labelledby="adActionLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <label class="modal-title" id="adActionLabel">Ad action</label>
            </div>
            <div class="modal-body">
                <form role="form">
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                    <input type="hidden" name="ad_id" id="ad_id"/>
                    <input type="hidden" name="ad_action" id="ad_action"/>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label id="ad_description">
                            </label>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-default btn-primary" id="ad_submit" style="float: right;">Apply</button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-default btn-danger" id="ad_cancel" style="float: right;">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
