{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
<div class="modal fade" id="admin-revoke-modal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 id="title" class="modal-title">Confirmare revocare drepturi de admin</h4>
                </div>
                <div class="modal-body">
                    <div id="confirmation" class="container-fluid">
                        <div class="alert alert-danger">
                            Sunteţi sigur că doriţi să revocaţi drepturile de admin pentru utilizatorul <strong><span id="name"></span></strong>?
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Anulează</button>
                        <a id="revoke-admin-btn" href="#" class="btn btn-success btn-md">Confirmă</a>
                    </div>
                </div>
            </div>
        </div>
    </div>