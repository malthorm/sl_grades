<form action="courses" method="POST">
    <!-- csrf -->
    <div class="panel panel-default">
        <div class="panel-heading">Neue Studenten eintragen</div>

        <div class="panel-body">
            <div class="form-group">
                <label for="module_nr">Uni-Kennzeichen</label>
                <input type="text" class="form-control" name="module_nr" required placeholder="563030">

                <label for="name">Note</label>
                <input type="text" class="form-control" name="name" required placeholder="Datenbanken Grundlagen">
            </div>
            <button type="submit" class="btn btn-default">Studenten hinzugügen</button>
        </div>
    </div>
</form>
