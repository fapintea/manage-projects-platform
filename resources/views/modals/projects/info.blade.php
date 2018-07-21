{{--    @author Fabian Emanuel Pintea
        Bachelor's degree project ACS UPB 2018  --}}
<div class="modal fade" id="project-info-modal" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">Informaţii proiect</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">

                    <label>Profesor coordonator</label>
                    <div id="project-teacher" class="container-fluid info-modal-field"></div>

                    <label>Tema proiectului</label>
                    <div id="project-title" class="container-fluid info-modal-field"></div>

                    <label>Descrierea proiectului</label>
                    <div id="project-description" class="container-fluid info-modal-field"></div>

                    <label id="ref-label">Bibliografie</label>
                    <div id="project-references" class="container-fluid info-modal-field">
                        <ul id="ref-list"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Înapoi</button>
                </div>
            </div>
        </div>
    </div>
</div>