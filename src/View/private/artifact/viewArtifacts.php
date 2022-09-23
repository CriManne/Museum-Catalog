<?php $this->layout('layouts::layout', ['title' => 'Artifact']) ?>

<?php $this->insert('reusable::viewArtifacts'); ?>

<?php $this->push('scripts') ?>
<script src="/resources/js/util.js"></script>
<script src="/resources/js/artifacts.js"></script>
<script>
function setSelectWidth() {
    var sel = $('#sel');
    $('#templateOption').text( sel.val() );
    // for some reason, a small fudge factor is needed
    // so that the text doesn't become clipped
    sel.width( $('#template').width() * 1.03 );
}

setSelectWidth();

$('#sel').change( function() {
    setSelectWidth();
} );
</script>
<?php $this->end() ?>