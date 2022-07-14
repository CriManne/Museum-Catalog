<?php $this->layout('layouts::layout-page',['title' => $title]) ?>

<form action='index.php' method='post'>
    
    <input type='text' name='search_query' id='search_query' value='Ricerca un reperto' required>
    <input type='submit' name='btnSubmit'>

</form>



<form action='src/employee-area.php' method='post'>

    <input type='submit' name='loginPageBtn' value='Area dipendenti'>

</form>


