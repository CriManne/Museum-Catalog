<?php $this->layout('layouts::artifact_form', ['title' => $title, 'user' => $user]) ?>

<div class="form-outline mb-4">
    <label class="form-label" for="title">Title</label>
    <input type="text" name="title" id="title" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="publisherId">Publisher</label>
    <select id="publisherId" name="publisherId" class="form-select" aria-label="Select a publisher" required>
        <option value="" hidden selected>Select a publisher</option>
    </select>
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="year">Year</label>
    <input type="number" min="1500" max="2500" name="year" id="year" class="form-control" required />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="pages">Pages</label>
    <input type="number" min="1" max="10000" name="Pages" id="pages" class="form-control" />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="isbn">ISBN</label>
    <input type="text" min="10" max="13" name="ISBN" id="isbn" class="form-control" />
</div>
<div class="form-outline mb-4">
    <label class="form-label" for="authorId">Select one or more authors</label>
    <select id="authorId" name="newAuthors[]" class="form-select" multiple="multiple" size="5" required>
    </select>
</div>
<input type='hidden' name='category' value='Book'>


<?php $this->push('scripts_inner') ?>
<script>
    let urlPublisher = "/api/generic/components?category=Publisher";
    loadSelect(urlPublisher, "#publisherId");
    let urlAuthor = "/api/generic/components?category=Author";
    loadSelect(urlAuthor, "#authorId");
</script>
<?php $this->end() ?>