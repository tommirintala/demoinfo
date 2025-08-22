<table class="table table-striped">
<thead>
<tr>
<th>Value 1</th>
<th>Value 2</th>
<th>Value 3</th>
</tr>
</thead>
<?php

for ($i=0;$i<5;$i++) {
    printf("<tr><td>%.4f</td><td>%.4f</td><td>%.4f</tr>",
           rand() / getrandmax(),
           rand() / getrandmax(),
           rand() / getrandmax()
    );
}

?>
</table>
