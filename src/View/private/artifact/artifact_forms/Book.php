<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<div class="form-outline mb-4">
    <label class="form-label" for="title">Titolo</label>
    <input type="text" name="title" id="title" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="PublisherID">Casa editrice</label>
    <select id="PublisherID" name="PublisherID" class="form-select" aria-label="Seleziona una casa editrice" required>
        <option value="" hidden selected>Seleziona una casa editrice</option>
    </select>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="Year">Anno</label>
    <input type="number" min="1500" max="2500" name="Year" id="Year" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="Pages">Pagine</label>
    <input type="number" min="1" max="10000" name="Pages" id="Pages" class="form-control" />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="ISBN">ISBN</label>
    <input type="text" min="10" max="13" name="ISBN" id="ISBN" class="form-control" />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="AuthorID">Seleziona uno o più autori</label>
    <select id="AuthorID" name="newAuthors[]" class="form-select" aria-label="Seleziona un supporto" multiple="multiple" size="5" required>
    </select>
</div>
<input type='hidden' name='category' value='Book'>


<?php $this->push('scripts_inner') ?>
<script>
    let urlPublisher = "/api/generic/components?category=Publisher";
    loadSelect(urlPublisher, "#PublisherID");
    let urlAuthor = "/api/generic/components?category=Author";
    loadSelect(urlAuthor, "#AuthorID");
</script>
<?php $this->end() ?>